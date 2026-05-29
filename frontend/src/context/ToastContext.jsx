import { createContext, useContext } from 'react';
import toast from 'react-hot-toast';

const ToastContext = createContext(null);

export function ToastProvider({ children }) {
  const notify = {
    success: (msg) => toast.success(msg),
    error: (msg) => toast.error(msg),
    loading: (msg) => toast.loading(msg),
    dismiss: (id) => toast.dismiss(id),
    promise: (promise, msgs) => toast.promise(promise, msgs),
  };

  const apiError = (err) => {
    const msg = err?.response?.data?.message
      || err?.response?.data?.errors
      || err?.message
      || 'An unexpected error occurred';
    notify.error(typeof msg === 'object' ? Object.values(msg).flat().join(', ') : msg);
  };

  return (
    <ToastContext.Provider value={{ ...notify, apiError }}>
      {children}
    </ToastContext.Provider>
  );
}

export const useToast = () => useContext(ToastContext);
