import api from './api';

const vehicleService = {
  list: (params = {}) => api.get('/vehicles', { params }),
  get: (id) => api.get(`/vehicles/${id}`),
  create: (data) => api.post('/vehicles', data),
  update: (id, data) => {
    // PHP only populates $_FILES for POST; spoof PUT so Laravel routes it correctly
    data.append('_method', 'PUT');
    return api.post(`/vehicles/${id}`, data);
  },
  delete: (id) => api.delete(`/vehicles/${id}`),
};

export default vehicleService;
