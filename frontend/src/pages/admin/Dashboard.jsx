import { useEffect, useState } from 'react';
import { Users, Car, CalendarDays, DollarSign, TrendingUp, Clock } from 'lucide-react';
import dashboardService from '../../services/dashboardService';
import AdminLayout from '../../layouts/AdminLayout';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import { useAuth } from '../../context/AuthContext';
import { formatCurrency, formatDate, statusBadgeClass } from '../../utils/helpers';
import { ROLES } from '../../utils/constants';

function StatCard({ icon: Icon, label, value, sub, color = 'bg-primary-50 text-primary-600' }) {
  return (
    <div className="stat-card">
      <div className={`stat-icon ${color}`}><Icon className="w-6 h-6" /></div>
      <div>
        <p className="stat-value">{value}</p>
        <p className="stat-label">{label}</p>
        {sub && <p className="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{sub}</p>}
      </div>
    </div>
  );
}

export default function AdminDashboard() {
  const [data, setData]       = useState(null);
  const [loading, setLoading] = useState(true);
  const { role }              = useAuth();

  useEffect(() => {
    const fetch = role === ROLES.MANAGER ? dashboardService.manager : dashboardService.admin;
    fetch().then(r => setData(r.data.data)).catch(() => {}).finally(() => setLoading(false));
  }, [role]);

  if (loading) return <AdminLayout><LoadingSpinner size="lg" className="mt-20" /></AdminLayout>;

  const isManager = role === ROLES.MANAGER;

  return (
    <AdminLayout>
      <div className="page-header">
        <h1 className="page-title">Dashboard</h1>
        <p className="page-subtitle">Overview of your rental business</p>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        {!isManager && (
          <StatCard icon={Users} label="Total Users" value={data?.users?.total ?? 0}
            sub={`+${data?.users?.new_this_month ?? 0} this month`} color="bg-violet-50 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400" />
        )}
        <StatCard icon={Car}          label="Total Vehicles"     value={data?.vehicles?.total ?? 0}
          sub={`${data?.vehicles?.available ?? 0} available`} color="bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400" />
        <StatCard icon={CalendarDays} label="Total Reservations" value={data?.reservations?.total ?? Object.values(data?.reservations?.status_counts ?? {}).reduce((a, b) => a + b, 0)}
          color="bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400" />
        <StatCard icon={DollarSign}   label="Total Revenue"      value={formatCurrency(data?.revenue?.total_revenue ?? 0)}
          sub={`${formatCurrency(data?.revenue?.monthly_revenue ?? 0)} this month`} color="bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400" />
        <StatCard icon={TrendingUp}   label="Weekly Revenue"     value={formatCurrency(data?.revenue?.weekly_revenue ?? 0)}
          color="bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400" />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <div className="card p-5">
          <h3 className="font-semibold text-gray-900 dark:text-gray-100 mb-4">Reservation Status</h3>
          <div className="space-y-3">
            {Object.entries(data?.reservations?.status_counts ?? {}).map(([status, count]) => (
              <div key={status} className="flex items-center justify-between">
                <span className={statusBadgeClass(status)}>{status}</span>
                <span className="font-semibold text-gray-900 dark:text-gray-100">{count}</span>
              </div>
            ))}
          </div>
        </div>

        {!isManager && data?.users?.role_distribution && (
          <div className="card p-5">
            <h3 className="font-semibold text-gray-900 dark:text-gray-100 mb-4">User Roles</h3>
            <div className="space-y-3">
              {Object.entries(data.users.role_distribution).map(([r, count]) => (
                <div key={r} className="flex items-center justify-between">
                  <span className="text-sm capitalize text-gray-600 dark:text-gray-300">{r}</span>
                  <span className="font-semibold text-gray-900 dark:text-gray-100">{count}</span>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>

      {!isManager && data?.recent_activity?.length > 0 && (
        <div className="table-wrapper">
          <div className="table-header">
            <h3 className="font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
              <Clock className="w-4 h-4" /> Recent Activity
            </h3>
          </div>
          <div className="overflow-x-auto">
            <table className="data-table">
              <thead><tr><th>Action</th><th>User</th><th>Time</th></tr></thead>
              <tbody>
                {data.recent_activity.slice(0, 10).map(log => (
                  <tr key={log.id}>
                    <td><span className="font-mono text-xs bg-gray-100 dark:bg-gray-700 dark:text-gray-300 px-2 py-0.5 rounded">{log.action}</span></td>
                    <td>{log.user?.name ?? 'System'}</td>
                    <td className="text-gray-400 dark:text-gray-500 text-xs">{formatDate(log.created_at)}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}
    </AdminLayout>
  );
}
