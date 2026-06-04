import { useEffect, useState, useCallback } from 'react';
import { CheckCircle, XCircle, Eye, RefreshCw } from 'lucide-react';
import reservationService from '../../services/reservationService';
import AdminLayout from '../../layouts/AdminLayout';
import Modal from '../../components/common/Modal';
import ConfirmDialog from '../../components/common/ConfirmDialog';
import Pagination from '../../components/common/Pagination';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import { useToast } from '../../context/ToastContext';
import { formatCurrency, formatDate, statusBadgeClass } from '../../utils/helpers';

export default function AdminReservations() {
  const [reservations, setReservations] = useState([]);
  const [meta, setMeta]                 = useState(null);
  const [loading, setLoading]           = useState(true);
  const [filter, setFilter]             = useState('');
  const [page, setPage]                 = useState(1);
  const [selected, setSelected]         = useState(null);
  const [confirmAction, setConfirmAction] = useState(null);
  const [submitting, setSubmitting]     = useState(false);
  const { success, apiError }           = useToast();

  const load = useCallback(() => {
    setLoading(true);
    const params = { page };
    if (filter) params.status = filter;
    reservationService.list(params).then(r => {
      setReservations(r.data.data);
      setMeta(r.data.meta);
    }).catch(apiError).finally(() => setLoading(false));
  }, [filter, page]);

  useEffect(() => { load(); }, [load]);

  const handleConfirm = async () => {
    setSubmitting(true);
    try { await reservationService.confirm(confirmAction.id); success('Reservation confirmed.'); setConfirmAction(null); load(); }
    catch (err) { apiError(err); } finally { setSubmitting(false); }
  };

  const handleCancel = async () => {
    setSubmitting(true);
    try { await reservationService.cancel(confirmAction.id, 'Cancelled by admin'); success('Reservation cancelled.'); setConfirmAction(null); load(); }
    catch (err) { apiError(err); } finally { setSubmitting(false); }
  };

  const STATUSES = ['', 'pending', 'confirmed', 'paid', 'cancelled'];

  return (
    <AdminLayout>
      <div className="page-header">
        <h1 className="page-title">Reservations</h1>
        <p className="page-subtitle">Manage all vehicle bookings</p>
      </div>

      <div className="table-wrapper">
        <div className="table-header gap-3 flex-wrap">
          <div className="flex gap-1 flex-wrap">
            {STATUSES.map(s => (
              <button key={s || 'all'} onClick={() => { setFilter(s); setPage(1); }}
                className={`px-3 py-1 rounded-full text-xs font-medium transition-colors ${
                  filter === s
                    ? 'bg-primary-600 text-white'
                    : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                }`}>
                {s || 'All'}
              </button>
            ))}
          </div>
          <button onClick={load} className="btn-ghost ml-auto"><RefreshCw className="w-4 h-4" /></button>
        </div>

        {loading ? <LoadingSpinner className="py-12" /> : (
          <>
            <div className="overflow-x-auto">
              <table className="data-table">
                <thead>
                  <tr><th>#</th><th>Client</th><th>Vehicle</th><th>Dates</th><th>Total</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                  {reservations.length === 0 ? (
                    <tr><td colSpan={7} className="text-center py-10 text-gray-400 dark:text-gray-500">No reservations</td></tr>
                  ) : reservations.map(r => (
                    <tr key={r.id}>
                      <td className="text-gray-400 dark:text-gray-500">#{r.id}</td>
                      <td>
                        <p className="font-medium text-gray-900 dark:text-gray-100">{r.user?.name}</p>
                        <p className="text-xs text-gray-400 dark:text-gray-500">{r.user?.email}</p>
                      </td>
                      <td>{r.vehicle?.brand} {r.vehicle?.model}</td>
                      <td className="text-xs">
                        <p>{formatDate(r.start_date)}</p>
                        <p className="text-gray-400 dark:text-gray-500">→ {formatDate(r.end_date)}</p>
                      </td>
                      <td className="font-semibold">{formatCurrency(r.total_price)}</td>
                      <td><span className={statusBadgeClass(r.status)}>{r.status}</span></td>
                      <td>
                        <div className="flex gap-1">
                          <button onClick={() => setSelected(r)} className="btn-ghost p-1.5 text-blue-600 dark:text-blue-400">
                            <Eye className="w-4 h-4" />
                          </button>
                          {r.status === 'pending' && (
                            <>
                              <button onClick={() => setConfirmAction({ ...r, _action: 'confirm' })} className="btn-ghost p-1.5 text-green-600 dark:text-green-400">
                                <CheckCircle className="w-4 h-4" />
                              </button>
                              <button onClick={() => setConfirmAction({ ...r, _action: 'cancel' })} className="btn-ghost p-1.5 text-red-600 dark:text-red-400">
                                <XCircle className="w-4 h-4" />
                              </button>
                            </>
                          )}
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
            <Pagination meta={meta} onPageChange={setPage} />
          </>
        )}
      </div>

      <Modal isOpen={!!selected} onClose={() => setSelected(null)} title={`Reservation #${selected?.id}`}>
        {selected && (
          <div className="space-y-3 text-sm">
            <div className="grid grid-cols-2 gap-3">
              <div><p className="text-gray-400 dark:text-gray-500">Client</p><p className="font-medium dark:text-gray-200">{selected.user?.name}</p></div>
              <div><p className="text-gray-400 dark:text-gray-500">Vehicle</p><p className="font-medium dark:text-gray-200">{selected.vehicle?.brand} {selected.vehicle?.model}</p></div>
              <div><p className="text-gray-400 dark:text-gray-500">Pickup</p><p className="font-medium dark:text-gray-200">{formatDate(selected.start_date)}</p></div>
              <div><p className="text-gray-400 dark:text-gray-500">Return</p><p className="font-medium dark:text-gray-200">{formatDate(selected.end_date)}</p></div>
              <div><p className="text-gray-400 dark:text-gray-500">Duration</p><p className="font-medium dark:text-gray-200">{selected.total_days} days</p></div>
              <div><p className="text-gray-400 dark:text-gray-500">Total</p><p className="font-bold text-primary-600 dark:text-primary-400">{formatCurrency(selected.total_price)}</p></div>
              <div><p className="text-gray-400 dark:text-gray-500">Status</p><span className={statusBadgeClass(selected.status)}>{selected.status}</span></div>
              <div><p className="text-gray-400 dark:text-gray-500">Payment</p><span className={statusBadgeClass(selected.payment_status)}>{selected.payment_status}</span></div>
            </div>
            {selected.notes && <div><p className="text-gray-400 dark:text-gray-500">Notes</p><p className="dark:text-gray-300">{selected.notes}</p></div>}
          </div>
        )}
      </Modal>

      <ConfirmDialog
        isOpen={!!confirmAction}
        onClose={() => setConfirmAction(null)}
        onConfirm={confirmAction?._action === 'confirm' ? handleConfirm : handleCancel}
        loading={submitting}
        title={confirmAction?._action === 'confirm' ? 'Confirm Reservation' : 'Cancel Reservation'}
        message={`Are you sure you want to ${confirmAction?._action} reservation #${confirmAction?.id}?`}
        confirmLabel={confirmAction?._action === 'confirm' ? 'Confirm' : 'Cancel Reservation'}
        variant={confirmAction?._action === 'confirm' ? 'primary' : 'danger'}
      />
    </AdminLayout>
  );
}
