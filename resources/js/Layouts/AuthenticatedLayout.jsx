import React from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { useFlash } from '@/Hooks/useFlash';

const HomeIcon = ({ isActive }) => (
    <svg className="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
    </svg>
);

const DocumentIcon = ({ isActive }) => (
    <svg className="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
        <path d="M16 14V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v15a3 3 0 0 0 3 3h12a1 1 0 0 0 0-2h-1v-2a2 2 0 0 0 2-2ZM4 2h2v12H4V2Zm8 16H3a1 1 0 0 1 0-2h9v2Z" />
    </svg>
);

const AccountIcon = ({ isActive }) => (
    <svg className="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
        <path d="M8 10a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm0 2c-4.42 0-8 2.24-8 5v1a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-1c0-2.76-3.58-5-8-5Z" />
    </svg>
);

const LogoutIcon = () => (
    <svg className="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
    </svg>
);

const WifiLogoIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5} style={{ width: 22, height: 22, color: 'white' }}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z" />
    </svg>
);

export default function AuthenticatedLayout({ children }) {
    const { url, props } = usePage();
    const { appEnv } = props;
    useFlash();

    const handleLogout = (e) => {
        e.preventDefault();
        router.post((route('customer.logout')));
    };

    return (
        <>
            <style>{`
                @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@400;600;700;800&display=swap');

                .nnl-root {
                    font-family: 'Plus Jakarta Sans', sans-serif;
                    background: #0f0f0f;
                    min-height: 100vh;
                    display: flex;
                    flex-direction: column;
                    padding-bottom: 68px;
                }
                @media (min-width: 640px) {
                    .nnl-root { padding-bottom: 0; }
                }

                /* ── Header ── */
                .nnl-header {
                    background: #0f0f0f;
                    border-bottom: 1px solid rgba(255,255,255,0.08);
                    position: sticky;
                    top: 0;
                    z-index: 50;
                    backdrop-filter: blur(12px);
                }
                .nnl-header-inner {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 0 16px;
                    height: 60px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 16px;
                }
                @media (min-width: 640px) {
                    .nnl-header-inner { padding: 0 24px; }
                }
                @media (min-width: 1024px) {
                    .nnl-header-inner { padding: 0 32px; }
                }

                /* Brand */
                .nnl-brand {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    text-decoration: none;
                    flex-shrink: 0;
                }
                .nnl-logo-wrap {
                    width: 36px;
                    height: 36px;
                    background: linear-gradient(135deg, #ff8c00, #ff6a00);
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 4px 12px rgba(255,110,0,0.4);
                    flex-shrink: 0;
                    overflow: hidden;
                }
                .nnl-logo-wrap img {
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                    padding: 4px;
                }
                .nnl-brand-text {
                    font-family: 'Sora', sans-serif;
                    font-size: 1.05rem;
                    font-weight: 800;
                    color: #ffffff;
                    letter-spacing: -0.5px;
                    line-height: 1;
                }
                .nnl-brand-sub {
                    font-size: 0.6rem;
                    font-weight: 500;
                    color: rgba(255,180,80,0.8);
                    letter-spacing: 0.08em;
                    text-transform: uppercase;
                    margin-top: 1px;
                }

                /* Desktop nav */
                .nnl-nav {
                    display: none;
                    align-items: center;
                    gap: 4px;
                }
                @media (min-width: 640px) {
                    .nnl-nav { display: flex; }
                }
                .nnl-nav-link {
                    display: inline-flex;
                    align-items: center;
                    gap: 7px;
                    padding: 7px 14px;
                    border-radius: 10px;
                    font-size: 0.82rem;
                    font-weight: 600;
                    color: rgba(255,255,255,0.5);
                    text-decoration: none;
                    transition: all 0.2s;
                    border: 1px solid transparent;
                    position: relative;
                }
                .nnl-nav-link:hover {
                    color: rgba(255,255,255,0.85);
                    background: rgba(255,255,255,0.05);
                }
                .nnl-nav-link.active {
                    color: #ff8c00;
                    background: rgba(255,140,0,0.1);
                    border-color: rgba(255,140,0,0.2);
                }
                .nnl-nav-link.active svg { color: #ff8c00; }

                /* Logout button */
                .nnl-logout-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 7px;
                    padding: 7px 16px;
                    border-radius: 10px;
                    font-size: 0.82rem;
                    font-weight: 700;
                    color: rgba(255,255,255,0.7);
                    background: rgba(239,68,68,0.1);
                    border: 1px solid rgba(239,68,68,0.2);
                    cursor: pointer;
                    transition: all 0.2s;
                    font-family: 'Plus Jakarta Sans', sans-serif;
                    white-space: nowrap;
                }
                .nnl-logout-btn:hover {
                    color: #fff;
                    background: rgba(239,68,68,0.2);
                    border-color: rgba(239,68,68,0.4);
                    box-shadow: 0 0 16px rgba(239,68,68,0.2);
                }

                /* ── Testing Banner ── */
                .nnl-test-banner {
                    background: linear-gradient(90deg, #7f1d1d, #991b1b, #7f1d1d);
                    color: rgba(255,255,255,0.9);
                    text-align: center;
                    padding: 6px 0;
                    font-size: 0.72rem;
                    font-weight: 700;
                    letter-spacing: 0.15em;
                    text-transform: uppercase;
                    border-bottom: 1px solid rgba(239,68,68,0.3);
                }

                /* ── Main content ── */
                .nnl-main {
                    flex-grow: 1;
                }

                /* ── Bottom nav (mobile) ── */
                .nnl-bottom-nav {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    z-index: 50;
                    height: 68px;
                    background: #161616;
                    border-top: 1px solid rgba(255,255,255,0.08);
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                }
                @media (min-width: 640px) {
                    .nnl-bottom-nav { display: none; }
                }
                .nnl-bottom-link {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    gap: 3px;
                    text-decoration: none;
                    position: relative;
                    transition: background 0.2s;
                    color: rgba(255,255,255,0.35);
                }
                .nnl-bottom-link:active { background: rgba(255,255,255,0.03); }
                .nnl-bottom-link.active {
                    color: #ff8c00;
                }
                .nnl-bottom-link.active::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 50%;
                    transform: translateX(-50%);
                    width: 36px;
                    height: 2px;
                    background: linear-gradient(90deg, #ff8c00, #ff6a00);
                    border-radius: 0 0 4px 4px;
                }
                .nnl-bottom-icon-wrap {
                    width: 36px;
                    height: 28px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 8px;
                    transition: all 0.2s;
                }
                .nnl-bottom-link.active .nnl-bottom-icon-wrap {
                    background: rgba(255,140,0,0.12);
                }
                .nnl-bottom-label {
                    font-size: 0.65rem;
                    font-weight: 600;
                    letter-spacing: 0.02em;
                }

                /* ── Footer ── */
                .nnl-footer {
                    display: none;
                    background: #0a0a0a;
                    border-top: 1px solid rgba(255,255,255,0.05);
                    padding: 14px 0;
                    text-align: center;
                    font-size: 0.7rem;
                    color: rgba(255,255,255,0.2);
                    font-weight: 500;
                }
                @media (min-width: 640px) {
                    .nnl-footer { display: block; }
                }
                .nnl-footer-brand {
                    color: rgba(255,140,0,0.5);
                    font-weight: 700;
                }
            `}</style>

            <div className="nnl-root">

                {/* ── Header ── */}
                <header className="nnl-header">
                    {appEnv !== 'production' && (
                        <div className="nnl-test-banner">⚠ Testing Environment — Not for Production Use</div>
                    )}
                    <div className="nnl-header-inner">

                        {/* Brand */}
                        <Link href={route('customer.dashboard')} className="nnl-brand">
                            <div className="nnl-logo-wrap">
                                <img
                                    src="/assets/img/logo.png"
                                    alt="Ningrat Net"
                                    onError={(e) => {
                                        e.target.style.display = 'none';
                                        e.target.parentElement.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:white;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z"/></svg>`;
                                    }}
                                />
                            </div>
                            <div>
                                <div className="nnl-brand-text">Ningrat Net</div>
                                <div className="nnl-brand-sub">Internet Premium</div>
                            </div>
                        </Link>

                        {/* Desktop Nav */}
                        <nav className="nnl-nav">
                            <Link
                                href={route('customer.dashboard')}
                                className={`nnl-nav-link ${url.startsWith('/dashboard') ? 'active' : ''}`}
                            >
                                <HomeIcon isActive={url.startsWith('/dashboard')} />
                                Dashboard
                            </Link>
                            <Link
                                href={route('customer.invoices.index')}
                                className={`nnl-nav-link ${url.startsWith('/tagihan') ? 'active' : ''}`}
                            >
                                <DocumentIcon isActive={url.startsWith('/tagihan')} />
                                Tagihan
                            </Link>
                            <Link
                                href={route('customer.account.edit')}
                                className={`nnl-nav-link ${url.startsWith('/account') ? 'active' : ''}`}
                            >
                                <AccountIcon isActive={url.startsWith('/account')} />
                                Akun
                            </Link>
                        </nav>

                        {/* Logout */}
                        <button onClick={handleLogout} type="button" className="nnl-logout-btn">
                            <LogoutIcon />
                            <span>Logout</span>
                        </button>

                    </div>
                </header>

                {/* ── Page Content ── */}
                <main className="nnl-main">{children}</main>

                {/* ── Bottom Nav (Mobile) ── */}
                <div className="nnl-bottom-nav">
                    <Link href={route('customer.dashboard')} className={`nnl-bottom-link ${url.startsWith('/dashboard') ? 'active' : ''}`}>
                        <div className="nnl-bottom-icon-wrap"><HomeIcon isActive={url.startsWith('/dashboard')} /></div>
                        <span className="nnl-bottom-label">Dashboard</span>
                    </Link>
                    <Link href={route('customer.invoices.index')} className={`nnl-bottom-link ${url.startsWith('/tagihan') ? 'active' : ''}`}>
                        <div className="nnl-bottom-icon-wrap"><DocumentIcon isActive={url.startsWith('/tagihan')} /></div>
                        <span className="nnl-bottom-label">Tagihan</span>
                    </Link>
                    <Link href={route('customer.account.edit')} className={`nnl-bottom-link ${url.startsWith('/account') ? 'active' : ''}`}>
                        <div className="nnl-bottom-icon-wrap"><AccountIcon isActive={url.startsWith('/account')} /></div>
                        <span className="nnl-bottom-label">Akun</span>
                    </Link>
                </div>

                {/* ── Footer ── */}
                <footer className="nnl-footer">
                    © {new Date().getFullYear()} <span className="nnl-footer-brand">Ningrat Net</span> — Your Premium Internet Provider. All rights reserved.
                </footer>

            </div>
        </>
    );
}