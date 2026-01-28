import { Link, Head } from '@inertiajs/react';
import PackageCard from '@/Components/PackageCard';

export default function Welcome({ auth, laravelVersion, phpVersion, packages }) {
    return (
        <>
            <Head title="Ningrat Internet - Cepat & Stabil" />

            <div className="min-h-screen bg-gray-50 selection:bg-indigo-500 selection:text-white">

                {/* --- NAVIGATION --- */}
                <nav className="fixed w-full z-50 bg-white/90 backdrop-blur-sm border-b border-gray-100">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between h-16 items-center">
                            <div className="flex-shrink-0 flex items-center gap-2">
                                <div className="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold">N</div>
                                <span className="font-bold text-xl text-gray-800">Ningrat.Net</span>
                            </div>
                            <div className="hidden sm:flex sm:items-center sm:space-x-8">
                                <a href="#keunggulan" className="text-gray-600 hover:text-indigo-600 transition">Keunggulan</a>
                                <a href="#paket" className="text-gray-600 hover:text-indigo-600 transition">Paket</a>
                                <a href="#jangkauan" className="text-gray-600 hover:text-indigo-600 transition">Jangkauan</a>
                            </div>
                            <div className="flex items-center space-x-4">
                                {auth.user ? (
                                    <Link
                                        href={route('customer.dashboard')}
                                        className="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500"
                                    >
                                        Dashboard
                                    </Link>
                                ) : (
                                    <>
                                        <Link
                                            href={route('login')}
                                            className="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500"
                                        >
                                            Log in
                                        </Link>
                                        <Link
                                            href={route('customer.register')}
                                            className="ml-4 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition"
                                        >
                                            Daftar
                                        </Link>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>
                </nav>

                {/* --- HERO SECTION --- */}
                <section className="pt-32 pb-20 bg-gradient-to-br from-indigo-50 to-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <h1 className="text-5xl md:text-6xl font-extrabold text-gray-900 tracking-tight mb-6">
                            Internet <span className="text-indigo-600">Cepat</span> untuk <br /> Keluarga <span className="text-indigo-600">Hebat</span>
                        </h1>
                        <p className="mt-4 text-xl text-gray-500 max-w-3xl mx-auto mb-10">
                            Nikmati streaming lancar, gaming tanpa lag, dan kerja dari rumah lebih produktif dengan koneksi fiber optik stabil.
                        </p>
                        <div className="flex justify-center gap-4">
                            <a href="#paket" className="px-8 py-3 rounded-full bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                Lihat Paket
                            </a>
                            <a href="https://wa.me/6281234567890" target="_blank" className="px-8 py-3 rounded-full bg-white text-indigo-600 font-semibold border border-indigo-200 hover:bg-gray-50 transition">
                                Konsultasi Gratis
                            </a>
                        </div>
                    </div>
                </section>

                {/* --- KEUNGGULAN --- */}
                <section id="keunggulan" className="py-20 bg-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 className="text-3xl font-bold text-gray-900">Kenapa Memilih Kami?</h2>
                            <p className="mt-4 text-gray-500">Kualitas terbaik untuk pengalaman internet tanpa batas.</p>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                            {[
                                { title: "True Unlimited", desc: "Tanpa batasan kuota (FUP). Download dan streaming sepuasnya.", icon: "∞" },
                                { title: "Koneksi Stabil", desc: "Menggunakan teknologi Fiber Optic terbaru minim gangguan cuaca.", icon: "⚡" },
                                { title: "Support 24/7", desc: "Tim teknisi kami siap membantu Anda kapanpun dibutuhkan.", icon: "🛠️" }
                            ].map((item, idx) => (
                                <div key={idx} className="p-8 rounded-2xl bg-gray-50 hover:bg-white border border-transparent hover:border-gray-100 hover:shadow-lg transition">
                                    <div className="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-2xl mb-4 text-indigo-600">
                                        {item.icon}
                                    </div>
                                    <h3 className="text-xl font-bold mb-2 text-gray-900">{item.title}</h3>
                                    <p className="text-gray-600">{item.desc}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* --- PAKET INTERNET (PRICING) --- */}
                <section id="paket" className="py-20 bg-indigo-900">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 className="text-3xl font-bold text-white">Pilihan Paket Internet</h2>
                            <p className="mt-4 text-indigo-200">Sesuaikan dengan kebutuhan digital harian Anda.</p>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 justify-center">
                            {packages && packages.length > 0 ? (
                                packages.map((pkg) => (
                                    <PackageCard key={pkg.id} data={pkg} />
                                ))
                            ) : (
                                <div className="col-span-3 text-center text-white py-10">
                                    <p>Belum ada paket tersedia saat ini.</p>
                                </div>
                            )}
                        </div>
                    </div>
                </section>

                {/* --- ALUR PENDAFTARAN --- */}
                <section className="py-20 bg-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 className="text-3xl font-bold text-gray-900">Cara Berlangganan</h2>
                        </div>
                        <div className="relative">
                            {/* Line Connector for Desktop */}
                            <div className="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-gray-200 -z-10 transform -translate-y-1/2"></div>

                            <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                                {[
                                    { step: "1", title: "Cek Lokasi", desc: "Pastikan area Anda tercover jaringan kami." },
                                    { step: "2", title: "Pilih Paket", desc: "Tentukan kecepatan sesuai kebutuhan." },
                                    { step: "3", title: "Registrasi", desc: "Isi formulir pendaftaran secara online." },
                                    { step: "4", title: "Instalasi", desc: "Teknisi datang dan internet langsung aktif." }
                                ].map((flow, idx) => (
                                    <div key={idx} className="bg-white p-6 text-center">
                                        <div className="w-12 h-12 mx-auto bg-indigo-600 text-white rounded-full flex items-center justify-center text-xl font-bold mb-4 border-4 border-white shadow-lg">
                                            {flow.step}
                                        </div>
                                        <h3 className="text-lg font-bold mb-2">{flow.title}</h3>
                                        <p className="text-sm text-gray-500">{flow.desc}</p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </section>

                {/* --- AREA JANGKAUAN --- */}
                <section id="jangkauan" className="py-20 bg-gray-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center gap-12">
                        <div className="md:w-1/2">
                            <h2 className="text-3xl font-bold text-gray-900 mb-6">Area Jangkauan Luas</h2>
                            <p className="text-gray-600 mb-8">
                                Kami terus memperluas jaringan fiber optik kami untuk menjangkau lebih banyak keluarga di Indonesia. Saat ini kami telah hadir di berbagai kota besar.
                            </p>
                            <ul className="grid grid-cols-2 gap-4">
                                {['Surabaya Pusat', 'Sidoarjo Kota', 'Gresik', 'Mojokerto', 'Malang', 'Pasuruan'].map((city, idx) => (
                                    <li key={idx} className="flex items-center text-gray-700">
                                        <svg className="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {city}
                                    </li>
                                ))}
                            </ul>
                        </div>
                        <div className="md:w-1/2 bg-indigo-200 rounded-2xl h-64 md:h-96 w-full flex items-center justify-center text-indigo-800 font-bold text-xl">
                            [Peta Jangkauan Placeholder]
                        </div>
                    </div>
                </section>

                {/* --- CTA WHATSAPP STICKY --- */}
                <a
                    href="https://wa.me/6281234567890"
                    target="_blank"
                    className="fixed bottom-6 right-6 z-50 bg-green-500 text-white px-6 py-4 rounded-full shadow-2xl hover:bg-green-600 transition flex items-center gap-2 animate-bounce"
                >
                    <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" /></svg>
                    Chat WhatsApp
                </a>

                {/* --- FOOTER --- */}
                <footer className="bg-gray-900 text-white py-12">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div>
                            <div className="text-2xl font-bold mb-4">Ningrat.Net</div>
                            <p className="text-gray-400 text-sm">Provider internet terpercaya untuk keluarga Indonesia. Cepat, stabil, dan terjangkau.</p>
                        </div>
                        <div>
                            <h4 className="font-bold mb-4">Layanan</h4>
                            <ul className="text-gray-400 space-y-2 text-sm">
                                <li><a href="#" className="hover:text-white">Internet Rumah</a></li>
                                <li><a href="#" className="hover:text-white">Internet Bisnis</a></li>
                                <li><a href="#" className="hover:text-white">Dedicated Server</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="font-bold mb-4">Perusahaan</h4>
                            <ul className="text-gray-400 space-y-2 text-sm">
                                <li><a href="#" className="hover:text-white">Tentang Kami</a></li>
                                <li><a href="#" className="hover:text-white">Karir</a></li>
                                <li><a href="#" className="hover:text-white">Kontak</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="font-bold mb-4">Ikuti Kami</h4>
                            <div className="flex space-x-4">
                                {/* Social Icons Placeholder */}
                                <div className="w-8 h-8 bg-gray-700 rounded-full"></div>
                                <div className="w-8 h-8 bg-gray-700 rounded-full"></div>
                                <div className="w-8 h-8 bg-gray-700 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-gray-800 text-center text-sm text-gray-500">
                        &copy; 2024 Ningrat Internet Provider. All rights reserved.
                    </div>
                </footer>
            </div>
        </>
    );
}
