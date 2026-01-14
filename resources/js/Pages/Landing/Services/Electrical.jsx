import React from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import { Wrench, Zap, Battery } from 'lucide-react';

export default function Electrical() {
    return (
        <div className="bg-white font-sans text-gray-900">
            <Head title="Maintenance Electrical - NingratNet" />
            <Navbar />

            <div className="pt-32 pb-20 bg-orange-600 text-white text-center">
                <h1 className="text-4xl md:text-5xl font-extrabold mb-6">Maintenance Electrical</h1>
                <p className="text-orange-100 max-w-2xl mx-auto px-4 text-lg">
                    Jasa kelistrikan profesional untuk mendukung stabilitas perangkat IT dan Data Center.
                </p>
            </div>

            <section className="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="grid md:grid-cols-3 gap-8">
                    <div className="border border-gray-200 rounded-2xl p-8 hover:border-orange-500 transition cursor-default">
                        <Zap size={40} className="text-orange-500 mb-6" />
                        <h3 className="text-xl font-bold mb-3">Instalasi Arus Kuat/Lemah</h3>
                        <p className="text-gray-600 text-sm">
                            Pemasangan panel listrik, cabling management, dan perbaikan instalasi gedung kantor.
                        </p>
                    </div>
                    <div className="border border-gray-200 rounded-2xl p-8 hover:border-orange-500 transition cursor-default">
                        <Battery size={40} className="text-orange-500 mb-6" />
                        <h3 className="text-xl font-bold mb-3">UPS Maintenance</h3>
                        <p className="text-gray-600 text-sm">
                            Pengecekan kesehatan baterai UPS, penggantian unit, dan load testing untuk backup power server.
                        </p>
                    </div>
                    <div className="border border-gray-200 rounded-2xl p-8 hover:border-orange-500 transition cursor-default">
                        <Wrench size={40} className="text-orange-500 mb-6" />
                        <h3 className="text-xl font-bold mb-3">Genset Support</h3>
                        <p className="text-gray-600 text-sm">
                            Instalasi ATS (Automatic Transfer Switch) dan maintenance rutin generator set.
                        </p>
                    </div>
                </div>
            </section>
            <Footer />
        </div>
    );
}
