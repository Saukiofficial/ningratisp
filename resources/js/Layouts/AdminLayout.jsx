import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { LayoutDashboard, Users, Wifi, MessageSquareWarning, LogOut, Menu, X, Map } from 'lucide-react';

export default function AdminLayout({ children, title }) {
    const { auth } = usePage().props;
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);

    const navItems = [
        {
            label: 'Dashboard',
            routeName: 'admin.dashboard',
            icon: LayoutDashboard
        },
        {
            label: 'Paket Internet',
            routeName: 'admin.packages.index',
            icon: Wifi
        },
        {
            label: 'Coverage Area',
            routeName: 'admin.coverage-areas.index',
            icon: Map
        },
        {
            label: 'Data Pelanggan',
            routeName: 'admin.customers.index',
            icon: Users
        },
        {
            label: 'Pengaduan',
            routeName: 'admin.complaints.index',
            icon: MessageSquareWarning
        },
    ];

    const isRouteActive = (itemRoute) => {
        const baseRoute = itemRoute.split('.').slice(0, 2).join('.');
        return route().current(baseRoute + '*');
    };

    return (
        <div className="min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-50 font-sans text-gray-900 flex">

            {/* Overlay Mobile */}
            {isSidebarOpen && (
                <div
                    className="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 md:hidden transition-opacity"
                    onClick={() => setIsSidebarOpen(false)}
                ></div>
            )}

            {/* SIDEBAR */}
            <aside className={`fixed inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-gray-900 via-gray-900 to-gray-800 border-r border-gray-800/50 transform transition-transform duration-300 md:relative md:translate-x-0 ${isSidebarOpen ? 'translate-x-0' : '-translate-x-full'}`}>

                {/* Logo Area */}
                <div className="h-20 flex items-center px-6 border-b border-gray-800/50 justify-between md:justify-start backdrop-blur-xl">
                    <div className="flex items-center gap-3">
                        <div className="bg-gradient-to-br from-gray-700 to-gray-600 p-2.5 rounded-xl shadow-lg shadow-gray-900/50">
                            <Wifi size={22} className="text-gray-100" />
                        </div>
                        <div className="flex flex-col">
                            <span className="text-gray-100 font-bold text-lg tracking-wide">Ningrat</span>
                            <span className="text-gray-400 text-xs font-medium tracking-wider">ADMIN PANEL</span>
                        </div>
                    </div>
                    <button
                        onClick={() => setIsSidebarOpen(false)}
                        className="md:hidden text-gray-400 hover:text-gray-200 transition-colors"
                    >
                        <X size={24} />
                    </button>
                </div>

                {/* Navigation Menu */}
                <nav className="p-4 space-y-1.5 overflow-y-auto h-[calc(100vh-12rem)]">
                    {navItems.map((item) => {
                        const Icon = item.icon;
                        const active = isRouteActive(item.routeName);

                        return (
                            <Link
                                key={item.routeName}
                                href={route(item.routeName)}
                                className={`flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-medium transition-all duration-200 group relative overflow-hidden ${active
                                        ? 'bg-gradient-to-r from-gray-700 to-gray-600 text-white shadow-lg shadow-gray-900/30'
                                        : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'
                                    }`}
                            >
                                {active && (
                                    <div className="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-gray-400 to-gray-500 rounded-r"></div>
                                )}
                                <Icon
                                    size={20}
                                    className={`${active ? 'text-gray-100' : 'text-gray-500 group-hover:text-gray-300'} transition-colors`}
                                />
                                <span className="tracking-wide">{item.label}</span>
                            </Link>
                        );
                    })}
                </nav>

                {/* User Info & Logout */}
                <div className="absolute bottom-0 w-full p-4 border-t border-gray-800/50 bg-gradient-to-b from-gray-900 to-gray-900/95 backdrop-blur-xl">
                    <div className="mb-3 px-4 py-3 rounded-xl bg-gray-800/50 border border-gray-700/50">
                        <div className="flex items-center gap-3">
                            <div className="w-10 h-10 bg-gradient-to-br from-gray-600 to-gray-700 rounded-lg flex items-center justify-center text-white font-bold shadow-lg">
                                {auth.user.name.charAt(0).toUpperCase()}
                            </div>
                            <div className="flex-1 min-w-0">
                                <div className="text-sm font-semibold text-gray-100 truncate">{auth.user.name}</div>
                                <div className="text-xs text-gray-400 uppercase tracking-wider">{auth.user.role}</div>
                            </div>
                        </div>
                    </div>

                    <Link
                        href={route('customer.logout')}
                        method="post"
                        as="button"
                        className="flex items-center justify-center gap-3 px-4 py-3 w-full text-sm font-medium text-gray-300 hover:text-white bg-gray-800/50 hover:bg-red-900/40 border border-gray-700/50 hover:border-red-800/50 rounded-xl transition-all duration-200 group"
                    >
                        <LogOut size={18} className="group-hover:rotate-12 transition-transform" />
                        <span className="tracking-wide">Logout</span>
                    </Link>
                </div>
            </aside>

            {/* MAIN CONTENT WRAPPER */}
            <div className="flex-1 flex flex-col min-w-0 overflow-hidden">

                {/* Header Navbar */}
                <header className="bg-white/80 backdrop-blur-xl border-b border-gray-200/50 h-20 flex items-center justify-between px-6 md:px-8 shadow-sm z-10">
                    <button
                        onClick={() => setIsSidebarOpen(true)}
                        className="md:hidden p-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-colors"
                    >
                        <Menu size={24} />
                    </button>

                    <div className="flex items-center gap-4 ml-auto">
                        <div className="text-right hidden sm:block">
                            <div className="text-sm font-bold text-gray-800 tracking-wide">{auth.user.name}</div>
                            <div className="text-xs text-gray-500 uppercase tracking-wider font-medium mt-0.5">
                                {auth.user.role}
                            </div>
                        </div>
                        <div className="w-11 h-11 bg-gradient-to-br from-gray-700 to-gray-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg shadow-gray-400/20">
                            {auth.user.name.charAt(0).toUpperCase()}
                        </div>
                    </div>
                </header>

                {/* Page Content */}
                <main className="flex-1 overflow-y-auto p-6 md:p-8 scroll-smooth">
                    <div className="max-w-7xl mx-auto">
                        <div className="mb-8">
                            <h1 className="text-3xl font-bold text-gray-800 tracking-tight mb-1">{title}</h1>
                            <div className="h-1 w-20 bg-gradient-to-r from-gray-700 to-gray-500 rounded-full"></div>
                        </div>

                        {/* Content Slot */}
                        <div className="animate-fade-in-up">
                            {children}
                        </div>
                    </div>
                </main>
            </div>
        </div>
    );
}
