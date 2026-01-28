import React, { useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import { Search, MapPin, User, Activity, Wifi, AlertCircle } from 'lucide-react';

export default function Tracking({ trackResult, flash }) {
    const { data, setData, get, processing, errors } = useForm({
        query: '',
    });

    const handleSearch = (e) => {
        e.preventDefault();
        get(route('customer.tools.tracking'), {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Helper warna status
    const getStatusColor = (status) => {
        switch (status) {
            case 'active': return 'bg-green-100 text-green-700 border-green-200';
            case 'suspended': return 'bg-red-100 text-red-700 border-red-200';
            case 'pending': return 'bg-yellow-100 text-yellow-700 border-yellow-200';
            default: return 'bg-gray-100 text-gray-700 border-gray-200';
        }
    };

    return (
        <div className="bg-white font-sans text-gray-900 min-h-screen flex flex-col">
            <Head title="Cek Status Pemasangan - NingratNet" />
            <Navbar />

            {/* Hero Simple */}
            <div className="pt-32 pb-16 bg-indigo-900 text-white text-center px-4">
                <h1 className="text-3xl md:text-4xl font-extrabold mb-4">Lacak Status Layanan</h1>
                <p className="text-indigo-200 text-lg max-w-xl mx-auto">
                    Masukkan ID Pelanggan atau Email yang terdaftar untuk melihat detail status pemasangan dan layanan Anda.
                </p>
            </div>

            {/* Search Box Section */}
            <div className="flex-grow bg-gray-50 py-12 px-4 relative">
                {/* Search Card */}
                <div className="max-w-2xl mx-auto -mt-24 relative z-10">
                    <div className="bg-white p-2 rounded-2xl shadow-xl border border-gray-100">
                        <form onSubmit={handleSearch} className="flex flex-col md:flex-row gap-2">
                            <div className="relative flex-grow">
                                <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size={20} />
                                <input
                                    type="text"
                                    placeholder="Masukkan ID Pelanggan (Contoh: 1) atau Email..."
                                    className="w-full pl-12 pr-4 py-4 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition"
                                    value={data.query}
                                    onChange={e => setData('query', e.target.value)}
                                />
                            </div>
                            <button
                                type="submit"
                                disabled={processing}
                                className="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-xl font-bold transition shadow-lg disabled:opacity-70 flex items-center justify-center gap-2"
                            >
                                {processing ? 'Mencari...' : 'Lacak'}
                            </button>
                        </form>
                    </div>
                </div>

                {/* Result Display */}
                <div className="max-w-2xl mx-auto mt-12">
                    {trackResult ? (
                        <div className="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden animate-fade-in-up">
                            <div className="bg-indigo-50 p-6 border-b border-indigo-100 flex justify-between items-center">
                                <div>
                                    <p className="text-xs font-bold text-indigo-500 uppercase tracking-wider">ID Pelanggan</p>
                                    <p className="text-xl font-bold text-gray-900">#{trackResult.id}</p>
                                </div>
                                <div className={`px-4 py-1.5 rounded-full text-sm font-bold border ${getStatusColor(trackResult.status)} capitalize flex items-center gap-2`}>
                                    <Activity size={16} />
                                    {trackResult.status}
                                </div>
                            </div>

                            <div className="p-8 space-y-6">
                                {/* Nama */}
                                <div className="flex items-start gap-4">
                                    <div className="bg-gray-100 p-3 rounded-full text-gray-500">
                                        <User size={24} />
                                    </div>
                                    <div>
                                        <p className="text-sm text-gray-500 mb-1">Nama Pelanggan</p>
                                        <h3 className="text-lg font-bold text-gray-800">{trackResult.name}</h3>
                                        <p className="text-sm text-gray-400">{trackResult.email}</p>
                                    </div>
                                </div>

                                <div className="h-px bg-gray-100 w-full"></div>

                                {/* Alamat */}
                                <div className="flex items-start gap-4">
                                    <div className="bg-gray-100 p-3 rounded-full text-gray-500">
                                        <MapPin size={24} />
                                    </div>
                                    <div>
                                        <p className="text-sm text-gray-500 mb-1">Alamat Pemasangan</p>
                                        <p className="text-gray-700 leading-relaxed">
                                            {trackResult.address || 'Alamat tidak tercatat'}
                                        </p>
                                    </div>
                                </div>

                                <div className="h-px bg-gray-100 w-full"></div>

                                {/* Paket */}
                                <div className="flex items-start gap-4">
                                    <div className="bg-gray-100 p-3 rounded-full text-gray-500">
                                        <Wifi size={24} />
                                    </div>
                                    <div>
                                        <p className="text-sm text-gray-500 mb-1">Paket Berlangganan</p>
                                        {trackResult.package ? (
                                            <div>
                                                <p className="font-bold text-indigo-600 text-lg">{trackResult.package.name}</p>
                                                <p className="text-sm text-gray-500">{trackResult.package.speed} - Rp {trackResult.package.price.toLocaleString('id-ID')}</p>
                                            </div>
                                        ) : (
                                            <p className="text-gray-500 italic">Belum memilih paket</p>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    ) : flash.error ? (
                        <div className="bg-red-50 border border-red-200 rounded-2xl p-6 flex flex-col items-center text-center text-red-700 animate-pulse">
                            <AlertCircle size={48} className="mb-3 opacity-50" />
                            <h3 className="font-bold text-lg">Data Tidak Ditemukan</h3>
                            <p className="text-sm mt-1">{flash.error}</p>
                        </div>
                    ) : (
                        <div className="text-center text-gray-400 py-10">
                            <p>Silakan masukkan ID atau Email untuk mulai melacak.</p>
                        </div>
                    )}
                </div>
            </div>

            <Footer />
        </div>
    );
}
