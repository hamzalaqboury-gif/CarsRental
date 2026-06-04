import { Bot, X } from 'lucide-react';
import { useState } from 'react';
import { useChat } from '../../hooks/useChat';
import ChatWindow from './ChatWindow';

export default function ChatWidget() {
  const [open, setOpen] = useState(false);
  const chatHook = useChat();

  const unread = !open && chatHook.messages.length > 0 &&
    chatHook.messages[chatHook.messages.length - 1]?.role === 'assistant';

  return (
    <div className="fixed bottom-5 right-5 z-50 flex flex-col items-end gap-3">
      {/* Fenêtre de chat */}
      {open && (
        <div
          className="w-[360px] h-[520px] bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden"
          style={{ animation: 'chatSlideUp 0.25s ease-out' }}
        >
          <ChatWindow onClose={() => setOpen(false)} hook={chatHook} />
        </div>
      )}

      {/* Bouton flottant */}
      <button
        onClick={() => setOpen(o => !o)}
        className="relative w-14 h-14 bg-primary-600 hover:bg-primary-700 text-white rounded-full shadow-lg hover:shadow-xl flex items-center justify-center transition-all duration-200 hover:scale-105"
        aria-label={open ? 'Fermer le chat' : 'Ouvrir le chat'}
      >
        <div
          className="transition-all duration-200"
          style={{ transform: open ? 'rotate(180deg)' : 'rotate(0deg)' }}
        >
          {open ? <X className="w-5 h-5" /> : <Bot className="w-6 h-6" />}
        </div>

        {/* Badge notification */}
        {unread && !open && (
          <span className="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-white text-[9px] text-white flex items-center justify-center font-bold">
            1
          </span>
        )}
      </button>

      {/* Tooltip d'invitation */}
      {!open && chatHook.messages.length === 0 && (
        <div
          className="absolute bottom-16 right-0 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 text-xs px-3 py-2 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 whitespace-nowrap pointer-events-none"
          style={{ animation: 'chatFadeIn 0.5s ease-out 2s both' }}
        >
          💬 Besoin d'aide ?
        </div>
      )}
    </div>
  );
}
