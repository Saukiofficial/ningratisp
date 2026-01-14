import React from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import { Server, Activity, ShieldCheck, Clock, CheckCircle2, Network, HardDrive, Monitor } from 'lucide-react';

export default function ManagedServices() {
    return (
        <div className="bg-white font-sans text-gray-900">
            <Head title="IT Managed Services - NingratNet" />
            <Navbar />

            {/* HERO SECTION */}
            <div className="pt-32 pb-20 bg-gradient-to-r from-gray-900 to-gray-800 text-white">
                <div className="max-w-7xl mx-auto px-4 text-center md:text-left">
                    <h1 className="text-4xl md:text-5xl font-extrabold mb-6">IT Managed Services</h1>
                    <p className="text-xl text-gray-300 max-w-2xl">
                        Fokus pada bisnis inti Anda, biarkan kami yang mengelola kompleksitas infrastruktur IT Anda.
                    </p>
                </div>
            </div>

            {/* MAIN CONTENT */}
            <section className="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {/* Intro & Benefits */}
                <div className="grid md:grid-cols-2 gap-12 items-center mb-20">
                    <div>
                        <h2 className="text-3xl font-bold mb-6 text-gray-900">Mengapa Managed Services?</h2>
                        <p className="text-gray-600 mb-4 leading-relaxed">
                            Mengelola departemen IT internal membutuhkan biaya besar untuk rekrutmen, pelatihan, dan peralatan. Layanan Managed Services NingratNet memberikan Anda akses ke tim ahli lengkap dengan biaya bulanan yang terprediksi.
                        </p>
                        <ul className="space-y-4 mt-6">
                            <li className="flex items-center gap-3">
                                <div className="bg-green-100 p-2 rounded-full"><Activity size={20} className="text-green-600" /></div>
                                <span className="font-medium text-gray-700">24/7 Monitoring System Proaktif</span>
                            </li>
                            <li className="flex items-center gap-3">
                                <div className="bg-blue-100 p-2 rounded-full"><ShieldCheck size={20} className="text-blue-600" /></div>
                                <span className="font-medium text-gray-700">Patch Management & Security Update</span>
                            </li>
                            <li className="flex items-center gap-3">
                                <div className="bg-orange-100 p-2 rounded-full"><Clock size={20} className="text-orange-600" /></div>
                                <span className="font-medium text-gray-700">Respon Cepat Insiden Teknis (SLA Guaranteed)</span>
                            </li>
                        </ul>
                    </div>
                    {/* Illustration / Image Placeholder */}
                    <div className="bg-gray-100 h-80 rounded-2xl flex items-center justify-center border border-gray-200 relative overflow-hidden group">
                        <div className="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px] opacity-50"></div>
                        <Server size={120} className="text-gray-400 relative z-10 group-hover:text-indigo-500 transition-colors duration-500" />
                    </div>
                </div>

                {/* Scope of Services Cards */}
                <div className="bg-indigo-50 rounded-3xl p-8 md:p-12 text-center">
                    <h2 className="text-2xl md:text-3xl font-bold mb-8 text-indigo-900">Cakupan Layanan Kami</h2>
                    <div className="grid md:grid-cols-3 gap-6 text-left">
                        {/* Card 1 */}
                        <div className="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-indigo-100 group">
                            <div className="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <Network size={24} />
                            </div>
                            <h3 className="font-bold text-lg text-gray-900 mb-2">Network Management</h3>
                            <p className="text-sm text-gray-600 leading-relaxed">
                                Pengelolaan Router, Switch, WiFi, dan Firewall untuk memastikan koneksi stabil dan aman dari akses tidak sah.
                            </p>
                        </div>

                        {/* Card 2 */}
                        <div className="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-indigo-100 group">
                            <div className="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <HardDrive size={24} />
                            </div>
                            <h3 className="font-bold text-lg text-gray-900 mb-2">Server & Storage</h3>
                            <p className="text-sm text-gray-600 leading-relaxed">
                                Maintenance OS Server, konfigurasi Virtualisasi (VMware/Proxmox), dan manajemen backup data berkala.
                            </p>
                        </div>

                        {/* Card 3 */}
                        <div className="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-indigo-100 group">
                            <div className="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <Monitor size={24} />
                            </div>
                            <h3 className="font-bold text-lg text-gray-900 mb-2">End-User Support</h3>
                            <p className="text-sm text-gray-600 leading-relaxed">
                                Layanan Helpdesk untuk menangani masalah PC/Laptop karyawan, printer, email, dan aplikasi kantor sehari-hari.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <Footer />
        </div>
    );
}
