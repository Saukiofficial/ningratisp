import React, { useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { Wifi, ChevronDown, Menu, X, Building2, Target, Network, Users, Cloud, Server, Lightbulb, Settings, Wrench, Shield, Gauge, Search, Phone } from 'lucide-react';

export default function Navbar() {
    const { auth } = usePage().props;
    const [isScrolled, setIsScrolled] = useState(false);
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            setIsScrolled(window.scrollY > 20);
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const isHomePage = typeof window !== 'undefined' && window.location.pathname === '/';

    const waNumber = "628123456789";
    const waMessage = "Halo Admin NingratNet, saya melihat website dan berminat untuk daftar pasang baru.";
    const waLink = `https://wa.me/${waNumber}?text=${encodeURIComponent(waMessage)}`;

    const aboutLinks = [
        { label: 'Tentang NingratNet', href: '/tentang-kami/profil', icon: Building2, description: 'Pelajari profil NingratNet' },
        { label: 'Visi dan Misi', href: '/tentang-kami/visi-misi', icon: Target, description: 'Pelajari Visi dan Misi NingratNet' },
        { label: 'Topologi NingratNet', href: '/tentang-kami/topologi', icon: Network, description: 'Pelajari Topologi NingratNet' },
        { label: 'Struktur Organisasi', href: '/tentang-kami/struktur-organisasi', icon: Users, description: 'Jelajahi Struktur Organisasi Perusahaan' },
    ];

    const serviceLinks = [
        { label: 'Cloud Access Service', href: '/layanan/cloud-access', icon: Cloud, description: 'Solusi cloud terpercaya untuk bisnis' },
        { label: 'IT Managed Services', href: '/layanan/managed-services', icon: Server, description: 'Kelola infrastruktur IT Anda' },
        { label: 'IT Consultant, IT Solutions', href: '/layanan/consultant', icon: Lightbulb, description: 'Konsultasi dan solusi IT terbaik' },
        { label: 'Management Service', href: '/layanan/management', icon: Settings, description: 'Layanan manajemen profesional' },
        { label: 'Maintenance Electrical', href: '/layanan/electrical', icon: Wrench, description: 'Perawatan sistem elektrikal' },
        { label: 'Security System', href: '/layanan/security', icon: Shield, description: 'Sistem keamanan terpadu' },
    ];

    const toolsLinks = [
        { label: 'Speed Test', href: '/tools/speed-test', icon: Gauge, description: 'Uji kecepatan internet Anda sekarang' },
        { label: 'Tracking & Status', href: '/tools/tracking', icon: Search, description: 'Lacak status pemasangan pelanggan' },
    ];

    return (
        <nav className={`fixed w-full top-0 z-50 transition-all duration-500 ${isScrolled || isMobileMenuOpen ? 'bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 shadow-2xl py-2' : 'bg-transparent py-4'}`}>
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between items-center h-16">
                    {/* LOGO */}
                    <div className="flex items-center">
                        <Link href="/" className="flex items-center gap-3 group">
                            <div className="relative">
                                <div className="absolute inset-0 bg-gradient-to-r from-orange-500 to-amber-500 rounded-full blur-lg opacity-50 group-hover:opacity-75 transition-opacity"></div>
                                <img
                                    src="/assets/img/logo.png"
                                    alt="NingratNet Logo"
                                    className="h-10 w-auto object-contain relative z-10"
                                />
                            </div>
                            <span className="font-black text-2xl tracking-tight">
                                <span className="text-white">Ningrat</span>
                                <span className="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-400">Net</span>
                            </span>
                        </Link>
                    </div>

                    {/* DESKTOP MENU */}
                    <div className="hidden lg:flex items-center space-x-1">
                        <Link href="/" className="px-4 py-2 rounded-full font-semibold text-sm text-white/90 hover:text-white hover:bg-white/10 transition-all">
                            Beranda
                        </Link>

                        {/* Dropdown Tentang Kami */}
                        <div className="relative group">
                            <button className="flex items-center gap-1 px-4 py-2 rounded-full font-semibold text-sm text-white/90 hover:text-white hover:bg-white/10 transition-all focus:outline-none">
                                Tentang Kami <ChevronDown size={14} className="group-hover:rotate-180 transition-transform duration-300" />
                            </button>
                            <div className="absolute top-full left-1/2 -translate-x-1/2 pt-4 w-[600px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                                <div className="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl shadow-2xl border border-orange-500/30 overflow-hidden backdrop-blur-xl">
                                    <div className="bg-gradient-to-r from-orange-600 to-amber-600 p-6 text-white">
                                        <h3 className="text-2xl font-bold mb-2">Perusahaan</h3>
                                        <p className="text-orange-100 text-sm">Kenali lebih dalam tentang NingratNet</p>
                                    </div>
                                    <div className="grid grid-cols-2 gap-3 p-4">
                                        {aboutLinks.map((link, idx) => {
                                            const IconComponent = link.icon;
                                            return (
                                                <Link key={idx} href={link.href} className="group/item flex items-start gap-3 p-4 rounded-xl hover:bg-orange-500/10 transition-all duration-200 border border-transparent hover:border-orange-500/30">
                                                    <div className="flex-shrink-0 w-10 h-10 rounded-lg bg-orange-500/20 text-orange-400 flex items-center justify-center group-hover/item:bg-orange-500 group-hover/item:text-white transition-colors"><IconComponent size={20} /></div>
                                                    <div className="flex-1 min-w-0">
                                                        <div className="font-semibold text-white text-sm mb-1 group-hover/item:text-orange-400 transition-colors">{link.label}</div>
                                                        <div className="text-xs text-gray-400 leading-relaxed">{link.description}</div>
                                                    </div>
                                                </Link>
                                            );
                                        })}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Link Produk */}
                        <a href={isHomePage ? "#packages" : "/#packages"} className="px-4 py-2 rounded-full font-semibold text-sm text-white/90 hover:text-white hover:bg-white/10 transition-all cursor-pointer">
                            Produk
                        </a>

                        {/* Dropdown Layanan */}
                        <div className="relative group">
                            <button className="flex items-center gap-1 px-4 py-2 rounded-full font-semibold text-sm text-white/90 hover:text-white hover:bg-white/10 transition-all focus:outline-none">
                                Layanan <ChevronDown size={14} className="group-hover:rotate-180 transition-transform duration-300" />
                            </button>
                            <div className="absolute top-full left-1/2 -translate-x-1/2 pt-4 w-[600px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                                <div className="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl shadow-2xl border border-orange-500/30 overflow-hidden backdrop-blur-xl">
                                    <div className="bg-gradient-to-r from-orange-600 to-amber-600 p-6 text-white">
                                        <h3 className="text-2xl font-bold mb-2">Layanan Kami</h3>
                                        <p className="text-orange-100 text-sm">Solusi IT lengkap untuk kebutuhan bisnis Anda</p>
                                    </div>
                                    <div className="grid grid-cols-2 gap-3 p-4">
                                        {serviceLinks.map((link, idx) => {
                                            const IconComponent = link.icon;
                                            return (
                                                <Link key={idx} href={link.href} className="group/item flex items-start gap-3 p-4 rounded-xl hover:bg-orange-500/10 transition-all duration-200 border border-transparent hover:border-orange-500/30">
                                                    <div className="flex-shrink-0 w-10 h-10 rounded-lg bg-orange-500/20 text-orange-400 flex items-center justify-center group-hover/item:bg-orange-500 group-hover/item:text-white transition-colors"><IconComponent size={20} /></div>
                                                    <div className="flex-1 min-w-0">
                                                        <div className="font-semibold text-white text-sm mb-1 group-hover/item:text-orange-400 transition-colors">{link.label}</div>
                                                        <div className="text-xs text-gray-400 leading-relaxed">{link.description}</div>
                                                    </div>
                                                </Link>
                                            );
                                        })}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Dropdown Tools */}
                        <div className="relative group">
                            <button className="flex items-center gap-1 px-4 py-2 rounded-full font-semibold text-sm text-white/90 hover:text-white hover:bg-white/10 transition-all focus:outline-none">
                                Tools <ChevronDown size={14} className="group-hover:rotate-180 transition-transform duration-300" />
                            </button>
                            <div className="absolute top-full left-1/2 -translate-x-1/2 pt-4 w-[400px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                                <div className="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl shadow-2xl border border-orange-500/30 overflow-hidden backdrop-blur-xl">
                                    <div className="bg-gradient-to-r from-cyan-600 to-teal-600 p-6 text-white">
                                        <h3 className="text-2xl font-bold mb-2">Tools</h3>
                                        <p className="text-cyan-100 text-sm">Alat bantu praktis</p>
                                    </div>
                                    <div className="flex flex-col gap-2 p-3">
                                        {toolsLinks.map((link, idx) => {
                                            const IconComponent = link.icon;
                                            return (
                                                <Link key={idx} href={link.href} className="group/item flex items-start gap-4 p-4 rounded-xl hover:bg-cyan-500/10 transition-all duration-200 border border-transparent hover:border-cyan-500/30">
                                                    <div className="flex-shrink-0 w-10 h-10 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center group-hover/item:bg-cyan-500 group-hover/item:text-white transition-colors"><IconComponent size={20} /></div>
                                                    <div className="flex-1 min-w-0">
                                                        <div className="font-semibold text-white text-sm mb-1 group-hover/item:text-cyan-400 transition-colors">{link.label}</div>
                                                        <div className="text-xs text-gray-400 leading-relaxed">{link.description}</div>
                                                    </div>
                                                </Link>
                                            );
                                        })}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Link href="/coverage-area" className="px-4 py-2 rounded-full font-semibold text-sm text-white/90 hover:text-white hover:bg-white/10 transition-all">
                            Coverage Area
                        </Link>
                    </div>

                    {/* AUTH BUTTONS */}
                    <div className="hidden lg:flex items-center gap-3 pl-6 ml-6 border-l border-white/10">
                        <Link href={route('login')} className="font-semibold text-sm text-white/90 hover:text-white transition-colors">Login Customer</Link>
                        <a
                            href={waLink}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="px-6 py-2.5 rounded-full font-bold text-sm transition-all shadow-lg hover:shadow-cyan-400/50 bg-gradient-to-r from-cyan-400 to-cyan-500 text-slate-900 hover:from-cyan-500 hover:to-cyan-600 flex items-center gap-2"
                        >
                            Daftar Sekarang
                        </a>
                    </div>

                    {/* MOBILE TOGGLE */}
                    <button onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)} className="lg:hidden p-2 rounded-lg transition-colors text-white">
                        {isMobileMenuOpen ? <X size={24} /> : <Menu size={24} />}
                    </button>
                </div>
            </div>

            {/* MOBILE MENU */}
            <div className={`lg:hidden absolute top-full left-0 w-full bg-gradient-to-br from-slate-900 to-slate-800 border-b border-orange-500/20 shadow-2xl transition-all duration-300 ease-in-out origin-top ${isMobileMenuOpen ? 'opacity-100 scale-y-100' : 'opacity-0 scale-y-0 h-0 overflow-hidden'}`}>
                <div className="px-4 py-6 space-y-4">
                    <Link href="/" className="block text-lg font-semibold text-white">Beranda</Link>

                    <div className="space-y-3">
                        <div className="text-sm font-bold text-orange-400 uppercase tracking-wider">Tentang Kami</div>
                        {aboutLinks.map((link, idx) => (
                            <Link key={idx} href={link.href} className="block pl-4 text-base text-gray-300 hover:text-orange-400 border-l-2 border-transparent hover:border-orange-500 transition-all">{link.label}</Link>
                        ))}
                    </div>

                    <a href={isHomePage ? "#packages" : "/#packages"} className="block text-lg font-semibold text-white">Produk</a>

                    <div className="space-y-3">
                        <div className="text-sm font-bold text-orange-400 uppercase tracking-wider">Layanan</div>
                        {serviceLinks.map((link, idx) => (
                            <Link key={idx} href={link.href} className="block pl-4 text-base text-gray-300 hover:text-orange-400 border-l-2 border-transparent hover:border-orange-500 transition-all">{link.label}</Link>
                        ))}
                    </div>

                    <div className="space-y-3">
                        <div className="text-sm font-bold text-orange-400 uppercase tracking-wider">Tools</div>
                        {toolsLinks.map((link, idx) => (
                            <Link key={idx} href={link.href} className="block pl-4 text-base text-gray-300 hover:text-orange-400 border-l-2 border-transparent hover:border-orange-500 transition-all">{link.label}</Link>
                        ))}
                    </div>

                    <Link href="/coverage-area" className="block text-lg font-semibold text-white">Coverage Area</Link>

                    <div className="pt-6 border-t border-orange-500/20 flex flex-col gap-3">
                        <Link href={route('login')} className="font-semibold text-sm text-white/90 hover:text-white transition-colors">Login Customer</Link>
                        <a
                            href={waLink}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="px-6 py-2.5 rounded-full font-bold text-sm transition-all shadow-lg hover:shadow-cyan-400/50 bg-gradient-to-r from-cyan-400 to-cyan-500 text-slate-900 hover:from-cyan-500 hover:to-cyan-600 flex items-center gap-2"
                        >
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    );
}
