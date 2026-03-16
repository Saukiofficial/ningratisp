import React, { useState } from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';

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

// ── CSS ────────────────────────────────────────────────────────────────────

const css = `
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

  *, *::before, *::after {
    box-sizing: border-box;
    -webkit-tap-highlight-color: transparent;
  }
  body { margin: 0; padding: 0; }

  /* ── Page & Background ── */
  .nn-page {
    min-height: 100dvh;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
    padding: 20px 16px;
    position: relative;
    overflow-x: hidden;
  }

  /* Mobile first — background mobile */
  .nn-bg {
    position: fixed;
    inset: 0;
    background-image: url('/assets/img/background-mobile.webp');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    z-index: 0;
  }

  /* Desktop (≥768px) — background desktop */
  @media (min-width: 768px) {
    .nn-bg {
      background-image: url('/assets/img/background-desktop.webp');
      background-position: center top;
    }
  }

  /* Very subtle overlay — background stays visible */
  .nn-overlay {
    position: fixed;
    inset: 0;
    background: linear-gradient(
      160deg,
      rgba(160, 50, 0, 0.38) 0%,
      rgba(80, 20, 0, 0.32) 50%,
      rgba(0, 0, 0, 0.42) 100%
    );
    z-index: 1;
  }

  .nn-glow-tr {
    position: fixed;
    top: -100px; right: -80px;
    width: 300px; height: 300px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,110,10,0.28) 0%, transparent 65%);
    z-index: 2;
    animation: floatA 9s ease-in-out infinite;
    pointer-events: none;
  }

  .nn-glow-bl {
    position: fixed;
    bottom: -100px; left: -80px;
    width: 280px; height: 280px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,70,0,0.20) 0%, transparent 65%);
    z-index: 2;
    animation: floatB 12s ease-in-out infinite;
    pointer-events: none;
  }

  .nn-container {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 400px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  /* ── Header ── */
  .nn-header {
    text-align: center;
    margin-bottom: 22px;
    animation: fadeSlideDown 0.6s ease-out both;
  }

  .nn-wifi-ring {
    width: 70px; height: 70px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.08);
    border: 1.5px solid rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 15px;
    color: #FFB347;
    box-shadow: 0 0 0 10px rgba(255,140,0,0.08), 0 4px 20px rgba(0,0,0,0.20);
    animation: ringPulse 3.5s ease-in-out infinite;
  }

  .nn-brand {
    font-size: 34px;
    font-weight: 900;
    color: #fff;
    letter-spacing: -0.5px;
    margin: 0 0 5px;
    text-shadow: 0 2px 14px rgba(0,0,0,0.40);
    line-height: 1;
  }
  .nn-brand span { color: #FFB347; }

  .nn-tagline {
    font-size: 11px;
    font-weight: 700;
    color: rgba(255,255,255,0.55);
    letter-spacing: 3.5px;
    text-transform: uppercase;
    margin: 0 0 16px;
  }

  .nn-pills {
    display: flex; gap: 8px;
    justify-content: center; flex-wrap: wrap;
  }

  .nn-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px;
    border-radius: 99px;
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.18);
    color: rgba(255,255,255,0.82);
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }

  /* ── Glass Card ── */
  .nn-card {
    width: 100%;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(28px);
    -webkit-backdrop-filter: blur(28px);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 26px;
    padding: 32px 26px 26px;
    position: relative;
    box-shadow:
      0 8px 40px rgba(0,0,0,0.28),
      inset 0 1.5px 0 rgba(255,255,255,0.15),
      inset 0 -1px 0 rgba(0,0,0,0.06);
    animation: fadeSlideUp 0.65s ease-out 0.1s both;
  }

  /* Shimmer top bar */
  .nn-card-bar {
    position: absolute;
    top: 0; left: 20px; right: 20px;
    height: 2.5px;
    border-radius: 0 0 4px 4px;
    background: linear-gradient(90deg, transparent, #FF8C00 30%, #FFD580 55%, #FF8C00 80%, transparent);
    background-size: 300% 100%;
    animation: shimmer 3.5s linear infinite;
  }

  /* Security badge */
  .nn-badge {
    position: absolute;
    top: -14px; left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #22c55e, #15803d);
    color: #fff;
    padding: 5px 14px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
    display: inline-flex; align-items: center; gap: 5px;
    white-space: nowrap;
    box-shadow: 0 3px 14px rgba(34,197,94,0.40);
  }

  .nn-card-title {
    font-size: 19px;
    font-weight: 800;
    color: #fff;
    text-align: center;
    margin: 14px 0 3px;
    text-shadow: 0 1px 8px rgba(0,0,0,0.20);
  }

  .nn-card-sub {
    font-size: 13px;
    color: rgba(255,255,255,0.50);
    text-align: center;
    margin: 0 0 22px;
    font-weight: 500;
  }

  /* ── Form ── */
  .nn-label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: rgba(255,255,255,0.72);
    margin-bottom: 8px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }

  .nn-input-wrap {
    position: relative;
    display: flex; align-items: center;
    margin-bottom: 16px;
  }

  .nn-input-icon {
    position: absolute; left: 14px;
    color: #FFB347;
    pointer-events: none;
    display: flex; align-items: center;
  }

  .nn-input {
    width: 100%;
    padding: 13px 14px 13px 46px;
    background: rgba(255,255,255,0.09);
    border: 1.5px solid rgba(255,255,255,0.16);
    border-radius: 13px;
    font-size: 14.5px;
    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
    font-weight: 500;
    color: #fff;
    outline: none;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
    -webkit-appearance: none; appearance: none;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
  }
  .nn-input::placeholder {
    color: rgba(255,255,255,0.28);
    font-size: 13px;
  }
  .nn-input:focus {
    border-color: #FF8C00 !important;
    background: rgba(255,255,255,0.13) !important;
    box-shadow: 0 0 0 3px rgba(255,140,0,0.18) !important;
  }
  .nn-input-pr { padding-right: 46px; }
  .nn-input-err { border-color: rgba(239,68,68,0.55) !important; }

  .nn-eye-btn {
    position: absolute; right: 14px;
    background: none; border: none; cursor: pointer;
    color: rgba(255,255,255,0.38);
    padding: 0; display: flex; align-items: center;
    transition: color 0.2s;
  }
  .nn-eye-btn:hover { color: #FFB347; }

  /* Error */
  .nn-error {
    display: flex; align-items: flex-start; gap: 7px;
    margin-top: -10px; margin-bottom: 14px;
    padding: 9px 12px;
    background: rgba(239,68,68,0.14);
    border: 1px solid rgba(239,68,68,0.30);
    border-radius: 10px;
    backdrop-filter: blur(6px);
  }
  .nn-error span {
    font-size: 12px; font-weight: 600;
    color: #fca5a5; line-height: 1.4;
  }

  /* Submit */
  .nn-submit {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #FF6B00 0%, #FF9500 50%, #FF6B00 100%);
    background-size: 200% 200%;
    border: none;
    border-radius: 14px;
    color: #fff;
    font-size: 15.5px;
    font-weight: 800;
    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    margin-top: 6px;
    letter-spacing: 0.3px;
    box-shadow: 0 6px 22px rgba(255,107,0,0.48), inset 0 1px 0 rgba(255,255,255,0.18);
    transition: transform 0.15s, box-shadow 0.15s;
    animation: gradientPan 4s ease infinite;
    touch-action: manipulation;
  }
  .nn-submit:not(:disabled):hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(255,107,0,0.58), inset 0 1px 0 rgba(255,255,255,0.18);
  }
  .nn-submit:not(:disabled):active { transform: scale(0.98); }
  .nn-submit:disabled { opacity: 0.58; cursor: not-allowed; }

  /* Divider */
  .nn-divider {
    display: flex; align-items: center; gap: 10px;
    margin: 20px 0;
  }
  .nn-divider-line { flex: 1; height: 1px; background: rgba(255,255,255,0.12); }
  .nn-divider-text {
    font-size: 10px; font-weight: 700;
    color: rgba(255,255,255,0.30);
    letter-spacing: 1.5px; text-transform: uppercase; white-space: nowrap;
  }

  /* Help */
  .nn-help {
    text-align: center;
    font-size: 12.5px;
    color: rgba(255,255,255,0.45);
    line-height: 1.65; font-weight: 500;
  }
  .nn-help strong { color: #FFB347; font-weight: 700; }
  .nn-help small {
    display: block; margin-top: 5px;
    font-size: 11px; color: rgba(255,255,255,0.30);
  }

  /* Footer */
  .nn-footer {
    text-align: center;
    margin-top: 18px;
    animation: fadeIn 1s ease-out 0.4s both;
  }
  .nn-footer-text {
    font-size: 11px;
    color: rgba(255,255,255,0.32);
    font-weight: 500; letter-spacing: 0.3px;
  }

  /* Test bar */
  .nn-testbar {
    position: fixed; top: 0; left: 0; width: 100%;
    background: linear-gradient(90deg, #dc2626, #b91c1c);
    color: #fff; text-align: center;
    padding: 7px 16px;
    font-size: 10.5px; font-weight: 800;
    letter-spacing: 2px; text-transform: uppercase;
    z-index: 9999;
    box-shadow: 0 2px 10px rgba(220,38,38,0.45);
  }

  /* Spinner */
  .nn-spin { width: 18px; height: 18px; animation: spin 0.8s linear infinite; }

  /* ── Keyframes ── */
  @keyframes fadeSlideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  @keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
  @keyframes floatA {
    0%,100% { transform: translate(0,0); }
    45%      { transform: translate(18px,-20px) scale(1.05); }
  }
  @keyframes floatB {
    0%,100% { transform: translate(0,0); }
    40%      { transform: translate(-15px,-18px) scale(1.04); }
  }
  @keyframes shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
  }
  @keyframes gradientPan {
    0%,100% { background-position: 0% 50%; }
    50%      { background-position: 100% 50%; }
  }
  @keyframes spin { to { transform: rotate(360deg); } }
  @keyframes ringPulse {
    0%,100% { box-shadow: 0 0 0 10px rgba(255,140,0,0.08), 0 4px 20px rgba(0,0,0,0.20); }
    50%      { box-shadow: 0 0 0 16px rgba(255,140,0,0.04), 0 4px 20px rgba(0,0,0,0.20); }
  }

  /* Responsive */
  @media (max-width: 360px) {
    .nn-card { padding: 28px 18px 22px; }
    .nn-brand { font-size: 28px; }
  }
`;

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
            <style dangerouslySetInnerHTML={{ __html: css }} />

            {appEnv !== 'production' && (
                <div className="nn-testbar">⚠ &nbsp; TESTING APP &nbsp; ⚠</div>
            )}

            <div className="nn-bg" />
            <div className="nn-overlay" />
            <div className="nn-glow-tr" />
            <div className="nn-glow-bl" />

            <div
                className="nn-page"
                style={{ paddingTop: appEnv !== 'production' ? '54px' : '20px' }}
            >
                <div className="nn-container">

                    {/* ── Header ── */}
                    <div className="nn-header">
                        <div className="nn-wifi-ring">
                            <WifiIcon size={34} />
                        </div>
                        <h1 className="nn-brand">
                            Ningrat<span>Net</span>
                        </h1>
                        <p className="nn-tagline">Internet Pilihan Masyarakat</p>
                        <div className="nn-pills">
                            <span className="nn-pill"><ZapIcon /> Kecepatan Tinggi</span>
                            <span className="nn-pill"><ShieldIcon size={10} /> Aman &amp; Stabil</span>
                        </div>
                    </div>

                    {/* ── Card ── */}
                    <div className="nn-card">
                        <div className="nn-card-bar" />

                        <div className="nn-badge">
                            <ShieldIcon size={12} />
                            Koneksi Aman &amp; Terenkripsi
                        </div>

                        <h2 className="nn-card-title">Masuk ke Akun Anda</h2>
                        <p className="nn-card-sub">Kelola layanan internet Anda</p>

                        <form onSubmit={submit} autoComplete="off">

                            {/* Username */}
                            <label htmlFor="username" className="nn-label">
                                Username / ID Pelanggan
                            </label>
                            <div className="nn-input-wrap">
                                <span className="nn-input-icon"><UserIcon size={18} /></span>
                                <input
                                    id="username"
                                    name="username"
                                    type="text"
                                    autoComplete="off"
                                    autoCorrect="off"
                                    autoCapitalize="none"
                                    spellCheck="false"
                                    required
                                    value={data.username}
                                    onChange={(e) => setData('username', e.target.value)}
                                    className={`nn-input${errors.username ? ' nn-input-err' : ''}`}
                                    placeholder="Masukkan username atau ID Anda"
                                />
                            </div>
                            {errors.username && (
                                <div className="nn-error">
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="#fca5a5" style={{ flexShrink: 0, marginTop: 1 }}>
                                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                                    </svg>
                                    <span>{errors.username}</span>
                                </div>
                            )}

                            {/* Password */}
                            <label htmlFor="password" className="nn-label">
                                Password
                            </label>
                            <div className="nn-input-wrap">
                                <span className="nn-input-icon"><LockIcon size={18} /></span>
                                <input
                                    id="password"
                                    name="password"
                                    type={showPassword ? 'text' : 'password'}
                                    autoComplete="new-password"
                                    required
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    className={`nn-input nn-input-pr${errors.password ? ' nn-input-err' : ''}`}
                                    placeholder="Masukkan password Anda"
                                />
                                <button
                                    type="button"
                                    className="nn-eye-btn"
                                    onClick={() => setShowPassword(!showPassword)}
                                    tabIndex={-1}
                                    aria-label={showPassword ? 'Sembunyikan' : 'Tampilkan'}
                                >
                                    {showPassword ? <EyeSlashIcon size={20} /> : <EyeIcon size={20} />}
                                </button>
                            </div>
                            {errors.password && (
                                <div className="nn-error">
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
                                className="nn-submit"
                            >
                                {processing ? (
                                    <>
                                        <svg className="nn-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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

                        <div className="nn-divider">
                            <div className="nn-divider-line" />
                            <span className="nn-divider-text">info akun</span>
                            <div className="nn-divider-line" />
                        </div>

                        <p className="nn-help">
                            <small>Butuh bantuan? Hubungi CS NingratNet</small>
                        </p>
                    </div>

                    {/* Footer */}
                    <div className="nn-footer">
                        <p className="nn-footer-text">
                            © {new Date().getFullYear()} NingratNet &nbsp;·&nbsp; Dilindungi enkripsi SSL
                        </p>
                    </div>

                </div>
            </div>
        </>
    );
}