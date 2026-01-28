import React from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import { UserCircle2, Briefcase, Award } from 'lucide-react';

export default function Struktur() {
    const executives = [
        { name: 'Bapak CEO', role: 'Chief Executive Officer', img: 'bg-gray-200' },
    ];

    const managers = [
        { name: 'Ibu CTO', role: 'Chief Technology Officer', img: 'bg-indigo-100', color: 'text-indigo-600' },
        { name: 'Bapak COO', role: 'Chief Operating Officer', img: 'bg-blue-100', color: 'text-blue-600' },
        { name: 'Ibu CMO', role: 'Chief Marketing Officer', img: 'bg-pink-100', color: 'text-pink-600' },
    ];

    return (
        <div className="bg-white font-sans text-gray-900">
            <Head title="Struktur Organisasi - NingratNet" />
            <Navbar />

            <div className="pt-32 pb-20 bg-indigo-900 text-white text-center">
                <h1 className="text-4xl font-extrabold mb-4">Tim Kami</h1>
                <p className="text-indigo-200 max-w-2xl mx-auto px-4">
                    Orang-orang berdedikasi di balik layar yang memastikan internet Anda tetap menyala.
                </p>
            </div>

            <section className="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

                {/* Level 1: CEO */}
                <div className="flex justify-center mb-16 relative">
                    {executives.map((person, idx) => (
                        <div key={idx} className="flex flex-col items-center relative z-10 group">
                            <div className={`w-32 h-32 ${person.img} rounded-full mb-4 border-4 border-white shadow-xl flex items-center justify-center text-gray-400 group-hover:scale-105 transition-transform duration-300`}>
                                <UserCircle2 size={64} />
                            </div>
                            <h3 className="font-bold text-xl text-gray-900">{person.name}</h3>
                            <div className="flex items-center gap-1 text-indigo-600 font-medium bg-indigo-50 px-3 py-1 rounded-full mt-2">
                                <Award size={14} />
                                <span className="text-sm">{person.role}</span>
                            </div>
                        </div>
                    ))}
                    {/* Connector Line Vertical */}
                    <div className="absolute top-24 left-1/2 w-0.5 h-20 bg-gray-300 -z-0"></div>
                </div>

                {/* Connector Line Horizontal */}
                <div className="w-2/3 mx-auto h-0.5 bg-gray-300 mb-8 hidden md:block"></div>

                {/* Level 2: Managers */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-8">
                    {managers.map((person, idx) => (
                        <div key={idx} className="flex flex-col items-center relative group">
                            {/* Vertical line for mobile */}
                            <div className="absolute -top-12 left-1/2 w-0.5 h-12 bg-gray-300 md:hidden"></div>
                             {/* Vertical line for desktop connecting to horizontal bar */}
                            <div className="absolute -top-8 left-1/2 w-0.5 h-8 bg-gray-300 hidden md:block"></div>

                            <div className={`w-24 h-24 ${person.img} rounded-full mb-4 border-4 border-white shadow-md flex items-center justify-center ${person.color} group-hover:scale-105 transition-transform duration-300`}>
                                <UserCircle2 size={48} />
                            </div>
                            <h3 className="font-bold text-lg text-gray-900">{person.name}</h3>
                            <div className="flex items-center gap-1 text-gray-500 text-sm mt-1">
                                <Briefcase size={12} />
                                <span>{person.role}</span>
                            </div>
                        </div>
                    ))}
                </div>

                <div className="mt-20">
                    <div className="bg-gradient-to-r from-gray-50 to-gray-100 p-8 rounded-2xl border border-gray-200">
                        <h3 className="font-bold text-xl text-gray-700 mb-4">Divisi Operasional & Support</h3>
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                            {['Network Operations Center (NOC)', 'Technical Support L1 & L2', 'Field Technician', 'Customer Service'].map((dept, i) => (
                                <div key={i} className="bg-white p-4 rounded-xl shadow-sm text-sm font-medium text-gray-600 hover:text-indigo-600 hover:shadow-md transition">
                                    {dept}
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </section>

            <Footer />
        </div>
    );
}
