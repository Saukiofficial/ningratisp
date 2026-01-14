import React from 'react';
import CustomerLayout from '@/Layouts/CustomerLayout';
import { Wifi, Activity, CreditCard } from 'lucide-react';

export default function Dashboard({ subscription }) {
    // Props 'subscription' berisi detail paket user saat ini

    return (
        <CustomerLayout title="Dashboard Saya">
            <div className="grid md:grid-cols-3 gap-6 mb-8">
                {/* Kartu Paket Aktif */}
                <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div className="absolute right-0 top-0 p-4 opacity-10">
                        <Wifi size={100} className="text-indigo-600" />
                    </div>
                    <div className="relative z-10">
                        <p className="text-sm text-gray-500 mb-1">Paket Internet</p>
                        <h3 className="text-2xl font-bold text-gray-800">{subscription.package_name}</h3>
                        <p className="text-indigo-600 font-medium mt-2">{subscription.speed}</p>
                    </div>
                </div>

                {/* Kartu Status */}
                <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div className={`p-3 rounded-full ${subscription.status === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'}`}>
                        <Activity size={24} />
                    </div>
                    <div>
                        <p className="text-sm text-gray-500">Status Koneksi</p>
                        <h3 className="text-xl font-bold text-gray-800 capitalize">{subscription.status}</h3>
                    </div>
                </div>

                {/* Kartu Tagihan */}
                <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div className="p-3 rounded-full bg-blue-100 text-blue-600">
                        <CreditCard size={24} />
                    </div>
                    <div>
                        <p className="text-sm text-gray-500">Tagihan Bulanan</p>
                        <h3 className="text-xl font-bold text-gray-800">Rp {subscription.price.toLocaleString('id-ID')}</h3>
                    </div>
                </div>
            </div>

            <div className="bg-indigo-50 border border-indigo-100 rounded-xl p-6 text-center">
                <h3 className="text-lg font-bold text-indigo-900 mb-2">Butuh Bantuan Teknis?</h3>
                <p className="text-indigo-700 mb-4">Jika koneksi Anda mengalami gangguan, segera buat tiket pengaduan.</p>
                <a href="/customer/complaints" className="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition">
                    Buat Pengaduan
                </a>
            </div>
        </CustomerLayout>
    );
}
