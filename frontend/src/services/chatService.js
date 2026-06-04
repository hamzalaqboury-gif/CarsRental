import api from './api';

const chatService = {
  sendMessage: (message, sessionKey) =>
    api.post('/chat/message', { message, session_key: sessionKey }),

  getHistory: (sessionKey) =>
    api.get('/chat/history', { params: { session_key: sessionKey } }),

  clearSession: (sessionKey) =>
    api.delete('/chat/session', { data: { session_key: sessionKey } }),
};

export default chatService;
