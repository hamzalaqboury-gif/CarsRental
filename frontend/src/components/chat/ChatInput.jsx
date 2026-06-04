import { Send } from 'lucide-react';

export default function ChatInput({ input, setInput, onSend, onKeyDown, loading }) {
  return (
    <div className="p-3 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
      <div className="flex items-end gap-2">
        <textarea
          value={input}
          onChange={e => setInput(e.target.value)}
          onKeyDown={onKeyDown}
          placeholder="Écrivez votre message... (Entrée pour envoyer)"
          rows={1}
          className="flex-1 resize-none px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all max-h-28 overflow-y-auto"
          style={{ height: 'auto', minHeight: '38px' }}
          onInput={e => {
            e.target.style.height = 'auto';
            e.target.style.height = Math.min(e.target.scrollHeight, 112) + 'px';
          }}
          disabled={loading}
        />
        <button
          onClick={onSend}
          disabled={!input.trim() || loading}
          className="w-9 h-9 flex-shrink-0 bg-primary-600 hover:bg-primary-700 disabled:opacity-40 disabled:cursor-not-allowed text-white rounded-xl flex items-center justify-center transition-colors"
        >
          <Send className="w-4 h-4" />
        </button>
      </div>
      <p className="text-[10px] text-gray-400 mt-1.5 text-center">
        Maj+Entrée pour aller à la ligne
      </p>
    </div>
  );
}
