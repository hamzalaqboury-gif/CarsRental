import { Navigate } from 'react-router-dom';
import { Moon, Sun } from 'lucide-react';
import { useAuth } from '../context/AuthContext';
import { useTheme } from '../context/ThemeContext';
import { PageLoader } from '../components/common/LoadingSpinner';
import { ROLES } from '../utils/constants';

export default function AuthLayout({ children }) {
  const { user, loading } = useAuth();
  const { dark, toggle } = useTheme();
  if (loading) return <PageLoader />;

  if (user) {
    const role = user?.roles?.[0]?.name;
    if (role === ROLES.CLIENT) return <Navigate to="/client/dashboard" replace />;
    return <Navigate to="/admin/dashboard" replace />;
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-primary-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 flex items-center justify-center p-4 relative">
      <button
        onClick={toggle}
        className="absolute top-4 right-4 p-2 rounded-lg bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 shadow-sm transition-colors"
        title={dark ? 'Switch to light mode' : 'Switch to dark mode'}
      >
        {dark ? <Sun className="w-4 h-4" /> : <Moon className="w-4 h-4" />}
      </button>
      <div className="w-full max-w-md">
        <div className="text-center mb-8">
          <div className="inline-flex items-center justify-center w-14 h-14 bg-primary-600 rounded-2xl mb-4 shadow-lg">
            <span className="text-2xl">🚗</span>
          </div>
          <h1 className="text-2xl font-bold text-gray-900 dark:text-gray-100">CarsRental</h1>
          <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Premium Vehicle Rental Management</p>
        </div>
        <div className="card p-8 shadow-lg">{children}</div>
      </div>
    </div>
  );
}
