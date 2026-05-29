import { CalendarDays, Car, DollarSign } from 'lucide-react';
import { formatCurrency, formatDate, statusBadgeClass } from '../../utils/helpers';

export default function ReservationCard({ reservation, actions }) {
  const { vehicle, start_date, end_date, total_days, total_price, status } = reservation;

  return (
    <div className="card p-4 flex flex-col gap-3">
      <div className="flex items-start justify-between">
        <div>
          <div className="flex items-center gap-2">
            <Car className="w-4 h-4 text-primary-600 dark:text-primary-400" />
            <span className="font-semibold text-gray-900 dark:text-gray-100">
              {vehicle?.brand} {vehicle?.model}
            </span>
          </div>
          <p className="text-xs text-gray-500 dark:text-gray-400 mt-0.5 capitalize">{vehicle?.category}</p>
        </div>
        <span className={statusBadgeClass(status)}>{status}</span>
      </div>

      <div className="grid grid-cols-3 gap-3 text-sm">
        <div>
          <p className="text-gray-400 dark:text-gray-500 text-xs">Pickup</p>
          <p className="font-medium text-gray-800 dark:text-gray-200">{formatDate(start_date)}</p>
        </div>
        <div>
          <p className="text-gray-400 dark:text-gray-500 text-xs">Return</p>
          <p className="font-medium text-gray-800 dark:text-gray-200">{formatDate(end_date)}</p>
        </div>
        <div>
          <p className="text-gray-400 dark:text-gray-500 text-xs">Duration</p>
          <p className="font-medium text-gray-800 dark:text-gray-200">{total_days} day{total_days !== 1 ? 's' : ''}</p>
        </div>
      </div>

      <div className="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-700">
        <div className="flex items-center gap-1.5">
          <DollarSign className="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
          <span className="font-bold text-gray-900 dark:text-gray-100">{formatCurrency(total_price)}</span>
          <span className="text-xs text-gray-400 dark:text-gray-500">total</span>
        </div>
        {actions && <div className="flex gap-2">{actions}</div>}
      </div>
    </div>
  );
}
