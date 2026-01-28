import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import { Network, Server, Router, Globe, Activity, ShieldCheck, Zap, ArrowRight } from 'lucide-react';

export default function Topologi() {
    const [activeStep, setActiveStep] = useState(null);

    const networkSteps = [
        {
            id: 1,
            icon: Globe,
            title: "Global Internet",
            subtitle: "Tier-1 Connection",
            description: "Koneksi Tier-1 ke IXP Internasional & Lokal (IIX/OpenIXP).",
            color: "from-blue-500 to-cyan-500",
            borderColor: "border-blue-500"
        },
        {
            id: 2,
            icon: Server,
            title: "Data Center Core",
            subtitle: "High Performance",
            description: "Server Utama & OLT NingratNet dengan backup power.",
            color: "from-indigo-500 to-purple-500",
            borderColor: "border-indigo-500"
        },
        {
            id: 3,
            icon: Network,
            title: "Distribusi Fiber",
            subtitle: "Optical Distribution",
            description: "Jaringan ODP (Optical Distribution Point) di area Anda.",
            color: "from-purple-500 to-pink-500",
            borderColor: "border-purple-500"
        },
        {
            id: 4,
            icon: Router,
            title: "ONT Pelanggan",
            subtitle: "Last Mile",
            description: "Modem WiFi di rumah/kantor pelanggan.",
            color: "from-green-500 to-emerald-500",
            borderColor: "border-green-500"
        }
    ];

    return (
        <div className="bg-white font-sans text-gray-900">
            <Head title="Topologi Jaringan - NingratNet" />
            <Navbar />

            {/* Hero Section - Updated Design */}
            <div className="relative pt-32 pb-20 bg-gradient-to-br from-gray-900 via-slate-800 to-gray-900 text-white overflow-hidden">
                {/* Animated Background */}
                <div className="absolute inset-0 overflow-hidden">
                    <div className="absolute -top-1/2 -left-1/2 w-full h-full bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-full blur-3xl animate-pulse"></div>
                    <div className="absolute -bottom-1/2 -right-1/2 w-full h-full bg-gradient-to-tl from-cyan-500/10 to-pink-500/10 rounded-full blur-3xl animate-pulse" style={{animationDelay: '1s'}}></div>
                </div>

                <div className="relative text-center max-w-4xl mx-auto px-4">
                    <div className="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20 mb-6">
                        <Activity className="w-4 h-4 text-cyan-400" />
                        <span className="text-sm font-medium">Enterprise-Grade Infrastructure</span>
                    </div>
                    <h1 className="text-4xl md:text-5xl font-extrabold mb-4 bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 bg-clip-text text-transparent">
                        Infrastruktur Jaringan
                    </h1>
                    <p className="text-gray-300 text-lg max-w-2xl mx-auto">
                        Mengintip di balik layar bagaimana koneksi super cepat NingratNet sampai ke rumah Anda.
                    </p>
                </div>
            </div>

            <section className="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {/* Diagram Visualisasi - Updated Design */}
                <div className="relative mb-20">
                    {/* Connection Lines */}
                    <div className="hidden md:block absolute top-1/2 left-0 w-full h-0.5 -translate-y-1/2 -z-10">
                        <div className="absolute inset-0 bg-gradient-to-r from-blue-500 via-purple-500 to-green-500 opacity-20"></div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                        {networkSteps.map((step, index) => {
                            const Icon = step.icon;
                            const isActive = activeStep === step.id;

                            return (
                                <div
                                    key={step.id}
                                    className="relative"
                                    onMouseEnter={() => setActiveStep(step.id)}
                                    onMouseLeave={() => setActiveStep(null)}
                                >
                                    {/* Arrow Between Steps */}
                                    {index < networkSteps.length - 1 && (
                                        <div className="hidden md:block absolute top-1/2 -right-4 z-10 -translate-y-1/2">
                                            <ArrowRight className={`w-6 h-6 transition-all ${isActive ? 'text-blue-600 scale-125' : 'text-gray-300'}`} />
                                        </div>
                                    )}

                                    {/* Card */}
                                    <div className={`relative bg-white p-6 rounded-2xl border-2 shadow-lg transition-all duration-500 ${
                                        isActive ? `${step.borderColor} shadow-2xl -translate-y-4` : 'border-gray-200 hover:border-gray-300 hover:-translate-y-2'
                                    }`}>
                                        {/* Glow Effect */}
                                        <div className={`absolute inset-0 rounded-2xl bg-gradient-to-br ${step.color} opacity-0 transition-opacity blur-xl ${isActive ? 'opacity-20' : ''}`}></div>

                                        {/* Step Number */}
                                        <div className="absolute -top-3 -left-3 w-8 h-8 bg-white border-2 border-gray-300 rounded-full flex items-center justify-center text-sm font-bold text-gray-700 shadow-md">
                                            {step.id}
                                        </div>

                                        {/* Icon */}
                                        <div className={`relative w-20 h-20 mx-auto mb-4 rounded-2xl bg-gradient-to-br ${step.color} p-0.5 transition-transform duration-500 ${isActive ? 'scale-110 rotate-6' : ''}`}>
                                            <div className="w-full h-full bg-white rounded-2xl flex items-center justify-center">
                                                <Icon className="w-10 h-10 text-gray-700" />
                                            </div>
                                        </div>

                                        {/* Content */}
                                        <div className="text-center relative z-10">
                                            <div className={`inline-block px-3 py-1 rounded-full text-xs font-semibold mb-2 bg-gradient-to-r ${step.color} text-white`}>
                                                {step.subtitle}
                                            </div>
                                            <h3 className="font-bold text-lg mb-2">{step.title}</h3>
                                            <p className="text-sm text-gray-600">
                                                {step.description}
                                            </p>
                                        </div>

                                        {/* Animated Border */}
                                        {isActive && (
                                            <div className="absolute inset-0 rounded-2xl border-2 border-blue-400 animate-ping opacity-75"></div>
                                        )}
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </div>

                {/* Features Section - Updated Design */}
                <div className="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 className="text-3xl font-bold mb-8 bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                            Keunggulan Topologi Kami
                        </h2>
                        <ul className="space-y-6">
                            <li className="group flex gap-4 p-6 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div className="mt-1 bg-gradient-to-br from-green-400 to-emerald-600 p-3 rounded-xl text-white shadow-lg group-hover:scale-110 transition-transform">
                                    <ShieldCheck size={24} />
                                </div>
                                <div>
                                    <h4 className="font-bold text-lg mb-2">Redundansi Link</h4>
                                    <p className="text-gray-600">Memiliki jalur backup otomatis. Jika satu jalur putus, koneksi dialihkan ke jalur lain dalam hitungan detik tanpa disconnect.</p>
                                </div>
                            </li>
                            <li className="group flex gap-4 p-6 rounded-2xl bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div className="mt-1 bg-gradient-to-br from-yellow-400 to-orange-600 p-3 rounded-xl text-white shadow-lg group-hover:scale-110 transition-transform">
                                    <Zap size={24} />
                                </div>
                                <div>
                                    <h4 className="font-bold text-lg mb-2">Teknologi GPON Terbaru</h4>
                                    <p className="text-gray-600">Gigabit Passive Optical Network memastikan kecepatan gigabit stabil hingga ke perangkat akhir tanpa gangguan interferensi.</p>
                                </div>
                            </li>
                            <li className="group flex gap-4 p-6 rounded-2xl bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div className="mt-1 bg-gradient-to-br from-purple-400 to-pink-600 p-3 rounded-xl text-white shadow-lg group-hover:scale-110 transition-transform">
                                    <Activity size={24} />
                                </div>
                                <div>
                                    <h4 className="font-bold text-lg mb-2">Low Latency Routing</h4>
                                    <p className="text-gray-600">Optimasi routing langsung (direct peering) ke server game populer dan penyedia konten streaming untuk ping yang minimal.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div className="relative bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl h-80 flex items-center justify-center border-2 border-dashed border-gray-300 overflow-hidden group">
                        <div className="absolute inset-0 bg-gradient-to-br from-blue-100/50 via-purple-100/50 to-pink-100/50 opacity-50"></div>
                        <div className="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div className="z-10 text-center p-6">
                            <div className="relative mb-4">
                                <Network size={64} className="mx-auto text-gray-400 group-hover:text-indigo-500 transition-all duration-500 group-hover:scale-110 group-hover:rotate-12" />
                                <div className="absolute inset-0 flex items-center justify-center">
                                    <div className="w-20 h-20 bg-indigo-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity animate-pulse"></div>
                                </div>
                            </div>
                            <span className="text-gray-600 font-semibold block text-lg">Diagram Teknis Detail</span>
                            <span className="text-sm text-gray-500 mt-2 block">(Akan diupdate oleh Tim Network)</span>
                        </div>
                    </div>
                </div>
            </section>

            <Footer />
        </div>
    );
}
