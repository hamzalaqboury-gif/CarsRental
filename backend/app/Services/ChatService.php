<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatService
{
    private string $apiKey;
    private string $model;
    private string $apiUrl = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.openai.key', '');
        $this->model  = config('services.openai.model', 'gpt-3.5-turbo');
    }

    // ── Résoudre ou créer une session ────────────────────────────────────────────
    public function resolveSession(string $sessionKey, ?int $userId): ChatSession
    {
        return ChatSession::firstOrCreate(
            ['session_key' => $sessionKey],
            ['user_id' => $userId, 'title' => null]
        );
    }

    // ── Envoyer un message et obtenir une réponse ─────────────────────────────────
    public function sendMessage(ChatSession $session, string $userMessage): ChatMessage
    {
        // Sauvegarder le message utilisateur
        ChatMessage::create([
            'session_id' => $session->id,
            'role'       => 'user',
            'content'    => $userMessage,
        ]);

        // Définir le titre de la session si vide
        if (! $session->title) {
            $session->update(['title' => Str::limit($userMessage, 50)]);
        }

        // Construire l'historique (30 derniers messages)
        $history = $session->messages()
            ->whereIn('role', ['user', 'assistant'])
            ->latest()
            ->limit(30)
            ->get()
            ->reverse()
            ->map(fn ($m) => ['role' => $m->role, 'content' => $m->content])
            ->values()
            ->toArray();

        // Appel IA
        $reply  = $this->callAI($history);
        $tokens = $reply['tokens'] ?? null;

        // Sauvegarder la réponse
        $message = ChatMessage::create([
            'session_id' => $session->id,
            'role'       => 'assistant',
            'content'    => $reply['content'],
            'tokens'     => $tokens,
        ]);

        return $message;
    }

    // ── Appel API OpenAI ──────────────────────────────────────────────────────────
    private function callAI(array $history): array
    {
        if (empty($this->apiKey)) {
            return ['content' => $this->fallbackResponse(end($history)['content'] ?? ''), 'tokens' => null];
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type'  => 'application/json',
                ])
                ->post($this->apiUrl, [
                    'model'       => $this->model,
                    'messages'    => array_merge([['role' => 'system', 'content' => $this->systemPrompt()]], $history),
                    'max_tokens'  => 600,
                    'temperature' => 0.7,
                ]);

            if ($response->failed()) {
                return ['content' => "Désolé, le service IA est temporairement indisponible. (Code: {$response->status()})", 'tokens' => null];
            }

            $data = $response->json();

            return [
                'content' => $data['choices'][0]['message']['content'] ?? 'Pas de réponse.',
                'tokens'  => $data['usage']['total_tokens'] ?? null,
            ];
        } catch (\Exception $e) {
            return ['content' => 'Une erreur est survenue. Veuillez réessayer.', 'tokens' => null];
        }
    }

    // ── Prompt système CarsRental ─────────────────────────────────────────────────
    private function systemPrompt(): string
    {
        return <<<PROMPT
Tu es un assistant virtuel pour CarsRental, une agence de location de voitures.
Tu aides les clients à :
- Trouver un véhicule adapté (catégorie, transmission, carburant, prix)
- Comprendre le processus de réservation
- Répondre aux questions sur les tarifs et disponibilités
- Résoudre les problèmes liés aux réservations
- Fournir des informations générales sur la location de voitures

Règles :
- Réponds toujours en français sauf si l'utilisateur écrit dans une autre langue
- Sois concis, professionnel et amical
- Si tu ne sais pas quelque chose, dis-le honnêtement
- Pour les questions techniques spécifiques à un compte, invite l'utilisateur à contacter le support
PROMPT;
    }

    // ── Réponses de secours (sans clé API) ───────────────────────────────────────
    private function fallbackResponse(string $message): string
    {
        $msg = strtolower($message);

        if (str_contains($msg, 'réserv') || str_contains($msg, 'reserv')) {
            return "Pour effectuer une réservation, rendez-vous dans la section **Browse Cars**, choisissez votre véhicule et sélectionnez vos dates. Notre équipe confirmera votre réservation rapidement !";
        }
        if (str_contains($msg, 'prix') || str_contains($msg, 'tarif') || str_contains($msg, 'coût')) {
            return "Nos tarifs varient selon le véhicule et la durée. Vous pouvez consulter les prix journaliers directement sur la page du catalogue. Des réductions sont disponibles pour les longues durées.";
        }
        if (str_contains($msg, 'voiture') || str_contains($msg, 'véhicule') || str_contains($msg, 'voitures')) {
            return "Nous proposons une large gamme de véhicules : berlines, SUV, camions, vans, véhicules de luxe et économiques. Consultez notre catalogue pour voir les disponibilités avec photos et caractéristiques détaillées.";
        }
        if (str_contains($msg, 'annul')) {
            return "Vous pouvez annuler une réservation depuis votre espace **Mes Réservations**. Notez que les annulations doivent être effectuées au moins 24h avant la date de prise en charge.";
        }
        if (str_contains($msg, 'bonjour') || str_contains($msg, 'salut') || str_contains($msg, 'hello')) {
            return "Bonjour ! 👋 Je suis l'assistant CarsRental. Comment puis-je vous aider aujourd'hui ? Je peux vous renseigner sur nos véhicules, tarifs, réservations ou tout autre service.";
        }
        if (str_contains($msg, 'contact') || str_contains($msg, 'support') || str_contains($msg, 'aide')) {
            return "Notre équipe support est disponible du lundi au vendredi de 8h à 18h. Vous pouvez également nous contacter par email à support@carsrental.com";
        }

        return "Je suis l'assistant CarsRental. Pour une expérience optimale, veuillez configurer la clé API OpenAI (`OPENAI_API_KEY` dans `.env`). En attendant, je peux répondre à des questions basiques sur nos services !";
    }
}
