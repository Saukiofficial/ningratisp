import React from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import { Lightbulb, Compass, FileText, BarChart } from 'lucide-react';

export default function Consultant() {
    return (
        <div className="bg-white font-sans text-gray-900">
            <Head title="IT Consultant - NingratNet" />
            <Navbar />

            <div className="pt-32 pb-20 bg-yellow-500 text-white text-center">
                <h1 className="text-4xl md:text-5xl font-extrabold mb-6">IT Consultant & Solutions</h1>
                <p className="text-white/90 max-w-2xl mx-auto px-4 text-lg font-medium">
                    Merancang peta jalan digital untuk efisiensi dan pertumbuhan bisnis Anda.
                </p>
            </div>

            <section className="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="grid md:grid-cols-3 gap-8">
                    <div className="bg-white border border-gray-100 p-8 rounded-2xl shadow-sm hover:shadow-md transition">
                        <div className="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center mb-6">
                            <Compass size={24} />
                        </div>
                        <h3 className="text-xl font-bold mb-3">Strategic Planning</h3>
                        <p className="text-gray-600 text-sm">
                            Membantu menyelaraskan strategi teknologi dengan tujuan bisnis jangka panjang perusahaan Anda.
                        </p>
                    </div>
                    <div className="bg-white border border-gray-100 p-8 rounded-2xl shadow-sm hover:shadow-md transition">
                        <div className="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center mb-6">
                            <FileText size={24} />
                        </div>
                        <h3 className="text-xl font-bold mb-3">System Audit</h3>
                        <p className="text-gray-600 text-sm">
                            Evaluasi menyeluruh terhadap infrastruktur yang ada untuk menemukan celah keamanan atau inefisiensi.
                        </p>
                    </div>
                    <div className="bg-white border border-gray-100 p-8 rounded-2xl shadow-sm hover:shadow-md transition">
                        <div className="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center mb-6">
                            <BarChart size={24} />
                        </div>
                        <h3 className="text-xl font-bold mb-3">Digital Transformation</h3>
                        <p className="text-gray-600 text-sm">
                            Pendampingan migrasi dari sistem manual/konvensional ke sistem digital yang terintegrasi.
                        </p>
                    </div>
                </div>

                <div className="mt-20 text-center">
                    <a href="https://wa.me/62812345678" className="bg-yellow-500 hover:bg-yellow-600 text-white px-10 py-4 rounded-full font-bold transition shadow-lg shadow-yellow-200">
                        Jadwalkan Sesi Konsultasi
                    </a>
                </div>
            </section>
            <Footer />
        </div>
    );
}
