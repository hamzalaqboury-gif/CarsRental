import { useEffect, useState, useCallback } from 'react';
import { RefreshCw, CreditCard, Landmark } from 'lucide-react';
import reservationService from '../../services/reservationService';
import paymentService from '../../services/paymentService';
import ClientLayout from '../../layouts/ClientLayout';
import ReservationCard from '../../components/reservations/ReservationCard';
import ConfirmDialog from '../../components/common/ConfirmDialog';
import Modal from '../../components/common/Modal';
import Pagination from '../../components/common/Pagination';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import { useToast } from '../../context/ToastContext';
import { formatCurrency } from '../../utils/helpers';

function formatCardNumber(val) {
  return val.replace(/\D/g, '').slice(0, 16).replace(/(.{4})/g, '$1 ').trim();
}
function formatExpiry(val) {
  const digits = val.replace(/\D/g, '').slice(0, 4);
  return digits.length > 2 ? digits.slice(0, 2) + '/' + digits.slice(2) : digits;
}

function CardForm({ amount, onPay, onBack, paying }) {
  const [card, setCard] = useState({ name: '', number: '', expiry: '', cvv: '' });
  const [errors, setErrors] = useState({});

  const validate = () => {
    const e = {};
    if (!card.name.trim())                             e.name   = 'Name is required';
    if (card.number.replace(/\s/g, '').length !== 16) e.number = 'Enter a 16-digit card number';
    if (!/^\d{2}\/\d{2}$/.test(card.expiry))          e.expiry = 'Use MM/YY format';
    if (!/^\d{3,4}$/.test(card.cvv))                  e.cvv    = 'CVV must be 3–4 digits';
    setErrors(e);
    return Object.keys(e).length === 0;
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (validate()) onPay();
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div className="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg flex items-center justify-between text-sm">
        <span className="text-gray-500 dark:text-gray-400">Amount due</span>
        <span className="font-bold text-gray-900 dark:text-gray-100">{formatCurrency(amount)}</span>
      </div>

      <div className="p-4 rounded-xl bg-gradient-to-br from-primary-600 to-primary-800 text-white space-y-3">
        <p className="text-xs opacity-70 uppercase tracking-widest">Card Preview</p>
        <p className="font-mono text-lg tracking-widest">
          {(card.number || '•••• •••• •••• ••••')}
        </p>
        <div className="flex justify-between text-sm">
          <span>{card.name || 'CARDHOLDER NAME'}</span>
          <span>{card.expiry || 'MM/YY'}</span>
        </div>
      </div>

      <div>
        <label className="label">Cardholder Name</label>
        <input
          className={`input ${errors.name ? 'input-error' : ''}`}
          placeholder="John Doe"
          value={card.name}
          onChange={e => setCard(c => ({ ...c, name: e.target.value }))}
        />
        {errors.name && <p className="error-text">{errors.name}</p>}
      </div>

      <div>
        <label className="label">Card Number</label>
        <input
          className={`input font-mono ${errors.number ? 'input-error' : ''}`}
          placeholder="1234 5678 9012 3456"
          value={card.number}
          onChange={e => setCard(c => ({ ...c, number: formatCardNumber(e.target.value) }))}
        />
        {errors.number && <p className="error-text">{errors.number}</p>}
      </div>

      <div className="grid grid-cols-2 gap-3">
        <div>
          <label className="label">Expiry</label>
          <input
            className={`input ${errors.expiry ? 'input-error' : ''}`}
            placeholder="MM/YY"
            value={card.expiry}
            onChange={e => setCard(c => ({ ...c, expiry: formatExpiry(e.target.value) }))}
          />
          {errors.expiry && <p className="error-text">{errors.expiry}</p>}
        </div>
        <div>
          <label className="label">CVV</label>
          <input
            type="password"
            maxLength={4}
            className={`input ${errors.cvv ? 'input-error' : ''}`}
            placeholder="•••"
            value={card.cvv}
            onChange={e => setCard(c => ({ ...c, cvv: e.target.value.replace(/\D/g, '').slice(0, 4) }))}
          />
          {errors.cvv && <p className="error-text">{errors.cvv}</p>}
        </div>
      </div>

      <p className="text-xs text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/40 rounded-lg p-2">
        This is a test environment — enter any plausible values.
      </p>

      <div className="flex gap-2">
        <button type="button" onClick={onBack} className="btn-secondary flex-1">Back</button>
        <button type="submit" disabled={paying} className="btn-primary flex-1">
          {paying ? <LoadingSpinner size="sm" /> : `Pay ${formatCurrency(amount)}`}
        </button>
      </div>
    </form>
  );
}

function PaymentModal({ reservation, onClose, onPaid }) {
  const [step, setStep]     = useState('choose'); // 'choose' | 'card'
  const [paying, setPaying] = useState(false);
  const { success, apiError } = useToast();

  // reset to choose screen whenever modal opens for a new reservation
  const handleClose = () => { setStep('choose'); onClose(); };

  const payMock = async () => {
    setPaying(true);
    try {
      await paymentService.mockPay(reservation.id);
      success('Payment processed! Reservation is now paid.');
      setStep('choose');
      onPaid();
    } catch (err) {
      apiError(err);
    } finally {
      setPaying(false);
    }
  };

  const payPaypal = async () => {
    setPaying(true);
    try {
      const res = await paymentService.createPaypalOrder(reservation.id);
      window.open(res.data.data.approve_url, '_blank');
      success('PayPal window opened — complete your payment there.');
      handleClose();
    } catch (err) {
      apiError(err);
    } finally {
      setPaying(false);
    }
  };

  return (
    <Modal isOpen={!!reservation} onClose={handleClose} title={step === 'card' ? 'Card Details' : 'Choose Payment Method'} size="sm">
      {step === 'choose' ? (
        <div className="space-y-3">
          <div className="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg flex items-center justify-between text-sm">
            <span className="text-gray-500 dark:text-gray-400">Amount due</span>
            <span className="font-bold text-gray-900 dark:text-gray-100">{formatCurrency(reservation?.total_price)}</span>
          </div>

          <button
            onClick={() => setStep('card')}
            disabled={paying}
            className="w-full flex items-center gap-4 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 hover:border-primary-400 hover:bg-primary-50 dark:hover:border-primary-500 dark:hover:bg-primary-900/20 transition-colors disabled:opacity-50 text-left"
          >
            <div className="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
              <CreditCard className="w-5 h-5 text-emerald-700 dark:text-emerald-400" />
            </div>
            <div className="flex-1">
              <p className="font-semibold text-gray-900 dark:text-gray-100 text-sm">Credit / Debit Card</p>
              <p className="text-xs text-gray-400 dark:text-gray-500">Simulated card payment — instant confirmation</p>
            </div>
            <span className="text-gray-400 dark:text-gray-500 text-lg">›</span>
          </button>

          <button
            onClick={payPaypal}
            disabled={paying}
            className="w-full flex items-center gap-4 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 hover:border-blue-400 hover:bg-blue-50 dark:hover:border-blue-500 dark:hover:bg-blue-900/20 transition-colors disabled:opacity-50 text-left"
          >
            <div className="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
              <Landmark className="w-5 h-5 text-blue-700 dark:text-blue-400" />
            </div>
            <div className="flex-1">
              <p className="font-semibold text-gray-900 dark:text-gray-100 text-sm">PayPal</p>
              <p className="text-xs text-gray-400 dark:text-gray-500">Pay securely via PayPal sandbox</p>
            </div>
            {paying ? <LoadingSpinner size="sm" /> : <span className="text-gray-400 dark:text-gray-500 text-lg">›</span>}
          </button>
        </div>
      ) : (
        <CardForm
          amount={reservation?.total_price}
          onPay={payMock}
          onBack={() => setStep('choose')}
          paying={paying}
        />
      )}
    </Modal>
  );
}

export default function ClientReservations() {
  const [reservations, setReservations] = useState([]);
  const [meta, setMeta]                 = useState(null);
  const [loading, setLoading]           = useState(true);
  const [filter, setFilter]             = useState('');
  const [page, setPage]                 = useState(1);
  const [cancelTarget, setCancelTarget] = useState(null);
  const [payTarget, setPayTarget]       = useState(null);
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

  const handleCancel = async () => {
    setSubmitting(true);
    try {
      await reservationService.cancel(cancelTarget.id, 'Client requested cancellation');
      success('Reservation cancelled.');
      setCancelTarget(null);
      load();
    } catch (err) { apiError(err); } finally { setSubmitting(false); }
  };

  const STATUSES = ['', 'pending', 'confirmed', 'paid', 'cancelled'];

  return (
    <ClientLayout>
      <div className="page-header flex items-center justify-between">
        <div><h1 className="page-title">My Reservations</h1><p className="page-subtitle">Track and manage your bookings</p></div>
        <button onClick={load} className="btn-secondary"><RefreshCw className="w-4 h-4" /> Refresh</button>
      </div>

      <div className="flex gap-2 mb-4 flex-wrap">
        {STATUSES.map(s => (
          <button key={s || 'all'} onClick={() => { setFilter(s); setPage(1); }}
            className={`px-3 py-1 rounded-full text-xs font-medium transition-colors ${filter === s ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'}`}>
            {s || 'All'}
          </button>
        ))}
      </div>

      {loading ? <LoadingSpinner size="lg" className="mt-16" /> : (
        reservations.length === 0 ? (
          <div className="text-center py-20 text-gray-400 dark:text-gray-500">
            <p>No reservations found</p>
          </div>
        ) : (
          <>
            <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
              {reservations.map(r => (
                <ReservationCard key={r.id} reservation={r} actions={
                  <>
                    {(r.status === 'pending' || r.status === 'confirmed') && r.payment_status !== 'paid' && (
                      <button onClick={() => setPayTarget(r)} className="btn-success flex-1 text-xs py-1.5">
                        <CreditCard className="w-3.5 h-3.5" /> Pay Now
                      </button>
                    )}
                    {(r.status === 'pending' || r.status === 'confirmed') && (
                      <button onClick={() => setCancelTarget(r)} className="btn-danger flex-1 text-xs py-1.5">
                        Cancel
                      </button>
                    )}
                  </>
                } />
              ))}
            </div>
            <div className="mt-4 card"><Pagination meta={meta} onPageChange={setPage} /></div>
          </>
        )
      )}

      <PaymentModal
        reservation={payTarget}
        onClose={() => setPayTarget(null)}
        onPaid={() => { setPayTarget(null); load(); }}
      />

      <ConfirmDialog
        isOpen={!!cancelTarget}
        onClose={() => setCancelTarget(null)}
        onConfirm={handleCancel}
        loading={submitting}
        title="Cancel Reservation"
        message="Are you sure you want to cancel this reservation? This action cannot be undone."
        confirmLabel="Yes, Cancel"
      />
    </ClientLayout>
  );
}
