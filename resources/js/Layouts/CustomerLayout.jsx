import React from 'react';
import { Link } from '@inertiajs/react';
import { Wifi, Home, MessageSquare, User, LogOut } from 'lucide-react';

export default function CustomerLayout({ children, title }) {
    return (
        <div className="flex min-h-screen bg-gray-50">
            {/* Sidebar Simple untuk Customer */}
            <aside className="w-64 bg-white border-r border-gray-100 hidden md:flex flex-col fixed h-full z-10">
                <div className="p-6 border-b border-gray-50 flex items-center gap-2">
                    <Wifi className="text-indigo-600" />
                    <span className="font-bold text-lg text-gray-900">MyProvider</span>
                </div>
                <nav className="p-4 space-y-2 flex-1">
                    <Link href={route('customer.dashboard')} className="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition">
                        <Home size={20} /> Dashboard
                    </Link>
                    <Link href={route('customer.complaints.index')} className="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition">
                        <MessageSquare size={20} /> Pengaduan
                    </Link>
                </nav>
                <div className="p-4 border-t border-gray-50">
                    <Link href={route('customer.logout')} method="post" as="button" className="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-lg w-full transition">
                        <LogOut size={20} /> Logout
                    </Link>
                </div>
            </aside>

            {/* Content */}
            <main className="flex-1 md:ml-64 p-6 md:p-8">
                <h1 className="text-2xl font-bold text-gray-800 mb-6">{title}</h1>
                {children}
            </main>
        </div>
    );
}
