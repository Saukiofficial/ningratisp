import React, { useEffect, useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { toast } from 'react-toastify';

export default function PendingPayment({ virtualAccount, flash }) {
    const [timeLeft, setTimeLeft] = useState(null);
    const [isCancelling, setIsCancelling] = useState(false);
    const [isCheckingStatus, setIsCheckingStatus] = useState(false);
    const [isCopied, setIsCopied] = useState(false);

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
            onFinish: () => setIsCancelling(false),
            onSuccess: () => toast.success(flash.success),
            onError: (errors) => toast.error(flash.error),
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
                                        <p>Total Pembayaran: {formatRupiah(virtualAccount.invoice.balance_due)}</p>
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
                                    onClick={handleCancel}
                                    disabled={isCancelling}
                                    className="inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
                                >
                                    {isCancelling ? 'Membatalkan...' : 'Batalkan Pembayaran'}
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

