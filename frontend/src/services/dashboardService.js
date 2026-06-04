import api from './api';

const dashboardService = {
  admin: () => api.get('/dashboard/admin'),
  manager: () => api.get('/dashboard/manager'),
  client: () => api.get('/dashboard/client'),
};

export default dashboardService;
