import React from 'react';
import { Link, Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';

export default function Packages({ packages }) {
    // 'packages' dikirim dari Controller: Package::all()

    return (
        <>
            <Head title="Daftar Paket" />
            <Navbar />

            <div className="py-16 bg-gray-50 min-h-screen">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-extrabold text-gray-900">Pilihan Paket Internet</h2>
                        <p className="mt-4 text-gray-500">Pilih kecepatan yang sesuai dengan kebutuhan digital Anda.</p>
                    </div>

                    <div className="grid gap-8 md:grid-cols-3">
                         {packages.map(pkg => (
                            <div key={pkg.id} className="bg-white rounded-2xl shadow-lg p-8 border-t-4 border-indigo-600 flex flex-col hover:-translate-y-1 transition duration-300">
                                <h3 className="text-2xl font-bold text-gray-800 mb-2">{pkg.name}</h3>
                                <div className="text-4xl font-extrabold text-indigo-600 mb-4">
                                    Rp {pkg.price.toLocaleString('id-ID')}
                                    <span className="text-sm text-gray-400 font-normal">/bulan</span>
                                </div>
                                <p className="text-gray-600 mb-6 flex-grow">{pkg.description}</p>

                                <ul className="mb-8 space-y-3 text-gray-600">
                                    <li className="flex items-center"><span className="text-green-500 mr-2 font-bold">✓</span> Speed up to {pkg.speed}</li>
                                    <li className="flex items-center"><span className="text-green-500 mr-2 font-bold">✓</span> Unlimited Quota</li>
                                    <li className="flex items-center"><span className="text-green-500 mr-2 font-bold">✓</span> Free Installation</li>
                                </ul>

                                <Link
                                    href={route('register', { package: pkg.id })}
                                    className="block w-full text-center bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-md hover:shadow-lg"
                                >
                                    Pilih Paket Ini
                                </Link>
                            </div>
                         ))}
                    </div>
                </div>
            </div>

            <Footer />
        </>
    );
}
