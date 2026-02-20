import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Show({ invoice }) {

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

    const getStatusClass = (status) => {
        switch (status) {
            case 'paid':
                return 'bg-green-100 text-green-800';
            case 'unpaid':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };

    return (
        <AuthenticatedLayout>
            <Head title={`Invoice #${invoice.invoice_number}`} />

            <div className="py-12">
                <div className="max-w-4xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 md:p-8 bg-white border-b border-gray-200">
                            <div className="flex justify-between items-start mb-8">
                                <div>
                                    <h1 className="text-2xl font-bold text-gray-900">Invoice #{invoice.invoice_number}</h1>
                                    <p className="text-sm text-gray-500 mt-1">
                                        Tanggal Invoice: {formatDate(invoice.invoice_date)}
                                    </p>
                                    <p className="text-sm text-gray-500">
                                        Jatuh Tempo: {formatDate(invoice.due_date)}
                                    </p>
                                </div>
                                <div className="text-right">
                                    <span className={`px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full ${getStatusClass(invoice.status)}`}>
                                        {invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1)}
                                    </span>
                                    {invoice.status === 'paid' && (
                                        <p className="text-sm text-green-600 font-semibold mt-1">
                                            Lunas pada {formatDate(invoice.paid_date)}
                                        </p>
                                    )}
                                </div>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                {/* <div>
                                    <h3 className="font-semibold text-gray-600 mb-2">Ditagihkan Kepada:</h3>
                                    <p className="font-bold text-gray-900">{invoice.customer.name}</p>
                                    <p className="text-gray-600">{invoice.customer.address}</p>
                                    <p className="text-gray-600">{invoice.customer.phone}</p>
                                    <p className="text-gray-600">{invoice.customer.email}</p>
                                </div> */}
                                <div className="text-left md:text-right">
                                    <h3 className="font-semibold text-gray-600 mb-2">Dari:</h3>
                                    <p className="font-bold text-gray-900">NingratNet</p>
                                    <p className="text-gray-600">Dusun Gutoguh, Poreh Kec. Lenteng</p>
                                    <p className="text-gray-600">Kab. Sumenep, 69461</p>
                                    <p className="text-gray-600">ningratisp@gmail.com</p>
                                </div>
                            </div>

                            <div className="mb-8">
                                <h3 className="font-semibold text-gray-800 mb-3 text-lg">Rincian Tagihan</h3>
                                <div className="overflow-x-auto">
                                    <table className="min-w-full divide-y divide-gray-200">
                                        <thead className="bg-gray-50">
                                            <tr>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                                <th scope="col" className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white divide-y divide-gray-200">
                                            {invoice.items.map((item) => (
                                                <tr key={item.id}>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{item.description}</td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-right">{formatRupiah(item.amount)}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div className="flex justify-end mb-8">
                                <div className="w-full max-w-xs">
                                    <div className="flex justify-between py-2 border-b">
                                        <span className="text-gray-600">Subtotal</span>
                                        <span className="text-gray-800">{formatRupiah(invoice.subtotal)}</span>
                                    </div>
                                    <div className="flex justify-between py-2 border-b">
                                        <span className="text-gray-600">Diskon</span>
                                        <span className="text-red-600">-{formatRupiah(invoice.discount_amount)}</span>
                                    </div>
                                    <div className="flex justify-between py-2 font-bold text-lg">
                                        <span className="text-gray-900">Total</span>
                                        <span className="text-gray-900">{formatRupiah(invoice.balance_due ?? invoice.subtotal)}</span>
                                    </div>
                                    <div className="flex justify-between py-2 text-green-600">
                                        <span className="font-semibold">Sudah Dibayar</span>
                                        <span className="font-semibold">{formatRupiah(invoice.paid_amount)}</span>
                                    </div>
                                    <div className="flex justify-between py-3 bg-gray-100 rounded-lg px-4 mt-2">
                                        <span className="font-bold text-xl">Sisa Tagihan</span>
                                        <span className="font-bold text-xl">{formatRupiah(invoice.balance_due)}</span>
                                    </div>
                                </div>
                            </div>

                            {invoice.payment_allocations && invoice.payment_allocations.length > 0 && (
                                <div className="mb-8">
                                    <h3 className="font-semibold text-gray-800 mb-3 text-lg">Riwayat Pembayaran</h3>
                                    <div className="overflow-x-auto">
                                        <table className="min-w-full divide-y divide-gray-200">
                                            <thead className="bg-gray-50">
                                                <tr>
                                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pembayaran</th>
                                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                                    <th scope="col" className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody className="bg-white divide-y divide-gray-200">
                                                {invoice.payment_allocations.map((payment) => (
                                                    <tr key={payment.id}>
                                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{formatDate(payment.payment_date)}</td>
                                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{payment.payment_method}</td>
                                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-right">{formatRupiah(payment.amount)}</td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            )}

                            {invoice.midtrans_transaction_id && invoice.status === 'unpaid' && (
                                <div className="bg-blue-50 border-l-4 border-blue-400 p-4 my-8">
                                    <h3 className="font-bold text-blue-800">Instruksi Pembayaran</h3>
                                    <p className="text-sm text-blue-700 mt-2">Segera selesaikan pembayaran Anda sebelum tanggal jatuh tempo.</p>
                                    <div className="mt-4">
                                        <p><span className="font-semibold">Metode Pembayaran:</span> {invoice.midtrans_payment_type.replace('_', ' ').toUpperCase()}</p>
                                        {invoice.midtrans_va_number && (
                                            <p><span className="font-semibold">Nomor Virtual Account:</span> {invoice.midtrans_va_number}</p>
                                        )}
                                        <p><span className="font-semibold">Total Pembayaran:</span> {formatRupiah(invoice.balance_due)}</p>
                                        <p><span className="font-semibold">Batas Waktu Pembayaran:</span> {formatDate(invoice.midtrans_expiry_time)}</p>
                                    </div>
                                </div>
                            )}

                            <div className="flex justify-between items-center mt-8 pt-6 border-t">
                                <Link href={route('customer.invoices.index')} className="text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out">
                                    &larr; Kembali ke Daftar Tagihan
                                </Link>
                                {invoice.status === 'unpaid' && (
                                    <Link href={route('customer.invoices.checkout', { invoice: invoice.id })} className="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                        Pilih Metode Pembayaran
                                    </Link>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
