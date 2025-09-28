import React, { useEffect, useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { toast } from 'react-toastify';
import { useEcho } from '@laravel/echo-react';

// Reusable Confirmation Modal
const ConfirmationModal = ({ isOpen, onClose, onConfirm, title, children, isProcessing, confirmText = 'Confirm', cancelText = 'Cancel', confirmColor = 'blue' }) => {
    if (!isOpen) return null;

    const colorClasses = {
        blue: 'bg-blue-600 hover:bg-blue-700',
        red: 'bg-red-600 hover:bg-red-700',
    };

    const iconContainerClasses = {
        blue: 'bg-blue-100 text-blue-600',
        red: 'bg-red-100 text-red-600',
    };

    const Icon = () => {
        if (confirmColor === 'red') {
            return (
                <svg className="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                </svg>
            );
        }
        return (
            <svg className="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
                <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 21z" />
            </svg>
        );
    };

    return (
        <div className="fixed inset-0 bg-black/60 backdrop-blur-sm flex justify-center items-center z-50 p-4" onClick={onClose}>
            <div className="bg-white rounded-2xl shadow-2xl w-full max-w-md" onClick={(e) => e.stopPropagation()}>
                <div className="p-6 text-center">
                    <div className={`mx-auto flex items-center justify-center h-16 w-16 rounded-full ${iconContainerClasses[confirmColor]} mb-4`}>
                        <Icon />
                    </div>
                    <h3 className="text-2xl font-bold text-gray-900">{title}</h3>
                </div>
                <div className="px-6 pb-6 text-center text-gray-600">
                    {children}
                </div>
                <div className="bg-gray-50 px-6 py-4 rounded-b-2xl flex justify-end space-x-3">
                    <button
                        onClick={onClose}
                        disabled={isProcessing}
                        className="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {cancelText}
                    </button>
                    <button
                        onClick={onConfirm}
                        disabled={isProcessing}
                        className={`px-6 py-2.5 text-white font-bold rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[150px] ${colorClasses[confirmColor]}`}>
                        {isProcessing ? (
                            <svg className="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        ) : (
                            confirmText
                        )}
                    </button>
                </div>
            </div>
        </div>
    );
};

const PaymentSuccessModal = ({ isOpen, onClose, countdown }) => {
    if (!isOpen) return null;

    const progress = (countdown / 10) * 100;

    return (
        <div className="fixed inset-0 bg-black/60 backdrop-blur-sm flex justify-center items-center z-50 p-4">
            <div className="bg-white rounded-2xl shadow-2xl w-full max-w-md text-center p-8 transform transition-all">
                <div className="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-5">
                    <svg className="h-12 w-12 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                        <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 className="text-3xl font-bold text-gray-900">Pembayaran Berhasil!</h3>
                <p className="text-gray-600 mt-3 mb-6">
                    Terima kasih! Pembayaran Anda telah kami terima dan invoice telah lunas.
                </p>
                <button
                    onClick={onClose}
                    className="w-full px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition duration-150"
                >
                    Lihat Invoice
                </button>
                <div className="mt-4">
                    <p className="text-sm text-gray-500">
                        Anda akan dialihkan dalam {countdown} detik...
                    </p>
                    <div className="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                        <div
                            className="bg-green-500 h-1.5 rounded-full transition-all duration-1000 linear"
                            style={{ width: `${progress}%` }}
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default function PendingPayment({ virtualAccount, flash }) {
    const [timeLeft, setTimeLeft] = useState(null);
    const [isCancelling, setIsCancelling] = useState(false);
    const [isCheckingStatus, setIsCheckingStatus] = useState(false);
    const [isCopied, setIsCopied] = useState(false);
    const [isCancelModalOpen, setIsCancelModalOpen] = useState(false);
    const [isPaymentSuccess, setIsPaymentSuccess] = useState(false);
    const [paidInvoiceId, setPaidInvoiceId] = useState(null);
    const [countdown, setCountdown] = useState(10);

    useEcho('InvoicePaid', 'CustomerInvoicePaidEvent', (e) => {
        if (e.va && e.va.id === virtualAccount.id) {
            setPaidInvoiceId(e.va.invoice_id);
            setIsPaymentSuccess(true);
        }
    })

    useEffect(() => {
        const calculateTimeLeft = () => {
            const expiryTime = new Date(virtualAccount.expired_at).getTime();
            const now = new Date().getTime();
            const distance = expiryTime - now;

            if (distance < 0) {
                setTimeLeft('Expired');
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            setTimeLeft(`${hours}h ${minutes}m ${seconds}s`);
        };

        const timer = setInterval(calculateTimeLeft, 1000);

        return () => clearInterval(timer);
    }, [virtualAccount.expired_at]);

    const redirectToInvoice = () => {
        if (paidInvoiceId) {
            router.visit(route('invoices.show', paidInvoiceId));
        }
    };

    // Timer for redirect on success
    useEffect(() => {
        let timer;
        let countdownInterval;
        if (isPaymentSuccess) {
            timer = setTimeout(redirectToInvoice, 10000);
            setCountdown(10);
            countdownInterval = setInterval(() => {
                setCountdown(prev => {
                    if (prev <= 1) {
                        clearInterval(countdownInterval);
                        return 0;
                    }
                    return prev - 1;
                });
            }, 1000);
        }
        return () => {
            clearTimeout(timer);
            clearInterval(countdownInterval);
        };
    }, [isPaymentSuccess, paidInvoiceId]);

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    const handleCancel = () => {
        setIsCancelling(true);
        router.post(route('virtual-accounts.cancel', virtualAccount.id), {}, {
            onSuccess: () => {
                setIsCancelling(false);
                setIsCancelModalOpen(false);
            },
            onError: (errors) => {
                setIsCancelling(false);
                const firstError = Object.values(errors)[0];
                toast.error(firstError || 'Gagal membatalkan pembayaran.');
            },
        });
    };

    const handleCheckStatus = () => {
        setIsCheckingStatus(true);
        router.post(route('virtual-accounts.check-status', virtualAccount.id), {}, {
            onFinish: () => setIsCheckingStatus(false),
            onSuccess: () => toast.success(flash.success),
            onError: (errors) => toast.error(flash.error),
        });
    };

    const copyToClipboard = () => {
        navigator.clipboard.writeText(virtualAccount.va_number).then(() => {
            setIsCopied(true);
            toast.success('Nomor Virtual Account berhasil disalin!');
            setTimeout(() => setIsCopied(false), 2000);
        });
    };

    const downloadQris = (url, invoiceNumber) => {
        fetch(url)
            .then(response => response.blob())
            .then(blob => {
                const blobUrl = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = blobUrl;
                link.download = `qris_invoice_${invoiceNumber}.png`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(blobUrl);
                toast.success('QRIS berhasil diunduh!');
            })
            .catch(error => {
                console.error('Error downloading QRIS:', error);
                toast.error('Gagal mengunduh QRIS.');
            });
    };

    return (
        <AuthenticatedLayout>
            <Head title={`Pembayaran Invoice #${virtualAccount.invoice.invoice_number}`} />

            <PaymentSuccessModal
                isOpen={isPaymentSuccess}
                onClose={redirectToInvoice}
                countdown={countdown}
            />

            <ConfirmationModal
                isOpen={isCancelModalOpen}
                onClose={() => setIsCancelModalOpen(false)}
                onConfirm={handleCancel}
                title="Konfirmasi Pembatalan"
                isProcessing={isCancelling}
                confirmText="Ya, Batalkan"
                cancelText="Tidak"
                confirmColor="red"
            >
                <p>
                    Apakah Anda yakin ingin membatalkan pembayaran ini? Tindakan ini tidak dapat diurungkan.
                </p>
            </ConfirmationModal>

            <div className="py-12">
                <div className="max-w-2xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200 text-center">
                            <h1 className="text-2xl font-bold text-gray-900">Selesaikan Pembayaran Anda</h1>
                            <p className="text-gray-600 mt-2">Invoice #{virtualAccount.invoice.invoice_number}</p>

                            <div className="my-8">
                                {virtualAccount.payment_type === 'gopay' && virtualAccount.qris_url && (
                                    <div>
                                        <h2 className="text-lg font-semibold">Scan QRIS untuk Membayar</h2>
                                        <div className="mt-2">
                                            <p className="text-gray-600">Total Pembayaran</p>
                                            <p className="text-3xl font-bold text-blue-600">{formatRupiah(virtualAccount.total_amount)}</p>
                                        </div>
                                        <img src={route('qris.proxy', virtualAccount.transaction_id)} alt="QRIS Code" className="mx-auto mt-4" />
                                        <button
                                            onClick={() => downloadQris(route('qris.proxy', virtualAccount.transaction_id), virtualAccount.invoice.invoice_number)}
                                            className="mt-4 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 disabled:opacity-50"
                                        >
                                            Unduh QRIS
                                        </button>
                                    </div>
                                )}

                                {(virtualAccount.payment_type === 'bank_transfer' || virtualAccount.payment_type === 'echannel') && virtualAccount.va_number && (
                                    <div>
                                        <h2 className="text-lg font-semibold">Virtual Account {virtualAccount.payment_method.name}</h2>
                                        <div className="flex items-center justify-center my-4">
                                            <p className="text-3xl font-bold text-blue-600 mr-4">{virtualAccount.va_number}</p>
                                            <button onClick={copyToClipboard} className="text-sm text-blue-600 hover:text-blue-800">
                                                {isCopied ? 'Disalin!' : 'Salin'}
                                            </button>
                                        </div>
                                        <p>Total Pembayaran: {formatRupiah(virtualAccount.total_amount)}</p>
                                    </div>
                                )}
                            </div>

                            <div className="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                                <p className="font-bold">Batas Waktu Pembayaran</p>
                                <p>{timeLeft}</p>
                            </div>

                            <div className="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <button
                                    onClick={handleCheckStatus}
                                    disabled={isCheckingStatus}
                                    className="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50"
                                >
                                    {isCheckingStatus ? 'Memeriksa...' : 'Cek Status Pembayaran'}
                                </button>
                                <button
                                    onClick={() => setIsCancelModalOpen(true)}
                                    className="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700"
                                >
                                    Batalkan Pembayaran
                                </button>
                            </div>

                            <div className="mt-8">
                                <Link href={route('invoices.index')} className="text-sm text-gray-600 hover:text-gray-900">
                                    Lihat invoice lainnya
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}