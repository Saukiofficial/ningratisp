import React from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { route } from 'ziggy-js';

const HomeIcon = ({ isActive }) => (
    <svg className={`w-6 h-6 mb-1 ${isActive ? 'text-blue-600' : 'text-gray-500'}`} aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
    </svg>
);


const DocumentIcon = ({ isActive }) => (
    <svg className={`w-6 h-6 mb-1 ${isActive ? 'text-blue-600' : 'text-gray-500'}`} aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
        <path d="M16 14V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v15a3 3 0 0 0 3 3h12a1 1 0 0 0 0-2h-1v-2a2 2 0 0 0 2-2ZM4 2h2v12H4V2Zm8 16H3a1 1 0 0 1 0-2h9v2Z" />
    </svg>
);


export default function AuthenticatedLayout({ children }) {
    const { url } = usePage();

    const handleLogout = (e) => {
        e.preventDefault();
        router.post((route('logout')));
    };

    return (

        <div className="min-h-screen bg-gray-100 flex flex-col pb-20 sm:pb-0">

            <header className="bg-white shadow-sm sticky top-0 z-10">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex items-center space-x-8">
                            <Link href={route('dashboard')} className="text-2xl font-bold text-blue-600">NingratNet</Link>

                            <div className="hidden sm:flex sm:space-x-8">
                                <Link
                                    href={route('dashboard')}
                                    className={`inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out ${url.startsWith('/dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                        }`}
                                >
                                    Dashboard
                                </Link>
                                <Link
                                    href={route('invoices.index')}
                                    className={`inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-150 ease-in-out ${url.startsWith('/tagihan') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                        }`}
                                >
                                    Tagihan
                                </Link>
                            </div>
                        </div>
                        <div className="flex items-center">
                            <button
                                onClick={handleLogout}
                                type="button"
                                className="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition duration-150 ease-in-out"
                            >
                                Logout
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <main className="flex-grow">{children}</main>


            <div className="sm:hidden fixed bottom-0 left-0 z-50 w-full h-16 bg-white border-t border-gray-200">
                <div className="grid h-full max-w-lg grid-cols-2 mx-auto font-medium">
                    <Link href={route('dashboard')} className="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50">
                        <HomeIcon isActive={url.startsWith('/dashboard')} />
                        <span className={`text-sm ${url.startsWith('/dashboard') ? 'text-blue-600' : 'text-gray-500'}`}>Dashboard</span>
                    </Link>
                    <Link href={route('invoices.index')} className="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50">
                        <DocumentIcon isActive={url.startsWith('/tagihan')} />
                        <span className={`text-sm ${url.startsWith('/tagihan') ? 'text-blue-600' : 'text-gray-500'}`}>Tagihan</span>
                    </Link>
                </div>
            </div>


            <footer className="bg-white mt-8 hidden sm:block">
                <div className="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
                    &copy; {new Date().getFullYear()} Ningrat.Net. All Rights Reserved.
                </div>
            </footer>
        </div>
    );
}
