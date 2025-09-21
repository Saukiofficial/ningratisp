import React, { useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';

// Modern WiFi Icon Component
const WifiIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z" />
    </svg>
);

// Lock Icon for Security
const LockIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
    </svg>
);

// User Icon
const UserIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
    </svg>
);

const EyeIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
        <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
);

const EyeSlashIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243l-4.243-4.243" />
    </svg>
);

export default function Login() {
    const { data, setData, post, processing, errors } = useForm({
        username: '',
        password: ''
    });
    const [showPassword, setShowPassword] = useState(false);

    function submit(e) {
        e.preventDefault();
        post(route('login.auth'));
    }

    return (
        <>
            <Head title="Login Pelanggan" />
            <div className="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 relative overflow-hidden">
                <div
                    className="absolute inset-0"
                    style={{ backgroundImage: "url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%2393c5fd\" fill-opacity=\"0.1\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"2\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')" }}
                ></div>
                <div className="absolute top-0 left-0 w-72 h-72 bg-gradient-to-br from-blue-200/30 to-purple-200/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                <div className="absolute top-0 right-0 w-72 h-72 bg-gradient-to-br from-purple-200/30 to-pink-200/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
                <div className="absolute -bottom-8 left-20 w-72 h-72 bg-gradient-to-br from-indigo-200/30 to-blue-200/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
                <div className="relative flex flex-col justify-center items-center min-h-screen p-4">
                    <div className="w-full max-w-md">
                        <div className="text-center mb-8">
                            <div className="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl mb-6 shadow-2xl">
                                <div className="text-white">
                                    <WifiIcon />
                                </div>
                            </div>
                            <h1 className="text-4xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                                WiFiKu
                            </h1>
                            <p className="text-gray-600 font-medium">Portal Pelanggan Terpercaya</p>
                            <p className="text-sm text-gray-500 mt-1">Masuk untuk mengakses layanan Anda</p>
                        </div>
                        <div className="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 p-8 relative">
                            <div className="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <div className="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-4 py-2 rounded-full flex items-center space-x-2 shadow-lg">
                                    <LockIcon />
                                    <span className="text-sm font-semibold">Aman & Terpercaya</span>
                                </div>
                            </div>
                            <form onSubmit={submit} className="mt-6">
                                <div>
                                    <label htmlFor="username" className="block text-sm font-semibold text-gray-700 mb-3">
                                        Username/ID Pelanggan
                                    </label>
                                    <div className="relative">
                                        <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <div className="text-gray-400">
                                                <UserIcon />
                                            </div>
                                        </div>
                                        <input
                                            id="username"
                                            name="username"
                                            type="text"
                                            autoComplete="off"
                                            required
                                            value={data.username}
                                            onChange={(e) => setData('username', e.target.value)}
                                            className="block w-full pl-12 pr-4 py-4 bg-gray-50/50 border-2 border-gray-200 rounded-2xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-lg font-mono"
                                            placeholder="Contoh: akunku / emailaku@mail.com"
                                        />
                                    </div>
                                    {errors.username && (
                                        <div className="mt-3 bg-red-50 border border-red-200 rounded-xl p-4">
                                            <div className="flex items-center">
                                                <div className="flex-shrink-0">
                                                    <svg className="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div className="ml-3">
                                                    <p className="text-sm font-medium text-red-800">
                                                        {errors.username}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    )}
                                </div>
                                <div>
                                    <label htmlFor="password" className="my-3 block text-sm font-semibold text-gray-700 mb-3">
                                        Password
                                    </label>
                                    <div className="relative">
                                        <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <div className="text-gray-400">
                                                <LockIcon />
                                            </div>
                                        </div>
                                        <input
                                            id="password"
                                            name="password"
                                            type={showPassword ? 'text' : 'password'}
                                            autoComplete="off"
                                            required
                                            onChange={(e) => setData('password', e.target.value)}
                                            value={data.password}
                                            className="block w-full pl-12 pr-12 py-4 bg-gray-50/50 border-2 border-gray-200 rounded-2xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 text-lg font-mono"
                                        />
                                        <div className="absolute inset-y-0 right-0 pr-4 flex items-center">
                                            <button type="button" onClick={() => setShowPassword(!showPassword)} className="text-gray-400 hover:text-blue-500">
                                                {showPassword ? <EyeSlashIcon /> : <EyeIcon />}
                                            </button>
                                        </div>
                                    </div>
                                    {errors.password && (
                                        <div className="mt-3 bg-red-50 border border-red-200 rounded-xl p-4">
                                            <div className="flex items-center">
                                                <div className="flex-shrink-0">
                                                    <svg className="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div className="ml-3">
                                                    <p className="text-sm font-medium text-red-800">
                                                        {errors.password}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    )}
                                </div>
                                <div className="mt-8">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="w-full flex justify-center items-center py-4 px-6 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl focus:outline-none focus:ring-4 focus:ring-blue-500/50 disabled:opacity-50 disabled:cursor-not-allowed transform transition-all duration-300 hover:scale-105 active:scale-95"
                                    >
                                        {processing ? (
                                            <>
                                                <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Memproses...
                                            </>
                                        ) : (
                                            <>
                                                <LockIcon />
                                                <span className="ml-2">Masuk Sekarang</span>
                                            </>
                                        )}
                                    </button>
                                </div>
                            </form>
                            <div className="mt-6 text-center">
                                <p className="text-xs text-gray-500">
                                    Kode unik dapat ditemukan pada invoice atau struk pembayaran Anda
                                </p>
                            </div>
                        </div>
                        <div className="text-center mt-8">
                            <p className="text-xs text-gray-500">
                                © 2025 WiFiKu. Dilindungi dengan enkripsi SSL.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
