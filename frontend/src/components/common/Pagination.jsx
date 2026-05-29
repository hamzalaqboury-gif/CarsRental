import { ChevronLeft, ChevronRight } from 'lucide-react';

export default function Pagination({ meta, onPageChange }) {
  if (!meta || meta.last_page <= 1) return null;

  const pages = Array.from({ length: meta.last_page }, (_, i) => i + 1);
  const visible = pages.filter(
    (p) => p === 1 || p === meta.last_page || Math.abs(p - meta.current_page) <= 1
  );

  return (
    <div className="flex items-center justify-between px-4 py-3 border-t border-gray-100 dark:border-gray-700">
      <p className="text-sm text-gray-500 dark:text-gray-400">
        Showing {(meta.current_page - 1) * meta.per_page + 1}–
        {Math.min(meta.current_page * meta.per_page, meta.total)} of {meta.total}
      </p>
      <div className="flex items-center gap-1">
        <button
          onClick={() => onPageChange(meta.current_page - 1)}
          disabled={meta.current_page === 1}
          className="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed text-gray-600 dark:text-gray-300"
        >
          <ChevronLeft className="w-4 h-4" />
        </button>

        {visible.map((page, idx) => {
          const prev = visible[idx - 1];
          return (
            <span key={page} className="flex items-center gap-1">
              {prev && page - prev > 1 && <span className="px-1 text-gray-400 dark:text-gray-500">…</span>}
              <button
                onClick={() => onPageChange(page)}
                className={`w-8 h-8 rounded-lg text-sm font-medium transition-colors ${
                  page === meta.current_page
                    ? 'bg-primary-600 text-white'
                    : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300'
                }`}
              >
                {page}
              </button>
            </span>
          );
        })}

        <button
          onClick={() => onPageChange(meta.current_page + 1)}
          disabled={meta.current_page === meta.last_page}
          className="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-40 disabled:cursor-not-allowed text-gray-600 dark:text-gray-300"
        >
          <ChevronRight className="w-4 h-4" />
        </button>
      </div>
    </div>
  );
}
