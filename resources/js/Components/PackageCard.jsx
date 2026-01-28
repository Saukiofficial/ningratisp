import React from 'react';
import { Link } from '@inertiajs/react';
import { CheckCircle } from 'lucide-react';

export default function PackageCard({ pkg, highlight = false }) {
    return (
        <div className={`bg-white rounded-2xl shadow-lg p-8 flex flex-col hover:-translate-y-1 transition duration-300 ${
            highlight ? 'border-4 border-indigo-600 relative' : 'border border-gray-100'
        }`}>
            {highlight && (
                <div className="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-indigo-600 text-white px-4 py-1 rounded-full text-sm font-bold shadow-sm">
                    Most Popular
                </div>
            )}

            <h3 className="text-2xl font-bold text-gray-800 mb-2">{pkg.name}</h3>
            <div className="text-4xl font-extrabold text-indigo-600 mb-4">
                Rp {pkg.price.toLocaleString('id-ID')}
                <span className="text-sm text-gray-400 font-normal">/bulan</span>
            </div>
            <p className="text-gray-600 mb-6 flex-grow">{pkg.description}</p>

            <ul className="mb-8 space-y-3 text-gray-600">
                <li className="flex items-center"><CheckCircle size={18} className="text-green-500 mr-2" /> Speed up to {pkg.speed}</li>
                <li className="flex items-center"><CheckCircle size={18} className="text-green-500 mr-2" /> Unlimited Quota</li>
            </ul>

            <Link
                href={route('register', { package: pkg.id })}
                className={`block w-full text-center py-3 rounded-xl font-bold transition shadow-md hover:shadow-lg ${
                    highlight
                    ? 'bg-indigo-600 text-white hover:bg-indigo-700'
                    : 'bg-white text-indigo-600 border-2 border-indigo-600 hover:bg-indigo-50'
                }`}
            >
                Pilih Paket Ini
            </Link>
        </div>
    );
}
