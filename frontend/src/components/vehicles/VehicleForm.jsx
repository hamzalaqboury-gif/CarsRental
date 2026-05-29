import { useRef, useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { VEHICLE_CATEGORIES, TRANSMISSION_TYPES, FUEL_TYPES } from '../../utils/constants';
import LoadingSpinner from '../common/LoadingSpinner';
import { getImageUrl } from '../../utils/helpers';

const schema = z.object({
  brand:         z.string().min(1, 'Brand is required'),
  model:         z.string().min(1, 'Model is required'),
  category:      z.string().min(1, 'Category is required'),
  transmission:  z.string().min(1, 'Transmission is required'),
  fuel_type:     z.string().min(1, 'Fuel type is required'),
  price_per_day: z.coerce.number().min(1, 'Price must be at least $1'),
  year:          z.coerce.number().min(1990).max(new Date().getFullYear() + 1).optional().nullable(),
  seats:         z.coerce.number().min(1).max(20).optional().nullable(),
  color:         z.string().optional(),
  license_plate: z.string().optional(),
  description:   z.string().optional(),
  is_available:  z.boolean().optional(),
});

export default function VehicleForm({ onSubmit, defaultValues = {}, loading = false, submitLabel = 'Save Vehicle' }) {
  const fileRef = useRef(null);
  const [preview, setPreview] = useState(null);

  const { register, handleSubmit, formState: { errors } } = useForm({
    resolver: zodResolver(schema),
    defaultValues: { is_available: true, seats: 5, ...defaultValues },
  });

  const handleFileChange = (e) => {
    const file = e.target.files?.[0];
    if (file) setPreview(URL.createObjectURL(file));
  };

  const handleFormSubmit = (data) => {
    const formData = new FormData();
    Object.entries(data).forEach(([k, v]) => {
      if (v === null || v === undefined || v === '') return;
      // Laravel boolean validation accepts "0"/"1" strings
      formData.append(k, typeof v === 'boolean' ? (v ? '1' : '0') : v);
    });
    if (fileRef.current?.files?.[0]) {
      formData.append('image', fileRef.current.files[0]);
    }
    onSubmit(formData);
  };

  const currentImage = preview || getImageUrl(defaultValues.image);

  return (
    <form onSubmit={handleSubmit(handleFormSubmit)} className="space-y-4">
      <div className="grid grid-cols-2 gap-4">
        <div>
          <label className="label">Brand *</label>
          <input {...register('brand')} className={`input ${errors.brand ? 'input-error' : ''}`} placeholder="Toyota" />
          {errors.brand && <p className="error-text">{errors.brand.message}</p>}
        </div>
        <div>
          <label className="label">Model *</label>
          <input {...register('model')} className={`input ${errors.model ? 'input-error' : ''}`} placeholder="Camry" />
          {errors.model && <p className="error-text">{errors.model.message}</p>}
        </div>
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div>
          <label className="label">Category *</label>
          <select {...register('category')} className={`input ${errors.category ? 'input-error' : ''}`}>
            <option value="">Select category</option>
            {VEHICLE_CATEGORIES.map(c => <option key={c.value} value={c.value}>{c.label}</option>)}
          </select>
          {errors.category && <p className="error-text">{errors.category.message}</p>}
        </div>
        <div>
          <label className="label">Transmission *</label>
          <select {...register('transmission')} className={`input ${errors.transmission ? 'input-error' : ''}`}>
            <option value="">Select type</option>
            {TRANSMISSION_TYPES.map(t => <option key={t.value} value={t.value}>{t.label}</option>)}
          </select>
          {errors.transmission && <p className="error-text">{errors.transmission.message}</p>}
        </div>
      </div>

      <div className="grid grid-cols-3 gap-4">
        <div>
          <label className="label">Fuel Type *</label>
          <select {...register('fuel_type')} className={`input ${errors.fuel_type ? 'input-error' : ''}`}>
            <option value="">Select fuel</option>
            {FUEL_TYPES.map(f => <option key={f.value} value={f.value}>{f.label}</option>)}
          </select>
          {errors.fuel_type && <p className="error-text">{errors.fuel_type.message}</p>}
        </div>
        <div>
          <label className="label">Price / Day ($) *</label>
          <input type="number" step="0.01" {...register('price_per_day')} className={`input ${errors.price_per_day ? 'input-error' : ''}`} />
          {errors.price_per_day && <p className="error-text">{errors.price_per_day.message}</p>}
        </div>
        <div>
          <label className="label">Year</label>
          <input type="number" {...register('year')} className="input" placeholder="2023" />
        </div>
      </div>

      <div className="grid grid-cols-3 gap-4">
        <div>
          <label className="label">Seats</label>
          <input type="number" {...register('seats')} className="input" />
        </div>
        <div>
          <label className="label">Color</label>
          <input {...register('color')} className="input" placeholder="Silver" />
        </div>
        <div>
          <label className="label">License Plate</label>
          <input {...register('license_plate')} className="input" placeholder="ABC-1234" />
        </div>
      </div>

      <div>
        <label className="label">Description</label>
        <textarea {...register('description')} rows={3} className="input resize-none" placeholder="Vehicle description..." />
      </div>

      <div>
        <label className="label">Vehicle Image</label>
        {currentImage && (
          <div className="mb-2 rounded-lg overflow-hidden h-36 bg-gray-100 dark:bg-gray-700">
            <img src={currentImage} alt="Vehicle preview" className="w-full h-full object-cover" />
          </div>
        )}
        <input
          ref={fileRef}
          type="file"
          accept="image/jpeg,image/png,image/webp"
          className="input py-1.5 cursor-pointer"
          onChange={handleFileChange}
        />
        <p className="text-xs text-gray-400 dark:text-gray-500 mt-1">JPEG, PNG or WebP, max 5MB</p>
      </div>

      <div className="flex items-center gap-2">
        <input type="checkbox" id="is_available_check" {...register('is_available')} className="rounded border-gray-300 dark:border-gray-600 text-primary-600 dark:bg-gray-700" />
        <label htmlFor="is_available_check" className="text-sm text-gray-700 dark:text-gray-300">Available for rental</label>
      </div>

      <button type="submit" disabled={loading} className="btn-primary w-full py-2.5">
        {loading ? <LoadingSpinner size="sm" /> : submitLabel}
      </button>
    </form>
  );
}
