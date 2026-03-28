import React, { useState, useEffect } from 'react';
import { Link, Head, useForm, usePage } from '@inertiajs/react';
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
    Mail,
    X,
    MessageCircle
} from 'lucide-react';

export default function Home({ packages }) {
    // Ambil flash message
    const { flash = {} } = usePage().props;

    const { data, setData, post, processing } = useForm({
        area: ''
    });

    const [openFaq, setOpenFaq] = useState(null);
    const [showAlert, setShowAlert] = useState(false);

    useEffect(() => {
        if (flash?.success || flash?.error) {
            setShowAlert(true);
            // Timer lebih lama agar user sempat baca dan klik WA
            const timer = setTimeout(() => setShowAlert(false), 10000);
            return () => clearTimeout(timer);
        }
    }, [flash]);

    const handleCheckCoverage = (e) => {
        e.preventDefault();
        post(route('customer.landing.check-coverage'), {
            preserveScroll: true,
            onSuccess: () => {
                // Biarkan input tetap ada
            }
        });
    };

    const toggleFaq = (index) => {
        setOpenFaq(openFaq === index ? null : index);
    };

    // --- KONFIGURASI WHATSAPP ADMIN ---
    const waNumber = "628123456789"; // Ganti dengan nomor Admin
    const waMessage = `Halo Admin NingratNet, saya sudah cek lokasi di "${data.area}" statusnya Terjangkau. Saya berminat pasang internet.`;
    const waLink = `https://wa.me/${waNumber}?text=${encodeURIComponent(waMessage)}`;

    const faqs = [
        { q: "Apakah harga sudah termasuk PPN?", a: "Harga yang tertera belum termasuk PPN 11%. Biaya instalasi gratis untuk paket tertentu." },
        { q: "Apakah ada batasan kuota (FUP)?", a: "Tidak ada. Semua paket NingratNet adalah Truly Unlimited tanpa batasan kuota (FUP)." },
        { q: "Berapa lama proses pemasangan?", a: "Estimasi pemasangan adalah 1-3 hari kerja setelah registrasi berhasil dan lokasi terverifikasi." },
        { q: "Bagaimana jika internet saya gangguan?", a: "Tim support kami aktif 24/7. Anda bisa melapor via WhatsApp atau Dashboard Pelanggan." },
    ];
    const awards = [
        { title: "Best Value ISP", year: "2024" },
        { title: "Customer Choice", year: "2024" },
        { title: "Fastest Growing", year: "2023" },
        { title: "Service Excellence", year: "2023" }
    ];

    return (
        <div className="font-sans antialiased text-gray-900 bg-white relative">
            <Head title="NingratNet - Harga Rakyat, Kualitas Ningrat" />
            <Navbar />

            {/* --- FLASH MESSAGE ALERT (MODIFIED) --- */}
            {showAlert && (flash?.success || flash?.error) && (
                <div className={`fixed top-24 left-1/2 transform -translate-x-1/2 z-50 w-11/12 max-w-lg p-4 rounded-xl shadow-2xl flex items-start gap-4 border-l-4 animate-fade-in-down transition-all duration-500 ease-in-out ${flash.success
                    ? 'bg-green-50 border-green-500 text-green-800'
                    : 'bg-red-50 border-red-500 text-red-800'
                    }`}>
                    <div className={`p-2 rounded-full ${flash.success ? 'bg-green-200' : 'bg-red-200'}`}>
                        {flash.success ? <CheckCircle2 size={20} /> : <MapPin size={20} />}
                    </div>
                    <div className="flex-1">
                        <h4 className="font-bold text-sm md:text-base">{flash.success ? 'Area Terjangkau!' : 'Belum Terjangkau'}</h4>
                        <p className="text-xs md:text-sm mt-1">{flash.success || flash.error}</p>

                        {/* BUTTON WHATSAPP PENGGANTI REGISTER */}
                        {flash.success && (
                            <a
                                href={waLink}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="mt-3 inline-flex items-center gap-2 text-xs font-bold text-white bg-green-600 px-4 py-2 rounded-lg hover:bg-green-700 transition shadow-md"
                            >
                                <MessageCircle size={16} />
                                Lanjut via WhatsApp
                            </a>
                        )}
                    </div>
                    <button onClick={() => setShowAlert(false)} className="text-gray-400 hover:text-gray-600 transition-colors">
                        <X size={18} />
                    </button>
                </div>
            )}

            {/* ... (Sisa kode Hero, Stats, Wave Divider, dll SAMA PERSIS seperti sebelumnya) ... */}
            {/* HERO SECTION */}
            <div className="relative bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 overflow-hidden">
                <div className="absolute inset-0 overflow-hidden">
                    <div className="absolute top-0 right-0 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
                    <div className="absolute top-0 left-0 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
                    <div className="absolute bottom-0 left-1/2 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
                    <div className="absolute inset-0 opacity-10">
                        <div className="absolute inset-0" style={{
                            backgroundImage: 'radial-gradient(circle at 2px 2px, white 1px, transparent 0)',
                            backgroundSize: '40px 40px'
                        }}></div>
                    </div>
                </div>

                <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12 md:pt-24 md:pb-16">
                    <div className="flex flex-row gap-3 md:gap-8 items-center min-h-[400px] md:min-h-[500px]">
                        <div className="w-1/2 lg:w-1/2 text-left z-10">
                            <div className="inline-block mb-3 md:mb-4">
                                <span className="inline-flex items-center gap-2 px-3 py-1 md:px-4 md:py-2 bg-white/20 backdrop-blur-sm rounded-full text-white text-xs md:text-sm font-semibold">
                                    <Wifi className="w-3 h-3 md:w-4 md:h-4" />
                                    Internet Fiber Optic
                                </span>
                            </div>
                            <h1 className="text-xl sm:text-3xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-2 md:mb-6 drop-shadow-lg">
                                Internet Cepat untuk <span className="block text-yellow-300">Semua Kalangan</span>
                            </h1>
                            <p className="text-sm sm:text-lg md:text-2xl font-semibold text-yellow-300 mb-2 md:mb-4 drop-shadow">
                                Harga Rakyat, Kualitas Ningrat
                            </p>
                            <p className="text-xs sm:text-sm md:text-lg text-blue-50 mb-3 md:mb-8 leading-relaxed">
                                Penyedia layanan internet lokal pemenang penghargaan yang menawarkan Broadband Superfast untuk rumah dan bisnis.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-2 md:gap-3 mb-0 md:mb-8">
                                <a href="#packages" className="inline-flex items-center justify-center px-4 py-2 md:px-8 md:py-4 bg-white text-blue-600 rounded-lg font-bold text-xs md:text-base hover:bg-blue-50 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    JELAJAHI LEBIH LANJUT <ArrowRight className="ml-1 md:ml-2 w-3 h-3 md:w-5 md:h-5" />
                                </a>
                                {/* Tombol WA di Hero juga */}
                                <a href={waLink} target="_blank" className="hidden sm:inline-flex items-center justify-center px-4 py-2 md:px-8 md:py-4 bg-transparent border-2 border-white text-white rounded-lg font-bold text-xs md:text-base hover:bg-white hover:text-blue-600 transition">
                                    <Phone className="mr-1 md:mr-2 w-3 h-3 md:w-5 md:h-5" /> Hubungi Kami
                                </a>
                            </div>
                        </div>
                        <div className="w-1/2 lg:w-1/2 flex items-center justify-center lg:justify-end relative">
                            <div className="absolute inset-0 bg-gradient-to-tr from-yellow-300/20 to-pink-300/20 rounded-full blur-3xl"></div>
                            <div className="relative w-full flex items-center justify-center lg:justify-end">
                                <img src="/assets/img/wanita.webp" alt="Person using fast internet" className="relative z-10 w-full max-w-[180px] sm:max-w-xs md:max-w-md lg:max-w-xl object-contain drop-shadow-2xl" style={{ height: 'auto' }} />
                            </div>
                        </div>
                    </div>
                    {/* ... (Lanjutan Stats Bar) ... */}
                    <div className="relative z-20 -mb-8 md:-mb-16">
                        <div className="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-4 md:p-6 border border-white/20">
                            <div className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center"><Zap className="w-5 h-5 md:w-6 md:h-6 text-blue-600" /></div>
                                    <div><div className="text-xs md:text-sm text-gray-500 font-medium">Kecepatan</div><div className="text-sm md:text-lg font-bold text-gray-900">Up to 1Gbps</div></div>
                                </div>
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-lg flex items-center justify-center"><Users className="w-5 h-5 md:w-6 md:h-6 text-green-600" /></div>
                                    <div><div className="text-xs md:text-sm text-gray-500 font-medium">Pelanggan</div><div className="text-sm md:text-lg font-bold text-gray-900">500+</div></div>
                                </div>
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 md:w-12 md:h-12 bg-purple-100 rounded-lg flex items-center justify-center"><Clock className="w-5 h-5 md:w-6 md:h-6 text-purple-600" /></div>
                                    <div><div className="text-xs md:text-sm text-gray-500 font-medium">Support</div><div className="text-sm md:text-lg font-bold text-gray-900">24/7</div></div>
                                </div>
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 md:w-12 md:h-12 bg-orange-100 rounded-lg flex items-center justify-center"><MapPin className="w-5 h-5 md:w-6 md:h-6 text-orange-600" /></div>
                                    <div><div className="text-xs md:text-sm text-gray-500 font-medium">Coverage</div><div className="text-sm md:text-lg font-bold text-gray-900">50+ Area</div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="relative"><svg className="w-full h-24 md:h-40" viewBox="0 0 1440 200" fill="none"><defs><linearGradient id="waveGradient" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" style={{ stopColor: '#FFFFFF', stopOpacity: 0.9 }} /><stop offset="50%" style={{ stopColor: '#F0F9FF', stopOpacity: 1 }} /><stop offset="100%" style={{ stopColor: '#FFFFFF', stopOpacity: 0.9 }} /></linearGradient></defs><path d="M0,96L48,112C96,128,192,160,288,165.3C384,171,480,149,576,133.3C672,117,768,107,864,112C960,117,1056,139,1152,144C1248,149,1344,139,1392,133.3L1440,128L1440,200L1392,200C1344,200,1248,200,1152,200C1056,200,960,200,864,200C768,200,672,200,576,200C480,200,384,200,288,200C192,200,96,200,48,200L0,200Z" fill="url(#waveGradient)" fillOpacity="0.3" /><path d="M0,128L48,138.7C96,149,192,171,288,170.7C384,171,480,149,576,138.7C672,128,768,128,864,138.7C960,149,1056,171,1152,170.7C1248,171,1344,149,1392,138.7L1440,128L1440,200L1392,200C1344,200,1248,200,1152,200C1056,200,960,200,864,200C768,200,672,200,576,200C480,200,384,200,288,200C192,200,96,200,48,200L0,200Z" fill="url(#waveGradient)" fillOpacity="0.6" /><path d="M0,160L48,165.3C96,171,192,181,288,181.3C384,181,480,171,576,160C672,149,768,139,864,144C960,149,1056,171,1152,176C1248,181,1344,171,1392,165.3L1440,160L1440,200L1392,200C1344,200,1248,200,1152,200C1056,200,960,200,864,200C768,200,672,200,576,200C480,200,384,200,288,200C192,200,96,200,48,200L0,200Z" fill="url(#waveGradient)" /></svg></div>

                {/* COVERAGE FORM */}
                <div className="relative bg-white border-t border-gray-200 shadow-lg z-20">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 md:py-6">
                        <form onSubmit={handleCheckCoverage} className="hidden md:flex flex-row items-center gap-4">
                            <div className="flex items-center gap-3 text-gray-700">
                                <div className="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0"><MapPin className="w-6 h-6 text-white" /></div>
                                <span className="font-semibold text-base">Cek ketersediaan di area Anda</span>
                            </div>
                            <div className="flex-1 max-w-md">
                                <input type="text" value={data.area} onChange={(e) => setData('area', e.target.value)} placeholder="Ketik nama desa/kecamatan..." className="w-full px-4 py-3 border border-gray-300 rounded-md text-gray-900 text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required />
                            </div>
                            <div className="flex items-center gap-3">
                                <div className="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0"><CheckCircle2 className="w-6 h-6 text-white" /></div>
                                <div className="text-left"><p className="text-xs text-gray-500">Status Jangkauan</p><p className="font-bold text-green-600 text-base">50+ Area Tersedia</p></div>
                            </div>
                            <button type="submit" disabled={processing} className="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-md font-bold transition whitespace-nowrap flex items-center gap-2 text-base disabled:opacity-70">
                                {processing ? 'Memeriksa...' : 'CEK AREA'} {!processing && <ArrowRight className="w-4 h-4" />}
                            </button>
                        </form>
                        <form onSubmit={handleCheckCoverage} className="md:hidden space-y-3">
                            <div className="flex items-center justify-between gap-2">
                                <div className="flex items-center gap-2 flex-1"><div className="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0"><MapPin className="w-5 h-5 text-white" /></div><span className="font-semibold text-xs leading-tight">Cek ketersediaan di area Anda</span></div>
                                <div className="flex items-center gap-2 flex-1 justify-end"><div className="text-right"><p className="text-[10px] text-gray-500 leading-none">Status</p><p className="font-bold text-green-600 text-xs">50+ Area</p></div><div className="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0"><CheckCircle2 className="w-4 h-4 text-white" /></div></div>
                            </div>
                            <div className="flex gap-2">
                                <input type="text" value={data.area} onChange={(e) => setData('area', e.target.value)} placeholder="Ketik nama desa/kecamatan..." className="flex-1 px-3 py-2.5 border border-gray-300 rounded-md text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required />
                                <button type="submit" disabled={processing} className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-md font-bold transition whitespace-nowrap flex items-center gap-1 text-sm disabled:opacity-70">{processing ? '...' : 'CEK'} {!processing && <ArrowRight className="w-3 h-3" />}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {/* REST OF THE SECTIONS (Wave, Stats, Awards, Why Us, Packages, Coverage Detail, Testimonials, FAQ) */}
            {/* Bagian ini sama seperti sebelumnya, hanya bagian atas yang berubah logika alert-nya */}
            <div className="relative"><svg className="w-full h-16 md:h-24" viewBox="0 0 1440 120" fill="none"><path d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,58.7C960,64,1056,64,1152,58.7C1248,53,1344,43,1392,37.3L1440,32L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z" fill="white" /></svg></div>
            <div className="bg-white py-12 md:py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                        <div className="text-center"><div className="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-blue-100 rounded-full mb-3 md:mb-4"><Users className="w-6 h-6 md:w-8 md:h-8 text-blue-600" /></div><div className="text-2xl md:text-4xl font-extrabold text-gray-900 mb-1 md:mb-2">5,000+</div><div className="text-xs md:text-sm text-gray-500 font-medium">Pelanggan Aktif</div></div>
                        <div className="text-center"><div className="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-green-100 rounded-full mb-3 md:mb-4"><TrendingUp className="w-6 h-6 md:w-8 md:h-8 text-green-600" /></div><div className="text-2xl md:text-4xl font-extrabold text-gray-900 mb-1 md:mb-2">99.9%</div><div className="text-xs md:text-sm text-gray-500 font-medium">Uptime Guarantee</div></div>
                        <div className="text-center"><div className="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-purple-100 rounded-full mb-3 md:mb-4"><Clock className="w-6 h-6 md:w-8 md:h-8 text-purple-600" /></div><div className="text-2xl md:text-4xl font-extrabold text-gray-900 mb-1 md:mb-2">24/7</div><div className="text-xs md:text-sm text-gray-500 font-medium">Customer Support</div></div>
                        <div className="text-center"><div className="inline-flex items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-orange-100 rounded-full mb-3 md:mb-4"><MapPin className="w-6 h-6 md:w-8 md:h-8 text-orange-600" /></div><div className="text-2xl md:text-4xl font-extrabold text-gray-900 mb-1 md:mb-2">50+</div><div className="text-xs md:text-sm text-gray-500 font-medium">Area Jangkauan</div></div>
                    </div>
                </div>
            </div>

            {/* Wave Divider */}
            <div className="relative"><svg className="w-full h-16 md:h-20" viewBox="0 0 1440 100" fill="none"><path d="M0,32L60,37.3C120,43,240,53,360,56C480,59,600,53,720,48C840,43,960,37,1080,42.7C1200,48,1320,64,1380,72L1440,80L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z" fill="#F9FAFB" /></svg></div>

            {/* AWARDS */}
            <div className="bg-gray-50 py-12 md:py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-8 md:mb-12"><h2 className="text-2xl md:text-3xl font-extrabold text-gray-900 mb-3 md:mb-4">Penyedia Internet Terpercaya & Berprestasi</h2><p className="text-sm md:text-base text-gray-600">Dipercaya ribuan pelanggan dan diakui industri</p></div>
                    <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                        {awards.map((award, idx) => (
                            <div key={idx} className="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-100 text-center hover:shadow-md transition"><Award className="w-10 h-10 md:w-12 md:h-12 text-yellow-500 mx-auto mb-2 md:mb-3" /><h4 className="font-bold text-gray-900 text-xs md:text-sm mb-1">{award.title}</h4><p className="text-xs text-gray-500">{award.year}</p></div>
                        ))}
                    </div>
                </div>
            </div>

            {/* Wave Divider */}
            <div className="relative"><svg className="w-full h-16 md:h-24" viewBox="0 0 1440 120" fill="none"><path d="M0,96L48,90.7C96,85,192,75,288,74.7C384,75,480,85,576,90.7C672,96,768,96,864,85.3C960,75,1056,53,1152,48C1248,43,1344,53,1392,58.7L1440,64L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z" fill="#F9FAFB" /></svg></div>

            {/* WHY CHOOSE US */}
            <div className="py-10 md:py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center max-w-3xl mx-auto mb-8 md:mb-16"><h2 className="text-xs md:text-sm font-semibold text-blue-600 tracking-wide uppercase mb-2">Kenapa NingratNet?</h2><h3 className="text-2xl md:text-4xl font-extrabold text-gray-900 mb-3 md:mb-4">Teknologi Terkini dengan Harga Terjangkau</h3><p className="text-sm md:text-lg text-gray-600">Kami percaya internet cepat adalah hak semua orang. Itulah mengapa kami hadirkan kualitas premium dengan harga rakyat.</p></div>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-8">
                        <div className="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 md:p-8 rounded-2xl border border-blue-100 hover:shadow-lg transition"><div className="w-12 h-12 md:w-14 md:h-14 bg-blue-600 rounded-xl flex items-center justify-center text-white mb-4 md:mb-6"><Zap size={24} className="md:w-7 md:h-7" /></div><h4 className="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3">Kecepatan Simetris</h4><p className="text-sm md:text-base text-gray-600 leading-relaxed">Download dan Upload sama cepatnya (1:1). Ideal untuk content creator, gamer, dan profesional yang butuh upload cepat.</p></div>
                        <div className="bg-gradient-to-br from-green-50 to-emerald-50 p-6 md:p-8 rounded-2xl border border-green-100 hover:shadow-lg transition"><div className="w-12 h-12 md:w-14 md:h-14 bg-green-600 rounded-xl flex items-center justify-center text-white mb-4 md:mb-6"><ShieldCheck size={24} className="md:w-7 md:h-7" /></div><h4 className="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3">100% Fiber Optic</h4><p className="text-sm md:text-base text-gray-600 leading-relaxed">Menggunakan teknologi FTTH (Fiber To The Home). Koneksi stabil bahkan saat hujan deras atau cuaca ekstrem.</p></div>
                        <div className="bg-gradient-to-br from-purple-50 to-pink-50 p-6 md:p-8 rounded-2xl border border-purple-100 hover:shadow-lg transition"><div className="w-12 h-12 md:w-14 md:h-14 bg-purple-600 rounded-xl flex items-center justify-center text-white mb-4 md:mb-6"><Headphones size={24} className="md:w-7 md:h-7" /></div><h4 className="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3">Support Lokal 24/7</h4><p className="text-sm md:text-base text-gray-600 leading-relaxed">Tim teknis kami siap membantu kapan saja. Hubungi via WhatsApp atau telepon, respons cepat dijamin!</p></div>
                    </div>
                </div>
            </div>

            {/* Wave Divider */}
            <div className="relative"><svg className="w-full h-20 md:h-32" viewBox="0 0 1440 150" fill="none"><path d="M0,96L48,106.7C96,117,192,139,288,138.7C384,139,480,117,576,112C672,107,768,117,864,117.3C960,117,1056,107,1152,96C1248,85,1344,75,1392,69.3L1440,64L1440,150L1392,150C1344,150,1248,150,1152,150C1056,150,960,150,864,150C768,150,672,150,576,150C480,150,384,150,288,150C192,150,96,150,48,150L0,150Z" fill="#F9FAFB" fillOpacity="0.5" /><path d="M0,64L48,74.7C96,85,192,107,288,112C384,117,480,107,576,96C672,85,768,75,864,80C960,85,1056,107,1152,112C1248,117,1344,107,1392,101.3L1440,96L1440,150L1392,150C1344,150,1248,150,1152,150C1056,150,960,150,864,150C768,150,672,150,576,150C480,150,384,150,288,150C192,150,96,150,48,150L0,150Z" fill="#F9FAFB" /></svg></div>

            {/* PACKAGES SECTION */}
            <div id="packages" className="relative py-20 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 scroll-mt-16 overflow-hidden">
                <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16"><h2 className="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-purple-200 to-pink-200 mb-6">Level Up Your Internet</h2></div>
                    <div className="grid gap-8 md:grid-cols-3 lg:gap-10">
                        {packages.map((pkg, index) => (
                            <PackageCard key={pkg.id} pkg={pkg} index={index} />
                        ))}
                    </div>
                </div>
            </div>

            {/* COVERAGE DETAIL */}
            <div className="py-12 md:py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col lg:flex-row gap-8 md:gap-12 lg:gap-16 items-center">
                        <div className="w-full lg:w-1/2 order-2 lg:order-1">
                            <h2 className="text-xs md:text-sm font-semibold text-blue-600 tracking-wide uppercase mb-2">Jangkauan Luas</h2>
                            <h3 className="text-2xl md:text-4xl font-extrabold text-gray-900 mb-4 md:mb-6">Tersedia di Seluruh Kecamatan Lenteng</h3>
                            <p className="text-sm md:text-lg text-gray-600 mb-6 md:mb-8 leading-relaxed">NingratNet terus berkembang untuk menjangkau lebih banyak area. Kami hadir di perumahan, kompleks bisnis, dan area perkotaan.</p>
                            <div className="space-y-3 md:space-y-4 mb-6 md:mb-8">
                                <div className="flex items-start gap-3 md:gap-4 p-3 md:p-4 bg-blue-50 rounded-xl"><MapPin className="w-5 h-5 md:w-6 md:h-6 text-blue-600 flex-shrink-0 mt-1" /><div><h4 className="font-bold text-gray-900 text-sm md:text-base mb-1">Jabodetabek</h4><p className="text-xs md:text-sm text-gray-600">Jakarta, Bogor, Depok, Tangerang, Bekasi</p></div></div>
                                <div className="flex items-start gap-3 md:gap-4 p-3 md:p-4 bg-blue-50 rounded-xl"><MapPin className="w-5 h-5 md:w-6 md:h-6 text-blue-600 flex-shrink-0 mt-1" /><div><h4 className="font-bold text-gray-900 text-sm md:text-base mb-1">Sumenep</h4><p className="text-xs md:text-sm text-gray-600">Poreh, Kec. Lenteng , Kabupaten Sumenep</p></div></div>
                            </div>
                            <a href={waLink} target="_blank" className="inline-flex items-center px-5 py-2.5 md:px-6 md:py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition text-sm md:text-base w-full sm:w-auto justify-center">
                                Hubungi via WhatsApp <ArrowRight className="ml-2 w-4 h-4 md:w-5 md:h-5" />
                            </a>
                        </div>
                        <div className="w-full lg:w-1/2 order-1 lg:order-2">
                            <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=1000&auto=format&fit=crop" alt="Coverage map" className="rounded-2xl shadow-xl w-full h-auto object-cover" />
                        </div>
                    </div>
                </div>
            </div>



            {/* FAQ */}
            <div className="py-12 md:py-20 bg-gray-50">
                <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 className="text-center text-3xl font-extrabold text-gray-900 mb-12">FAQ</h2>
                    <div className="space-y-4">
                        {faqs.map((faq, index) => (
                            <div key={index} className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                <button onClick={() => toggleFaq(index)} className="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none">
                                    <span className="font-bold text-gray-900">{faq.q}</span>
                                    {openFaq === index ? <ChevronUp className="w-5 h-5 text-blue-600" /> : <ChevronDown className="w-5 h-5 text-gray-400" />}
                                </button>
                                <div className={`transition-all duration-300 ${openFaq === index ? 'max-h-40 opacity-100 p-6 pt-0' : 'max-h-0 opacity-0 overflow-hidden'}`}>
                                    <p className="text-gray-600">{faq.a}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* CTA SECTION - Updated Button */}
            <div className="py-12 md:py-20 bg-white">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-2xl md:rounded-3xl p-8 md:p-12 lg:p-16 text-center text-white shadow-2xl overflow-hidden">
                        <div className="relative z-10">
                            <h2 className="text-2xl md:text-3xl lg:text-5xl font-extrabold mb-4 md:mb-6 leading-tight">Siap Upgrade Internet Anda?</h2>
                            <p className="text-base md:text-xl text-blue-100 mb-6 md:mb-10 max-w-2xl mx-auto leading-relaxed">Bergabung dengan ribuan pelanggan yang sudah merasakan internet cepat dengan harga terjangkau.</p>

                            <div className="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center mb-6 md:mb-8">
                                <a href={waLink} target="_blank" className="inline-flex items-center justify-center px-8 py-3 md:px-10 md:py-4 bg-white text-blue-600 rounded-lg font-bold text-base md:text-lg shadow-lg hover:shadow-xl hover:bg-gray-50 transform hover:-translate-y-1 transition">
                                    Pasang Sekarang <ArrowRight className="ml-2 w-4 h-4 md:w-5 md:h-5" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <Footer />

            <style dangerouslySetInnerHTML={{
                __html: `
                @keyframes blob { 0%, 100% { transform: translate(0px, 0px) scale(1); } 33% { transform: translate(30px, -50px) scale(1.1); } 66% { transform: translate(-20px, 20px) scale(0.9); } }
                .animate-blob { animation: blob 7s infinite; }
                .animation-delay-2000 { animation-delay: 2s; }
                .animation-delay-4000 { animation-delay: 4s; }
                @keyframes float { 0%, 100% { transform: translateY(0px) rotate(0deg); } 50% { transform: translateY(-20px) rotate(10deg); } }
                .animate-float { animation: float 4s ease-in-out infinite; }
                .animate-float-delayed { animation: float-delayed 5s ease-in-out infinite; animation-delay: 1s; }
                .animate-fade-in-down { animation: fadeInDown 0.5s ease-out forwards; }
                @keyframes fadeInDown { from { opacity: 0; transform: translate(-50%, -20px); } to { opacity: 1; transform: translate(-50%, 0); } }
            `}} />
        </div>
    );
}
