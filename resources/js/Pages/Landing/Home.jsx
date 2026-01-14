import React, { useState } from 'react';
import { Link, Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import PackageCard from '@/Components/PackageCard';
import {
    MapPin,
    ShieldCheck,
    Zap,
    Headphones,
    CheckCircle2,
    ArrowRight,
    Wifi,
    ChevronDown,
    ChevronUp,
    Award,
    Clock,
    Users,
    TrendingUp,
    Phone,
    Mail
} from 'lucide-react';

export default function Home({ packages }) {
    const [openFaq, setOpenFaq] = useState(null);
    const [coverageInput, setCoverageInput] = useState('');

    const toggleFaq = (index) => {
        setOpenFaq(openFaq === index ? null : index);
    };

    const faqs = [
        { q: "Apakah harga sudah termasuk PPN?", a: "Harga yang tertera belum termasuk PPN 11%. Biaya instalasi gratis untuk paket tertentu." },
        { q: "Apakah ada batasan kuota (FUP)?", a: "Tidak ada. Semua paket NingratNet adalah Truly Unlimited tanpa batasan kuota (FUP)." },
        { q: "Berapa lama proses pemasangan?", a: "Estimasi pemasangan adalah 1-3 hari kerja setelah registrasi berhasil dan lokasi terverifikasi." },
        { q: "Bagaimana jika internet saya gangguan?", a: "Tim support kami aktif 24/7. Anda bisa melapor via WhatsApp atau Dashboard Pelanggan." },
    ];

    const testimonials = [
        { name: "Andi Saputra", role: "Freelancer", text: "Upload speednya simetris, sangat membantu kerjaan saya sebagai video editor. Jarang RTO juga." },
        { name: "Sarah Wijaya", role: "Ibu Rumah Tangga", text: "Anak-anak streaming YouTube lancar, suami Zoom meeting juga aman. Teknisi ramah banget pas pasang." },
        { name: "Budi Santoso", role: "Gamer", text: "Ping kecil banget buat main Valorant. Rekomen buat yang cari internet stabil buat gaming." },
    ];

    const awards = [
        { title: "Best Value ISP", year: "2024" },
        { title: "Customer Choice", year: "2024" },
        { title: "Fastest Growing", year: "2023" },
        { title: "Service Excellence", year: "2023" }
    ];

    return (
        <div className="font-sans antialiased text-gray-900 bg-white">
            <Head title="NingratNet - Harga Rakyat, Kualitas Ningrat" />
            <Navbar />

            {/* HERO SECTION - Elegant Split Layout */}
            <div className="relative bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 overflow-hidden">
                {/* Animated Background Elements */}
                <div className="absolute inset-0 overflow-hidden">
                    {/* Gradient Orbs */}
                    <div className="absolute top-0 right-0 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
                    <div className="absolute top-0 left-0 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
                    <div className="absolute bottom-0 left-1/2 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

                    {/* Grid Pattern */}
                    <div className="absolute inset-0 opacity-10">
                        <div className="absolute inset-0" style={{
                            backgroundImage: 'radial-gradient(circle at 2px 2px, white 1px, transparent 0)',
                            backgroundSize: '40px 40px'
                        }}></div>
                    </div>

                    {/* Geometric Shapes */}
                    <div className="absolute top-20 left-10 w-20 h-20 border-4 border-white/20 rounded-lg transform rotate-12 animate-float"></div>
                    <div className="absolute bottom-32 right-20 w-16 h-16 border-4 border-white/20 rounded-full animate-float-delayed"></div>
                    <div className="absolute top-1/2 left-1/4 w-12 h-12 bg-white/10 backdrop-blur-sm rounded-lg transform -rotate-6"></div>
                </div>

                <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12 md:pt-24 md:pb-16">
                    <div className="flex flex-row gap-3 md:gap-8 items-center min-h-[400px] md:min-h-[500px]">
                        {/* Left Content */}
                        <div className="w-1/2 lg:w-1/2 text-left z-10">
                            <div className="inline-block mb-3 md:mb-4">
                                <span className="inline-flex items-center gap-2 px-3 py-1 md:px-4 md:py-2 bg-white/20 backdrop-blur-sm rounded-full text-white text-xs md:text-sm font-semibold">
                                    <Wifi className="w-3 h-3 md:w-4 md:h-4" />
                                    Internet Fiber Optic
                                </span>
                            </div>

                            <h1 className="text-xl sm:text-3xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-2 md:mb-6 drop-shadow-lg">
                                Internet Cepat untuk
                                <span className="block text-yellow-300">
                                    Semua Kalangan
                                </span>
                            </h1>

                            <p className="text-sm sm:text-lg md:text-2xl font-semibold text-yellow-300 mb-2 md:mb-4 drop-shadow">
                                Harga Rakyat, Kualitas Ningrat
                            </p>

                            <p className="text-xs sm:text-sm md:text-lg text-blue-50 mb-3 md:mb-8 leading-relaxed">
                                Penyedia layanan internet lokal pemenang penghargaan yang menawarkan Broadband Superfast untuk rumah dan bisnis di area pedesaan dan perkotaan.
                            </p>

                            <div className="flex flex-col sm:flex-row gap-2 md:gap-3 mb-0 md:mb-8">
                                <a
                                    href="#packages"
                                    className="inline-flex items-center justify-center px-4 py-2 md:px-8 md:py-4 bg-white text-blue-600 rounded-lg font-bold text-xs md:text-base hover:bg-blue-50 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1"
                                >
                                    JELAJAHI LEBIH LANJUT
                                    <ArrowRight className="ml-1 md:ml-2 w-3 h-3 md:w-5 md:h-5" />
                                </a>
                                <a
                                    href="#packages"
                                    className="hidden sm:inline-flex items-center justify-center px-4 py-2 md:px-8 md:py-4 bg-transparent border-2 border-white text-white rounded-lg font-bold text-xs md:text-base hover:bg-white hover:text-blue-600 transition"
                                >
                                    <Phone className="mr-1 md:mr-2 w-3 h-3 md:w-5 md:h-5" />
                                    Hubungi Kami
                                </a>
                            </div>
                        </div>

                        {/* Right Image - Person with device */}
                        <div className="w-1/2 lg:w-1/2 flex items-center justify-center lg:justify-end relative">
                            {/* Glow Effect Behind Image */}
                            <div className="absolute inset-0 bg-gradient-to-tr from-yellow-300/20 to-pink-300/20 rounded-full blur-3xl"></div>

                            <div className="relative w-full flex items-center justify-center lg:justify-end">
                                {/* Main hero image */}
                                <img
                                    src="/assets/img/wanita.png"
                                    alt="Person using fast internet"
                                    className="relative z-10 w-full max-w-[180px] sm:max-w-xs md:max-w-md lg:max-w-xl object-contain drop-shadow-2xl"
                                    style={{ height: 'auto' }}
                                />

                                {/* Floating Elements - Hidden on mobile, adjusted for larger screens */}
                                <div className="hidden lg:block absolute top-20 right-10 w-16 h-16 opacity-60 animate-float">
                                    <div className="w-12 h-12 bg-yellow-300 rounded-full shadow-lg"></div>
                                </div>
                                <div className="hidden lg:block absolute top-40 right-32 w-12 h-12 opacity-50 animate-float-delayed">
                                    <div className="w-10 h-10 bg-pink-300 rounded-full shadow-lg"></div>
                                </div>
                                <div className="hidden lg:block absolute bottom-32 right-20 w-10 h-10 opacity-40 animate-float">
                                    <div className="w-8 h-8 bg-blue-300 rounded-full shadow-lg"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Stats Bar - Floating */}
                    <div className="relative z-20 -mb-8 md:-mb-16">
                        <div className="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-4 md:p-6 border border-white/20">
                            <div className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <Zap className="w-5 h-5 md:w-6 md:h-6 text-blue-600" />
                                    </div>
                                    <div>
                                        <div className="text-xs md:text-sm text-gray-500 font-medium">Kecepatan</div>
                                        <div className="text-sm md:text-lg font-bold text-gray-900">Up to 1Gbps</div>
                                    </div>
                                </div>
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <Users className="w-5 h-5 md:w-6 md:h-6 text-green-600" />
                                    </div>
                                    <div>
                                        <div className="text-xs md:text-sm text-gray-500 font-medium">Pelanggan</div>
                                        <div className="text-sm md:text-lg font-bold text-gray-900">500+</div>
                                    </div>
                                </div>
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 md:w-12 md:h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <Clock className="w-5 h-5 md:w-6 md:h-6 text-purple-600" />
                                    </div>
                                    <div>
                                        <div className="text-xs md:text-sm text-gray-500 font-medium">Support</div>
                                        <div className="text-sm md:text-lg font-bold text-gray-900">24/7</div>
                                    </div>
                                </div>
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 md:w-12 md:h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <MapPin className="w-5 h-5 md:w-6 md:h-6 text-orange-600" />
                                    </div>
                                    <div>
                                        <div className="text-xs md:text-sm text-gray-500 font-medium">Coverage</div>
                                        <div className="text-sm md:text-lg font-bold text-gray-900">50+ Area</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Dynamic Wave Divider with Gradient */}
                <div className="relative">
                    <svg className="w-full h-24 md:h-40" viewBox="0 0 1440 200" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="waveGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style={{stopColor: '#FFFFFF', stopOpacity: 0.9}} />
                                <stop offset="50%" style={{stopColor: '#F0F9FF', stopOpacity: 1}} />
                                <stop offset="100%" style={{stopColor: '#FFFFFF', stopOpacity: 0.9}} />
                            </linearGradient>
                        </defs>
                        {/* Bottom Wave Layer */}
                        <path d="M0,96L48,112C96,128,192,160,288,165.3C384,171,480,149,576,133.3C672,117,768,107,864,112C960,117,1056,139,1152,144C1248,149,1344,139,1392,133.3L1440,128L1440,200L1392,200C1344,200,1248,200,1152,200C1056,200,960,200,864,200C768,200,672,200,576,200C480,200,384,200,288,200C192,200,96,200,48,200L0,200Z" fill="url(#waveGradient)" fillOpacity="0.3"/>
                        {/* Middle Wave Layer */}
                        <path d="M0,128L48,138.7C96,149,192,171,288,170.7C384,171,480,149,576,138.7C672,128,768,128,864,138.7C960,149,1056,171,1152,170.7C1248,171,1344,149,1392,138.7L1440,128L1440,200L1392,200C1344,200,1248,200,1152,200C1056,200,960,200,864,200C768,200,672,200,576,200C480,200,384,200,288,200C192,200,96,200,48,200L0,200Z" fill="url(#waveGradient)" fillOpacity="0.6"/>
                        {/* Top Wave Layer */}
                        <path d="M0,160L48,165.3C96,171,192,181,288,181.3C384,181,480,171,576,160C672,149,768,139,864,144C960,149,1056,171,1152,176C1248,181,1344,171,1392,165.3L1440,160L1440,200L1392,200C1344,200,1248,200,1152,200C1056,200,960,200,864,200C768,200,672,200,576,200C480,200,384,200,288,200C192,200,96,200,48,200L0,200Z" fill="url(#waveGradient)"/>
                    </svg>
                </div>

                {/* Coverage Check Bar */}
                <div className="relative bg-white border-t border-gray-200 shadow-lg z-20">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 md:py-6">
                        {/* Desktop Layout */}
                        <div className="hidden md:flex flex-row items-center gap-4">
                            {/* Left side - Check availability text */}
                            <div className="flex items-center gap-3 text-gray-700">
                                <div className="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <MapPin className="w-6 h-6 text-white" />
                                </div>
                                <span className="font-semibold text-base">Cek ketersediaan di area Anda</span>
                            </div>

                            {/* Center - Input field */}
                            <div className="flex-1 max-w-md">
                                <input
                                    type="text"
                                    value={coverageInput}
                                    onChange={(e) => setCoverageInput(e.target.value)}
                                    placeholder="Mulai ketik nama desa/kelurahan Anda..."
                                    className="w-full px-4 py-3 border border-gray-300 rounded-md text-gray-900 text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>

                            {/* Right side - Coverage status */}
                            <div className="flex items-center gap-3">
                                <div className="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <CheckCircle2 className="w-6 h-6 text-white" />
                                </div>
                                <div className="text-left">
                                    <p className="text-xs text-gray-500">Status Jangkauan</p>
                                    <p className="font-bold text-green-600 text-base">50+ Area Tersedia</p>
                                </div>
                            </div>

                            {/* Find out more button */}
                            <button className="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-md font-bold transition whitespace-nowrap flex items-center gap-2 text-base">
                                CEK AREA
                                <ArrowRight className="w-4 h-4" />
                            </button>
                        </div>

                        {/* Mobile Compact Layout - 2 Rows */}
                        <div className="md:hidden space-y-3">
                            {/* Row 1: Icons with text side by side */}
                            <div className="flex items-center justify-between gap-2">
                                <div className="flex items-center gap-2 flex-1">
                                    <div className="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <MapPin className="w-5 h-5 text-white" />
                                    </div>
                                    <span className="font-semibold text-xs leading-tight">Cek ketersediaan di area Anda</span>
                                </div>

                                <div className="flex items-center gap-2 flex-1">
                                    <div className="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <CheckCircle2 className="w-5 h-5 text-white" />
                                    </div>
                                    <div className="text-left">
                                        <p className="text-xs text-gray-500 leading-none">Status Jangkauan</p>
                                        <p className="font-bold text-green-600 text-xs">50+ Area Tersedia</p>
                                    </div>
                                </div>
                            </div>

                            {/* Row 2: Input and Button */}
                            <div className="flex gap-2">
                                <input
                                    type="text"
                                    value={coverageInput}
                                    onChange={(e) => setCoverageInput(e.target.value)}
                                    placeholder="Mulai ketik nama desa/kelurahan Anda..."
                                    className="flex-1 px-3 py-2.5 border border-gray-300 rounded-md text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                                <button className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-md font-bold transition whitespace-nowrap flex items-center gap-1 text-sm">
                                    CEK AREA
                                    <ArrowRight className="w-3 h-3" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Wave Divider 1 */}
            <div className="relative">
                <svg className="w-full h-16 md:h-24" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                    <path d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,58.7C960,64,1056,64,1152,58.7C1248,53,1344,43,1392,37.3L1440,32L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z" fill="white"/>
                </svg>
            </div>

            {/* STATS SECTION */}
            <div className="bg-white py-12 md:py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                        <div className="text-center">
                            <div className="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-blue-100 rounded-full mb-3 md:mb-4">
                                <Users className="w-6 h-6 md:w-8 md:h-8 text-blue-600" />
                            </div>
                            <div className="text-2xl md:text-4xl font-extrabold text-gray-900 mb-1 md:mb-2">5,000+</div>
                            <div className="text-xs md:text-sm text-gray-500 font-medium">Pelanggan Aktif</div>
                        </div>
                        <div className="text-center">
                            <div className="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-green-100 rounded-full mb-3 md:mb-4">
                                <TrendingUp className="w-6 h-6 md:w-8 md:h-8 text-green-600" />
                            </div>
                            <div className="text-2xl md:text-4xl font-extrabold text-gray-900 mb-1 md:mb-2">99.9%</div>
                            <div className="text-xs md:text-sm text-gray-500 font-medium">Uptime Guarantee</div>
                        </div>
                        <div className="text-center">
                            <div className="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-purple-100 rounded-full mb-3 md:mb-4">
                                <Clock className="w-6 h-6 md:w-8 md:h-8 text-purple-600" />
                            </div>
                            <div className="text-2xl md:text-4xl font-extrabold text-gray-900 mb-1 md:mb-2">24/7</div>
                            <div className="text-xs md:text-sm text-gray-500 font-medium">Customer Support</div>
                        </div>
                        <div className="text-center">
                            <div className="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-orange-100 rounded-full mb-3 md:mb-4">
                                <MapPin className="w-6 h-6 md:w-8 md:h-8 text-orange-600" />
                            </div>
                            <div className="text-2xl md:text-4xl font-extrabold text-gray-900 mb-1 md:mb-2">50+</div>
                            <div className="text-xs md:text-sm text-gray-500 font-medium">Area Jangkauan</div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Wave Divider 2 */}
            <div className="relative">
                <svg className="w-full h-16 md:h-20" viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                    <path d="M0,32L60,37.3C120,43,240,53,360,56C480,59,600,53,720,48C840,43,960,37,1080,42.7C1200,48,1320,64,1380,72L1440,80L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z" fill="#F9FAFB"/>
                </svg>
            </div>

            {/* AWARDS SECTION */}
            <div className="bg-gray-50 py-12 md:py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-8 md:mb-12">
                        <h2 className="text-2xl md:text-3xl font-extrabold text-gray-900 mb-3 md:mb-4">
                            Penyedia Internet Terpercaya & Berprestasi
                        </h2>
                        <p className="text-sm md:text-base text-gray-600">Dipercaya ribuan pelanggan dan diakui industri</p>
                    </div>

                    <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                        {awards.map((award, idx) => (
                            <div key={idx} className="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                                <Award className="w-10 h-10 md:w-12 md:h-12 text-yellow-500 mx-auto mb-2 md:mb-3" />
                                <h4 className="font-bold text-gray-900 text-xs md:text-sm mb-1">{award.title}</h4>
                                <p className="text-xs text-gray-500">{award.year}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* Wave Divider 3 */}
            <div className="relative">
                <svg className="w-full h-16 md:h-24" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                    <path d="M0,96L48,90.7C96,85,192,75,288,74.7C384,75,480,85,576,90.7C672,96,768,96,864,85.3C960,75,1056,53,1152,48C1248,43,1344,53,1392,58.7L1440,64L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z" fill="#F9FAFB"/>
                </svg>
            </div>

            {/* WHY CHOOSE US */}
            <div className="py-10 md:py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center max-w-3xl mx-auto mb-8 md:mb-16">
                        <h2 className="text-xs md:text-sm font-semibold text-blue-600 tracking-wide uppercase mb-2">Kenapa NingratNet?</h2>
                        <h3 className="text-2xl md:text-4xl font-extrabold text-gray-900 mb-3 md:mb-4">
                            Teknologi Terkini dengan Harga Terjangkau
                        </h3>
                        <p className="text-sm md:text-lg text-gray-600">
                            Kami percaya internet cepat adalah hak semua orang. Itulah mengapa kami hadirkan kualitas premium dengan harga rakyat.
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-8">
                        <div className="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 md:p-8 rounded-2xl border border-blue-100 hover:shadow-lg transition">
                            <div className="w-12 h-12 md:w-14 md:h-14 bg-blue-600 rounded-xl flex items-center justify-center text-white mb-4 md:mb-6">
                                <Zap size={24} className="md:w-7 md:h-7" />
                            </div>
                            <h4 className="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3">Kecepatan Simetris</h4>
                            <p className="text-sm md:text-base text-gray-600 leading-relaxed">
                                Download dan Upload sama cepatnya (1:1). Ideal untuk content creator, gamer, dan profesional yang butuh upload cepat.
                            </p>
                        </div>

                        <div className="bg-gradient-to-br from-green-50 to-emerald-50 p-6 md:p-8 rounded-2xl border border-green-100 hover:shadow-lg transition">
                            <div className="w-12 h-12 md:w-14 md:h-14 bg-green-600 rounded-xl flex items-center justify-center text-white mb-4 md:mb-6">
                                <ShieldCheck size={24} className="md:w-7 md:h-7" />
                            </div>
                            <h4 className="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3">100% Fiber Optic</h4>
                            <p className="text-sm md:text-base text-gray-600 leading-relaxed">
                                Menggunakan teknologi FTTH (Fiber To The Home). Koneksi stabil bahkan saat hujan deras atau cuaca ekstrem.
                            </p>
                        </div>

                        <div className="bg-gradient-to-br from-purple-50 to-pink-50 p-6 md:p-8 rounded-2xl border border-purple-100 hover:shadow-lg transition">
                            <div className="w-12 h-12 md:w-14 md:h-14 bg-purple-600 rounded-xl flex items-center justify-center text-white mb-4 md:mb-6">
                                <Headphones size={24} className="md:w-7 md:h-7" />
                            </div>
                            <h4 className="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3">Support Lokal 24/7</h4>
                            <p className="text-sm md:text-base text-gray-600 leading-relaxed">
                                Tim teknis kami siap membantu kapan saja. Hubungi via WhatsApp atau telepon, respons cepat dijamin!
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {/* Wave Divider 4 - Layered */}
            <div className="relative">
                <svg className="w-full h-20 md:h-32" viewBox="0 0 1440 150" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                    <path d="M0,96L48,106.7C96,117,192,139,288,138.7C384,139,480,117,576,112C672,107,768,117,864,117.3C960,117,1056,107,1152,96C1248,85,1344,75,1392,69.3L1440,64L1440,150L1392,150C1344,150,1248,150,1152,150C1056,150,960,150,864,150C768,150,672,150,576,150C480,150,384,150,288,150C192,150,96,150,48,150L0,150Z" fill="#F9FAFB" fillOpacity="0.5"/>
                    <path d="M0,64L48,74.7C96,85,192,107,288,112C384,117,480,107,576,96C672,85,768,75,864,80C960,85,1056,107,1152,112C1248,117,1344,107,1392,101.3L1440,96L1440,150L1392,150C1344,150,1248,150,1152,150C1056,150,960,150,864,150C768,150,672,150,576,150C480,150,384,150,288,150C192,150,96,150,48,150L0,150Z" fill="#F9FAFB"/>
                </svg>
            </div>

            {/* PACKAGES SECTION */}
            <div id="packages" className="py-10 md:py-20 bg-gray-50 scroll-mt-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-8 md:mb-16">
                        <h2 className="text-xs md:text-sm font-semibold text-blue-600 tracking-wide uppercase mb-2">Paket Pilihan</h2>
                        <h3 className="text-2xl md:text-4xl font-extrabold text-gray-900 mb-3 md:mb-4">
                            Pilih Paket Sesuai Kebutuhan Anda
                        </h3>
                        <p className="text-sm md:text-lg text-gray-600">
                            Harga transparan, tanpa biaya tersembunyi. Semua paket unlimited tanpa FUP.
                        </p>
                    </div>

                    <div className="grid gap-5 md:gap-8 grid-cols-1 md:grid-cols-3 mb-6 md:mb-8">
                        {packages.map((pkg, index) => (
                            <PackageCard
                                key={pkg.id}
                                pkg={pkg}
                                highlight={index === 1}
                            />
                        ))}
                    </div>

                    <div className="text-center">
                        <p className="text-xs md:text-sm text-gray-500">
                            *Harga belum termasuk PPN 11%. Instalasi GRATIS untuk paket tertentu.
                        </p>
                    </div>
                </div>
            </div>

            {/* Wave Divider 5 - Curved */}
            <div className="relative">
                <svg className="w-full h-16 md:h-24" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                    <path d="M0,64L80,58.7C160,53,320,43,480,48C640,53,800,75,960,85.3C1120,96,1280,96,1360,96L1440,96L1440,0L1360,0C1280,0,1120,0,960,0C800,0,640,0,480,0C320,0,160,0,80,0L0,0Z" fill="#F9FAFB"/>
                </svg>
            </div>

            {/* COVERAGE AREAS */}
            <div className="py-12 md:py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col lg:flex-row gap-8 md:gap-12 lg:gap-16 items-center">
                        {/* Text Content - Left */}
                        <div className="w-full lg:w-1/2 order-2 lg:order-1">
                            <h2 className="text-xs md:text-sm font-semibold text-blue-600 tracking-wide uppercase mb-2">Jangkauan Luas</h2>
                            <h3 className="text-2xl md:text-4xl font-extrabold text-gray-900 mb-4 md:mb-6">
                                Tersedia di 50+ Area
                            </h3>
                            <p className="text-sm md:text-lg text-gray-600 mb-6 md:mb-8 leading-relaxed">
                                NingratNet terus berkembang untuk menjangkau lebih banyak area. Kami hadir di perumahan, kompleks bisnis, dan area perkotaan.
                            </p>

                            <div className="space-y-3 md:space-y-4 mb-6 md:mb-8">
                                <div className="flex items-start gap-3 md:gap-4 p-3 md:p-4 bg-blue-50 rounded-xl">
                                    <MapPin className="w-5 h-5 md:w-6 md:h-6 text-blue-600 flex-shrink-0 mt-1" />
                                    <div>
                                        <h4 className="font-bold text-gray-900 text-sm md:text-base mb-1">Jabodetabek</h4>
                                        <p className="text-xs md:text-sm text-gray-600">Jakarta, Bogor, Depok, Tangerang, Bekasi</p>
                                    </div>
                                </div>
                                <div className="flex items-start gap-3 md:gap-4 p-3 md:p-4 bg-blue-50 rounded-xl">
                                    <MapPin className="w-5 h-5 md:w-6 md:h-6 text-blue-600 flex-shrink-0 mt-1" />
                                    <div>
                                        <h4 className="font-bold text-gray-900 text-sm md:text-base mb-1">Bandung Raya</h4>
                                        <p className="text-xs md:text-sm text-gray-600">Bandung, Cimahi, Bandung Barat</p>
                                    </div>
                                </div>
                                <div className="flex items-start gap-3 md:gap-4 p-3 md:p-4 bg-blue-50 rounded-xl">
                                    <MapPin className="w-5 h-5 md:w-6 md:h-6 text-blue-600 flex-shrink-0 mt-1" />
                                    <div>
                                        <h4 className="font-bold text-gray-900 text-sm md:text-base mb-1">Surabaya & Sidoarjo</h4>
                                        <p className="text-xs md:text-sm text-gray-600">Surabaya, Sidoarjo, Gresik</p>
                                    </div>
                                </div>
                            </div>

                            <Link
                                href={route('register')}
                                className="inline-flex items-center px-5 py-2.5 md:px-6 md:py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition text-sm md:text-base w-full sm:w-auto justify-center"
                            >
                                Daftar Sekarang
                                <ArrowRight className="ml-2 w-4 h-4 md:w-5 md:h-5" />
                            </Link>
                        </div>

                        {/* Image - Right */}
                        <div className="w-full lg:w-1/2 order-1 lg:order-2">
                            <img
                                src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=1000&auto=format&fit=crop"
                                alt="Coverage map"
                                className="rounded-2xl shadow-xl w-full h-auto object-cover"
                            />
                        </div>
                    </div>
                </div>
            </div>

            {/* Wave Divider 6 - Gradient */}
            <div className="relative">
                <svg className="w-full h-20 md:h-32" viewBox="0 0 1440 150" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" style={{stopColor: '#2563EB', stopOpacity: 1}} />
                            <stop offset="50%" style={{stopColor: '#4F46E5', stopOpacity: 1}} />
                            <stop offset="100%" style={{stopColor: '#7C3AED', stopOpacity: 1}} />
                        </linearGradient>
                    </defs>
                    <path d="M0,64L48,80C96,96,192,128,288,128C384,128,480,96,576,85.3C672,75,768,85,864,90.7C960,96,1056,96,1152,85.3C1248,75,1344,53,1392,42.7L1440,32L1440,150L1392,150C1344,150,1248,150,1152,150C1056,150,960,150,864,150C768,150,672,150,576,150C480,150,384,150,288,150C192,150,96,150,48,150L0,150Z" fill="url(#gradient1)"/>
                </svg>
            </div>

            {/* TESTIMONIALS */}
            <div className="py-10 md:py-20 bg-gradient-to-br from-blue-600 to-indigo-700 text-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-8 md:mb-16">
                        <h2 className="text-xs md:text-sm font-semibold text-blue-200 tracking-wide uppercase mb-2">Testimoni</h2>
                        <h3 className="text-2xl md:text-4xl font-extrabold mb-3 md:mb-4">
                            Apa Kata Pelanggan Kami?
                        </h3>
                        <p className="text-sm md:text-lg text-blue-100">
                            Ribuan pelanggan puas dengan layanan NingratNet
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-8">
                        {testimonials.map((testi, idx) => (
                            <div key={idx} className="bg-white/10 backdrop-blur-sm p-6 md:p-8 rounded-2xl border border-white/20 hover:bg-white/15 transition">
                                <div className="flex items-center gap-1 mb-3 md:mb-4">
                                    {[...Array(5)].map((_, i) => (
                                        <svg key={i} className="w-4 h-4 md:w-5 md:h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                    ))}
                                </div>
                                <p className="text-sm md:text-base text-blue-50 mb-4 md:mb-6 leading-relaxed italic">"{testi.text}"</p>
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center font-bold text-white text-base md:text-lg">
                                        {testi.name.charAt(0)}
                                    </div>
                                    <div>
                                        <h5 className="font-bold text-white text-sm md:text-base">{testi.name}</h5>
                                        <p className="text-xs md:text-sm text-blue-200">{testi.role}</p>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* Wave Divider 7 */}
            <div className="relative">
                <svg className="w-full h-16 md:h-24" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                    <path d="M0,32L48,42.7C96,53,192,75,288,74.7C384,75,480,53,576,42.7C672,32,768,32,864,42.7C960,53,1056,75,1152,80C1248,85,1344,75,1392,69.3L1440,64L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z" fill="rgb(30, 58, 138)"/>
                </svg>
            </div>

            {/* FAQ */}
            <div className="py-12 md:py-20 bg-gray-50">
                <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-8 md:mb-12">
                        <h2 className="text-xs md:text-sm font-semibold text-blue-600 tracking-wide uppercase mb-2">FAQ</h2>
                        <h3 className="text-2xl md:text-4xl font-extrabold text-gray-900 mb-3 md:mb-4">
                            Pertanyaan yang Sering Diajukan
                        </h3>
                    </div>

                    <div className="space-y-3 md:space-y-4">
                        {faqs.map((faq, index) => (
                            <div key={index} className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                                <button
                                    onClick={() => toggleFaq(index)}
                                    className="w-full px-4 py-4 md:px-6 md:py-5 text-left flex justify-between items-center focus:outline-none hover:bg-gray-50 transition"
                                >
                                    <span className="font-bold text-gray-900 pr-4 text-sm md:text-base">{faq.q}</span>
                                    {openFaq === index ?
                                        <ChevronUp className="w-5 h-5 text-blue-600 flex-shrink-0" /> :
                                        <ChevronDown className="w-5 h-5 text-gray-400 flex-shrink-0" />
                                    }
                                </button>
                                <div className={`transition-all duration-300 ease-in-out ${openFaq === index ? 'max-h-40 opacity-100' : 'max-h-0 opacity-0'}`}>
                                    <div className="px-4 pb-4 md:px-6 md:pb-5 text-gray-600 leading-relaxed text-sm md:text-base">
                                        {faq.a}
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* Wave Divider 8 - Animated */}
            <div className="relative">
                <svg className="w-full h-20 md:h-28" viewBox="0 0 1440 140" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                    <path d="M0,96L48,85.3C96,75,192,53,288,53.3C384,53,480,75,576,85.3C672,96,768,96,864,85.3C960,75,1056,53,1152,48C1248,43,1344,53,1392,58.7L1440,64L1440,140L1392,140C1344,140,1248,140,1152,140C1056,140,960,140,864,140C768,140,672,140,576,140C480,140,384,140,288,140C192,140,96,140,48,140L0,140Z" fill="white"/>
                </svg>
            </div>

            {/* CTA SECTION */}
            <div className="py-12 md:py-20 bg-white">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-2xl md:rounded-3xl p-8 md:p-12 lg:p-16 text-center text-white shadow-2xl overflow-hidden">
                        {/* Decorative elements */}
                        <div className="absolute top-0 left-0 w-full h-full opacity-10">
                            <div className="absolute top-10 left-10 w-32 h-32 bg-white rounded-full blur-3xl"></div>
                            <div className="absolute bottom-10 right-10 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                        </div>

                        <div className="relative z-10">
                            <h2 className="text-2xl md:text-3xl lg:text-5xl font-extrabold mb-4 md:mb-6 leading-tight">
                                Siap Upgrade Internet Anda?
                            </h2>
                            <p className="text-base md:text-xl text-blue-100 mb-6 md:mb-10 max-w-2xl mx-auto leading-relaxed">
                                Bergabung dengan ribuan pelanggan yang sudah merasakan internet cepat dengan harga terjangkau. Jangan biarkan koneksi lambat menghambat produktivitas Anda!
                            </p>

                            <div className="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center mb-6 md:mb-8">
                                <Link
                                    href={route('register')}
                                    className="inline-flex items-center justify-center px-8 py-3 md:px-10 md:py-4 bg-white text-blue-600 rounded-lg font-bold text-base md:text-lg shadow-lg hover:shadow-xl hover:bg-gray-50 transform hover:-translate-y-1 transition"
                                >
                                    Daftar Sekarang
                                    <ArrowRight className="ml-2 w-4 h-4 md:w-5 md:h-5" />
                                </Link>
                                <a
                                    href="tel:+62123456789"
                                    className="inline-flex items-center justify-center px-8 py-3 md:px-10 md:py-4 bg-transparent border-2 border-white text-white rounded-lg font-bold text-base md:text-lg hover:bg-white hover:text-blue-600 transition"
                                >
                                    <Phone className="mr-2 w-4 h-4 md:w-5 md:h-5" />
                                    Hubungi Kami
                                </a>
                            </div>

                            <div className="flex flex-col sm:flex-row gap-4 md:gap-6 justify-center text-xs md:text-sm text-blue-100">
                                <div className="flex items-center justify-center gap-2">
                                    <CheckCircle2 className="w-4 h-4 md:w-5 md:h-5 flex-shrink-0" />
                                    <span>Proses cepat 1-3 hari</span>
                                </div>
                                <div className="flex items-center justify-center gap-2">
                                    <CheckCircle2 className="w-4 h-4 md:w-5 md:h-5 flex-shrink-0" />
                                    <span>Gratis instalasi*</span>
                                </div>
                                <div className="flex items-center justify-center gap-2">
                                    <CheckCircle2 className="w-4 h-4 md:w-5 md:h-5 flex-shrink-0" />
                                    <span>Garansi uang kembali</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <Footer />

            <style jsx>{`
                @keyframes blob {
                    0%, 100% { transform: translate(0px, 0px) scale(1); }
                    33% { transform: translate(30px, -50px) scale(1.1); }
                    66% { transform: translate(-20px, 20px) scale(0.9); }
                }
                .animate-blob {
                    animation: blob 7s infinite;
                }
                .animation-delay-2000 {
                    animation-delay: 2s;
                }
                @keyframes float {
                    0%, 100% { transform: translateY(0px) rotate(0deg); }
                    50% { transform: translateY(-20px) rotate(10deg); }
                }
                @keyframes float-delayed {
                    0%, 100% { transform: translateY(0px) rotate(0deg); }
                    50% { transform: translateY(-30px) rotate(-15deg); }
                }
                .animate-float {
                    animation: float 4s ease-in-out infinite;
                }
                .animate-float-delayed {
                    animation: float-delayed 5s ease-in-out infinite;
                    animation-delay: 1s;
                }
            `}</style>
        </div>
    );
}
