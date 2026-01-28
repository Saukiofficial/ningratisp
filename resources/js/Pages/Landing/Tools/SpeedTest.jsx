import React from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import { Gauge, Zap, Globe, Wifi, Activity, TrendingUp, Shield, ArrowDown, ArrowUp, Radio } from 'lucide-react';

export default function SpeedTest() {
    return (
        <div className="bg-white font-sans text-gray-900">
            <Head title="Speed Test - NingratNet" />
            <Navbar />

            {/* Hero Section - Enhanced Design */}
            <div className="pt-32 pb-20 relative overflow-hidden">
                {/* Animated Background */}
                <div className="absolute inset-0 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800"></div>
                <div className="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMC41IiBvcGFjaXR5PSIwLjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-20"></div>

                <div className="relative max-w-5xl mx-auto px-4 text-center">
                    <div className="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 px-4 py-2 rounded-full text-white text-sm font-medium mb-6">
                        <Activity size={16} className="animate-pulse" />
                        <span>NingratNet Speed Server</span>
                        <span className="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    </div>

                    <h1 className="text-4xl md:text-5xl font-extrabold mb-6 text-white">
                        Uji Kecepatan Internet
                    </h1>

                    <p className="text-indigo-200 max-w-2xl mx-auto px-4 text-lg">
                        Cek kecepatan download, upload, dan ping koneksi Anda secara real-time dan akurat.
                    </p>
                </div>
            </div>

            {/* Speed Test Widget Section */}
            <section className="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                {/* Stats Preview Cards */}
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div className="bg-white rounded-2xl p-4 shadow-lg border border-gray-100 hover:shadow-xl hover:border-blue-200 transition-all duration-300">
                        <div className="flex items-center gap-3">
                            <div className="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                <ArrowDown className="text-white" size={22} />
                            </div>
                            <div>
                                <p className="text-xs text-gray-500 font-semibold uppercase tracking-wide">Download</p>
                                <p className="text-xl font-bold text-gray-900">-- Mbps</p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-2xl p-4 shadow-lg border border-gray-100 hover:shadow-xl hover:border-green-200 transition-all duration-300">
                        <div className="flex items-center gap-3">
                            <div className="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                                <ArrowUp className="text-white" size={22} />
                            </div>
                            <div>
                                <p className="text-xs text-gray-500 font-semibold uppercase tracking-wide">Upload</p>
                                <p className="text-xl font-bold text-gray-900">-- Mbps</p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-2xl p-4 shadow-lg border border-gray-100 hover:shadow-xl hover:border-purple-200 transition-all duration-300">
                        <div className="flex items-center gap-3">
                            <div className="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <Radio className="text-white" size={22} />
                            </div>
                            <div>
                                <p className="text-xs text-gray-500 font-semibold uppercase tracking-wide">Ping</p>
                                <p className="text-xl font-bold text-gray-900">-- ms</p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-2xl p-4 shadow-lg border border-gray-100 hover:shadow-xl hover:border-orange-200 transition-all duration-300">
                        <div className="flex items-center gap-3">
                            <div className="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                                <Activity className="text-white" size={22} />
                            </div>
                            <div>
                                <p className="text-xs text-gray-500 font-semibold uppercase tracking-wide">Jitter</p>
                                <p className="text-xl font-bold text-gray-900">-- ms</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Speed Test Widget Container - Enhanced */}
                <div className="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-200 relative">
                    {/* Decorative Top Bar */}
                    <div className="h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

                    {/* Server Info Bar - Enhanced */}
                    <div className="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <div className="flex items-center justify-between flex-wrap gap-3">
                            <div className="flex items-center gap-4">
                                <div className="flex items-center gap-2">
                                    <div className="w-3 h-3 bg-green-500 rounded-full shadow-lg shadow-green-500/50 animate-pulse"></div>
                                    <span className="text-sm font-bold text-gray-700">Server Online</span>
                                </div>
                                <div className="hidden md:block h-5 w-px bg-gray-300"></div>
                                <span className="text-xs text-gray-600 font-mono bg-white px-3 py-1 rounded-full border border-gray-200">
                                    Server ID: NINGRAT-JKT-01
                                </span>
                            </div>

                            <div className="flex items-center gap-2 text-xs text-gray-500 bg-white px-3 py-1.5 rounded-full border border-gray-200">
                                <Shield size={14} className="text-green-600" />
                                <span className="font-medium">Secure Connection</span>
                            </div>
                        </div>
                    </div>

                    {/* IFRAME WIDGET (Menggunakan OpenSpeedTest / HTML5) */}
                    <div className="relative bg-gradient-to-br from-gray-900 to-gray-800">
                        <iframe
                            src="https://openspeedtest.com/Get-widget.php"
                            width="100%"
                            height="600"
                            frameBorder="0"
                            allow="autoplay; encrypted-media"
                            title="Internet Speed Test"
                            className="w-full"
                        ></iframe>

                        {/* Corner Branding Overlay */}
                        <div className="absolute bottom-4 right-4 bg-black/50 backdrop-blur-md px-4 py-2 rounded-full border border-white/10">
                            <span className="text-white text-xs font-semibold">Powered by NingratNet</span>
                        </div>
                    </div>
                </div>

                {/* Tips Section - Enhanced Design */}
                <div className="mt-16 grid md:grid-cols-3 gap-8">
                    <div className="group bg-gradient-to-br from-indigo-50 to-indigo-100 p-8 rounded-3xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 border border-indigo-200">
                        <div className="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-lg">
                            <Zap className="text-white" size={28} />
                        </div>
                        <h3 className="font-bold text-xl mb-3 text-gray-900">Tips Pengujian</h3>
                        <p className="text-sm text-gray-700 leading-relaxed">
                            Untuk hasil maksimal, gunakan kabel LAN (Ethernet). Jika menggunakan WiFi, pastikan berada dekat dengan router.
                        </p>
                    </div>

                    <div className="group bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-3xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 border border-purple-200">
                        <div className="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-lg">
                            <Globe className="text-white" size={28} />
                        </div>
                        <h3 className="font-bold text-xl mb-3 text-gray-900">Ping & Jitter</h3>
                        <p className="text-sm text-gray-700 leading-relaxed">
                            Ping di bawah 20ms sangat bagus untuk gaming. Jitter rendah menandakan koneksi stabil tanpa lag.
                        </p>
                    </div>

                    <div className="group bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-3xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 border border-green-200">
                        <div className="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-lg">
                            <TrendingUp className="text-white" size={28} />
                        </div>
                        <h3 className="font-bold text-xl mb-3 text-gray-900">Simetris 1:1</h3>
                        <p className="text-sm text-gray-700 leading-relaxed">
                            Paket NingratNet menjamin kecepatan Upload setara dengan Download untuk kenyamanan streaming & backup.
                        </p>
                    </div>
                </div>

                {/* Additional Info Banner */}
                <div className="mt-12 bg-gradient-to-r from-indigo-50 via-purple-50 to-pink-50 rounded-2xl p-6 border border-indigo-100 shadow-md">
                    <div className="flex items-start gap-4">
                        <div className="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                            <Wifi className="text-white" size={24} />
                        </div>
                        <div className="flex-1">
                            <h4 className="font-bold text-lg text-gray-900 mb-2">Tentang Speed Test</h4>
                            <p className="text-sm text-gray-700 leading-relaxed">
                                Kami menggunakan server Jakarta dengan teknologi HTML5 untuk mengukur bandwidth real-time.
                                Hasil dapat bervariasi berdasarkan waktu, perangkat, dan kondisi jaringan. Untuk bantuan teknis,
                                hubungi <span className="font-bold text-indigo-600">support@ningratnet.id</span>
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <Footer />
        </div>
    );
}
