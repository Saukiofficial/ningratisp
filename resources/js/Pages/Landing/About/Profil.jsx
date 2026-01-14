import React from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import { Building2, Award, History, Users } from 'lucide-react';

export default function Profil() {
    return (
        <div className="bg-white font-sans text-gray-900">
            <Head title="Profil Perusahaan - NingratNet" />
            <Navbar />

            {/* Hero Section */}
            <div className="pt-32 pb-20 bg-indigo-900 text-white text-center">
                <h1 className="text-4xl md:text-5xl font-extrabold mb-6">Tentang NingratNet</h1>
                <p className="text-indigo-200 max-w-2xl mx-auto px-4 text-lg">
                    Membangun konektivitas digital Indonesia dengan infrastruktur handal dan layanan sepenuh hati.
                </p>
            </div>

            {/* Main Content */}
            <section className="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex flex-col md:flex-row gap-12 items-start">
                    <div className="md:w-1/2">
                        <div className="inline-flex items-center gap-2 text-indigo-600 font-bold mb-4 uppercase tracking-wider text-sm">
                            <Building2 size={18} /> Profil Kami
                        </div>
                        <h2 className="text-3xl font-bold mb-6 text-gray-900 leading-tight">
                            Solusi Internet Premium untuk Masa Depan Digital
                        </h2>
                        <div className="prose prose-lg text-gray-600">
                            <p className="mb-4">
                                <strong>NingratNet</strong> adalah penyedia layanan internet (ISP) yang berdedikasi untuk menghadirkan akses internet berkecepatan tinggi, stabil, dan terjangkau bagi masyarakat dan pelaku bisnis di Indonesia.
                            </p>
                            <p className="mb-4">
                                Berdiri sejak tahun 2020, kami mengawali langkah dengan satu visi sederhana: menghapus kesenjangan digital. Kami percaya bahwa akses internet yang berkualitas adalah hak setiap orang, bukan privilege segelintir pihak.
                            </p>
                            <p>
                                Didukung oleh infrastruktur <strong>100% Fiber Optic</strong> dan tim teknis yang berpengalaman, NingratNet kini telah melayani ribuan pelanggan, mulai dari perumahan, UMKM, hingga korporasi besar.
                            </p>
                        </div>
                    </div>

                    {/* Stats / Highlights */}
                    <div className="md:w-1/2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div className="bg-indigo-50 p-6 rounded-2xl border border-indigo-100">
                            <History className="text-indigo-600 mb-4" size={32} />
                            <h3 className="text-xl font-bold mb-2">Sejak 2020</h3>
                            <p className="text-sm text-gray-600">Berpengalaman melayani kebutuhan jaringan di berbagai sektor.</p>
                        </div>
                        <div className="bg-indigo-50 p-6 rounded-2xl border border-indigo-100">
                            <Users className="text-indigo-600 mb-4" size={32} />
                            <h3 className="text-xl font-bold mb-2">5000+ Pelanggan</h3>
                            <p className="text-sm text-gray-600">Kepercayaan ribuan pengguna aktif di berbagai area coverage.</p>
                        </div>
                        <div className="bg-indigo-50 p-6 rounded-2xl border border-indigo-100">
                            <Award className="text-indigo-600 mb-4" size={32} />
                            <h3 className="text-xl font-bold mb-2">99.9% SLA</h3>
                            <p className="text-sm text-gray-600">Jaminan tingkat layanan uptime tinggi untuk bisnis Anda.</p>
                        </div>
                    </div>
                </div>
            </section>

            <Footer />
        </div>
    );
}
