import React, { useState } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { useForm } from '@inertiajs/react'; // Penting untuk Inertia
import { Plus, Edit, Trash2 } from 'lucide-react';
import Modal from '@/Components/Modal'; // Anggap komponen Modal sudah dipisah

export default function Packages({ packages }) {
  // 'packages' adalah props dari Controller: Package::all()

  const [isModalOpen, setIsModalOpen] = useState(false);
  const { data, setData, post, put, delete: destroy, reset } = useForm({
    id: null, name: '', speed: '', price: '', description: ''
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    if (data.id) {
        put(route('packages.update', data.id), { onSuccess: () => setIsModalOpen(false) });
    } else {
        post(route('packages.store'), { onSuccess: () => setIsModalOpen(false) });
    }
  };

  const handleDelete = (id) => {
    if(confirm('Hapus paket?')) destroy(route('packages.destroy', id));
  };

  return (
    <AdminLayout title="Manajemen Paket">
      <button onClick={() => setIsModalOpen(true)} className="bg-indigo-600 text-white px-4 py-2 rounded mb-4">
        + Tambah Paket
      </button>

      <div className="bg-white rounded-xl shadow-sm overflow-hidden">
        <table className="w-full text-left">
          {/* ... Header Table ... */}
          <tbody>
            {packages.map(pkg => (
              <tr key={pkg.id} className="hover:bg-gray-50">
                <td className="p-4">{pkg.name}</td>
                <td className="p-4">{pkg.speed}</td>
                <td className="p-4">Rp {pkg.price}</td>
                <td className="p-4">
                    <button onClick={() => { setData(pkg); setIsModalOpen(true); }} className="text-blue-600 mr-2"><Edit size={16}/></button>
                    <button onClick={() => handleDelete(pkg.id)} className="text-red-600"><Trash2 size={16}/></button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Modal Form disini */}
      <Modal isOpen={isModalOpen} onClose={() => setIsModalOpen(false)}>
        {/* Form menggunakan value={data.name} onChange={e => setData('name', e.target.value)} */}
      </Modal>
    </AdminLayout>
  );
}
