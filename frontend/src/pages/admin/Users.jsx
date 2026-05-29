import { useEffect, useState, useCallback } from 'react';
import { Plus, Pencil, Trash2, ToggleLeft, ToggleRight, Search } from 'lucide-react';
import userService from '../../services/userService';
import AdminLayout from '../../layouts/AdminLayout';
import Modal from '../../components/common/Modal';
import ConfirmDialog from '../../components/common/ConfirmDialog';
import Pagination from '../../components/common/Pagination';
import LoadingSpinner from '../../components/common/LoadingSpinner';
import { useToast } from '../../context/ToastContext';
import { formatDate, roleBadgeClass } from '../../utils/helpers';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';

const createSchema = z.object({
  name:     z.string().min(2),
  email:    z.string().email(),
  password: z.string().min(8),
  role:     z.string().min(1),
  phone:    z.string().optional(),
});

const editSchema = z.object({
  name:      z.string().min(2).optional(),
  email:     z.string().email().optional(),
  role:      z.string().optional(),
  phone:     z.string().optional(),
  is_active: z.boolean().optional(),
});

function UserForm({ onSubmit, defaultValues = {}, loading, submitLabel, schema }) {
  const { register, handleSubmit, formState: { errors } } = useForm({
    resolver: zodResolver(schema),
    defaultValues,
  });
  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      <div className="grid grid-cols-2 gap-4">
        <div>
          <label className="label">Name</label>
          <input {...register('name')} className={`input ${errors.name ? 'input-error' : ''}`} />
          {errors.name && <p className="error-text">{errors.name.message}</p>}
        </div>
        <div>
          <label className="label">Email</label>
          <input type="email" {...register('email')} className={`input ${errors.email ? 'input-error' : ''}`} />
          {errors.email && <p className="error-text">{errors.email.message}</p>}
        </div>
      </div>
      {schema === createSchema && (
        <div>
          <label className="label">Password</label>
          <input type="password" {...register('password')} className={`input ${errors.password ? 'input-error' : ''}`} />
          {errors.password && <p className="error-text">{errors.password.message}</p>}
        </div>
      )}
      <div className="grid grid-cols-2 gap-4">
        <div>
          <label className="label">Role</label>
          <select {...register('role')} className="input">
            <option value="client">Client</option>
            <option value="manager">Manager</option>
            <option value="admin">Admin</option>
            <option value="super-admin">Super Admin</option>
          </select>
        </div>
        <div>
          <label className="label">Phone</label>
          <input {...register('phone')} className="input" />
        </div>
      </div>
      {schema === editSchema && (
        <div className="flex items-center gap-2">
          <input type="checkbox" id="is_active" {...register('is_active')} className="rounded dark:accent-primary-500" />
          <label htmlFor="is_active" className="text-sm dark:text-gray-300">Active</label>
        </div>
      )}
      <button type="submit" disabled={loading} className="btn-primary w-full">
        {loading ? 'Saving…' : submitLabel}
      </button>
    </form>
  );
}

export default function AdminUsers() {
  const [users, setUsers]               = useState([]);
  const [meta, setMeta]                 = useState(null);
  const [loading, setLoading]           = useState(true);
  const [search, setSearch]             = useState('');
  const [page, setPage]                 = useState(1);
  const [createOpen, setCreateOpen]     = useState(false);
  const [editUser, setEditUser]         = useState(null);
  const [deleteTarget, setDeleteTarget] = useState(null);
  const [submitting, setSubmitting]     = useState(false);
  const { success, apiError }           = useToast();

  const load = useCallback(() => {
    setLoading(true);
    userService.list({ search, page }).then(r => {
      setUsers(r.data.data);
      setMeta(r.data.meta);
    }).catch(apiError).finally(() => setLoading(false));
  }, [search, page]);

  useEffect(() => { load(); }, [load]);

  const handleCreate = async (data) => {
    setSubmitting(true);
    try { await userService.create(data); success('User created.'); setCreateOpen(false); load(); }
    catch (err) { apiError(err); } finally { setSubmitting(false); }
  };

  const handleEdit = async (data) => {
    setSubmitting(true);
    try { await userService.update(editUser.id, data); success('User updated.'); setEditUser(null); load(); }
    catch (err) { apiError(err); } finally { setSubmitting(false); }
  };

  const handleDelete = async () => {
    setSubmitting(true);
    try { await userService.delete(deleteTarget.id); success('User deleted.'); setDeleteTarget(null); load(); }
    catch (err) { apiError(err); } finally { setSubmitting(false); }
  };

  const toggleStatus = async (user) => {
    try { await userService.toggleStatus(user.id); success('Status updated.'); load(); }
    catch (err) { apiError(err); }
  };

  return (
    <AdminLayout>
      <div className="page-header flex items-center justify-between">
        <div><h1 className="page-title">Users</h1><p className="page-subtitle">Manage system users</p></div>
        <button onClick={() => setCreateOpen(true)} className="btn-primary"><Plus className="w-4 h-4" /> Add User</button>
      </div>

      <div className="table-wrapper">
        <div className="table-header">
          <div className="relative max-w-xs">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <input value={search} onChange={e => { setSearch(e.target.value); setPage(1); }} placeholder="Search users…" className="input pl-9" />
          </div>
        </div>

        {loading ? <LoadingSpinner className="py-12" /> : (
          <>
            <div className="overflow-x-auto">
              <table className="data-table">
                <thead><tr><th>User</th><th>Role</th><th>Status</th><th>Joined</th><th>Actions</th></tr></thead>
                <tbody>
                  {users.length === 0 ? (
                    <tr><td colSpan={5} className="text-center py-10 text-gray-400 dark:text-gray-500">No users found</td></tr>
                  ) : users.map(u => (
                    <tr key={u.id}>
                      <td>
                        <div className="flex items-center gap-3">
                          <div className="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300 text-sm font-semibold flex items-center justify-center">
                            {u.name?.[0]?.toUpperCase()}
                          </div>
                          <div>
                            <p className="font-medium text-gray-900 dark:text-gray-100">{u.name}</p>
                            <p className="text-xs text-gray-400 dark:text-gray-500">{u.email}</p>
                          </div>
                        </div>
                      </td>
                      <td><span className={roleBadgeClass(u.roles?.[0]?.name)}>{u.roles?.[0]?.name ?? '—'}</span></td>
                      <td>
                        <span className={u.is_active ? 'badge-available' : 'badge-unavailable'}>
                          {u.is_active ? 'Active' : 'Inactive'}
                        </span>
                      </td>
                      <td className="text-gray-400 dark:text-gray-500 text-xs">{formatDate(u.created_at)}</td>
                      <td>
                        <div className="flex gap-1">
                          <button onClick={() => setEditUser(u)} className="btn-ghost p-1.5 text-blue-600 dark:text-blue-400"><Pencil className="w-4 h-4" /></button>
                          <button onClick={() => toggleStatus(u)} className={`btn-ghost p-1.5 ${u.is_active ? 'text-amber-600 dark:text-amber-400' : 'text-green-600 dark:text-green-400'}`}>
                            {u.is_active ? <ToggleRight className="w-4 h-4" /> : <ToggleLeft className="w-4 h-4" />}
                          </button>
                          <button onClick={() => setDeleteTarget(u)} className="btn-ghost p-1.5 text-red-600 dark:text-red-400"><Trash2 className="w-4 h-4" /></button>
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

      <Modal isOpen={createOpen} onClose={() => setCreateOpen(false)} title="Add User">
        <UserForm onSubmit={handleCreate} loading={submitting} submitLabel="Create User" schema={createSchema} />
      </Modal>
      <Modal isOpen={!!editUser} onClose={() => setEditUser(null)} title="Edit User">
        {editUser && <UserForm onSubmit={handleEdit} loading={submitting} defaultValues={{ ...editUser, role: editUser.roles?.[0]?.name, is_active: editUser.is_active }} submitLabel="Update User" schema={editSchema} />}
      </Modal>
      <ConfirmDialog isOpen={!!deleteTarget} onClose={() => setDeleteTarget(null)} onConfirm={handleDelete} loading={submitting}
        title="Delete User" message={`Delete ${deleteTarget?.name}? This action cannot be undone.`} />
    </AdminLayout>
  );
}
