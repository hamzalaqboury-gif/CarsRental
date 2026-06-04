import { useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { Upload, User } from 'lucide-react';
import ClientLayout from '../../layouts/ClientLayout';
import { useAuth } from '../../context/AuthContext';
import { useToast } from '../../context/ToastContext';
import authService from '../../services/authService';
import api from '../../services/api';
import { getImageUrl, roleBadgeClass } from '../../utils/helpers';
import LoadingSpinner from '../../components/common/LoadingSpinner';

const profileSchema = z.object({
  name:    z.string().min(2),
  phone:   z.string().optional(),
  address: z.string().optional(),
});

const passwordSchema = z.object({
  current_password:      z.string().min(1),
  password:              z.string().min(8),
  password_confirmation: z.string(),
}).refine(d => d.password === d.password_confirmation, {
  message: 'Passwords do not match', path: ['password_confirmation'],
});

export default function Profile() {
  const { user, updateUser }      = useAuth();
  const { success, apiError }     = useToast();
  const [saving, setSaving]       = useState(false);
  const [pwSaving, setPwSaving]   = useState(false);
  const [uploading, setUploading] = useState(false);
  const role                      = user?.roles?.[0]?.name;

  const { register, handleSubmit, formState: { errors } } = useForm({
    resolver: zodResolver(profileSchema),
    defaultValues: { name: user?.name, phone: user?.phone ?? '', address: user?.address ?? '' },
  });

  const { register: regPw, handleSubmit: handlePw, formState: { errors: pwErrors }, reset: resetPw } = useForm({
    resolver: zodResolver(passwordSchema),
  });

  const onSaveProfile = async (data) => {
    setSaving(true);
    try { const res = await authService.updateProfile(data); updateUser(res.data.data); success('Profile updated.'); }
    catch (err) { apiError(err); } finally { setSaving(false); }
  };

  const onChangePassword = async (data) => {
    setPwSaving(true);
    try { await authService.updateProfile(data); success('Password changed.'); resetPw(); }
    catch (err) { apiError(err); } finally { setPwSaving(false); }
  };

  const uploadAvatar = async (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    setUploading(true);
    const formData = new FormData();
    formData.append('avatar', file);
    try {
      const res = await api.post('/upload/avatar', formData);
      updateUser({ ...user, avatar: res.data.data.path });
      success('Avatar updated.');
    } catch (err) { apiError(err); } finally { setUploading(false); }
  };

  const uploadLicense = async (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    setUploading(true);
    const formData = new FormData();
    formData.append('driver_license', file);
    try {
      const res = await api.post('/upload/driver-license', formData);
      updateUser({ ...user, driver_license: res.data.data.path });
      success('Driver license uploaded.');
    } catch (err) { apiError(err); } finally { setUploading(false); }
  };

  const avatarUrl = getImageUrl(user?.avatar);

  return (
    <ClientLayout>
      <div className="page-header">
        <h1 className="page-title">Profile</h1>
        <p className="page-subtitle">Manage your account settings</p>
      </div>

      <div className="grid lg:grid-cols-3 gap-6">
        <div className="card p-6 text-center">
          <div className="relative inline-block mb-4">
            <div className="w-24 h-24 rounded-full bg-primary-100 dark:bg-primary-900/50 text-primary-600 dark:text-primary-300 text-3xl font-bold flex items-center justify-center overflow-hidden mx-auto">
              {avatarUrl ? <img src={avatarUrl} alt="Avatar" className="w-full h-full object-cover" /> : user?.name?.[0]?.toUpperCase()}
            </div>
            <label className="absolute bottom-0 right-0 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-full p-1.5 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600 shadow-sm">
              {uploading ? <LoadingSpinner size="sm" /> : <Upload className="w-3.5 h-3.5 text-gray-600 dark:text-gray-300" />}
              <input type="file" className="hidden" accept="image/*" onChange={uploadAvatar} />
            </label>
          </div>
          <h3 className="font-semibold text-gray-900 dark:text-gray-100">{user?.name}</h3>
          <p className="text-sm text-gray-500 dark:text-gray-400 mb-2">{user?.email}</p>
          <span className={roleBadgeClass(role)}>{role}</span>

          <div className="mt-5 pt-5 border-t border-gray-100 dark:border-gray-700 text-left space-y-2 text-sm">
            <div className="flex justify-between">
              <span className="text-gray-400 dark:text-gray-500">Phone</span>
              <span className="dark:text-gray-300">{user?.phone || '—'}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-gray-400 dark:text-gray-500">Address</span>
              <span className="text-right max-w-[60%] truncate dark:text-gray-300">{user?.address || '—'}</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-gray-400 dark:text-gray-500">License</span>
              {user?.driver_license
                ? <span className="badge-available">Uploaded</span>
                : (
                  <label className="btn-secondary text-xs py-1 cursor-pointer">
                    <Upload className="w-3 h-3" /> Upload
                    <input type="file" className="hidden" accept=".pdf,image/*" onChange={uploadLicense} />
                  </label>
                )}
            </div>
          </div>
        </div>

        <div className="lg:col-span-2 space-y-5">
          <div className="card p-6">
            <h3 className="font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2"><User className="w-4 h-4" /> Personal Info</h3>
            <form onSubmit={handleSubmit(onSaveProfile)} className="space-y-4">
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="label">Full Name</label>
                  <input {...register('name')} className={`input ${errors.name ? 'input-error' : ''}`} />
                  {errors.name && <p className="error-text">{errors.name.message}</p>}
                </div>
                <div>
                  <label className="label">Phone</label>
                  <input {...register('phone')} className="input" />
                </div>
              </div>
              <div>
                <label className="label">Address</label>
                <input {...register('address')} className="input" />
              </div>
              <button type="submit" disabled={saving} className="btn-primary">{saving ? 'Saving…' : 'Save Changes'}</button>
            </form>
          </div>

          <div className="card p-6">
            <h3 className="font-semibold text-gray-900 dark:text-gray-100 mb-4">Change Password</h3>
            <form onSubmit={handlePw(onChangePassword)} className="space-y-4">
              <div>
                <label className="label">Current Password</label>
                <input type="password" {...regPw('current_password')} className={`input ${pwErrors.current_password ? 'input-error' : ''}`} />
                {pwErrors.current_password && <p className="error-text">{pwErrors.current_password.message}</p>}
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="label">New Password</label>
                  <input type="password" {...regPw('password')} className={`input ${pwErrors.password ? 'input-error' : ''}`} />
                  {pwErrors.password && <p className="error-text">{pwErrors.password.message}</p>}
                </div>
                <div>
                  <label className="label">Confirm Password</label>
                  <input type="password" {...regPw('password_confirmation')} className={`input ${pwErrors.password_confirmation ? 'input-error' : ''}`} />
                  {pwErrors.password_confirmation && <p className="error-text">{pwErrors.password_confirmation.message}</p>}
                </div>
              </div>
              <button type="submit" disabled={pwSaving} className="btn-primary">{pwSaving ? 'Changing…' : 'Change Password'}</button>
            </form>
          </div>
        </div>
      </div>
    </ClientLayout>
  );
}
