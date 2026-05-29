import { useState } from 'react';
import { Link, useLocation, Navigate } from 'react-router-dom';
import {
  LayoutDashboard, Car, CalendarDays, Users, LogOut,
  Menu, User, Moon, Sun,
} from 'lucide-react';
import { useAuth } from '../context/AuthContext';
import { useToast } from '../context/ToastContext';
import { useTheme } from '../context/ThemeContext';
import { PageLoader } from '../components/common/LoadingSpinner';
import { ROLES } from '../utils/constants';

const navItems = [
  { to: '/admin/dashboard',    icon: LayoutDashboard, label: 'Dashboard',    roles: [ROLES.SUPER_ADMIN, ROLES.ADMIN, ROLES.MANAGER] },
  { to: '/admin/vehicles',     icon: Car,             label: 'Vehicles',     roles: [ROLES.SUPER_ADMIN, ROLES.ADMIN, ROLES.MANAGER] },
  { to: '/admin/reservations', icon: CalendarDays,    label: 'Reservations', roles: [ROLES.SUPER_ADMIN, ROLES.ADMIN, ROLES.MANAGER] },
  { to: '/admin/users',        icon: Users,           label: 'Users',        roles: [ROLES.SUPER_ADMIN, ROLES.ADMIN] },
];

function Sidebar({ open, onClose }) {
  const { pathname } = useLocation();
  const { user, logout, role } = useAuth();
  const { success } = useToast();

  const handleLogout = async () => { await logout(); success('Logged out successfully.'); };
  const filteredNav = navItems.filter(i => i.roles.includes(role));

  return (
    <>
      {open && <div className="fixed inset-0 z-20 bg-black/30 lg:hidden" onClick={onClose} />}
      <aside className={`fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 dark:bg-gray-950 text-white flex flex-col transition-transform duration-300 ${open ? 'translate-x-0' : '-translate-x-full'} lg:translate-x-0`}>
        <div className="flex items-center gap-3 px-5 py-5 border-b border-gray-700/50">
          <div className="w-9 h-9 bg-primary-600 rounded-xl flex items-center justify-center text-lg">🚗</div>
          <div>
            <div className="font-bold text-sm">CarsRental</div>
            <div className="text-xs text-gray-400 capitalize">{role}</div>
          </div>
        </div>

        <nav className="flex-1 px-3 py-4 space-y-0.5">
          {filteredNav.map(({ to, icon: Icon, label }) => {
            const active = pathname.startsWith(to);
            return (
              <Link key={to} to={to} onClick={onClose}
                className={`flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors ${
                  active ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white'
                }`}
              >
                <Icon className="w-[18px] h-[18px]" />
                {label}
              </Link>
            );
          })}
        </nav>

        <div className="px-3 py-4 border-t border-gray-700/50 space-y-0.5">
          <Link to="/admin/profile" onClick={onClose}
            className="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 hover:bg-gray-800 hover:text-white transition-colors"
          >
            <User className="w-[18px] h-[18px]" /> Profile
          </Link>
          <button onClick={handleLogout}
            className="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 hover:bg-red-500/20 hover:text-red-400 transition-colors"
          >
            <LogOut className="w-[18px] h-[18px]" /> Logout
          </button>
        </div>
      </aside>
    </>
  );
}

export default function AdminLayout({ children }) {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const { user, loading, isAdmin } = useAuth();
  const { dark, toggle } = useTheme();
  if (loading) return <PageLoader />;
  if (!user) return <Navigate to="/login" replace />;
  if (!isAdmin()) return <Navigate to="/client/dashboard" replace />;

  return (
    <div className="flex h-screen bg-gray-50 dark:bg-gray-900">
      <Sidebar open={sidebarOpen} onClose={() => setSidebarOpen(false)} />
      <div className="flex-1 flex flex-col lg:pl-64 min-w-0">
        <header className="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 px-4 lg:px-6 py-3 flex items-center gap-4">
          <button onClick={() => setSidebarOpen(true)} className="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
            <Menu className="w-5 h-5 dark:text-gray-300" />
          </button>
          <div className="ml-auto flex items-center gap-3">
            <div className="text-right hidden sm:block">
              <p className="text-sm font-medium text-gray-900 dark:text-gray-100">{user.name}</p>
              <p className="text-xs text-gray-400 dark:text-gray-500">{user.email}</p>
            </div>
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
          </div>
        </header>
        <main className="flex-1 overflow-y-auto p-4 lg:p-6">{children}</main>
      </div>
    </div>
  );
}
