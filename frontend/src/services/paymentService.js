import api from './api';

const paymentService = {
  mockPay: (reservationId) => api.post('/payments/mock', { reservation_id: reservationId }),
  createPaypalOrder: (reservationId) => api.post('/payments/paypal/create', { reservation_id: reservationId }),
};

export default paymentService;
