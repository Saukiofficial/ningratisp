import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';


const UserCircleIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
);

const ShieldCheckIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.623 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
    </svg>
);

const WifiIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z" />
    </svg>
);

const BillIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
    </svg>
);

export default function Dashboard({ pelanggan, statusLangganan, unpaid_invoices, pending_va }) {
    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    const formatDate = (dateString) => {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    };

    return (
        <AuthenticatedLayout>
            <Head title="Dashboard" />


            <header className="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 overflow-hidden">
                <div className="absolute inset-0 bg-black/10"></div>
                <div className="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width=%2260%22%20height=%2260%22%20viewBox=%220%200%2060%2060%22%20xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg%20fill=%22none%22%20fill-rule=%22evenodd%22%3E%3Cg%20fill=%22%23ffffff%22%20fill-opacity=%220.05%22%3E%3Ccircle%20cx=%2230%22%20cy=%2230%22%20r=%222%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>

                <div className="relative max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center space-x-4">
                        <div className="bg-white/20 backdrop-blur-sm p-3 rounded-2xl">
                            <WifiIcon />
                        </div>
                        <div className="text-white">
                            <p className="text-sm font-medium opacity-90">Selamat Datang Kembali</p>
                            <h1 className="text-2xl sm:text-3xl font-bold">
                                {pelanggan.nama}
                            </h1>
                        </div>
                    </div>
                </div>
            </header>

            <main className="bg-gray-50 flex-grow">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4 relative z-10">

                    {pending_va && (
                        <div className="mb-8 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
                            <p className="font-bold">Anda memiliki pembayaran yang belum selesai</p>
                            <p>Selesaikan pembayaran untuk invoice #{pending_va.invoice.invoice_number} sebelum waktu habis.</p>
                            <Link href={route('pending-payment.show', pending_va.id)} className="font-bold text-blue-800 hover:text-blue-900">
                                Lihat Detail Pembayaran
                            </Link>
                        </div>
                    )}

                    <div className="mb-8">
                        <div className={`bg-white rounded-2xl shadow-xl border-l-4 p-6 ${statusLangganan
                                ? 'border-emerald-500 bg-gradient-to-r from-emerald-50/50 to-white'
                                : 'border-red-500 bg-gradient-to-r from-red-50/50 to-white'
                            }`}>
                            <div className="flex items-center justify-between">
                                <div className="flex items-center space-x-4">
                                    <div className={`p-4 rounded-xl ${statusLangganan
                                            ? 'bg-emerald-100 text-emerald-600'
                                            : 'bg-red-100 text-red-600'
                                        }`}>
                                        <ShieldCheckIcon />
                                    </div>
                                    <div>
                                        <p className="text-sm font-medium text-gray-600 mb-1">Status Koneksi WiFi</p>
                                        {statusLangganan ? (
                                            <div className="flex items-center space-x-2">
                                                <span className="px-4 py-2 text-sm font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    ✓ Aktif & Terhubung
                                                </span>
                                            </div>
                                        ) : (
                                            <div className="flex items-center space-x-2">
                                                <span className="px-4 py-2 text-sm font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                    ⚠ Ada Tagihan Tertunggak
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                </div>
                                <div className={`h-4 w-4 rounded-full ${statusLangganan ? 'bg-emerald-400 animate-pulse' : 'bg-red-400 animate-pulse'
                                    }`}></div>
                            </div>
                        </div>
                    </div>

                    {unpaid_invoices.length > 0 && (
                        <div className="mb-8">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">Tagihan Belum Dibayar</h3>
                            <div className="space-y-4">
                                <div className="bg-white rounded-2xl shadow-lg border-l-4 border-yellow-400 p-6 flex justify-between items-center transition hover:shadow-xl hover:border-yellow-500">
                                    <div>
                                        <p className="text-sm font-medium text-gray-500">Jatuh Tempo: {formatDate(unpaid_invoices[0].due_date)}</p>
                                        <p className="text-xl font-bold text-gray-800 mt-1">{formatRupiah(unpaid_invoices[0].balance_due)}</p>
                                    </div>
                                    <Link href={`/pembayaran/${unpaid_invoices[0].id}`} className="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Bayar Sekarang
                                    </Link>
                                </div>

                                {unpaid_invoices.length > 1 && (
                                    <div className="text-center pt-2">
                                        <Link
                                            href={route('invoices.index', { status: 'unpaid' })}
                                            className="text-sm font-medium text-blue-600 hover:text-blue-800 transition duration-150"
                                        >
                                            Lihat {unpaid_invoices.length - 1} tagihan lainnya &rarr;
                                        </Link>
                                    </div>
                                )}
                            </div>
                        </div>
                    )}


                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">


                        <div className="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                            <div className="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                                <h3 className="text-lg font-semibold text-gray-900 flex items-center">
                                    <UserCircleIcon />
                                    <span className="ml-3">Informasi Akun</span>
                                </h3>
                            </div>
                            <div className="p-6">
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div className="space-y-3">
                                        <div>
                                            <label className="text-sm font-medium text-gray-500">Nama Pelanggan</label>
                                            <p className="text-lg font-semibold text-gray-900 mt-1">{pelanggan.nama}</p>
                                        </div>
                                        <div>
                                            <label className="text-sm font-medium text-gray-500">ID PELANGGAN</label>
                                            <div className="flex items-center mt-1">
                                                <code className="bg-gray-100 text-gray-800 px-3 py-2 rounded-lg font-mono text-lg font-bold border">
                                                    {pelanggan.kode_unik}
                                                </code>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="flex items-center justify-center">
                                        <div className="bg-gradient-to-br from-blue-100 to-indigo-100 p-8 rounded-2xl">
                                            <div className="text-blue-600">
                                                <UserCircleIcon />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">Status Layanan</h3>
                        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div className="text-center p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                                <div className="text-emerald-600 mb-2">
                                    <div className="mx-auto w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                        <div className="w-3 h-3 bg-emerald-500 rounded-full"></div>
                                    </div>
                                </div>
                                <p className="text-sm font-medium text-emerald-800">Server Online</p>
                                <p className="text-xs text-emerald-600">99.9% Uptime</p>
                            </div>
                            <div className="text-center p-4 bg-blue-50 rounded-xl border border-blue-100">
                                <div className="text-blue-600 mb-2">
                                    <div className="mx-auto w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <div className="w-3 h-3 bg-blue-500 rounded-full"></div>
                                    </div>
                                </div>
                                <p className="text-sm font-medium text-blue-800">Koneksi Stabil</p>
                                <p className="text-xs text-blue-600">Latensi Rendah</p>
                            </div>
                            <div className="text-center p-4 bg-purple-50 rounded-xl border border-purple-100">
                                <div className="text-purple-600 mb-2">
                                    <div className="mx-auto w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                        <div className="w-3 h-3 bg-purple-500 rounded-full"></div>
                                    </div>
                                </div>
                                <p className="text-sm font-medium text-purple-800">Support 24/7</p>
                                <p className="text-xs text-purple-600">Siap Membantu</p>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </AuthenticatedLayout>
    );
}
