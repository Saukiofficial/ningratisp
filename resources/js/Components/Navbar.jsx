import React, { useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { Wifi, ChevronDown, Menu, X, Building2, Target, Network, Users, Cloud, Server, Lightbulb, Settings, Wrench, Shield, Gauge, Search } from 'lucide-react';

export default function Navbar() {
    const { auth } = usePage().props;
    const [isScrolled, setIsScrolled] = useState(false);
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

    // Deteksi scroll untuk efek navbar transparan/solid
    useEffect(() => {
        const handleScroll = () => {
            setIsScrolled(window.scrollY > 20);
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    // Cek apakah user sedang berada di halaman home ('/')
    const isHomePage = typeof window !== 'undefined' && window.location.pathname === '/';

    // --- DATA LINK NAVIGASI ---

    // Link Sub-menu "Tentang Kami"
    const aboutLinks = [
        {
            label: 'Tentang NingratNet',
            href: '/tentang-kami/profil',
            icon: Building2,
            description: 'Pelajari profil NingratNet'
        },
        {
            label: 'Visi dan Misi',
            href: '/tentang-kami/visi-misi',
            icon: Target,
            description: 'Pelajari Visi dan Misi NingratNet'
        },
        {
            label: 'Topologi NingratNet',
            href: '/tentang-kami/topologi',
            icon: Network,
            description: 'Pelajari Topologi NingratNet'
        },
        {
            label: 'Struktur Organisasi',
            href: '/tentang-kami/struktur-organisasi',
            icon: Users,
            description: 'Jelajahi Struktur Organisasi Perusahaan'
        },
    ];

    // Link Sub-menu "Layanan"
    const serviceLinks = [
        {
            label: 'Cloud Access Service',
            href: '/layanan/cloud-access',
            icon: Cloud,
            description: 'Solusi cloud terpercaya untuk bisnis'
        },
        {
            label: 'IT Managed Services',
            href: '/layanan/managed-services',
            icon: Server,
            description: 'Kelola infrastruktur IT Anda'
        },
        {
            label: 'IT Consultant, IT Solutions',
            href: '/layanan/consultant',
            icon: Lightbulb,
            description: 'Konsultasi dan solusi IT terbaik'
        },
        {
            label: 'Management Service',
            href: '/layanan/management',
            icon: Settings,
            description: 'Layanan manajemen profesional'
        },
        {
            label: 'Maintenance Electrical',
            href: '/layanan/electrical',
            icon: Wrench,
            description: 'Perawatan sistem elektrikal'
        },
        {
            label: 'Security System',
            href: '/layanan/security',
            icon: Shield,
            description: 'Sistem keamanan terpadu'
        },
    ];

    // Link Sub-menu "Tools" (Update: Search diganti Tracking)
    const toolsLinks = [
        {
            label: 'Speed Test',
            href: '/tools/speed-test',
            icon: Gauge,
            description: 'Uji kecepatan internet Anda sekarang'
        },
        {
            label: 'Tracking & Status',
            href: '/tools/tracking',
            icon: Search,
            description: 'Lacak status pemasangan pelanggan'
        },
    ];

    return (
        <nav className="fixed w-full top-0 z-50 bg-white shadow-md border-b border-gray-100 py-2 transition-all duration-300">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between items-center h-16">
                    {/* --- LOGO SECTION --- */}
                    <div className="flex items-center">
                        <Link href="/" className="flex items-center gap-2 group">
                            <div className="p-2 rounded-xl bg-indigo-600 text-white transition-colors">
                                <Wifi size={24} />
                            </div>
                            <span className="font-extrabold text-2xl tracking-tight text-gray-900">
                                Ningrat<span className="text-indigo-500">Net</span>
                            </span>
                        </Link>
                    </div>

                    {/* --- DESKTOP MENU --- */}
                    <div className="hidden lg:flex items-center space-x-1">
                        <Link
                            href="/"
                            className="px-4 py-2 rounded-full font-medium text-sm transition-colors text-gray-600 hover:text-indigo-600 hover:bg-indigo-50"
                        >
                            Beranda
                        </Link>

                        {/* Mega Dropdown Tentang Kami */}
                        <div className="relative group">
                            <button className="flex items-center gap-1 px-4 py-2 rounded-full font-medium text-sm transition-colors focus:outline-none text-gray-600 hover:text-indigo-600 hover:bg-indigo-50">
                                Tentang Kami
                                <ChevronDown size={14} className="group-hover:rotate-180 transition-transform duration-300" />
                            </button>

                            {/* Mega Menu Content */}
                            <div className="absolute top-full left-1/2 -translate-x-1/2 pt-4 w-[600px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                                <div className="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                                    {/* Header Section dengan Gradient - UPDATED: Menggunakan Indigo/Purple (bukan Teal) */}
                                    <div className="bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 p-6 text-white">
                                        <h3 className="text-2xl font-bold mb-2">Perusahaan</h3>
                                        <p className="text-indigo-100 text-sm">Kenali lebih dalam tentang NingratNet</p>
                                    </div>

                                    {/* Menu Items Grid - UPDATED: Hover & Icon color changed to Indigo */}
                                    <div className="grid grid-cols-2 gap-3 p-4">
                                        {aboutLinks.map((link, idx) => {
                                            const IconComponent = link.icon;
                                            return (
                                                <Link
                                                    key={idx}
                                                    href={link.href}
                                                    className="group/item flex items-start gap-3 p-4 rounded-xl hover:bg-indigo-50 transition-all duration-200 border border-transparent hover:border-indigo-200"
                                                >
                                                    <div className="flex-shrink-0 w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center group-hover/item:bg-indigo-600 group-hover/item:text-white transition-colors">
                                                        <IconComponent size={20} />
                                                    </div>
                                                    <div className="flex-1 min-w-0">
                                                        <div className="font-semibold text-gray-900 text-sm mb-1 group-hover/item:text-indigo-600 transition-colors">
                                                            {link.label}
                                                        </div>
                                                        <div className="text-xs text-gray-500 leading-relaxed">
                                                            {link.description}
                                                        </div>
                                                    </div>
                                                </Link>
                                            );
                                        })}
                                    </div>

                                    {/* Footer Banner - UPDATED: Background gradient */}
                                    <div className="bg-gradient-to-r from-indigo-50 to-purple-50 p-4 border-t border-gray-100">
                                        <div className="flex items-center justify-between">
                                            <div>
                                                <h4 className="font-bold text-gray-900 text-sm">Paket Internet Unggulan</h4>
                                                <p className="text-xs text-gray-600 mt-0.5">Segera Dapatkan Promo dan Semua Layanan Terbaik kami</p>
                                            </div>
                                            <Link
                                                href={route('landing.packages')}
                                                className="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition-colors whitespace-nowrap"
                                            >
                                                Lihat Paket
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Link
                            href={route('landing.packages')}
                            className="px-4 py-2 rounded-full font-medium text-sm transition-colors text-gray-600 hover:text-indigo-600 hover:bg-indigo-50"
                        >
                            Produk
                        </Link>

                        {/* Mega Dropdown Layanan */}
                        <div className="relative group">
                            <button className="flex items-center gap-1 px-4 py-2 rounded-full font-medium text-sm transition-colors focus:outline-none text-gray-600 hover:text-indigo-600 hover:bg-indigo-50">
                                Layanan
                                <ChevronDown size={14} className="group-hover:rotate-180 transition-transform duration-300" />
                            </button>

                            {/* Mega Menu Content - Layanan */}
                            <div className="absolute top-full left-1/2 -translate-x-1/2 pt-4 w-[600px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                                <div className="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                                    {/* Header Section dengan Gradient */}
                                    <div className="bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 p-6 text-white">
                                        <h3 className="text-2xl font-bold mb-2">Layanan Kami</h3>
                                        <p className="text-indigo-100 text-sm">Solusi IT lengkap untuk kebutuhan bisnis Anda</p>
                                    </div>

                                    {/* Menu Items Grid */}
                                    <div className="grid grid-cols-2 gap-3 p-4">
                                        {serviceLinks.map((link, idx) => {
                                            const IconComponent = link.icon;
                                            return (
                                                <Link
                                                    key={idx}
                                                    href={link.href}
                                                    className="group/item flex items-start gap-3 p-4 rounded-xl hover:bg-indigo-50 transition-all duration-200 border border-transparent hover:border-indigo-200"
                                                >
                                                    <div className="flex-shrink-0 w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center group-hover/item:bg-indigo-600 group-hover/item:text-white transition-colors">
                                                        <IconComponent size={20} />
                                                    </div>
                                                    <div className="flex-1 min-w-0">
                                                        <div className="font-semibold text-gray-900 text-sm mb-1 group-hover/item:text-indigo-600 transition-colors">
                                                            {link.label}
                                                        </div>
                                                        <div className="text-xs text-gray-500 leading-relaxed">
                                                            {link.description}
                                                        </div>
                                                    </div>
                                                </Link>
                                            );
                                        })}
                                    </div>

                                    {/* Footer Banner */}
                                    <div className="bg-gradient-to-r from-indigo-50 to-purple-50 p-4 border-t border-gray-100">
                                        <div className="flex items-center justify-between">
                                            <div>
                                                <h4 className="font-bold text-gray-900 text-sm">Butuh Konsultasi?</h4>
                                                <p className="text-xs text-gray-600 mt-0.5">Hubungi tim kami untuk solusi terbaik</p>
                                            </div>
                                            <a
                                                href="https://wa.me/62812345678" // Ganti dengan link WA Anda
                                                target="_blank"
                                                className="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition-colors whitespace-nowrap"
                                            >
                                                Hubungi Kami
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Dropdown Tools (Baru) */}
                        <div className="relative group">
                            <button className="flex items-center gap-1 px-4 py-2 rounded-full font-medium text-sm transition-colors focus:outline-none text-gray-600 hover:text-indigo-600 hover:bg-indigo-50">
                                Tools
                                <ChevronDown size={14} className="group-hover:rotate-180 transition-transform duration-300" />
                            </button>

                            {/* Dropdown Content - Tools */}
                            <div className="absolute top-full left-1/2 -translate-x-1/2 pt-4 w-[400px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                                <div className="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                                    <div className="bg-gradient-to-br from-blue-600 via-blue-700 to-cyan-800 p-6 text-white">
                                        <h3 className="text-2xl font-bold mb-2">Tools</h3>
                                        <p className="text-blue-100 text-sm">Alat bantu praktis</p>
                                    </div>
                                    <div className="flex flex-col gap-2 p-3">
                                        {toolsLinks.map((link, idx) => {
                                            const IconComponent = link.icon;
                                            return (
                                                <Link
                                                    key={idx}
                                                    href={link.href}
                                                    className="group/item flex items-start gap-4 p-4 rounded-xl hover:bg-blue-50 transition-all duration-200 border border-transparent hover:border-blue-200"
                                                >
                                                    <div className="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center group-hover/item:bg-blue-600 group-hover/item:text-white transition-colors">
                                                        <IconComponent size={20} />
                                                    </div>
                                                    <div className="flex-1 min-w-0">
                                                        <div className="font-semibold text-gray-900 text-sm mb-1 group-hover/item:text-blue-600 transition-colors">
                                                            {link.label}
                                                        </div>
                                                        <div className="text-xs text-gray-500 leading-relaxed">
                                                            {link.description}
                                                        </div>
                                                    </div>
                                                </Link>
                                            );
                                        })}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Link
                            href="/coverage-area"
                            className="px-4 py-2 rounded-full font-medium text-sm transition-colors text-gray-600 hover:text-indigo-600 hover:bg-indigo-50"
                        >
                            Coverage Area
                        </Link>
                    </div>

                    {/* --- AUTH BUTTONS --- */}
                    <div className="hidden lg:flex items-center gap-3 pl-6 ml-6 border-l border-gray-200/20">
                        {auth.user ? (
                            <Link
                                href="/dashboard"
                                className="px-5 py-2.5 rounded-full font-bold text-sm transition-all shadow-lg hover:shadow-indigo-500/30 bg-indigo-600 text-white hover:bg-indigo-700"
                            >
                                Dashboard
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href={route('login')}
                                    className="font-medium text-sm transition-colors text-gray-600 hover:text-indigo-600"
                                >
                                    Login
                                </Link>
                                <Link
                                    href={route('register')}
                                    className="px-5 py-2.5 rounded-full font-bold text-sm transition-all shadow-lg hover:shadow-indigo-500/30 bg-indigo-600 text-white hover:bg-indigo-700"
                                >
                                    Daftar Sekarang
                                </Link>
                            </>
                        )}
                    </div>

                    {/* --- MOBILE TOGGLE --- */}
                    <button
                        onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
                        className="lg:hidden p-2 rounded-lg transition-colors text-gray-900"
                    >
                        {isMobileMenuOpen ? <X size={24} /> : <Menu size={24} />}
                    </button>
                </div>
            </div>

            {/* --- MOBILE MENU --- */}
            <div className={`lg:hidden absolute top-full left-0 w-full bg-white border-b border-gray-100 shadow-xl transition-all duration-300 ease-in-out origin-top ${
                isMobileMenuOpen ? 'opacity-100 scale-y-100' : 'opacity-0 scale-y-0 h-0 overflow-hidden'
            }`}>
                <div className="px-4 py-6 space-y-4">
                    <Link href="/" className="block text-lg font-medium text-gray-900">Beranda</Link>

                    {/* Tentang Kami Mobile */}
                    <div className="space-y-3">
                        <div className="text-sm font-bold text-indigo-600 uppercase tracking-wider">Tentang Kami</div>
                        {aboutLinks.map((link, idx) => (
                            <Link
                                key={idx}
                                href={link.href}
                                className="block pl-4 text-base text-gray-600 hover:text-indigo-600 border-l-2 border-transparent hover:border-indigo-600 transition-all"
                            >
                                {link.label}
                            </Link>
                        ))}
                    </div>

                    <Link href={route('landing.packages')} className="block text-lg font-medium text-gray-900">Produk</Link>

                    {/* Layanan Mobile */}
                    <div className="space-y-3">
                        <div className="text-sm font-bold text-indigo-600 uppercase tracking-wider">Layanan</div>
                        {serviceLinks.map((link, idx) => (
                            <Link
                                key={idx}
                                href={link.href}
                                className="block pl-4 text-base text-gray-600 hover:text-indigo-600 border-l-2 border-transparent hover:border-indigo-600 transition-all"
                            >
                                {link.label}
                            </Link>
                        ))}
                    </div>

                    {/* Tools Mobile */}
                    <div className="space-y-3">
                        <div className="text-sm font-bold text-indigo-600 uppercase tracking-wider">Tools</div>
                        {toolsLinks.map((link, idx) => (
                            <Link
                                key={idx}
                                href={link.href}
                                className="block pl-4 text-base text-gray-600 hover:text-indigo-600 border-l-2 border-transparent hover:border-indigo-600 transition-all"
                            >
                                {link.label}
                            </Link>
                        ))}
                    </div>

                    <Link href="/coverage-area" className="block text-lg font-medium text-gray-900">Coverage Area</Link>

                    <div className="pt-6 border-t border-gray-100 flex flex-col gap-3">
                        {auth.user ? (
                            <Link href="/dashboard" className="w-full py-3 bg-indigo-600 text-white rounded-xl font-bold text-center">
                                Dashboard
                            </Link>
                        ) : (
                            <>
                                <Link href={route('login')} className="w-full py-3 border border-gray-200 text-gray-700 rounded-xl font-bold text-center">
                                    Login
                                </Link>
                                <Link href={route('register')} className="w-full py-3 bg-indigo-600 text-white rounded-xl font-bold text-center shadow-lg shadow-indigo-200">
                                    Daftar Sekarang
                                </Link>
                            </>
                        )}
                    </div>
                </div>
            </div>
        </nav>
    );
}
