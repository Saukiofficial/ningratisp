import React from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import { Settings, Wifi, DollarSign } from 'lucide-react';

export default function Management() {
    return (
        <div className="bg-white font-sans text-gray-900">
            <Head title="Management Service - NingratNet" />
            <Navbar />

            <div className="pt-32 pb-20 bg-teal-600 text-white text-center">
                <h1 className="text-4xl md:text-5xl font-extrabold mb-6">Management Service</h1>
                <p className="text-teal-100 max-w-2xl mx-auto px-4 text-lg">
                    Optimalisasi pengelolaan jaringan hotspot dan sistem billing untuk usaha Anda.
                </p>
            </div>

            <section className="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="space-y-20">
                    {/* Hotspot */}
                    <div className="flex flex-col md:flex-row gap-12 items-center">
                        <div className="md:w-1/2">
                            <div className="w-14 h-14 bg-teal-100 text-teal-600 rounded-2xl flex items-center justify-center mb-6">
                                <Wifi size={32} />
                            </div>
                            <h2 className="text-3xl font-bold mb-4">Hotspot Management</h2>
                            <p className="text-gray-600 mb-4">
                                Solusi untuk Hotel, Cafe, Kos, atau Area Publik. Kami menyediakan sistem portal login (Landing Page) yang bisa dikustomisasi dengan brand Anda.
                            </p>
                            <ul className="list-disc list-inside text-gray-600 space-y-2">
                                <li>Custom Captive Portal</li>
                                <li>Voucher System</li>
                                <li>User Bandwidth Limiter</li>
                            </ul>
                        </div>
                        <div className="md:w-1/2 bg-gray-50 p-8 rounded-3xl border border-gray-200">
                            {/* Visualisasi Mockup Login Page */}
                            <div className="bg-white shadow-lg rounded-xl p-6 max-w-xs mx-auto border border-gray-100 text-center">
                                <div className="w-12 h-12 bg-teal-500 rounded-full mx-auto mb-4"></div>
                                <h4 className="font-bold text-gray-800">Welcome to Cafe WiFi</h4>
                                <p className="text-xs text-gray-400 mb-4">Please login to continue</p>
                                <div className="space-y-2">
                                    <div className="h-8 bg-gray-100 rounded"></div>
                                    <div className="h-8 bg-gray-100 rounded"></div>
                                    <div className="h-8 bg-teal-500 rounded text-white text-xs flex items-center justify-center">Login</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Billing */}
                    <div className="flex flex-col md:flex-row-reverse gap-12 items-center">
                        <div className="md:w-1/2">
                            <div className="w-14 h-14 bg-teal-100 text-teal-600 rounded-2xl flex items-center justify-center mb-6">
                                <DollarSign size={32} />
                            </div>
                            <h2 className="text-3xl font-bold mb-4">Billing System Integration</h2>
                            <p className="text-gray-600">
                                Integrasi sistem pembayaran internet dengan sistem keuangan Anda. Otomatisasi isolir bagi pelanggan yang telat bayar.
                            </p>
                        </div>
                        <div className="md:w-1/2 bg-gray-50 h-64 rounded-3xl flex items-center justify-center">
                            <Settings size={80} className="text-gray-300 animate-spin-slow" />
                        </div>
                    </div>
                </div>
            </section>
            <Footer />
        </div>
    );
}
