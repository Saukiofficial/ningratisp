import React from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import { Network, Server, Router, Globe, Activity, ShieldCheck, Zap } from 'lucide-react';

export default function Topologi() {
    return (
        <div className="bg-white font-sans text-gray-900">
            <Head title="Topologi Jaringan - NingratNet" />
            <Navbar />

            <div className="pt-32 pb-20 bg-gray-900 text-white text-center">
                <h1 className="text-4xl font-extrabold mb-4">Infrastruktur Jaringan</h1>
                <p className="text-gray-400 max-w-2xl mx-auto px-4">
                    Mengintip di balik layar bagaimana koneksi super cepat NingratNet sampai ke rumah Anda.
                </p>
            </div>

            <section className="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {/* Diagram Visualisasi Sederhana */}
                <div className="relative mb-20">
                    <div className="absolute top-1/2 left-0 w-full h-1 bg-gray-200 -z-10 hidden md:block transform -translate-y-1/2"></div>

                    <div className="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                        {/* Step 1 */}
                        <div className="bg-white p-6 rounded-xl border border-gray-200 shadow-sm relative group hover:-translate-y-2 transition-transform duration-300">
                            <div className="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <Globe size={32} />
                            </div>
                            <h3 className="font-bold text-lg mb-2">Global Internet</h3>
                            <p className="text-sm text-gray-500">Koneksi Tier-1 ke IXP Internasional & Lokal (IIX/OpenIXP).</p>
                        </div>

                        {/* Step 2 */}
                        <div className="bg-white p-6 rounded-xl border border-gray-200 shadow-sm relative group hover:-translate-y-2 transition-transform duration-300">
                            <div className="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <Server size={32} />
                            </div>
                            <h3 className="font-bold text-lg mb-2">Data Center Core</h3>
                            <p className="text-sm text-gray-500">Server Utama & OLT NingratNet dengan backup power.</p>
                        </div>

                        {/* Step 3 */}
                        <div className="bg-white p-6 rounded-xl border border-gray-200 shadow-sm relative group hover:-translate-y-2 transition-transform duration-300">
                            <div className="w-16 h-16 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                <Network size={32} />
                            </div>
                            <h3 className="font-bold text-lg mb-2">Distribusi Fiber</h3>
                            <p className="text-sm text-gray-500">Jaringan ODP (Optical Distribution Point) di area Anda.</p>
                        </div>

                        {/* Step 4 */}
                        <div className="bg-white p-6 rounded-xl border border-gray-200 shadow-sm relative group hover:-translate-y-2 transition-transform duration-300">
                            <div className="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white group-hover:bg-green-600 group-hover:text-white transition-colors">
                                <Router size={32} />
                            </div>
                            <h3 className="font-bold text-lg mb-2">ONT Pelanggan</h3>
                            <p className="text-sm text-gray-500">Modem WiFi di rumah/kantor pelanggan.</p>
                        </div>
                    </div>
                </div>

                <div className="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 className="text-3xl font-bold mb-6">Keunggulan Topologi Kami</h2>
                        <ul className="space-y-6">
                            <li className="flex gap-4">
                                <div className="mt-1 bg-green-100 p-2 rounded-lg text-green-600"><ShieldCheck size={24} /></div>
                                <div>
                                    <h4 className="font-bold text-lg">Redundansi Link</h4>
                                    <p className="text-gray-600">Memiliki jalur backup otomatis. Jika satu jalur putus, koneksi dialihkan ke jalur lain dalam hitungan detik tanpa disconnect.</p>
                                </div>
                            </li>
                            <li className="flex gap-4">
                                <div className="mt-1 bg-blue-100 p-2 rounded-lg text-blue-600"><Zap size={24} /></div>
                                <div>
                                    <h4 className="font-bold text-lg">Teknologi GPON Terbaru</h4>
                                    <p className="text-gray-600">Gigabit Passive Optical Network memastikan kecepatan gigabit stabil hingga ke perangkat akhir tanpa gangguan interferensi.</p>
                                </div>
                            </li>
                            <li className="flex gap-4">
                                <div className="mt-1 bg-purple-100 p-2 rounded-lg text-purple-600"><Activity size={24} /></div>
                                <div>
                                    <h4 className="font-bold text-lg">Low Latency Routing</h4>
                                    <p className="text-gray-600">Optimasi routing langsung (direct peering) ke server game populer dan penyedia konten streaming untuk ping yang minimal.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div className="bg-gray-100 rounded-3xl h-80 flex items-center justify-center border-2 border-dashed border-gray-300 relative overflow-hidden group">
                        <div className="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200 opacity-50"></div>
                        <div className="z-10 text-center p-6">
                            <Network size={64} className="mx-auto text-gray-400 mb-4 group-hover:text-indigo-500 transition-colors" />
                            <span className="text-gray-500 font-medium block">Diagram Teknis Detail</span>
                            <span className="text-xs text-gray-400">(Akan diupdate oleh Tim Network)</span>
                        </div>
                    </div>
                </div>
            </section>

            <Footer />
        </div>
    );
}
