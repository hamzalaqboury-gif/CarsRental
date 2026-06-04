import { Bot, Trash2, X } from 'lucide-react';
import ChatInput from './ChatInput';
import ChatMessage from './ChatMessage';
import TypingIndicator from './TypingIndicator';

const WELCOME = {
  id: 'welcome',
  role: 'assistant',
  content: 'Bonjour ! 👋 Je suis l\'assistant CarsRental.\nJe peux vous aider à trouver un véhicule, gérer vos réservations ou répondre à vos questions.\nComment puis-je vous aider ?',
  created_at: new Date().toISOString(),
};

export default function ChatWindow({ onClose, hook }) {
  const {
    messages, input, setInput,
    loading, typing, error,
    sendMessage, clearHistory,
    handleKeyDown, bottomRef,
  } = hook;

  const allMessages = messages.length === 0 ? [WELCOME] : messages;

  return (
    <div className="flex flex-col h-full">
      {/* Header */}
      <div className="flex items-center gap-3 px-4 py-3 bg-primary-600 text-white rounded-t-2xl flex-shrink-0">
        <div className="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
          <Bot className="w-4 h-4" />
        </div>
        <div className="flex-1 min-w-0">
          <p className="font-semibold text-sm leading-none">Assistant CarsRental</p>
          <div className="flex items-center gap-1 mt-0.5">
            <span className="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
            <span className="text-[11px] text-white/80">En ligne</span>
          </div>
        </div>
        <button
          onClick={clearHistory}
          className="p-1.5 hover:bg-white/20 rounded-lg transition-colors"
          title="Effacer la conversation"
        >
          <Trash2 className="w-3.5 h-3.5" />
        </button>
        <button
          onClick={onClose}
          className="p-1.5 hover:bg-white/20 rounded-lg transition-colors"
        >
          <X className="w-4 h-4" />
        </button>
      </div>

      {/* Messages */}
      <div className="flex-1 overflow-y-auto px-4 py-4 bg-gray-50 dark:bg-gray-900 space-y-0">
        {allMessages.map(msg => (
          <ChatMessage key={msg.id} message={msg} />
        ))}
        {typing && <TypingIndicator />}
        {error && (
          <div className="flex justify-center mb-3">
            <span className="text-xs text-red-500 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 px-3 py-1.5 rounded-full">
              {error}
            </span>
          </div>
        )}
        <div ref={bottomRef} />
      </div>

      {/* Input */}
      <ChatInput
        input={input}
        setInput={setInput}
        onSend={sendMessage}
        onKeyDown={handleKeyDown}
        loading={loading}
      />
    </div>
  );
}
