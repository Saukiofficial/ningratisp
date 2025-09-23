import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { toast } from 'react-toastify';
import { route } from 'ziggy-js';

// Modern Confirmation Modal Component
const ConfirmationModal = ({ isOpen, onClose, onConfirm, title, children, isProcessing }) => {
    if (!isOpen) {
        return null;
    }

    return (
        <div className="fixed inset-0 bg-black/60 backdrop-blur-sm flex justify-center items-center z-50 p-4 transition-opacity duration-300 ease-in-out">
            <div className="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-transform duration-300 ease-in-out" onClick={(e) => e.stopPropagation()}>
                <div className="p-6 text-center">
                    <div className="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                        <svg className="h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
                            <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 21z" />
                        </svg>
                    </div>
                    <h3 className="text-2xl font-bold text-gray-900">{title}</h3>
                </div>

                <div className="px-6 pb-6">
                    {children}
                </div>

                <div className="bg-gray-50 px-6 py-4 rounded-b-2xl flex justify-end space-x-3">
                    <button
                        onClick={onClose}
                        disabled={isProcessing}
                        className="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Batal
                    </button>
                    <button
                        onClick={onConfirm}
                        disabled={isProcessing}
                        className="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[180px]"
                    >
                        {isProcessing ? (
                            <svg className="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        ) : (
                            'Konfirmasi & Bayar'
                        )}
                    </button>
                </div>
            </div>
        </div>
    );
};


export default function Checkout({ invoice, paymentMethods, flash }) {
    const [openMethod, setOpenMethod] = useState(null);
    const [isProcessing, setIsProcessing] = useState(false);
    const [isConfirmationModalOpen, setIsConfirmationModalOpen] = useState(false);
    const [confirmationData, setConfirmationData] = useState(null);

    const toggleMethod = (id) => {
        setOpenMethod(openMethod === id ? null : id);
    };

    const handleOpenConfirmation = (method) => {
        const fee = calculateFee(method.fee);
        const total = parseFloat(invoice.balance_due) + fee;
        setConfirmationData({ method, fee, total });
        setIsConfirmationModalOpen(true);
    };

    const handlePayment = () => {
        if (!confirmationData) return;

        setIsProcessing(true);
        router.post(route('invoices.pay', { invoice: invoice.id }), {
            payment_method: confirmationData.method.id,
        }, {
            onSuccess: () => {
                setIsProcessing(false);
                setIsConfirmationModalOpen(false);
                setConfirmationData(null);
            },
            onError: (errors) => {
                setIsProcessing(false);
                const firstError = Object.values(errors)[0];
                toast.error(firstError || 'Terjadi kesalahan saat memproses pembayaran.');
            },
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

            <ConfirmationModal
                isOpen={isConfirmationModalOpen}
                onClose={() => setIsConfirmationModalOpen(false)}
                onConfirm={handlePayment}
                title="Konfirmasi Pembayaran"
                isProcessing={isProcessing}
            >
                {confirmationData && (
                    <div className="text-center space-y-4">
                        <p className="text-gray-600">
                            Harap konfirmasi detail pembayaran Anda sebelum melanjutkan.
                        </p>
                        <div className="bg-gray-50 border border-gray-200 rounded-lg p-4 text-left space-y-3">
                            <div className="flex justify-between items-center">
                                <span className="text-gray-500 text-sm">Metode</span>
                                <span className="font-bold text-gray-900">{confirmationData.method.name}</span>
                            </div>
                            <div className="flex justify-between items-center">
                                <span className="text-gray-500 text-sm">Tagihan</span>
                                <span className="font-semibold text-gray-900">{formatRupiah(invoice.balance_due)}</span>
                            </div>
                            <div className="flex justify-between items-center">
                                <span className="text-gray-500 text-sm">Biaya Admin</span>
                                <span className="font-semibold text-gray-900">{formatRupiah(confirmationData.fee)}</span>
                            </div>
                        </div>
                        <div className="bg-blue-50 border border-blue-200 rounded-lg p-4 text-left">
                            <div className="flex justify-between items-center">
                                <span className="text-blue-800 font-semibold">Total Pembayaran</span>
                                <span className="text-2xl font-bold text-blue-900">{formatRupiah(confirmationData.total)}</span>
                            </div>
                        </div>
                    </div>
                )}
            </ConfirmationModal>

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
                                                        onClick={() => handleOpenConfirmation(method)}
                                                        className="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out"
                                                    >
                                                        Bayar Sekarang
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
