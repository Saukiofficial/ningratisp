import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { useGeolocated } from 'react-geolocated';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

// ─── Icons ────────────────────────────────────────────────────────────────────

const UserIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 16, height: 16 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
    </svg>
);

const LockIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 16, height: 16 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
    </svg>
);

const EditIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 13, height: 13 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
    </svg>
);

const SaveIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 14, height: 14 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 12.75l6 6 9-13.5" />
    </svg>
);

const LocationIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 13, height: 13 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
        <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
    </svg>
);

const CheckIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 14, height: 14 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 12.75l6 6 9-13.5" />
    </svg>
);

const SpinnerIcon = () => (
    <svg className="animate-spin" style={{ width: 15, height: 15 }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
    </svg>
);

export default function Account({ customer }) {
    const [isEditing, setIsEditing] = React.useState(false);
    const [passwordEditing, setPasswordEditing] = React.useState(false);

    // Auto-open edit mode if profile is incomplete
    React.useEffect(() => {
        if (!customer.full_name || !customer.whatsapp_number || !customer.latitude || !customer.longitude) {
            setIsEditing(true);
        }
    }, [customer]);

    const { data, setData, put, errors, processing, recentlySuccessful } = useForm({
        email: customer.email || '',
        full_name: customer.full_name || '',
        whatsapp_number: customer.whatsapp_number || '',
        address: customer.address || '',
        latitude: customer.latitude ?? '',
        longitude: customer.longitude ?? '',
    });

    const { data: passwordData, setData: setPasswordData, put: putPassword, errors: passwordErrors,
        processing: passwordProcessing, recentlySuccessful: passwordRecentlySuccessful, reset: resetPasswordForm,
    } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const { coords, isGeolocationAvailable, isGeolocationEnabled, getPosition, positionError } = useGeolocated({
        positionOptions: { enableHighAccuracy: true },
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
        put(route('customer.account.update'), { onSuccess: () => setIsEditing(false) });
    };

    const submitPassword = (e) => {
        e.preventDefault();
        putPassword(route('customer.account.password.update'), { onSuccess: () => resetPasswordForm() });
    };

    return (
        <AuthenticatedLayout>
            <Head title="Informasi Akun" />

            <style>{`
                @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@400;600;700;800&display=swap');

                .ac-root {
                    font-family: 'Plus Jakarta Sans', sans-serif;
                    background: #0f0f0f;
                    min-height: 100vh;
                    padding: 28px 0 60px;
                }
                .ac-container {
                    max-width: 760px;
                    margin: 0 auto;
                    padding: 0 16px;
                    display: flex;
                    flex-direction: column;
                    gap: 20px;
                }
                @media (min-width: 640px)  { .ac-container { padding: 0 24px; } }
                @media (min-width: 1024px) { .ac-container { padding: 0 32px; } }

                /* ── Page heading ── */
                .ac-page-title {
                    font-family: 'Sora', sans-serif;
                    font-size: clamp(1.3rem, 3vw, 1.8rem);
                    font-weight: 800; color: #ffffff; letter-spacing: -0.5px;
                }
                .ac-page-sub { font-size: 0.8rem; color: rgba(255,255,255,0.35); margin-top: 4px; margin-bottom: 4px; }

                /* ── Card ── */
                .ac-card {
                    background: #1a1a1a;
                    border: 1px solid rgba(255,255,255,0.07);
                    border-radius: 20px;
                    overflow: hidden;
                    position: relative;
                }
                .ac-card::before {
                    content: '';
                    position: absolute; top: 0; left: 0; right: 0; height: 1px;
                    background: linear-gradient(90deg, transparent, rgba(255,140,0,0.3), transparent);
                }

                /* ── Card header ── */
                .ac-card-header {
                    display: flex; align-items: flex-start; justify-content: space-between;
                    gap: 16px; padding: 20px 22px 16px;
                    border-bottom: 1px solid rgba(255,255,255,0.05);
                }
                .ac-card-header-left { display: flex; align-items: flex-start; gap: 12px; }
                .ac-card-icon {
                    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
                    display: flex; align-items: center; justify-content: center;
                    background: rgba(255,140,0,0.1); color: #ff8c00; margin-top: 2px;
                }
                .ac-card-title {
                    font-family: 'Sora', sans-serif; font-size: 0.95rem;
                    font-weight: 700; color: #ffffff; margin-bottom: 2px;
                }
                .ac-card-desc { font-size: 0.78rem; color: rgba(255,255,255,0.4); line-height: 1.5; }

                /* ── Header action buttons ── */
                .ac-header-btns { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
                .ac-cancel-btn {
                    padding: 7px 14px; border-radius: 9px; font-size: 0.78rem; font-weight: 600;
                    background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
                    color: rgba(255,255,255,0.55); cursor: pointer; transition: all 0.2s;
                    font-family: 'Plus Jakarta Sans', sans-serif;
                }
                .ac-cancel-btn:hover { background: rgba(255,255,255,0.09); color: rgba(255,255,255,0.8); }
                .ac-edit-btn {
                    display: inline-flex; align-items: center; gap: 6px;
                    padding: 7px 16px; border-radius: 9px; font-size: 0.78rem; font-weight: 700;
                    background: rgba(255,140,0,0.1); border: 1px solid rgba(255,140,0,0.25);
                    color: #ff8c00; cursor: pointer; transition: all 0.2s;
                    font-family: 'Plus Jakarta Sans', sans-serif;
                }
                .ac-edit-btn:hover { background: rgba(255,140,0,0.18); border-color: rgba(255,140,0,0.4); }

                /* ── Card body ── */
                .ac-card-body { padding: 20px 22px; }

                /* ── Info display grid ── */
                .ac-info-grid {
                    display: grid; grid-template-columns: 1fr; gap: 16px;
                }
                @media (min-width: 480px) { .ac-info-grid { grid-template-columns: 1fr 1fr; } }

                .ac-info-item {}
                .ac-info-label {
                    font-size: 0.68rem; font-weight: 700; letter-spacing: 0.1em;
                    text-transform: uppercase; color: rgba(255,255,255,0.3); margin-bottom: 5px;
                }
                .ac-info-value {
                    font-size: 0.88rem; font-weight: 600; color: rgba(255,255,255,0.85);
                    line-height: 1.5;
                }
                .ac-info-value.mono {
                    font-family: 'Courier New', monospace;
                    display: inline-flex; align-items: center;
                    background: rgba(255,140,0,0.08); border: 1px solid rgba(255,140,0,0.2);
                    color: #ff8c00; padding: 3px 10px; border-radius: 7px;
                    font-size: 0.82rem; letter-spacing: 0.06em;
                }
                .ac-info-hint {
                    font-size: 0.72rem; color: rgba(255,255,255,0.25); margin-top: 14px;
                }

                /* ── Password masked ── */
                .ac-password-masked {
                    display: flex; align-items: center; gap: 8px;
                    padding: 10px 14px; border-radius: 10px;
                    background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);
                    font-size: 0.9rem; color: rgba(255,255,255,0.4); letter-spacing: 0.15em;
                }

                /* ── Form fields ── */
                .ac-form { display: flex; flex-direction: column; gap: 18px; }
                .ac-field-label {
                    display: block; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.07em;
                    text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 7px;
                }
                .ac-field-hint {
                    font-size: 0.72rem; color: rgba(255,255,255,0.25); margin-top: 5px;
                }
                .ac-input {
                    width: 100%; background: #111; border: 1px solid rgba(255,255,255,0.1);
                    border-radius: 10px; padding: 10px 13px; font-size: 0.85rem; font-weight: 500;
                    color: rgba(255,255,255,0.85); font-family: 'Plus Jakarta Sans', sans-serif;
                    transition: border-color 0.2s, box-shadow 0.2s; outline: none; box-sizing: border-box;
                }
                .ac-input:focus { border-color: rgba(255,140,0,0.5); box-shadow: 0 0 0 3px rgba(255,140,0,0.07); }
                .ac-input::placeholder { color: rgba(255,255,255,0.2); }
                .ac-input.highlight-required { border-color: rgba(255,140,0,0.4); background: rgba(255,140,0,0.03); }
                .ac-textarea {
                    width: 100%; background: #111; border: 1px solid rgba(255,255,255,0.1);
                    border-radius: 10px; padding: 10px 13px; font-size: 0.85rem; font-weight: 500;
                    color: rgba(255,255,255,0.85); font-family: 'Plus Jakarta Sans', sans-serif;
                    transition: border-color 0.2s; outline: none; resize: vertical;
                    box-sizing: border-box; min-height: 80px;
                }
                .ac-textarea:focus { border-color: rgba(255,140,0,0.5); box-shadow: 0 0 0 3px rgba(255,140,0,0.07); }
                .ac-field-error { font-size: 0.75rem; color: #f87171; font-weight: 600; margin-top: 5px; }

                /* ── Grid 2-col for fields ── */
                .ac-field-grid { display: grid; grid-template-columns: 1fr; gap: 18px; }
                @media (min-width: 480px) { .ac-field-grid { grid-template-columns: 1fr 1fr; } }

                /* ── Location row ── */
                .ac-location-label-row {
                    display: flex; align-items: center; justify-content: space-between; margin-bottom: 7px;
                }
                .ac-location-btn {
                    display: inline-flex; align-items: center; gap: 5px;
                    font-size: 0.72rem; font-weight: 700; color: #ff8c00;
                    background: none; border: none; cursor: pointer; transition: color 0.2s;
                    font-family: 'Plus Jakarta Sans', sans-serif; padding: 0;
                }
                .ac-location-btn:hover { color: #ffaa33; }
                .ac-location-btn:disabled { opacity: 0.35; cursor: not-allowed; }
                .ac-geo-hint {
                    font-size: 0.72rem; color: rgba(255,255,255,0.25); margin-top: 6px;
                }
                .ac-geo-hint.error { color: #f87171; }
                .ac-geo-hint.success { color: rgba(52,211,153,0.7); }

                /* ── Save button ── */
                .ac-save-btn {
                    display: inline-flex; align-items: center; gap: 7px;
                    padding: 10px 22px; border-radius: 10px;
                    background: linear-gradient(135deg, #ff8c00, #ff6a00);
                    color: white; font-size: 0.85rem; font-weight: 700;
                    border: none; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif;
                    box-shadow: 0 4px 14px rgba(255,110,0,0.35); transition: all 0.2s;
                }
                .ac-save-btn:hover:not(:disabled) { box-shadow: 0 6px 20px rgba(255,110,0,0.5); transform: translateY(-1px); }
                .ac-save-btn:disabled { opacity: 0.5; cursor: not-allowed; box-shadow: none; transform: none; }
                .ac-save-success {
                    display: inline-flex; align-items: center; gap: 5px;
                    font-size: 0.78rem; font-weight: 600; color: #34d399;
                }

                /* ── Section divider ── */
                .ac-divider {
                    height: 1px; background: rgba(255,255,255,0.05); margin: 4px 0;
                }
            `}</style>

            <div className="ac-root">
                <div className="ac-container">

                    {/* ── Page Heading ── */}
                    <div>
                        <div className="ac-page-title">Informasi Akun</div>
                        <div className="ac-page-sub">Kelola profil dan keamanan akun Anda</div>
                    </div>

                    {/* ── Account Info Card ── */}
                    <div className="ac-card">
                        <div className="ac-card-header">
                            <div className="ac-card-header-left">
                                <div className="ac-card-icon"><UserIcon /></div>
                                <div>
                                    <div className="ac-card-title">Informasi Akun</div>
                                    <div className="ac-card-desc">Lihat dan kelola informasi kontak serta lokasi pemasangan.</div>
                                </div>
                            </div>
                            <div className="ac-header-btns">
                                {isEditing && (
                                    <button type="button" onClick={() => setIsEditing(false)} className="ac-cancel-btn">Batal</button>
                                )}
                                <button type="button" onClick={() => setIsEditing(p => !p)} className="ac-edit-btn">
                                    <EditIcon /> {isEditing ? 'Selesai' : 'Edit'}
                                </button>
                            </div>
                        </div>

                        <div className="ac-card-body">

                            {/* ── View Mode ── */}
                            {!isEditing && (
                                <>
                                    <div className='ac-info-grid' style={{ marginBottom: 16 }}>
                                        <div className="ac-info-item">
                                            <div className="ac-info-label">Name</div>
                                            <span className="ac-info-value">{customer.full_name}</span>
                                        </div>
                                        <div className="ac-info-item">
                                            <div className="ac-info-label">ID Number</div>
                                            <span className="ac-info-value mono">{customer.billing_number}</span>
                                        </div>
                                    </div>
                                    <div className="ac-info-grid">
                                        <div className="ac-info-item">
                                            <div className="ac-info-label">Email</div>
                                            <div className="ac-info-value">{customer.email || '-'}</div>
                                        </div>
                                        <div className="ac-info-item">
                                            <div className="ac-info-label">Nomor WhatsApp</div>
                                            <div className="ac-info-value">{customer.whatsapp_number || '-'}</div>
                                        </div>
                                    </div>
                                    <div className="ac-divider" style={{ margin: '16px 0' }} />
                                    <div className="ac-info-item" style={{ marginBottom: 16 }}>
                                        <div className="ac-info-label">Alamat</div>
                                        <div className="ac-info-value" style={{ whiteSpace: 'pre-line' }}>{customer.address || '-'}</div>
                                    </div>
                                    <div className="ac-info-grid">
                                        <div className="ac-info-item">
                                            <div className="ac-info-label">Latitude</div>
                                            <div className="ac-info-value">{customer.latitude ?? '-'}</div>
                                        </div>
                                        <div className="ac-info-item">
                                            <div className="ac-info-label">Longitude</div>
                                            <div className="ac-info-value">{customer.longitude ?? '-'}</div>
                                        </div>
                                    </div>
                                    <p className="ac-info-hint">Untuk mengubah data di atas, klik tombol <strong style={{ color: 'rgba(255,255,255,0.5)' }}>Edit</strong>.</p>
                                </>
                            )}

                            {/* ── Edit Mode ── */}
                            {isEditing && (
                                <form onSubmit={submitAccount} className="ac-form">
                                    {(!customer.full_name || !customer.whatsapp_number || !customer.latitude || !customer.longitude) && (
                                        <div style={{ padding: '12px 16px', background: 'rgba(255,140,0,0.1)', border: '1px solid rgba(255,140,0,0.25)', borderRadius: '12px', fontSize: '0.8rem', color: '#ff8c00', fontWeight: '600', marginBottom: '4px' }}>
                                            ⚠️ Mohon lengkapi data profil yang ditandai wajib (*) di bawah ini.
                                        </div>
                                    )}

                                    <div>
                                        <label htmlFor="full_name" className="ac-field-label">Name <span style={{ color: '#ff8c00' }}>*</span></label>
                                        <input id="full_name" type="text" required value={data.full_name} autoComplete="full_name"
                                            onChange={e => setData('full_name', e.target.value)}
                                            className={`ac-input ${!data.full_name ? 'highlight-required' : ''}`} />
                                        {errors.full_name && <div className="ac-field-error">{errors.full_name}</div>}
                                    </div>

                                    <div>
                                        <label htmlFor="email" className="ac-field-label">Email</label>
                                        <input id="email" type="email" value={data.email} autoComplete="email"
                                            onChange={e => setData('email', e.target.value)} className="ac-input" />
                                        {errors.email && <div className="ac-field-error">{errors.email}</div>}
                                    </div>

                                    <div>
                                        <label htmlFor="whatsapp_number" className="ac-field-label">Nomor WhatsApp <span style={{ color: '#ff8c00' }}>*</span></label>
                                        <input id="whatsapp_number" type="text" required value={data.whatsapp_number}
                                            onChange={e => setData('whatsapp_number', e.target.value)}
                                            placeholder="contoh: 6281234567890"
                                            className={`ac-input ${!data.whatsapp_number ? 'highlight-required' : ''}`} />
                                        <div className="ac-field-hint">Gunakan format internasional tanpa tanda +, contoh: 6281234567890.</div>
                                        {errors.whatsapp_number && <div className="ac-field-error">{errors.whatsapp_number}</div>}
                                    </div>

                                    <div>
                                        <label htmlFor="address" className="ac-field-label">Alamat</label>
                                        <textarea id="address" rows={3} value={data.address}
                                            onChange={e => setData('address', e.target.value)} className="ac-textarea" />
                                        {errors.address && <div className="ac-field-error">{errors.address}</div>}
                                    </div>

                                    <div className="ac-field-grid">
                                        <div>
                                            <div className="ac-location-label-row">
                                                <label htmlFor="latitude" className="ac-field-label" style={{ margin: 0 }}>Latitude <span style={{ color: '#ff8c00' }}>*</span></label>
                                                <button type="button" onClick={() => getPosition()} className="ac-location-btn"
                                                    disabled={!isGeolocationAvailable || !isGeolocationEnabled}>
                                                    <LocationIcon /> Lokasi saya
                                                </button>
                                            </div>
                                            <input id="latitude" type="number" step="0.0000001" required value={data.latitude}
                                                onChange={e => setData('latitude', e.target.value)}
                                                className={`ac-input ${!data.latitude ? 'highlight-required' : ''}`} />
                                            {errors.latitude && <div className="ac-field-error">{errors.latitude}</div>}
                                        </div>
                                        <div>
                                            <label htmlFor="longitude" className="ac-field-label">Longitude <span style={{ color: '#ff8c00' }}>*</span></label>
                                            <input id="longitude" type="number" step="0.0000001" required value={data.longitude}
                                                onChange={e => setData('longitude', e.target.value)}
                                                className={`ac-input ${!data.longitude ? 'highlight-required' : ''}`} />
                                            {errors.longitude && <div className="ac-field-error">{errors.longitude}</div>}
                                        </div>
                                    </div>

                                    {/* Geo hints */}
                                    {!isGeolocationAvailable && (
                                        <div className="ac-geo-hint">Browser Anda tidak mendukung fitur geolokasi.</div>
                                    )}
                                    {isGeolocationAvailable && !isGeolocationEnabled && (
                                        <div className="ac-geo-hint">Izinkan akses lokasi di browser untuk menggunakan tombol "Lokasi saya".</div>
                                    )}
                                    {positionError && (
                                        <div className="ac-geo-hint error">Gagal mendapatkan lokasi: {positionError.message}</div>
                                    )}
                                    {coords && (
                                        <div className="ac-geo-hint success">Lokasi terkini diambil dari perangkat Anda. Silakan simpan untuk menyimpan ke akun.</div>
                                    )}

                                    <div style={{ display: 'flex', alignItems: 'center', gap: 12, paddingTop: 4 }}>
                                        <button type="submit" disabled={processing} className="ac-save-btn">
                                            {processing ? <SpinnerIcon /> : <SaveIcon />}
                                            Simpan
                                        </button>
                                        {recentlySuccessful && (
                                            <span className="ac-save-success"><CheckIcon /> Tersimpan</span>
                                        )}
                                    </div>
                                </form>
                            )}
                        </div>
                    </div>

                    {/* ── Password Card ── */}
                    <div className="ac-card">
                        <div className="ac-card-header">
                            <div className="ac-card-header-left">
                                <div className="ac-card-icon" style={{ background: 'rgba(239,68,68,0.1)', color: '#f87171' }}><LockIcon /></div>
                                <div>
                                    <div className="ac-card-title">Reset Password</div>
                                    <div className="ac-card-desc">Ganti password login akun pelanggan Anda.</div>
                                </div>
                            </div>
                            <div className="ac-header-btns">
                                {passwordEditing && (
                                    <button type="button" onClick={() => setPasswordEditing(false)} className="ac-cancel-btn">Batal</button>
                                )}
                                <button type="button" onClick={() => setPasswordEditing(p => !p)} className="ac-edit-btn">
                                    <EditIcon /> {passwordEditing ? 'Selesai' : 'Ubah'}
                                </button>
                            </div>
                        </div>

                        <div className="ac-card-body">
                            {!passwordEditing && (
                                <div className="ac-password-masked">
                                    <LockIcon /> ••••••••••••
                                </div>
                            )}

                            {passwordEditing && (
                                <form onSubmit={submitPassword} className="ac-form">
                                    <div>
                                        <label htmlFor="current_password" className="ac-field-label">Password Saat Ini</label>
                                        <input id="current_password" type="password" value={passwordData.current_password} autoComplete="current-password"
                                            onChange={e => setPasswordData('current_password', e.target.value)} className="ac-input" />
                                        {passwordErrors.current_password && <div className="ac-field-error">{passwordErrors.current_password}</div>}
                                    </div>

                                    <div>
                                        <label htmlFor="password" className="ac-field-label">Password Baru</label>
                                        <input id="password" type="password" value={passwordData.password} autoComplete="new-password"
                                            onChange={e => setPasswordData('password', e.target.value)} className="ac-input" />
                                        {passwordErrors.password && <div className="ac-field-error">{passwordErrors.password}</div>}
                                    </div>

                                    <div>
                                        <label htmlFor="password_confirmation" className="ac-field-label">Konfirmasi Password Baru</label>
                                        <input id="password_confirmation" type="password" value={passwordData.password_confirmation} autoComplete="new-password"
                                            onChange={e => setPasswordData('password_confirmation', e.target.value)} className="ac-input" />
                                        {passwordErrors.password_confirmation && <div className="ac-field-error">{passwordErrors.password_confirmation}</div>}
                                    </div>

                                    <div style={{ display: 'flex', alignItems: 'center', gap: 12, paddingTop: 4 }}>
                                        <button type="submit" disabled={passwordProcessing} className="ac-save-btn">
                                            {passwordProcessing ? <SpinnerIcon /> : <SaveIcon />}
                                            Simpan Password
                                        </button>
                                        {passwordRecentlySuccessful && (
                                            <span className="ac-save-success"><CheckIcon /> Password berhasil diubah</span>
                                        )}
                                    </div>
                                </form>
                            )}
                        </div>
                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}