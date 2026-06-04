<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function __construct(private readonly ChatService $chatService) {}

    // ── POST /api/chat/message ────────────────────────────────────────────────────
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message'     => 'required|string|max:2000',
            'session_key' => 'nullable|string|max:64',
        ]);

        $sessionKey = $request->input('session_key') ?: Str::uuid()->toString();
        $userId     = $request->user()?->id;

        try {
            $session = $this->chatService->resolveSession($sessionKey, $userId);
            $reply   = $this->chatService->sendMessage($session, $request->input('message'));

            return response()->json([
                'session_key' => $session->session_key,
                'message'     => [
                    'id'         => $reply->id,
                    'role'       => $reply->role,
                    'content'    => $reply->content,
                    'created_at' => $reply->created_at->toISOString(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors du traitement de votre message.'], 500);
        }
    }

    // ── GET /api/chat/history?session_key=xxx ─────────────────────────────────────
    public function history(Request $request): JsonResponse
    {
        $request->validate(['session_key' => 'required|string|max:64']);

        $session = \App\Models\ChatSession::where('session_key', $request->session_key)->first();

        if (! $session) {
            return response()->json(['messages' => [], 'session_key' => $request->session_key]);
        }

        $messages = $session->messages()
            ->whereIn('role', ['user', 'assistant'])
            ->orderBy('created_at')
            ->get()
            ->map(fn ($m) => [
                'id'         => $m->id,
                'role'       => $m->role,
                'content'    => $m->content,
                'created_at' => $m->created_at->toISOString(),
            ]);

        return response()->json([
            'session_key' => $session->session_key,
            'title'       => $session->title,
            'messages'    => $messages,
        ]);
    }

    // ── DELETE /api/chat/session ──────────────────────────────────────────────────
    public function clearSession(Request $request): JsonResponse
    {
        $request->validate(['session_key' => 'required|string|max:64']);

        \App\Models\ChatSession::where('session_key', $request->session_key)->delete();

        return response()->json(['message' => 'Session effacée.']);
    }
}
