import React from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import { Target, Rocket, Heart } from 'lucide-react';

export default function VisiMisi() {
    return (
        <div className="bg-white font-sans text-gray-900">
            <Head title="Visi dan Misi - NingratNet" />
            <Navbar />

            <div className="pt-32 pb-12 bg-indigo-900 text-white text-center">
                <h1 className="text-4xl font-extrabold mb-4">Visi & Misi</h1>
                <p className="text-indigo-200 max-w-2xl mx-auto px-4">
                    Kompas yang mengarahkan setiap langkah kami dalam melayani Anda.
                </p>
            </div>

            <section className="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="grid md:grid-cols-2 gap-12">
                    {/* Visi */}
                    <div className="bg-indigo-50 p-10 rounded-3xl border border-indigo-100">
                        <div className="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white mb-6">
                            <Target size={32} />
                        </div>
                        <h2 className="text-3xl font-bold mb-4 text-indigo-900">Visi Kami</h2>
                        <p className="text-lg text-gray-700 leading-relaxed italic">
                            "Menjadi penyedia layanan internet dan solusi teknologi terdepan yang menghubungkan setiap pelosok negeri dengan peluang digital tanpa batas."
                        </p>
                    </div>

                    {/* Misi */}
                    <div className="bg-white p-10 rounded-3xl border border-gray-200 shadow-sm">
                         <div className="w-16 h-16 bg-pink-600 rounded-2xl flex items-center justify-center text-white mb-6">
                            <Rocket size={32} />
                        </div>
                        <h2 className="text-3xl font-bold mb-6 text-gray-900">Misi Kami</h2>
                        <ul className="space-y-4 text-gray-600">
                            <li className="flex gap-3">
                                <div className="mt-1"><Heart size={20} className="text-pink-500" /></div>
                                <span>Memberikan pelayanan pelanggan yang responsif, hangat, dan solutif.</span>
                            </li>
                            <li className="flex gap-3">
                                <div className="mt-1"><Rocket size={20} className="text-pink-500" /></div>
                                <span>Membangun infrastruktur jaringan Fiber Optic yang stabil dan handal.</span>
                            </li>
                            <li className="flex gap-3">
                                <div className="mt-1"><Target size={20} className="text-pink-500" /></div>
                                <span>Menghadirkan inovasi produk digital yang mendukung pertumbuhan UMKM.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            <Footer />
        </div>
    );
}
