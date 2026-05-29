import { useState } from 'react';
import { Link, useLocation, Navigate } from 'react-router-dom';
import { LayoutDashboard, Car, CalendarDays, User, LogOut, Menu, Moon, Sun } from 'lucide-react';
import { useAuth } from '../context/AuthContext';
import { useToast } from '../context/ToastContext';
import { useTheme } from '../context/ThemeContext';
import { PageLoader } from '../components/common/LoadingSpinner';

const navItems = [
  { to: '/client/dashboard',    icon: LayoutDashboard, label: 'Dashboard' },
  { to: '/client/vehicles',     icon: Car,             label: 'Browse Cars' },
  { to: '/client/reservations', icon: CalendarDays,    label: 'My Reservations' },
  { to: '/client/profile',      icon: User,            label: 'Profile' },
];

export default function ClientLayout({ children }) {
  const [open, setOpen] = useState(false);
  const { user, loading, logout, isAdmin } = useAuth();
  const { success } = useToast();
  const { dark, toggle } = useTheme();
  const { pathname } = useLocation();

  if (loading) return <PageLoader />;
  if (!user) return <Navigate to="/login" replace />;
  if (isAdmin()) return <Navigate to="/admin/dashboard" replace />;

  const handleLogout = async () => { await logout(); success('Logged out.'); };

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      <nav className="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 sticky top-0 z-20">
        <div className="max-w-7xl mx-auto px-4 flex items-center h-14 gap-4">
          <Link to="/client/dashboard" className="flex items-center gap-2 font-bold text-primary-600 mr-4">
            <span className="text-xl">🚗</span> CarsRental
          </Link>
          <div className="hidden md:flex items-center gap-1 flex-1">
            {navItems.map(({ to, icon: Icon, label }) => (
              <Link key={to} to={to}
                className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors ${
                  pathname.startsWith(to)
                    ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300'
                    : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'
                }`}
              >
                <Icon className="w-4 h-4" />{label}
              </Link>
            ))}
          </div>
          <div className="ml-auto flex items-center gap-2">
            <span className="hidden sm:block text-sm text-gray-600 dark:text-gray-300">{user.name}</span>
            <div className="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300 flex items-center justify-center text-sm font-semibold">
              {user.name?.[0]?.toUpperCase()}
            </div>
            <button
              onClick={toggle}
              className="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition-colors"
              title={dark ? 'Switch to light mode' : 'Switch to dark mode'}
            >
              {dark ? <Sun className="w-4 h-4" /> : <Moon className="w-4 h-4" />}
            </button>
            <button onClick={handleLogout} className="btn-ghost text-sm gap-1.5">
              <LogOut className="w-4 h-4" /> Logout
            </button>
            <button onClick={() => setOpen(o => !o)} className="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
              <Menu className="w-5 h-5 dark:text-gray-300" />
            </button>
          </div>
        </div>
        {open && (
          <div className="md:hidden border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 space-y-1">
            {navItems.map(({ to, icon: Icon, label }) => (
              <Link key={to} to={to} onClick={() => setOpen(false)}
                className={`flex items-center gap-2 px-3 py-2 rounded-lg text-sm ${
                  pathname.startsWith(to)
                    ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 font-medium'
                    : 'text-gray-600 dark:text-gray-300'
                }`}
              >
                <Icon className="w-4 h-4" />{label}
              </Link>
            ))}
          </div>
        )}
      </nav>
      <main className="max-w-7xl mx-auto px-4 py-6">{children}</main>
    </div>
  );
}
