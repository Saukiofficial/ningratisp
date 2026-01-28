import React from 'react';
import { Wifi } from 'lucide-react';

export default function Footer() {
    return (
        <footer className="bg-gray-900 text-white py-12">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="grid md:grid-cols-4 gap-8">
                    <div className="col-span-1 md:col-span-2">
                        <div className="flex items-center gap-2 mb-4">
                            <Wifi className="text-indigo-400" />
                            <span className="font-bold text-xl">MyProvider</span>
                        </div>
                        <p className="text-gray-400 max-w-sm">
                            Penyedia layanan internet fiber optic tercepat dan terstabil untuk kebutuhan digital keluarga dan bisnis Anda.
                        </p>
                    </div>
                    <div>
                        <h4 className="font-bold text-lg mb-4">Layanan</h4>
                        <ul className="space-y-2 text-gray-400">
                            <li><a href="#" className="hover:text-white">Internet Rumah</a></li>
                            <li><a href="#" className="hover:text-white">Internet Bisnis</a></li>
                            <li><a href="#" className="hover:text-white">Dedicated Server</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 className="font-bold text-lg mb-4">Hubungi Kami</h4>
                        <ul className="space-y-2 text-gray-400">
                            <li>support@myprovider.com</li>
                            <li>0800-1234-5678</li>
                            <li>Jl. Teknologi No. 10, Jakarta</li>
                        </ul>
                    </div>
                </div>
                <div className="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500 text-sm">
                    &copy; {new Date().getFullYear()} MyProvider. All rights reserved.
                </div>
            </div>
        </footer>
    );
}
