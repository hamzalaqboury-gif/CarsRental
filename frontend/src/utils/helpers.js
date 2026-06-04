import { format, parseISO } from 'date-fns';

export const formatDate = (date) => {
  if (!date) return '—';
  try {
    return format(typeof date === 'string' ? parseISO(date) : date, 'MMM dd, yyyy');
  } catch {
    return date;
  }
};

export const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  }).format(amount ?? 0);
};

export const statusBadgeClass = (status) => {
  const map = {
    pending: 'badge-pending',
    confirmed: 'badge-confirmed',
    paid: 'badge-paid',
    cancelled: 'badge-cancelled',
  };
  return map[status] || 'badge';
};

export const roleBadgeClass = (role) => {
  const map = {
    'super-admin': 'badge-super-admin',
    admin:   'badge-admin',
    manager: 'badge-manager',
    client:  'badge-client',
  };
  return map[role] || 'badge';
};

export const getImageUrl = (path) => {
  if (!path) return null;
  if (path.startsWith('http')) return path;
  return `${import.meta.env.VITE_STORAGE_URL || 'http://localhost:8000/storage'}/${path}`;
};

export const calcDays = (start, end) => {
  if (!start || !end) return 0;
  const s = new Date(start);
  const e = new Date(end);
  return Math.max(0, Math.ceil((e - s) / (1000 * 60 * 60 * 24)));
};
