import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { toast } from 'react-toastify';

export default function Checkout({ invoice, paymentMethods, flash }) {
    const [openMethod, setOpenMethod] = useState(null);
    const [isProcessing, setIsProcessing] = useState(false);

    const toggleMethod = (id) => {
        setOpenMethod(openMethod === id ? null : id);
    };

    const handlePayment = (method) => {
        setIsProcessing(true);
        router.post(route('invoices.pay', { invoice: invoice.id }), {
            payment_method: method.id,
        }, {
            onFinish: () => setIsProcessing(false),
            onSuccess: () => toast.success(flash.success),
            onError: (errors) => toast.error(flash.error),
        });
    };

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    const calculateFee = (fee) => {
        if (!fee) return 0;
        if (fee.unit === 'p') {
            return (parseFloat(invoice.balance_due) * parseFloat(fee.amount)) / 100;
        }
        return parseFloat(fee.amount);
    };

    return (
        <AuthenticatedLayout>
            <Head title={`Checkout Invoice #${invoice.invoice_number}`} />

            <div className="py-12">
                <div className="max-w-2xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <div className="text-center mb-8">
                                <h1 className="text-2xl font-bold text-gray-900">Checkout</h1>
                                <p className="text-gray-600">Invoice #{invoice.invoice_number}</p>
                                <p className="text-4xl font-bold text-blue-600 mt-2">{formatRupiah(invoice.balance_due)}</p>
                            </div>

                            <div className="space-y-4">
                                {paymentMethods.map((method) => {
                                    const fee = calculateFee(method.fee);
                                    const total = parseFloat(invoice.balance_due) + fee;
                                    return (
                                        <div key={method.id} className="border border-gray-200 rounded-lg">
                                            <button
                                                onClick={() => toggleMethod(method.id)}
                                                className="w-full flex items-center justify-between px-6 py-4 bg-gray-50 hover:bg-gray-100 transition duration-150 ease-in-out rounded-t-lg"
                                            >
                                                <span className="font-semibold text-lg text-gray-800">{method.name}</span>
                                                <svg className={`h-6 w-6 text-gray-400 transform transition-transform ${openMethod === method.id ? 'rotate-90' : ''}`} fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                            {openMethod === method.id && (
                                                <div className="p-6 bg-white rounded-b-lg">
                                                    <div className="flex justify-between items-center mb-4">
                                                        <p className="text-gray-600">Biaya Admin:</p>
                                                        <p className="font-semibold text-gray-800">{formatRupiah(fee)}</p>
                                                    </div>
                                                    <div className="flex justify-between items-center font-bold text-lg mb-6">
                                                        <p>Total Pembayaran:</p>
                                                        <p>{formatRupiah(total)}</p>
                                                    </div>
                                                    <button
                                                        onClick={() => handlePayment(method)}
                                                        disabled={isProcessing}
                                                        className="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out disabled:opacity-50"
                                                    >
                                                        {isProcessing ? 'Memproses...' : 'Bayar Sekarang'}
                                                    </button>
                                                </div>
                                            )}
                                        </div>
                                    );
                                })}
                            </div>

                            <div className="mt-8 text-center">
                                <Link href={route('invoices.show', { invoice: invoice.id })} className="text-sm text-gray-600 hover:text-gray-900">
                                    Kembali ke Invoice
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </AuthenticatedLayout>
    );
}