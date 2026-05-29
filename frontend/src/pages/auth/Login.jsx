import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { useAuth } from '../../context/AuthContext';
import { useToast } from '../../context/ToastContext';
import AuthLayout from '../../layouts/AuthLayout';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import { ROLES } from '../../utils/constants';

const schema = z.object({
  email:    z.string().email('Invalid email'),
  password: z.string().min(1, 'Password is required'),
});

const DEMO_ACCOUNTS = [
  { label: 'Super Admin', email: 'superadmin@carsrental.com', password: 'SuperAdmin@123', color: 'bg-purple-100 text-purple-800 hover:bg-purple-200' },
  { label: 'Admin',       email: 'admin@carsrental.com',      password: 'Admin@123456',   color: 'bg-indigo-100 text-indigo-800 hover:bg-indigo-200' },
  { label: 'Manager',     email: 'manager@carsrental.com',    password: 'Manager@123',    color: 'bg-sky-100 text-sky-800 hover:bg-sky-200' },
  { label: 'Client',      email: 'client@carsrental.com',     password: 'Client@123456',  color: 'bg-teal-100 text-teal-800 hover:bg-teal-200' },
];

export default function Login() {
  const [loading, setLoading]       = useState(false);
  const [demoLoading, setDemoLoading] = useState(null);
  const { login } = useAuth();
  const { apiError, success } = useToast();
  const navigate = useNavigate();

  const { register, handleSubmit, formState: { errors } } = useForm({ resolver: zodResolver(schema) });

  const doLogin = async (email, password) => {
    const user = await login(email, password);
    success('Welcome back!');
    const role = user?.roles?.[0]?.name;
    navigate(role === ROLES.CLIENT ? '/client/dashboard' : '/admin/dashboard', { replace: true });
  };

  const onSubmit = async (data) => {
    setLoading(true);
    try {
      await doLogin(data.email, data.password);
    } catch (err) {
      apiError(err);
    } finally {
      setLoading(false);
    }
  };

  const loginAsDemo = async (account) => {
    setDemoLoading(account.email);
    try {
      await doLogin(account.email, account.password);
    } catch (err) {
      apiError(err);
    } finally {
      setDemoLoading(null);
    }
  };

  const busy = loading || demoLoading !== null;

  return (
    <AuthLayout>
      <h2 className="text-xl font-bold text-gray-900 dark:text-gray-100 mb-1">Welcome back</h2>
      <p className="text-sm text-gray-500 dark:text-gray-400 mb-6">Sign in to your account</p>

      <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
        <div>
          <label className="label">Email</label>
          <input type="email" {...register('email')} className={`input ${errors.email ? 'input-error' : ''}`} placeholder="you@example.com" autoComplete="email" />
          {errors.email && <p className="error-text">{errors.email.message}</p>}
        </div>
        <div>
          <label className="label">Password</label>
          <input type="password" {...register('password')} className={`input ${errors.password ? 'input-error' : ''}`} placeholder="••••••••" autoComplete="current-password" />
          {errors.password && <p className="error-text">{errors.password.message}</p>}
        </div>

        <button type="submit" disabled={busy} className="btn-primary w-full py-2.5 text-base mt-2">
          {loading ? <LoadingSpinner size="sm" /> : 'Sign In'}
        </button>
      </form>

      <p className="text-center text-sm text-gray-500 dark:text-gray-400 mt-5">
        Don't have an account?{' '}
        <Link to="/register" className="text-primary-600 dark:text-primary-400 font-medium hover:underline">Create one</Link>
      </p>

      <div className="mt-5 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
        <p className="text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2">Quick login — demo accounts</p>
        <div className="grid grid-cols-2 gap-2">
          {DEMO_ACCOUNTS.map(account => (
            <button
              key={account.email}
              type="button"
              disabled={busy}
              onClick={() => loginAsDemo(account)}
              className={`flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition-colors disabled:opacity-50 ${account.color}`}
            >
              <span>{account.label}</span>
              {demoLoading === account.email
                ? <LoadingSpinner size="sm" />
                : <span className="opacity-60">→</span>}
            </button>
          ))}
        </div>
      </div>
    </AuthLayout>
  );
}
