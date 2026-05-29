import { Users, Fuel, Settings } from 'lucide-react';
import { formatCurrency, getImageUrl } from '../../utils/helpers';

export default function VehicleCard({ vehicle, actions }) {
  const img = getImageUrl(vehicle.image);

  return (
    <div className="card-hover overflow-hidden flex flex-col">
      <div className="relative h-44 bg-gray-100 dark:bg-gray-700">
        {img ? (
          <img src={img} alt={`${vehicle.brand} ${vehicle.model}`} className="w-full h-full object-cover" />
        ) : (
          <div className="w-full h-full flex items-center justify-center">
            <span className="text-5xl">🚗</span>
          </div>
        )}
        <span className={`absolute top-2 left-2 ${vehicle.is_available ? 'badge-available' : 'badge-unavailable'}`}>
          {vehicle.is_available ? 'Available' : 'Unavailable'}
        </span>
      </div>

      <div className="p-4 flex flex-col flex-1">
        <div className="flex items-start justify-between mb-2">
          <div>
            <h3 className="font-semibold text-gray-900 dark:text-gray-100">{vehicle.brand} {vehicle.model}</h3>
            <span className="text-xs text-gray-500 dark:text-gray-400 capitalize">{vehicle.category} · {vehicle.year}</span>
          </div>
          <div className="text-right">
            <div className="text-lg font-bold text-primary-600 dark:text-primary-400">{formatCurrency(vehicle.price_per_day)}</div>
            <div className="text-xs text-gray-400 dark:text-gray-500">per day</div>
          </div>
        </div>

        <div className="grid grid-cols-3 gap-2 my-3 text-xs text-gray-500 dark:text-gray-400">
          <div className="flex items-center gap-1">
            <Users className="w-3.5 h-3.5" />{vehicle.seats} seats
          </div>
          <div className="flex items-center gap-1">
            <Settings className="w-3.5 h-3.5" />{vehicle.transmission}
          </div>
          <div className="flex items-center gap-1">
            <Fuel className="w-3.5 h-3.5" />{vehicle.fuel_type}
          </div>
        </div>

        {actions && <div className="mt-auto pt-3 border-t border-gray-100 dark:border-gray-700">{actions}</div>}
      </div>
    </div>
  );
}
