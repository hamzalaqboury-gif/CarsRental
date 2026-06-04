import { useCallback, useEffect, useRef, useState } from 'react';
import chatService from '../services/chatService';

const SESSION_KEY = 'chat_session_key';

export function useChat() {
  const [messages,  setMessages]  = useState([]);
  const [input,     setInput]     = useState('');
  const [loading,   setLoading]   = useState(false);
  const [typing,    setTyping]    = useState(false);
  const [error,     setError]     = useState(null);
  const [sessionKey, setSessionKey] = useState(() => localStorage.getItem(SESSION_KEY) || '');
  const bottomRef = useRef(null);

  // Charger l'historique au montage si session existante
  useEffect(() => {
    if (!sessionKey) return;
    chatService.getHistory(sessionKey)
      .then(res => setMessages(res.data.messages || []))
      .catch(() => {});
  }, []);

  // Scroll automatique vers le bas
  useEffect(() => {
    bottomRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [messages, typing]);

  const sendMessage = useCallback(async () => {
    const text = input.trim();
    if (!text || loading) return;

    setError(null);
    setInput('');

    // Afficher immédiatement le message utilisateur (optimistic)
    const tempId = Date.now();
    setMessages(prev => [...prev, {
      id: tempId,
      role: 'user',
      content: text,
      created_at: new Date().toISOString(),
    }]);

    setTyping(true);
    setLoading(true);

    try {
      const res = await chatService.sendMessage(text, sessionKey);
      const { session_key, message } = res.data;

      // Sauvegarder la clé de session
      if (session_key && session_key !== sessionKey) {
        setSessionKey(session_key);
        localStorage.setItem(SESSION_KEY, session_key);
      }

      setMessages(prev => [...prev, message]);
    } catch (err) {
      const msg = err.response?.data?.error || 'Erreur de connexion. Veuillez réessayer.';
      setError(msg);
      // Retirer le message optimiste en cas d'erreur
      setMessages(prev => prev.filter(m => m.id !== tempId));
    } finally {
      setTyping(false);
      setLoading(false);
    }
  }, [input, loading, sessionKey]);

  const clearHistory = useCallback(async () => {
    if (!sessionKey) return;
    try {
      await chatService.clearSession(sessionKey);
    } catch {}
    setMessages([]);
    setSessionKey('');
    localStorage.removeItem(SESSION_KEY);
  }, [sessionKey]);

  const handleKeyDown = useCallback((e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  }, [sendMessage]);

  return {
    messages, input, setInput,
    loading, typing, error,
    sendMessage, clearHistory,
    handleKeyDown, bottomRef,
  };
}
