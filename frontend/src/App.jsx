import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { Toaster } from 'react-hot-toast';
import { AuthProvider } from './context/AuthContext';
import { ToastProvider } from './context/ToastContext';
import { ThemeProvider } from './context/ThemeContext';
// Auth
import Login    from './pages/auth/Login';
import Register from './pages/auth/Register';

// Admin
import AdminDashboard    from './pages/admin/Dashboard';
import AdminVehicles     from './pages/admin/Vehicles';
import AdminReservations from './pages/admin/Reservations';
import AdminUsers        from './pages/admin/Users';
import AdminProfile      from './pages/admin/Profile';

// Client
import ClientDashboard    from './pages/client/Dashboard';
import ClientVehicles     from './pages/client/Vehicles';
import ClientReservations from './pages/client/Reservations';
import ClientProfile      from './pages/client/Profile';

export default function App() {
  return (
    <BrowserRouter>
      <ThemeProvider>
      <AuthProvider>
        <ToastProvider>
          <Toaster position="top-right" toastOptions={{ duration: 4000, style: { fontSize: '14px' }, className: 'dark:!bg-gray-800 dark:!text-gray-100 dark:!border-gray-700' }} />
          <Routes>
            <Route path="/" element={<Navigate to="/login" replace />} />

            {/* Auth */}
            <Route path="/login"    element={<Login />} />
            <Route path="/register" element={<Register />} />

            {/* Admin / Manager */}
            <Route path="/admin/dashboard"    element={<AdminDashboard />} />
            <Route path="/admin/vehicles"     element={<AdminVehicles />} />
            <Route path="/admin/reservations" element={<AdminReservations />} />
            <Route path="/admin/users"        element={<AdminUsers />} />
            <Route path="/admin/profile"      element={<AdminProfile />} />

            {/* Client */}
            <Route path="/client/dashboard"    element={<ClientDashboard />} />
            <Route path="/client/vehicles"     element={<ClientVehicles />} />
            <Route path="/client/reservations" element={<ClientReservations />} />
            <Route path="/client/profile"      element={<ClientProfile />} />

            <Route path="*" element={<Navigate to="/login" replace />} />
          </Routes>
        </ToastProvider>
      </AuthProvider>
      </ThemeProvider>
    </BrowserRouter>
  );
}
