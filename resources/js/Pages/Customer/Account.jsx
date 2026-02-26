import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { useGeolocated } from 'react-geolocated';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';

export default function Account({ customer }) {
    const [isEditing, setIsEditing] = React.useState(false);
    const [passwordEditing, setPasswordEditing] = React.useState(false);

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

    const {
        coords,
        isGeolocationAvailable,
        isGeolocationEnabled,
        getPosition,
        positionError,
    } = useGeolocated({
        positionOptions: {
            enableHighAccuracy: true,
        },
        userDecisionTimeout: 10000,
        suppressLocationOnMount: true,
        watchPosition: false,
    });

    React.useEffect(() => {
        if (coords) {
            setData('latitude', coords.latitude?.toFixed(7) ?? '');
            setData('longitude', coords.longitude?.toFixed(7) ?? '');
        }
    }, [coords, setData]);

    const submitAccount = (e) => {
        e.preventDefault();

        put(route('customer.account.update'), {
            onSuccess: () => {
                setIsEditing(false);
            },
        });
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
                    <div className="mb-6 flex items-start justify-between gap-4">
                        <div>
                            <h2 className="text-lg font-semibold text-gray-900">Informasi Akun</h2>
                            <p className="mt-1 text-sm text-gray-600">
                                Lihat dan kelola informasi kontak serta lokasi pemasangan.
                            </p>
                            <p className="mt-2 text-xs text-gray-500">
                                Username:{' '}
                                <span className="font-mono font-semibold bg-gray-100 px-2 py-0.5 rounded">
                                    {customer.username}
                                </span>
                            </p>
                        </div>
                        <div className="flex items-center gap-2">
                            {isEditing && (
                                <button
                                    type="button"
                                    onClick={() => setIsEditing(false)}
                                    className="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Batal
                                </button>
                            )}
                            <PrimaryButton
                                type="button"
                                onClick={() => setIsEditing((prev) => !prev)}
                            >
                                {isEditing ? 'Selesai' : 'Edit'}
                            </PrimaryButton>
                        </div>
                    </div>

                    {!isEditing && (
                        <div className="space-y-4 text-sm text-gray-700">
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div className="space-y-1">
                                    <p className="text-xs uppercase tracking-wide text-gray-500">
                                        Email
                                    </p>
                                    <p className="font-medium break-all">
                                        {customer.email || '-'}
                                    </p>
                                </div>
                                <div className="space-y-1">
                                    <p className="text-xs uppercase tracking-wide text-gray-500">
                                        Nomor WhatsApp
                                    </p>
                                    <p className="font-medium">
                                        {customer.whatsapp_number || '-'}
                                    </p>
                                </div>
                            </div>

                            <div className="space-y-1">
                                <p className="text-xs uppercase tracking-wide text-gray-500">
                                    Alamat
                                </p>
                                <p className="font-medium whitespace-pre-line">
                                    {customer.address || '-'}
                                </p>
                            </div>

                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div className="space-y-1">
                                    <p className="text-xs uppercase tracking-wide text-gray-500">
                                        Latitude
                                    </p>
                                    <p className="font-medium">
                                        {customer.latitude ?? '-'}
                                    </p>
                                </div>
                                <div className="space-y-1">
                                    <p className="text-xs uppercase tracking-wide text-gray-500">
                                        Longitude
                                    </p>
                                    <p className="font-medium">
                                        {customer.longitude ?? '-'}
                                    </p>
                                </div>
                            </div>

                            <p className="mt-4 text-xs text-gray-500">
                                Untuk mengubah data di atas, klik tombol <span className="font-semibold">Edit</span>.
                            </p>
                        </div>
                    )}

                    {isEditing && (
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
                                <p className="mt-1 text-xs text-gray-500">
                                    Gunakan format internasional tanpa tanda +, contoh: 6281234567890.
                                </p>
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
                                    <div className="flex items-center justify-between">
                                        <InputLabel htmlFor="latitude" value="Latitude" />
                                        <button
                                            type="button"
                                            onClick={() => getPosition()}
                                            className="text-xs font-medium text-indigo-600 hover:text-indigo-700"
                                            disabled={!isGeolocationAvailable || !isGeolocationEnabled}
                                        >
                                            Gunakan lokasi saya
                                        </button>
                                    </div>
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

                            <div className="space-y-1 text-xs text-gray-500">
                                {!isGeolocationAvailable && (
                                    <p>Browser Anda tidak mendukung fitur geolokasi.</p>
                                )}
                                {isGeolocationAvailable && !isGeolocationEnabled && (
                                    <p>Izinkan akses lokasi di browser untuk menggunakan tombol &quot;Gunakan lokasi saya&quot;.</p>
                                )}
                                {positionError && (
                                    <p className="text-red-600">
                                        Gagal mendapatkan lokasi: {positionError.message}
                                    </p>
                                )}
                                {coords && (
                                    <p>
                                        Lokasi terkini diambil dari perangkat Anda. Silakan simpan untuk menyimpan ke
                                        akun.
                                    </p>
                                )}
                            </div>

                            <div className="flex items-center gap-4">
                                <PrimaryButton disabled={processing}>Simpan</PrimaryButton>
                                {recentlySuccessful && (
                                    <p className="text-sm text-gray-600">Tersimpan.</p>
                                )}
                            </div>
                        </form>
                    )}
                </section>

                <section className="bg-white shadow-sm rounded-xl border border-gray-100 p-6">
                    <div className="mb-6 flex items-start justify-between gap-4">
                        <div>
                            <h2 className="text-lg font-semibold text-gray-900">Reset Password</h2>
                            <p className="mt-1 text-sm text-gray-600">
                                Ganti password login akun pelanggan Anda.
                            </p>
                        </div>
                        <div className="flex items-center gap-2">
                            {passwordEditing && (
                                <button
                                    type="button"
                                    onClick={() => setPasswordEditing(false)}
                                    className="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Batal
                                </button>
                            )}
                            <PrimaryButton
                                type="button"
                                onClick={() => setPasswordEditing((prev) => !prev)}
                            >
                                {passwordEditing ? 'Selesai' : 'Edit'}
                            </PrimaryButton>
                        </div>
                    </div>

                    {!passwordEditing && (
                        <div className="space-y-4 text-sm text-gray-700">
                            <p className="font-medium">
                                Password saat ini: *********
                            </p>
                        </div>
                    )}

                    {passwordEditing && (
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
                    )}


                </section>
            </div>
        </AuthenticatedLayout>
    );
}

