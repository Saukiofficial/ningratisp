import React, { useState } from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';
import './Login.css';

// ── Icons ──────────────────────────────────────────────────────────────────

const LockIcon = ({ size = 20 }) => (
    <svg width={size} height={size} viewBox="0 0 24 24" fill="none"
        stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
    </svg>
);

const UserIcon = ({ size = 20 }) => (
    <svg width={size} height={size} viewBox="0 0 24 24" fill="none"
        stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
        <circle cx="12" cy="7" r="4" />
    </svg>
);

const EyeIcon = ({ size = 20 }) => (
    <svg width={size} height={size} viewBox="0 0 24 24" fill="none"
        stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round">
        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
        <circle cx="12" cy="12" r="3" />
    </svg>
);

const EyeSlashIcon = ({ size = 20 }) => (
    <svg width={size} height={size} viewBox="0 0 24 24" fill="none"
        stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round">
        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
        <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
        <line x1="1" y1="1" x2="23" y2="23" />
    </svg>
);

const WifiIcon = ({ size = 36 }) => (
    <svg width={size} height={size} viewBox="0 0 24 24" fill="none"
        stroke="currentColor" strokeWidth={1.8} strokeLinecap="round" strokeLinejoin="round">
        <path d="M5 12.55a11 11 0 0 1 14.08 0" />
        <path d="M1.42 9a16 16 0 0 1 21.16 0" />
        <path d="M8.53 16.11a6 6 0 0 1 6.95 0" />
        <circle cx="12" cy="20" r="1" fill="currentColor" />
    </svg>
);

const ShieldIcon = ({ size = 13 }) => (
    <svg width={size} height={size} viewBox="0 0 24 24" fill="none"
        stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round">
        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
    </svg>
);

const ZapIcon = ({ size = 11 }) => (
    <svg width={size} height={size} viewBox="0 0 24 24" fill="none"
        stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round">
        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
    </svg>
);

// ── Component ───────────────────────────────────────────────────────────────

export default function Login() {
    const { data, setData, post, processing, errors } = useForm({
        username: '',
        password: '',
    });
    const { props } = usePage();
    const { appEnv } = props;
    const [showPassword, setShowPassword] = useState(false);

    function submit(e) {
        e.preventDefault();
        post(route('login.auth'));
    }

    return (
        <>
            <Head title="Login – NingratNet" />

            {appEnv !== 'production' && (
                <div className="nn-login-testbar">⚠ &nbsp; TESTING APP &nbsp; ⚠</div>
            )}

            <div className="nn-login-bg" />
            <div className="nn-login-overlay" />
            <div className="nn-login-glow-tr" />
            <div className="nn-login-glow-bl" />

            <div
                className="nn-login-page"
                style={{ paddingTop: appEnv !== 'production' ? '54px' : '20px' }}
            >
                <div className="nn-login-container">

                    {/* ── Header ── */}
                    <div className="nn-login-header">
                        <div className="nn-login-wifi-ring">
                            <WifiIcon size={34} />
                        </div>
                        <h1 className="nn-login-brand">
                            Ningrat<span>Net</span>
                        </h1>
                        <p className="nn-login-tagline">Internet Pilihan Masyarakat</p>
                        <div className="nn-login-pills">
                            <span className="nn-login-pill"><ZapIcon /> Kecepatan Tinggi</span>
                            <span className="nn-login-pill"><ShieldIcon size={10} /> Aman &amp; Stabil</span>
                        </div>
                    </div>

                    {/* ── Card ── */}
                    <div className="nn-login-card">
                        <div className="nn-login-card-bar" />

                        <div className="nn-login-badge">
                            <ShieldIcon size={12} />
                            Koneksi Aman &amp; Terenkripsi
                        </div>

                        <h2 className="nn-login-card-title">Masuk ke Akun Anda</h2>
                        <p className="nn-login-card-sub">Kelola layanan internet Anda</p>

                        <form onSubmit={submit} autoComplete="off">

                            {/* Username */}
                            <label htmlFor="username" className="nn-login-label">
                                Username / ID Pelanggan
                            </label>
                            <div className="nn-login-input-wrap">
                                <span className="nn-login-input-icon"><UserIcon size={18} /></span>
                                <input
                                    id="username"
                                    name="username"
                                    type="text"
                                    autoComplete="username"
                                    autoCorrect="off"
                                    autoCapitalize="none"
                                    spellCheck="false"
                                    required
                                    value={data.username}
                                    onChange={(e) => setData('username', e.target.value)}
                                    className={`nn-login-input${errors.username ? ' nn-login-input-err' : ''}`}
                                    placeholder="Masukkan username atau ID Anda"
                                />
                            </div>
                            {errors.username && (
                                <div className="nn-login-error">
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="#fca5a5" style={{ flexShrink: 0, marginTop: 1 }}>
                                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                                    </svg>
                                    <span>{errors.username}</span>
                                </div>
                            )}

                            {/* Password */}
                            <label htmlFor="password" className="nn-login-label">
                                Password
                            </label>
                            <div className="nn-login-input-wrap">
                                <span className="nn-login-input-icon"><LockIcon size={18} /></span>
                                <input
                                    id="password"
                                    name="password"
                                    type={showPassword ? 'text' : 'password'}
                                    autoComplete="current-password"
                                    required
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    className={`nn-login-input nn-login-input-pr${errors.password ? ' nn-login-input-err' : ''}`}
                                    placeholder="Masukkan password Anda"
                                />
                                <button
                                    type="button"
                                    className="nn-login-eye-btn"
                                    onClick={() => setShowPassword(!showPassword)}
                                    tabIndex={-1}
                                    aria-label={showPassword ? 'Sembunyikan' : 'Tampilkan'}
                                >
                                    {showPassword ? <EyeSlashIcon size={20} /> : <EyeIcon size={20} />}
                                </button>
                            </div>
                            {errors.password && (
                                <div className="nn-login-error">
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="#fca5a5" style={{ flexShrink: 0, marginTop: 1 }}>
                                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                                    </svg>
                                    <span>{errors.password}</span>
                                </div>
                            )}

                            {/* Submit */}
                            <button
                                type="submit"
                                disabled={processing}
                                className="nn-login-submit"
                            >
                                {processing ? (
                                    <>
                                        <svg className="nn-login-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle style={{ opacity: 0.25 }} cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                            <path style={{ opacity: 0.75 }} fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                        </svg>
                                        Memproses Login...
                                    </>
                                ) : (
                                    <>
                                        <LockIcon size={17} />
                                        Masuk Sekarang
                                    </>
                                )}
                            </button>
                        </form>

                        <div className="nn-login-divider">
                            <div className="nn-login-divider-line" />
                            <span className="nn-login-divider-text">info akun</span>
                            <div className="nn-login-divider-line" />
                        </div>

                        <p className="nn-login-help">
                            <small>Butuh bantuan? Hubungi CS NingratNet</small>
                        </p>
                    </div>

                    {/* Footer */}
                    <div className="nn-login-footer">
                        <p className="nn-login-footer-text">
                            © {new Date().getFullYear()} NingratNet &nbsp;·&nbsp; Dilindungi enkripsi SSL
                        </p>
                    </div>

                </div>
            </div>
        </>
    );
}
