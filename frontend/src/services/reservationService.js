import api from './api';

const reservationService = {
  list: (params = {}) => api.get('/reservations', { params }),
  get: (id) => api.get(`/reservations/${id}`),
  create: (data) => api.post('/reservations', data),
  confirm: (id) => api.post(`/reservations/${id}/confirm`),
  cancel: (id, reason = '') => api.post(`/reservations/${id}/cancel`, { reason }),
  checkAvailability: (data) => api.post('/reservations/check-availability', data),
};

export default reservationService;
