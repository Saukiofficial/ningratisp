import React from 'react';
import { Link, Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';

export default function Packages({ packages }) {
    // Mapping tier names untuk visual
    const tierConfig = {
        'Epic': {
            gradient: 'from-purple-600 via-purple-500 to-pink-500',
            badge: 'bg-purple-100 text-purple-700',
            button: 'bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700',
            icon: '⚡',
            ring: 'ring-purple-500/20'
        },
        'Legend': {
            gradient: 'from-blue-600 via-cyan-500 to-teal-500',
            badge: 'bg-blue-100 text-blue-700',
            button: 'bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700',
            icon: '🔥',
            ring: 'ring-blue-500/20'
        },
        'Mythic': {
            gradient: 'from-amber-500 via-orange-500 to-red-500',
            badge: 'bg-amber-100 text-amber-700',
            button: 'bg-gradient-to-r from-amber-600 to-red-600 hover:from-amber-700 hover:to-red-700',
            icon: '👑',
            ring: 'ring-amber-500/20'
        }
    };

    const getTierConfig = (pkgName) => {
        if (pkgName.includes('Epic')) return tierConfig.Epic;
        if (pkgName.includes('Legend')) return tierConfig.Legend;
        if (pkgName.includes('Mythic')) return tierConfig.Mythic;
        return tierConfig.Epic; // default
    };

    return (
        <>
            <Head title="Daftar Paket" />
            <Navbar />

            <div className="relative py-20 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen overflow-hidden">
                {/* Background Effects */}
                <div className="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIi8+PC9zdmc+')] opacity-40"></div>

                <div className="absolute top-20 left-10 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
                <div className="absolute top-40 right-10 w-72 h-72 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse delay-1000"></div>
                <div className="absolute bottom-20 left-1/2 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse delay-2000"></div>

                <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="text-center mb-16">
                        <div className="inline-block mb-4">
                            <span className="px-4 py-2 rounded-full bg-gradient-to-r from-purple-600/20 to-pink-600/20 border border-purple-500/30 text-purple-300 text-sm font-semibold">
                                CHOOSE YOUR POWER
                            </span>
                        </div>
                        <h2 className="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-purple-200 to-pink-200 mb-6">
                            Level Up Your Internet
                        </h2>
                        <p className="text-xl text-slate-400 max-w-2xl mx-auto">
                            Pilih tier yang sesuai dengan gaya gaming dan streaming Anda. Setiap paket dirancang untuk performa maksimal.
                        </p>
                    </div>

                    {/* Package Cards */}
                    <div className="grid gap-8 md:grid-cols-3 lg:gap-10">
                        {packages.map((pkg, index) => {
                            const config = getTierConfig(pkg.name);
                            const isPopular = pkg.name.includes('Legend');

                            return (
                                <div
                                    key={pkg.id}
                                    className={`relative group ${isPopular ? 'md:-translate-y-4' : ''}`}
                                >
                                    {/* Popular Badge */}
                                    {isPopular && (
                                        <div className="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                                            <span className="px-4 py-1 bg-gradient-to-r from-cyan-500 to-blue-500 text-white text-xs font-bold rounded-full shadow-lg">
                                                MOST POPULAR
                                            </span>
                                        </div>
                                    )}

                                    {/* Card */}
                                    <div className={`relative bg-slate-800/50 backdrop-blur-sm rounded-3xl p-8 border border-slate-700/50 hover:border-slate-600 transition-all duration-500 hover:shadow-2xl hover:shadow-${config.gradient.split('-')[1]}-500/20 group-hover:scale-105 ring-4 ${config.ring} h-full flex flex-col`}>
                                        {/* Gradient Overlay on Hover */}
                                        <div className={`absolute inset-0 bg-gradient-to-br ${config.gradient} opacity-0 group-hover:opacity-5 rounded-3xl transition-opacity duration-500`}></div>

                                        <div className="relative z-10 flex flex-col h-full">
                                            {/* Icon & Badge */}
                                            <div className="flex items-center justify-between mb-6">
                                                <span className="text-5xl">{config.icon}</span>
                                                <span className={`px-3 py-1 rounded-full text-xs font-bold ${config.badge}`}>
                                                    TIER {index + 1}
                                                </span>
                                            </div>

                                            {/* Package Name */}
                                            <h3 className="text-3xl font-black text-white mb-3 tracking-tight">
                                                {pkg.name}
                                            </h3>

                                            {/* Price */}
                                            <div className="mb-6">
                                                <div className="flex items-baseline">
                                                    <span className="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r ${config.gradient}">
                                                        {pkg.price.toLocaleString('id-ID')}
                                                    </span>
                                                    <span className="ml-2 text-slate-400 font-medium">IDR</span>
                                                </div>
                                                <span className="text-sm text-slate-500 font-medium">per bulan</span>
                                            </div>

                                            {/* Description */}
                                            <p className="text-slate-400 mb-8 leading-relaxed flex-grow">
                                                {pkg.description}
                                            </p>

                                            {/* Features */}
                                            <ul className="space-y-4 mb-8">
                                                <li className="flex items-center text-slate-300">
                                                    <div className={`w-6 h-6 rounded-full bg-gradient-to-r ${config.gradient} flex items-center justify-center mr-3 flex-shrink-0`}>
                                                        <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                    <span className="font-medium">Speed up to <span className="text-white font-bold">{pkg.speed}</span></span>
                                                </li>
                                                <li className="flex items-center text-slate-300">
                                                    <div className={`w-6 h-6 rounded-full bg-gradient-to-r ${config.gradient} flex items-center justify-center mr-3 flex-shrink-0`}>
                                                        <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                    <span className="font-medium"><span className="text-white font-bold">Unlimited</span> Quota</span>
                                                </li>
                                                {/* <li className="flex items-center text-slate-300">
                                                    <div className={`w-6 h-6 rounded-full bg-gradient-to-r ${config.gradient} flex items-center justify-center mr-3 flex-shrink-0`}>
                                                        <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                    <span className="font-medium"><span className="text-white font-bold">Free</span> Installation</span>
                                                </li> */}
                                                <li className="flex items-center text-slate-300">
                                                    <div className={`w-6 h-6 rounded-full bg-gradient-to-r ${config.gradient} flex items-center justify-center mr-3 flex-shrink-0`}>
                                                        <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                    <span className="font-medium"><span className="text-white font-bold">24/7</span> Support</span>
                                                </li>
                                            </ul>

                                            {/* CTA Button */}
                                            <Link
                                                href={route('customer.register', { package: pkg.id })}
                                                className={`block w-full text-center ${config.button} text-white py-4 rounded-xl font-bold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1`}
                                            >
                                                Activate Now
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>

                    {/* Bottom CTA */}
                    <div className="mt-20 text-center">
                        <p className="text-slate-400 mb-4">Masih bingung memilih paket yang tepat?</p>
                        <button className="px-8 py-3 bg-slate-700/50 hover:bg-slate-700 text-white rounded-xl font-semibold transition-all duration-300 border border-slate-600 hover:border-slate-500">
                            Hubungi Kami
                        </button>
                    </div>
                </div>
            </div>

            <Footer />
        </>
    );
}
