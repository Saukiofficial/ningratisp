import React, { useState } from 'react';
import CustomerLayout from '@/Layouts/CustomerLayout';
import { useForm } from '@inertiajs/react';
import Modal from '@/Components/Modal'; // Asumsi komponen Modal reusable
import { Plus, Clock, CheckCircle } from 'lucide-react';

export default function Complaints({ complaints }) {
    const [isOpen, setIsOpen] = useState(false);
    const { data, setData, post, reset, processing, errors } = useForm({
        subject: '',
        description: ''
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('customer.complaints.store'), {
            onSuccess: () => {
                setIsOpen(false);
                reset();
            }
        });
    };

    return (
        <CustomerLayout title="Riwayat Pengaduan">
            <div className="flex justify-between items-center mb-6">
                <p className="text-gray-600">Laporkan kendala teknis Anda di sini.</p>
                <button
                    onClick={() => setIsOpen(true)}
                    className="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition"
                >
                    <Plus size={18} /> Buat Tiket Baru
                </button>
            </div>

            <div className="space-y-4">
                {complaints.length > 0 ? (
                    complaints.map((item) => (
                        <div key={item.id} className="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
                            <div className="flex justify-between items-start">
                                <div>
                                    <h3 className="font-bold text-gray-800 text-lg">{item.subject}</h3>
                                    <p className="text-gray-600 mt-1">{item.description}</p>
                                    <span className="text-xs text-gray-400 mt-2 block">{item.created_at}</span>
                                </div>
                                <div className={`px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1 ${item.status === 'resolved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'
                                    }`}>
                                    {item.status === 'resolved' ? <CheckCircle size={14} /> : <Clock size={14} />}
                                    {item.status === 'resolved' ? 'Selesai' : 'Menunggu'}
                                </div>
                            </div>
                        </div>
                    ))
                ) : (
                    <div className="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        <p className="text-gray-500">Belum ada riwayat pengaduan.</p>
                    </div>
                )}
            </div>

            {/* Modal Form Pengaduan */}
            <Modal isOpen={isOpen} onClose={() => setIsOpen(false)} title="Buat Pengaduan Baru">
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Judul Masalah</label>
                        <input
                            type="text"
                            className="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Contoh: Internet Mati Total"
                            value={data.subject}
                            onChange={e => setData('subject', e.target.value)}
                        />
                        {errors.subject && <span className="text-red-500 text-xs">{errors.subject}</span>}
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Deskripsi Detail</label>
                        <textarea
                            rows="4"
                            className="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Jelaskan kendala yang Anda alami..."
                            value={data.description}
                            onChange={e => setData('description', e.target.value)}
                        />
                        {errors.description && <span className="text-red-500 text-xs">{errors.description}</span>}
                    </div>
                    <div className="flex justify-end gap-2 mt-6">
                        <button type="button" onClick={() => setIsOpen(false)} className="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                        <button
                            type="submit"
                            disabled={processing}
                            className="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50"
                        >
                            {processing ? 'Mengirim...' : 'Kirim Pengaduan'}
                        </button>
                    </div>
                </form>
            </Modal>
        </CustomerLayout>
    );
}
