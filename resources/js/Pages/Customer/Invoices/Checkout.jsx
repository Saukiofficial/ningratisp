import React, { useState, useEffect } from 'react';
import { Head, Link, router, useForm } from '@inertiajs/react';
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

const OrderSummary = ({ invoice, claimedDiscounts = [], selectedMethod, handleOpenConfirmation, isProcessing, summary }) => {
    const { data, setData, post, processing, errors, clearErrors } = useForm({ code: '' });

    const handleClaimVoucher = (e) => {
        e.preventDefault();
        post(route('discounts.claim'), { onSuccess: () => setData('code', '') });
    };

    const handleApplyDiscount = (discountId) => {
        router.post(route('invoices.apply-discount', { invoice: invoice.id }), { discount_id: discountId });
    };

    const handleRemoveDiscount = () => {
        router.post(route('invoices.remove-discount', { invoice: invoice.id }));
    };

    const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);

    return (
        <div className="bg-gray-50 rounded-2xl p-6 lg:p-8 space-y-6 sticky top-24">
            <h2 className="text-2xl font-bold text-gray-900">Ringkasan Pesanan</h2>
            <div className="space-y-3 text-gray-700">
                <div className="flex justify-between"><p>Subtotal</p><p className="font-medium">{formatRupiah(invoice.subtotal)}</p></div>
                {invoice.discount_amount > 0 && (
                    <div className="flex justify-between text-green-600">
                        <p>Discount ({invoice.discount.name})</p>
                        <p className="font-medium">- {formatRupiah(invoice.discount_amount)}</p>
                    </div>
                )}
                {summary.fee > 0 && (
                    <div className="flex justify-between">
                        <p>Biaya Admin</p>
                        <p className="font-medium">{formatRupiah(summary.fee)}</p>
                    </div>
                )}
            </div>
            <div className="border-t border-gray-200 pt-4 flex justify-between items-center">
                <p className="text-lg font-bold text-gray-900">Total</p>
                <p className="text-2xl font-bold text-blue-600">{formatRupiah(summary.grandTotal)}</p>
            </div>

            <div className="border-t border-gray-200 pt-6">
                {invoice.discount ? (
                    <div className="text-center">
                        <p className="text-green-600 font-semibold">Discount applied: {invoice.discount.name}</p>
                        <button onClick={handleRemoveDiscount} className="text-sm text-red-500 hover:underline mt-1">Remove</button>
                    </div>
                ) : (
                    <>
                        <form onSubmit={handleClaimVoucher} className="space-y-2">
                            <label htmlFor="voucher-code" className="font-semibold text-gray-800">Punya Voucher?</label>
                            <div className="flex space-x-2">
                                <input
                                    id="voucher-code"
                                    type="text"
                                    value={data.code}
                                    onChange={e => setData('code', e.target.value.toUpperCase().replaceAll(" ", ""))}
                                    onFocus={() => clearErrors('code')}
                                    placeholder="Masukkan kode voucher"
                                    className="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    maxLength={10}
                                />
                                <button type="submit" disabled={processing} className="px-4 py-2 bg-gray-800 text-white font-semibold rounded-lg hover:bg-gray-900 transition disabled:opacity-50">Claim</button>
                            </div>
                            {errors.code && <p className="text-red-500 text-sm mt-1">{errors.code}</p>}
                        </form>

                        {claimedDiscounts.length > 0 && (
                            <div className="mt-6">
                                <h3 className="font-semibold text-gray-800 mb-2">Voucher Tersedia</h3>
                                <div className="space-y-2 max-h-40 overflow-y-auto pr-2">
                                    {claimedDiscounts.map(d => (
                                        <div key={d.id} onClick={() => handleApplyDiscount(d.discount.id)} className="p-3 bg-white border border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">
                                            <p className="font-bold text-blue-600">{d.discount.name}</p>
                                            <p className="text-sm text-gray-600">{d.discount.description}</p>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}
                    </>
                )}
            </div>
            <div className="border-t border-gray-200 pt-6">
                <button
                    onClick={() => handleOpenConfirmation()}
                    disabled={!selectedMethod || isProcessing}
                    className="w-full bg-blue-600 text-white font-bold py-3.5 px-4 rounded-lg hover:bg-blue-700 transition shadow-lg hover:shadow-blue-500/50 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none"
                >
                    Bayar Sekarang
                </button>
                {!selectedMethod && <p className="text-xs text-center text-gray-500 mt-2">Pilih metode pembayaran untuk melanjutkan.</p>}
            </div>
        </div>
    );
};

const CheckCircleIcon = (props) => (
    <svg {...props} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor">
        <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
);

export default function Checkout({ invoice, paymentMethods = [], claimedDiscounts = [], flash }) {
    const [selectedMethod, setSelectedMethod] = useState(paymentMethods[0] || null);
    const [isProcessing, setIsProcessing] = useState(false);
    const [isConfirmationModalOpen, setIsConfirmationModalOpen] = useState(false);
    const [confirmationData, setConfirmationData] = useState(null);
    const [summary, setSummary] = useState({ fee: 0, grandTotal: invoice.balance_due });

    const calculateFee = (fee) => {
        if (!fee) return 0;
        return fee.unit === 'p' ? (parseFloat(invoice.balance_due) * parseFloat(fee.amount)) / 100 : parseFloat(fee.amount);
    };

    useEffect(() => {
        if (selectedMethod) {
            const fee = calculateFee(selectedMethod.fee);
            const grandTotal = parseFloat(invoice.balance_due) + fee;
            setSummary({ fee, grandTotal });
        } else {
            setSummary({ fee: 0, grandTotal: parseFloat(invoice.balance_due) });
        }
    }, [selectedMethod, invoice.balance_due]);

    const handleOpenConfirmation = () => {
        if (!selectedMethod) return;
        setConfirmationData({
            method: selectedMethod,
            fee: summary.fee,
            total: summary.grandTotal
        });
        setIsConfirmationModalOpen(true);
    };

    const handlePayment = () => {
        if (!confirmationData) return;
        setIsProcessing(true);
        router.post(route('invoices.pay', { invoice: invoice.id }), { payment_method: confirmationData.method.id }, {
            onSuccess: () => setIsConfirmationModalOpen(false),
            onError: (errors) => toast.error(Object.values(errors)[0] || 'Payment processing failed.'),
            onFinish: () => setIsProcessing(false),
        });
    };

    const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);

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
                            <div className="flex justify-between"><span className="text-gray-500 text-sm">Metode</span><span className="font-bold">{confirmationData.method.name}</span></div>
                            <div className="flex justify-between"><span className="text-gray-500 text-sm">Tagihan</span><span className="font-semibold">{formatRupiah(invoice.balance_due)}</span></div>
                            <div className="flex justify-between"><span className="text-gray-500 text-sm">Biaya Admin</span><span className="font-semibold">{formatRupiah(confirmationData.fee)}</span></div>
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
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12 items-start">
                        <div className="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6">
                            <h1 className="text-2xl font-bold text-gray-900 mb-6">Pilih Metode Pembayaran</h1>
                            <div className="space-y-4">
                                {paymentMethods.map((method) => {
                                    const isSelected = selectedMethod && selectedMethod.id === method.id;
                                    const fee = calculateFee(method.fee);
                                    return (
                                        <div
                                            key={method.id}
                                            onClick={() => setSelectedMethod(method)}
                                            className={`border rounded-lg p-4 cursor-pointer transition-all duration-200 ${isSelected ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-500' : 'border-gray-200 hover:border-gray-300'}`}>
                                            <div className="flex justify-between items-center">
                                                <span className="font-semibold text-lg text-gray-800">{method.name}</span>
                                                {isSelected ? <CheckCircleIcon className="h-6 w-6 text-blue-600" /> : <div className="h-6 w-6 rounded-full border-2 border-gray-300" />}
                                            </div>
                                            <p className="text-sm text-gray-500 mt-1">Biaya Admin: {formatRupiah(fee)}</p>
                                        </div>
                                    );
                                })}
                            </div>
                        </div>

                        <div className="lg:col-span-1">
                            <OrderSummary
                                invoice={invoice}
                                claimedDiscounts={claimedDiscounts}
                                selectedMethod={selectedMethod}
                                handleOpenConfirmation={handleOpenConfirmation}
                                isProcessing={isProcessing}
                                summary={summary}
                            />
                            <div className="mt-6 text-center">
                                <Link href={route('invoices.show', { invoice: invoice.id })} className="text-sm text-gray-600 hover:text-gray-900">
                                    &larr; Kembali ke Invoice
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
