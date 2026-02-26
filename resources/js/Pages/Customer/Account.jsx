import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';

export default function Account({ customer }) {
    const {
        data,
        setData,
        put,
        errors,
        processing,
        recentlySuccessful,
    } = useForm({
        email: customer.email || '',
        whatsapp_number: customer.whatsapp_number || '',
        address: customer.address || '',
        latitude: customer.latitude ?? '',
        longitude: customer.longitude ?? '',
    });

    const {
        data: passwordData,
        setData: setPasswordData,
        put: putPassword,
        errors: passwordErrors,
        processing: passwordProcessing,
        recentlySuccessful: passwordRecentlySuccessful,
        reset: resetPasswordForm,
    } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const submitAccount = (e) => {
        e.preventDefault();

        put(route('customer.account.update'));
    };

    const submitPassword = (e) => {
        e.preventDefault();

        putPassword(route('customer.account.password.update'), {
            onSuccess: () => {
                resetPasswordForm();
            },
        });
    };

    return (
        <AuthenticatedLayout>
            <Head title="Informasi Akun" />

            <div className="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">
                <section className="bg-white shadow-sm rounded-xl border border-gray-100 p-6">
                    <div className="mb-6">
                        <h2 className="text-lg font-semibold text-gray-900">Informasi Akun</h2>
                        <p className="mt-1 text-sm text-gray-600">
                            Perbarui email, nomor WhatsApp, alamat, dan lokasi Anda.
                        </p>
                        <p className="mt-2 text-xs text-gray-500">
                            Username: <span className="font-mono font-semibold">{customer.username}</span>
                        </p>
                    </div>

                    <form onSubmit={submitAccount} className="space-y-6">
                        <div>
                            <InputLabel htmlFor="email" value="Email" />
                            <TextInput
                                id="email"
                                type="email"
                                className="mt-1 block w-full"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                required
                                autoComplete="email"
                            />
                            <InputError className="mt-2" message={errors.email} />
                        </div>

                        <div>
                            <InputLabel htmlFor="whatsapp_number" value="Nomor WhatsApp" />
                            <TextInput
                                id="whatsapp_number"
                                type="text"
                                className="mt-1 block w-full"
                                value={data.whatsapp_number}
                                onChange={(e) => setData('whatsapp_number', e.target.value)}
                                placeholder="contoh: 6281234567890"
                            />
                            <InputError className="mt-2" message={errors.whatsapp_number} />
                        </div>

                        <div>
                            <InputLabel htmlFor="address" value="Alamat" />
                            <textarea
                                id="address"
                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                rows={3}
                                value={data.address}
                                onChange={(e) => setData('address', e.target.value)}
                            />
                            <InputError className="mt-2" message={errors.address} />
                        </div>

                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <InputLabel htmlFor="latitude" value="Latitude" />
                                <TextInput
                                    id="latitude"
                                    type="number"
                                    step="0.0000001"
                                    className="mt-1 block w-full"
                                    value={data.latitude}
                                    onChange={(e) => setData('latitude', e.target.value)}
                                />
                                <InputError className="mt-2" message={errors.latitude} />
                            </div>
                            <div>
                                <InputLabel htmlFor="longitude" value="Longitude" />
                                <TextInput
                                    id="longitude"
                                    type="number"
                                    step="0.0000001"
                                    className="mt-1 block w-full"
                                    value={data.longitude}
                                    onChange={(e) => setData('longitude', e.target.value)}
                                />
                                <InputError className="mt-2" message={errors.longitude} />
                            </div>
                        </div>

                        <div className="flex items-center gap-4">
                            <PrimaryButton disabled={processing}>Simpan</PrimaryButton>
                            {recentlySuccessful && (
                                <p className="text-sm text-gray-600">Tersimpan.</p>
                            )}
                        </div>
                    </form>
                </section>

                <section className="bg-white shadow-sm rounded-xl border border-gray-100 p-6">
                    <div className="mb-6">
                        <h2 className="text-lg font-semibold text-gray-900">Reset Password</h2>
                        <p className="mt-1 text-sm text-gray-600">
                            Ganti password login akun pelanggan Anda.
                        </p>
                    </div>

                    <form onSubmit={submitPassword} className="space-y-6">
                        <div>
                            <InputLabel htmlFor="current_password" value="Password Saat Ini" />
                            <TextInput
                                id="current_password"
                                type="password"
                                className="mt-1 block w-full"
                                value={passwordData.current_password}
                                onChange={(e) => setPasswordData('current_password', e.target.value)}
                                autoComplete="current-password"
                            />
                            <InputError className="mt-2" message={passwordErrors.current_password} />
                        </div>

                        <div>
                            <InputLabel htmlFor="password" value="Password Baru" />
                            <TextInput
                                id="password"
                                type="password"
                                className="mt-1 block w-full"
                                value={passwordData.password}
                                onChange={(e) => setPasswordData('password', e.target.value)}
                                autoComplete="new-password"
                            />
                            <InputError className="mt-2" message={passwordErrors.password} />
                        </div>

                        <div>
                            <InputLabel htmlFor="password_confirmation" value="Konfirmasi Password Baru" />
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                className="mt-1 block w-full"
                                value={passwordData.password_confirmation}
                                onChange={(e) => setPasswordData('password_confirmation', e.target.value)}
                                autoComplete="new-password"
                            />
                            <InputError className="mt-2" message={passwordErrors.password_confirmation} />
                        </div>

                        <div className="flex items-center gap-4">
                            <PrimaryButton disabled={passwordProcessing}>Simpan Password</PrimaryButton>
                            {passwordRecentlySuccessful && (
                                <p className="text-sm text-gray-600">Password berhasil diubah.</p>
                            )}
                        </div>
                    </form>
                </section>
            </div>
        </AuthenticatedLayout>
    );
}

