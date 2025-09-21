import React, { useEffect, useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function PendingPayment({ invoice }) {
    const [timeLeft, setTimeLeft] = useState(null);

    useEffect(() => {
        const calculateTimeLeft = () => {
            const expiryTime = new Date(invoice.midtrans_expiry_time).getTime();
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
    }, [invoice.midtrans_expiry_time]);

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    return (
        <AuthenticatedLayout>
            <Head title={`Pembayaran Invoice #${invoice.invoice_number}`} />

            <div className="py-12">
                <div className="max-w-2xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200 text-center">
                            <h1 className="text-2xl font-bold text-gray-900">Selesaikan Pembayaran Anda</h1>
                            <p className="text-gray-600 mt-2">Invoice #{invoice.invoice_number}</p>

                            <div className="my-8">
                                {invoice.midtrans_payment_type === 'gopay' && invoice.midtrans_qris_url && (
                                    <div>
                                        <h2 className="text-lg font-semibold">Scan QRIS untuk Membayar</h2>
                                        <img src={invoice.midtrans_qris_url} alt="QRIS Code" className="mx-auto mt-4" />
                                    </div>
                                )}

                                {(invoice.midtrans_payment_type === 'bank_transfer' || invoice.midtrans_payment_type === 'echannel') && invoice.midtrans_va_number && (
                                    <div>
                                        <h2 className="text-lg font-semibold">Virtual Account {invoice.midtrans_payment_type === 'bank_transfer' ? 'BCA' : 'Mandiri'}</h2>
                                        <p className="text-3xl font-bold text-blue-600 my-4">{invoice.midtrans_va_number}</p>
                                        <p>Total Pembayaran: {formatRupiah(invoice.balance_due)}</p>
                                    </div>
                                )}
                            </div>

                            <div className="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                                <p className="font-bold">Batas Waktu Pembayaran</p>
                                <p>{timeLeft}</p>
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
