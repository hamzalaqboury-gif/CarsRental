import { format } from 'date-fns';

export default function ChatMessage({ message }) {
  const isUser = message.role === 'user';
  const time   = message.created_at
    ? format(new Date(message.created_at), 'HH:mm')
    : '';

  // Convertir **texte** → <strong> et sauts de ligne
  const formatContent = (text) => {
    return text
      .split('\n')
      .map((line, i) => {
        const parts = line.split(/(\*\*[^*]+\*\*)/g).map((part, j) => {
          if (part.startsWith('**') && part.endsWith('**')) {
            return <strong key={j}>{part.slice(2, -2)}</strong>;
          }
          return part;
        });
        return <span key={i}>{parts}{i < text.split('\n').length - 1 && <br />}</span>;
      });
  };

  if (isUser) {
    return (
      <div className="flex justify-end mb-3">
        <div className="max-w-[78%]">
          <div className="bg-primary-600 text-white rounded-2xl rounded-br-none px-4 py-2.5 shadow-sm">
            <p className="text-sm leading-relaxed">{message.content}</p>
          </div>
          <p className="text-[10px] text-gray-400 text-right mt-1 mr-1">{time}</p>
        </div>
      </div>
    );
  }

  return (
    <div className="flex items-end gap-2 mb-3">
      <div className="w-7 h-7 rounded-full bg-primary-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
        AI
      </div>
      <div className="max-w-[78%]">
        <div className="bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-2xl rounded-bl-none px-4 py-2.5 shadow-sm">
          <p className="text-sm leading-relaxed text-gray-800 dark:text-gray-100">
            {formatContent(message.content)}
          </p>
        </div>
        <p className="text-[10px] text-gray-400 mt-1 ml-1">{time}</p>
      </div>
    </div>
  );
}
