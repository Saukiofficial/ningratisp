import React from 'react';
import { useForm, Head } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';

export default function Register({ selectedPackage }) {
    // 'selectedPackage' adalah ID paket yang dipilih (opsional), dikirim dari controller

    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        phone: '',
        address: '',
        package_id: selectedPackage || '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('register.store')); // Pastikan route ini ada di web.php
    };

    return (
        <>
            <Head title="Daftar Baru" />
            <Navbar />

            <div className="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                <div className="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl">
                    <div className="text-center">
                        <h2 className="mt-2 text-3xl font-extrabold text-gray-900">Daftar Berlangganan</h2>
                        <p className="mt-2 text-sm text-gray-600">Isi data diri Anda untuk mulai berlangganan.</p>
                    </div>

                    <form className="mt-8 space-y-6" onSubmit={submit}>
                        <div className="space-y-4">
                            {/* Nama */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input
                                    type="text"
                                    required
                                    className="mt-1 block w-full border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500"
                                    value={data.name}
                                    onChange={e => setData('name', e.target.value)}
                                />
                                {errors.name && <div className="text-red-500 text-xs mt-1">{errors.name}</div>}
                            </div>

                            {/* Email */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700">Email Address</label>
                                <input
                                    type="email"
                                    required
                                    className="mt-1 block w-full border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500"
                                    value={data.email}
                                    onChange={e => setData('email', e.target.value)}
                                />
                                {errors.email && <div className="text-red-500 text-xs mt-1">{errors.email}</div>}
                            </div>

                            {/* Alamat */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700">Alamat Pemasangan</label>
                                <textarea
                                    required
                                    rows="3"
                                    className="mt-1 block w-full border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500"
                                    value={data.address}
                                    onChange={e => setData('address', e.target.value)}
                                />
                                {errors.address && <div className="text-red-500 text-xs mt-1">{errors.address}</div>}
                            </div>

                            {/* Password */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700">Password Akun</label>
                                <input
                                    type="password"
                                    required
                                    className="mt-1 block w-full border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500"
                                    value={data.password}
                                    onChange={e => setData('password', e.target.value)}
                                />
                                {errors.password && <div className="text-red-500 text-xs mt-1">{errors.password}</div>}
                            </div>
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition"
                        >
                            {processing ? 'Memproses...' : 'Daftar Sekarang'}
                        </button>
                    </form>
                </div>
            </div>
        </>
    );
}
