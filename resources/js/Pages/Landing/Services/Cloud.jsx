import React from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import { Cloud, CheckCircle2, Server } from 'lucide-react';

export default function CloudService() {
    return (
        <div className="bg-white font-sans text-gray-900">
            <Head title="Cloud Access Service - NingratNet" />
            <Navbar />

            {/* Hero */}
            <div className="pt-32 pb-20 bg-gradient-to-r from-blue-900 to-indigo-900 text-white">
                <div className="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-12">
                    <div className="md:w-1/2">
                        <div className="inline-flex items-center gap-2 bg-blue-800/50 border border-blue-400/30 px-4 py-1.5 rounded-full text-blue-200 text-sm font-medium mb-6">
                            <Cloud size={16} /> Enterprise Grade Solution
                        </div>
                        <h1 className="text-4xl md:text-5xl font-extrabold mb-6 leading-tight">
                            Cloud Access Service
                        </h1>
                        <p className="text-lg text-blue-100 mb-8 leading-relaxed">
                            Akses data dan aplikasi bisnis Anda dari mana saja dengan infrastruktur cloud yang aman, skalabel, dan berkinerja tinggi.
                        </p>
                        <button className="bg-white text-blue-900 px-8 py-3 rounded-full font-bold hover:bg-blue-50 transition">
                            Konsultasi Gratis
                        </button>
                    </div>
                    <div className="md:w-1/2 flex justify-center">
                        <div className="bg-white/10 p-8 rounded-3xl border border-white/20 backdrop-blur-sm">
                            <Server size={120} className="text-blue-300 opacity-80" />
                        </div>
                    </div>
                </div>
            </div>

            {/* Content */}
            <section className="py-20 max-w-7xl mx-auto px-4">
                <div className="grid md:grid-cols-3 gap-8 mb-16">
                    <div className="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                        <h3 className="text-xl font-bold mb-3">Private Cloud</h3>
                        <p className="text-gray-600 text-sm">Server didedikasikan khusus untuk perusahaan Anda, menjamin keamanan data maksimal.</p>
                    </div>
                    <div className="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                        <h3 className="text-xl font-bold mb-3">Hybrid Cloud</h3>
                        <p className="text-gray-600 text-sm">Kombinasi fleksibilitas public cloud dengan keamanan private cloud.</p>
                    </div>
                    <div className="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                        <h3 className="text-xl font-bold mb-3">Data Backup</h3>
                        <p className="text-gray-600 text-sm">Sistem backup otomatis berkala untuk mencegah kehilangan data kritis.</p>
                    </div>
                </div>

                <div className="bg-indigo-900 text-white rounded-3xl p-8 md:p-12 text-center">
                    <h2 className="text-3xl font-bold mb-4">Siap Migrasi ke Cloud?</h2>
                    <p className="text-indigo-200 mb-8">Tim ahli kami siap membantu proses migrasi tanpa mengganggu operasional bisnis Anda.</p>
                    <a href="https://wa.me/62812345678" className="inline-block bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-xl font-bold transition">
                        Hubungi Tim Sales
                    </a>
                </div>
            </section>

            <Footer />
        </div>
    );
}
