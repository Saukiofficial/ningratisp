import React from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import { Shield, Eye, Lock, GlobeLock } from 'lucide-react';

export default function Security() {
    return (
        <div className="bg-white font-sans text-gray-900">
            <Head title="Security System - NingratNet" />
            <Navbar />

            <div className="pt-32 pb-20 bg-slate-800 text-white text-center">
                <h1 className="text-4xl md:text-5xl font-extrabold mb-6">Security System</h1>
                <p className="text-slate-300 max-w-2xl mx-auto px-4 text-lg">
                    Proteksi aset fisik dan digital Anda dengan teknologi keamanan terintegrasi.
                </p>
            </div>

            <section className="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="grid md:grid-cols-3 gap-6">
                    {/* CCTV */}
                    <div className="bg-white shadow-lg rounded-2xl overflow-hidden group">
                        <div className="h-48 bg-slate-100 flex items-center justify-center group-hover:bg-slate-200 transition">
                            <Eye size={64} className="text-slate-500" />
                        </div>
                        <div className="p-8">
                            <h3 className="text-xl font-bold mb-2">CCTV IP Camera</h3>
                            <p className="text-gray-600 text-sm mb-4">
                                Instalasi kamera pengawas resolusi tinggi dengan akses monitoring via Smartphone. Support fitur Motion Detection dan Night Vision.
                            </p>
                        </div>
                    </div>

                    {/* Access Control */}
                    <div className="bg-white shadow-lg rounded-2xl overflow-hidden group">
                        <div className="h-48 bg-slate-100 flex items-center justify-center group-hover:bg-slate-200 transition">
                            <Lock size={64} className="text-slate-500" />
                        </div>
                        <div className="p-8">
                            <h3 className="text-xl font-bold mb-2">Access Door Control</h3>
                            <p className="text-gray-600 text-sm mb-4">
                                Sistem kunci pintu pintar menggunakan Kartu RFID, Fingerprint, atau Face Recognition untuk membatasi akses ruang server/kantor.
                            </p>
                        </div>
                    </div>

                    {/* Network Security */}
                    <div className="bg-white shadow-lg rounded-2xl overflow-hidden group">
                        <div className="h-48 bg-slate-100 flex items-center justify-center group-hover:bg-slate-200 transition">
                            <GlobeLock size={64} className="text-slate-500" />
                        </div>
                        <div className="p-8">
                            <h3 className="text-xl font-bold mb-2">Network Firewall</h3>
                            <p className="text-gray-600 text-sm mb-4">
                                Konfigurasi Firewall Mikrotik/Cisco untuk melindungi jaringan internal dari serangan Cyber, Malware, dan akses tidak sah.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <Footer />
        </div>
    );
}
