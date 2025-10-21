import React, { useState } from 'react';
import { Head, Link, usePage, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Tagihan({ tagihans, filters, invoice_statuses }) {
    const { flash } = usePage().props;
    const totalBelumLunas = tagihans.total > 0 ? tagihans.data.filter(t => t.status === invoice_statuses.unpaid).length : 0;

    const [isFilterOpen, setIsFilterOpen] = useState(false);

    const [filterState, setFilterState] = useState({
        status: filters.status || '',
        start_date: filters.start_date || '',
        end_date: filters.end_date || '',
    });

    const handleFilterChange = (e) => {
        setFilterState({
            ...filterState,
            [e.target.name]: e.target.value,
        });
    };

    const applyFilters = () => {
        router.get(route('invoices.index'), filterState, {
            preserveState: true,
            replace: true,
        });
    };

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    const formatDate = (dateString) => {
        if (!dateString) return '-';
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    };

    return (
        <AuthenticatedLayout>
            <Head title="Daftar Tagihan" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <h2 className="text-2xl font-bold mb-6 px-4 sm:px-0">Riwayat Tagihan Anda</h2>

                    {flash.success && (
                        <div className="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 mx-4 sm:mx-0 rounded-r-lg" role="alert">
                            <p className="font-bold">{flash.success}</p>
                        </div>
                    )}
                    {totalBelumLunas > 0 && (
                        <div className="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 mx-4 sm:mx-0 rounded-r-lg" role="alert">
                            <p className="font-bold">⚠️ Anda memiliki {totalBelumLunas} tagihan yang belum dibayar.</p>
                        </div>
                    )}
                    {totalBelumLunas === 0 && !flash.success && tagihans.total > 0 && (
                        <div className="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 mx-4 sm:mx-0 rounded-r-lg" role="alert">
                            <p className="font-bold">✅ Selamat, semua tagihan Anda sudah lunas!</p>
                        </div>
                    )}

                    <div className="mb-6 bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6">
                        <div className="flex justify-between items-center">
                            <h3 className="text-lg font-semibold text-gray-800">Filter Tagihan</h3>
                            <button
                                onClick={() => setIsFilterOpen(!isFilterOpen)}
                                className="sm:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                aria-expanded={isFilterOpen}
                            >
                                {isFilterOpen ? (
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                    </svg>
                                ) : (
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                )}
                            </button>
                        </div>

                        <div className={`${isFilterOpen ? 'block' : 'hidden'} sm:block mt-4`}>
                            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                                <div className="lg:col-span-1">
                                    <label htmlFor="status" className="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select
                                        id="status"
                                        name="status"
                                        value={filterState.status}
                                        onChange={handleFilterChange}
                                        className="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150"
                                    >
                                        <option value="">Semua Status</option>
                                        <option value={invoice_statuses.unpaid}>Belum Dibayar</option>
                                        <option value={invoice_statuses.paid}>Lunas</option>
                                    </select>
                                </div>
                                <div>
                                    <label htmlFor="start_date" className="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                                    <input
                                        type="date"
                                        id="start_date"
                                        name="start_date"
                                        value={filterState.start_date}
                                        onChange={handleFilterChange}
                                        className="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150"
                                    />
                                </div>
                                <div>
                                    <label htmlFor="end_date" className="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                                    <input
                                        type="date"
                                        id="end_date"
                                        name="end_date"
                                        value={filterState.end_date}
                                        onChange={handleFilterChange}
                                        className="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150"
                                    />
                                </div>
                                <div className="text-right">
                                    <button
                                        onClick={applyFilters}
                                        className="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="hidden sm:block bg-white shadow-xl rounded-lg overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {tagihans.data.map((tagihan) => (
                                        <tr key={tagihan.id} className="hover:bg-gray-50 transition duration-150">
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{formatDate(tagihan.invoice_date)}</td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{formatRupiah(tagihan.balance_due)}</td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm">
                                                <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${tagihan.status === invoice_statuses.paid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
                                                    {tagihan.status === invoice_statuses.paid ? 'Lunas' : 'Belum Dibayar'}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                {tagihan.status === invoice_statuses.paid ? (
                                                    <a href={`/tagihan/${tagihan.id}/struk`} target="_blank" rel="noopener noreferrer" className="text-blue-600 hover:text-blue-900 font-semibold transition duration-150">
                                                        Cetak Struk
                                                    </a>
                                                ) : (
                                                    <Link href={`/pembayaran/${tagihan.id}`} className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-150">
                                                        Bayar Sekarang
                                                    </Link>
                                                )}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* mobile view */}
                    <div className="sm:hidden space-y-4 px-4">
                        {tagihans.data.map((tagihan) => (
                            <div key={tagihan.id} className="bg-white shadow-lg rounded-lg p-4">
                                <div className="flex justify-between items-start">
                                    <div>
                                        <p className="font-bold text-gray-800">{formatDate(tagihan.invoice_date)}</p>
                                        <p className="text-sm text-gray-600">{formatRupiah(tagihan.balance_due)}</p>
                                    </div>
                                    <span className={`px-2 py-1 text-xs font-semibold rounded-full ${tagihan.status === invoice_statuses.paid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
                                        {tagihan.status === invoice_statuses.paid ? 'Lunas' : 'Belum Dibayar'}
                                    </span>
                                </div>
                                <div className="mt-4 pt-4 border-t border-gray-200 flex justify-end">
                                    {tagihan.status === invoice_statuses.paid ? (
                                        <a href={`/tagihan/${tagihan.id}/struk`} target="_blank" rel="noopener noreferrer" className="text-blue-600 hover:text-blue-900 font-semibold text-sm transition duration-150">
                                            Cetak Struk
                                        </a>
                                    ) : (
                                        <Link href={`/pembayaran/${tagihan.id}`} className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-150">
                                            Bayar Sekarang
                                        </Link>
                                    )}
                                </div>
                            </div>
                        ))}
                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
