import { Navigate } from 'react-router-dom';
import { Moon, Sun } from 'lucide-react';
import { useAuth } from '../context/AuthContext';
import { useTheme } from '../context/ThemeContext';
import { PageLoader } from '../components/common/LoadingSpinner';
import { ROLES } from '../utils/constants';
import './AuthLayout.css';

const YT_VIDEO_ID = 'YAFUyPp_238';
const YT_SRC =
  `https://www.youtube.com/embed/${YT_VIDEO_ID}` +
  '?autoplay=1&mute=1&loop=1' +
  `&playlist=${YT_VIDEO_ID}` +
  '&controls=0&showinfo=0&rel=0' +
  '&modestbranding=1&iv_load_policy=3' +
  '&disablekb=1&fs=0&playsinline=1';

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
    <div className="auth-layout">
      {/* ── YouTube background video ── */}
      <div className="auth-video-bg" aria-hidden="true">
        <iframe
          className="auth-video-iframe"
          src={YT_SRC}
          title="Background video"
          allow="autoplay; encrypted-media"
          allowFullScreen={false}
        />
      </div>

      {/* ── Dark overlay for readability ── */}
      <div className="auth-video-overlay" aria-hidden="true" />

      {/* ── Theme toggle ── */}
      <button
        onClick={toggle}
        className="auth-theme-toggle"
        title={dark ? 'Switch to light mode' : 'Switch to dark mode'}
      >
        {dark ? <Sun className="w-4 h-4" /> : <Moon className="w-4 h-4" />}
      </button>

      {/* ── Page content ── */}
      <div className="auth-layout-content">
        <div className="text-center mb-8">
         
          <h1 className="auth-header-title">CarsRental</h1>
          <p className="auth-header-subtitle">Premium Vehicle Rental Management</p>
        </div>
        <div className="auth-glass-card p-8">{children}</div>
      </div>
    </div>
  );
}
