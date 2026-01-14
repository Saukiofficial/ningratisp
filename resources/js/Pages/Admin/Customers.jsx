import React, { useState } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { useForm } from '@inertiajs/react';

export default function Customers({ users, packages }) {
  const { data, setData, put } = useForm({ status: '', package_id: '' });
  const [editingUser, setEditingUser] = useState(null);

  const updateStatus = (e) => {
    e.preventDefault();
    put(route('customers.update', editingUser.id), {
        onSuccess: () => setEditingUser(null)
    });
  };

  return (
    <AdminLayout title="Data Pelanggan">
      {/* Table Customers rendering 'users' props */}
      {/* Logic Modal Update Status mirip dengan di AdminPanel.jsx tapi menggunakan useForm Inertia */}
    </AdminLayout>
  );
}
