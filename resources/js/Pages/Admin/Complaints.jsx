import React from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { useForm } from '@inertiajs/react';
import { CheckCircle, Users } from 'lucide-react';

export default function Complaints({ complaints }) {
    // complaints adalah props yang dikirim dari Controller: Complaint::with('user')->get()

    const { put, processing } = useForm();

    const handleResolve = (id) => {
        if (confirm('Tandai keluhan ini sebagai selesai?')) {
            put(route('complaints.resolve', id));
        }
    };

    // Helper sederhana untuk Badge Status
    const StatusBadge = ({ status }) => {
        const styles = {
            resolved: 'bg-green-100 text-green-800',
            pending: 'bg-yellow-100 text-yellow-800',
        };
        const label = status === 'resolved' ? 'Selesai' : 'Menunggu';

        return (
            <span className={`px-2 py-1 rounded-full text-xs font-semibold ${styles[status]}`}>
                {label}
            </span>
        );
    };

    return (
        <AdminLayout title="Layanan Pengaduan">
            <div className="space-y-4">
                {complaints.length === 0 ? (
                    <div className="text-center py-10 text-gray-500">Belum ada pengaduan.</div>
                ) : (
                    complaints.map(complaint => (
                        <div key={complaint.id} className="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center">
                            <div className="mb-4 md:mb-0">
                                <div className="flex items-center space-x-2 mb-1">
                                    <StatusBadge status={complaint.status} />
                                    <span className="text-xs text-gray-400">{complaint.created_at_formatted}</span>
                                </div>
                                <h3 className="font-bold text-gray-800 text-lg">{complaint.subject}</h3>
                                <p className="text-gray-600 text-sm mt-1">"{complaint.description}"</p>
                                <div className="text-xs text-gray-400 mt-2 flex items-center">
                                    <Users size={12} className="mr-1" />
                                    {complaint.user ? `${complaint.user.name} (${complaint.user.email})` : 'User Tidak Diketahui'}
                                </div>
                            </div>

                            {complaint.status === 'pending' && (
                                <button
                                    onClick={() => handleResolve(complaint.id)}
                                    disabled={processing}
                                    className="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center transition disabled:opacity-50"
                                >
                                    <CheckCircle size={16} className="mr-2" /> Selesaikan
                                </button>
                            )}
                        </div>
                    ))
                )}
            </div>
        </AdminLayout>
    );
}
