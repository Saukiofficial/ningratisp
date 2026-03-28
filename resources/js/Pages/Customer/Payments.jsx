import React, { useState, useEffect } from 'react';
import { Head, useForm, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import './Payments.css';

const CreditCardIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
    </svg>
);

const BanknotesIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H4.5m2.25 0v3m0 0v.75A.75.75 0 016 9h-.75m0 0h-.375A1.125 1.125 0 004.5 7.875v-.75m3.75 0h1.5m-1.5 0h-.375a1.125 1.125 0 00-1.125 1.125v.75m1.5-1.125v0m4.5 0h2.25m-2.25 0h.375a1.125 1.125 0 011.125 1.125v.75m-1.5-1.125h.375c.621 0 1.125.504 1.125 1.125v.75m-1.5-1.125v0M2.25 10.5c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m0 0H3.75v.375c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125v-.375H15m-12 0v-1.5A1.125 1.125 0 012.25 12v-1.5m0 10.5c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m0 0H3.75v.375c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125v-.375H15m-12 0v-1.5A1.125 1.125 0 012.25 18v-1.5" />
    </svg>
);

const ShieldCheckIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.623 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
    </svg>
);

const CalendarIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
    </svg>
);

export default function Pembayaran({ tagihan }) {
    const { data, setData, post, processing, errors } = useForm({
        metode_pembayaran: 'BCA',
    });


    const [paymentType, setPaymentType] = useState('Bank Transfer');

    const bankOptions = ['BCA', 'BRI', 'Mandiri', 'BNI', 'SeaBank'];
    const eWalletOptions = ['GoPay', 'DANA', 'OVO', 'ShopeePay'];


    useEffect(() => {
        if (paymentType === 'Bank Transfer') {
            setData('metode_pembayaran', bankOptions[0]);
        } else {
            setData('metode_pembayaran', eWalletOptions[0]);
        }
    }, [paymentType]);

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    function submit(e) {
        e.preventDefault();
        post(`/pembayaran/${tagihan.id}`);
    }

    return (
        <AuthenticatedLayout>
            <Head title={`Bayar Tagihan ${tagihan.bulan}`} />

            <div className="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 py-8">
                <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center text-white">
                        <div className="inline-flex items-center justify-center w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl mb-4">
                            <CreditCardIcon />
                        </div>
                        <h1 className="text-2xl sm:text-3xl font-bold mb-2">Konfirmasi Pembayaran</h1>
                        <p className="text-blue-100">Selesaikan pembayaran tagihan WiFi Anda</p>
                    </div>
                </div>
            </div>

            <div className="py-8 pay-root">
                <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4 relative z-10">


                    <div className="pay-card mb-6">
                        <div className="pay-header">
                            <div className="flex items-center space-x-3">
                                <div className="bg-blue-100 text-blue-600 p-2 rounded-xl">
                                    <CalendarIcon />
                                </div>
                                <div>
                                    <h3 className="pay-title text-lg">Detail Tagihan</h3>
                                    <p className="pay-sub">Bulan <strong>{tagihan.bulan}</strong></p>
                                </div>
                            </div>
                        </div>

                        <div className="p-6">
                            <div className="bg-green-500/10 border border-green-500/20 rounded-2xl p-6">
                                <div className="text-center">
                                    <p className="text-sm font-medium text-green-600 mb-2">Total yang harus dibayar</p>
                                    <div className="text-3xl font-bold text-green-600 mb-1">
                                        {formatRupiah(tagihan.jumlah + tagihan.denda)}
                                    </div>
                                    <div className="flex items-center justify-center space-x-2 text-xs text-green-600 opacity-70">
                                        <ShieldCheckIcon />
                                        <span>Pembayaran aman dan terenkripsi</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div className="pay-card">
                        <div className="pay-header">
                            <div className="flex items-center space-x-3">
                                <div className="bg-purple-100 text-purple-600 p-2 rounded-xl">
                                    <BanknotesIcon />
                                </div>
                                <div>
                                    <h3 className="pay-title text-lg">Pilih Metode Pembayaran</h3>
                                    <p className="pay-sub">Pilih cara pembayaran yang Anda inginkan</p>
                                </div>
                            </div>
                        </div>

                        <form onSubmit={submit} className="p-6">
                            <div className="space-y-6">

                                <div>
                                    <label htmlFor="payment_type" className="pay-label">
                                        Jenis Pembayaran
                                    </label>
                                    <div className="relative">
                                        <select
                                            id="payment_type"
                                            value={paymentType}
                                            onChange={(e) => setPaymentType(e.target.value)}
                                            className="pay-input pr-10"
                                        >
                                            <option>Bank Transfer</option>
                                            <option>E-Wallet</option>
                                        </select>
                                        <div className="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg className="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fillRule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clipRule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>


                                <div>
                                    <label htmlFor="metode_pembayaran" className="pay-label">
                                        Pilih {paymentType === 'Bank Transfer' ? 'Bank' : 'E-Wallet'}
                                    </label>
                                    <div className="relative">
                                        <select
                                            id="metode_pembayaran"
                                            name="metode_pembayaran"
                                            value={data.metode_pembayaran}
                                            onChange={(e) => setData('metode_pembayaran', e.target.value)}
                                            className="pay-input pr-10"
                                        >
                                            {paymentType === 'Bank Transfer' ? (
                                                bankOptions.map(bank => <option key={bank} value={bank}>{bank}</option>)
                                            ) : (
                                                eWalletOptions.map(wallet => <option key={wallet} value={wallet}>{wallet}</option>)
                                            )}
                                        </select>
                                        <div className="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg className="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fillRule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clipRule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {errors.metode_pembayaran && (
                                    <div className="bg-red-500/10 border border-red-500/20 rounded-xl p-4">
                                        <div className="flex items-center">
                                            <div className="flex-shrink-0">
                                                <svg className="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                                                </svg>
                                            </div>
                                            <div className="ml-3">
                                                <p className="text-sm font-medium text-red-500">
                                                    {errors.metode_pembayaran}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                )}
                            </div>


                            <div className="mt-8 flex flex-col sm:flex-row gap-4 sm:justify-end">
                                <Link
                                    href="/tagihan"
                                    className="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200"
                                >
                                    Batal
                                </Link>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="inline-flex justify-center items-center px-8 py-3 border border-transparent text-base font-bold rounded-xl text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 active:scale-95"
                                >
                                    {processing ? (
                                        <>
                                            <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Memproses...
                                        </>
                                    ) : (
                                        <>
                                            <ShieldCheckIcon />
                                            <span className="ml-2">Bayar Sekarang</span>
                                        </>
                                    )}
                                </button>
                            </div>
                        </form>
                    </div>


                    <div className="mt-6 text-center">
                        <div className="inline-flex items-center space-x-2 pay-footer-text opacity-70">
                            <ShieldCheckIcon />
                            <span>Pembayaran Anda dilindungi dengan enkripsi SSL 256-bit</span>
                        </div>
                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
