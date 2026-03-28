import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { route } from 'ziggy-js';
import './Dashboard.css'

// ─── Icon Components ──────────────────────────────────────────────────────────

const UserCircleIcon = ({ className = "h-6 w-6" }) => (
    <svg xmlns="http://www.w3.org/2000/svg" className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
);

const ShieldCheckIcon = ({ className = "h-6 w-6" }) => (
    <svg xmlns="http://www.w3.org/2000/svg" className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.623 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
    </svg>
);

const WifiIcon = ({ className = "h-6 w-6" }) => (
    <svg xmlns="http://www.w3.org/2000/svg" className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z" />
    </svg>
);

const BillIcon = ({ className = "h-6 w-6" }) => (
    <svg xmlns="http://www.w3.org/2000/svg" className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
    </svg>
);

const PackageIcon = ({ className = "h-6 w-6" }) => (
    <svg xmlns="http://www.w3.org/2000/svg" className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
    </svg>
);

const SpeedIcon = ({ className = "h-6 w-6" }) => (
    <svg xmlns="http://www.w3.org/2000/svg" className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
    </svg>
);

const CalendarIcon = ({ className = "h-6 w-6" }) => (
    <svg xmlns="http://www.w3.org/2000/svg" className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5m-9-6h.008v.008H12V13.5z" />
    </svg>
);

const PhoneIcon = ({ className = "h-6 w-6" }) => (
    <svg xmlns="http://www.w3.org/2000/svg" className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
    </svg>
);

const ExclamationIcon = ({ className = "h-5 w-5" }) => (
    <svg xmlns="http://www.w3.org/2000/svg" className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
    </svg>
);

const CheckCircleIcon = ({ className = "h-5 w-5" }) => (
    <svg xmlns="http://www.w3.org/2000/svg" className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
);

const ServerIcon = ({ className = "h-5 w-5" }) => (
    <svg xmlns="http://www.w3.org/2000/svg" className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M21.75 17.25v.75a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25v-.75m19.5 0A2.25 2.25 0 0019.5 15h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 19.409a2.25 2.25 0 01-1.07-1.916V17.25m19.5-9.75a2.25 2.25 0 00-2.25-2.25h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.409A2.25 2.25 0 012.25 6.493V6.75" />
    </svg>
);

const SupportIcon = ({ className = "h-5 w-5" }) => (
    <svg xmlns="http://www.w3.org/2000/svg" className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5.25 0a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
    </svg>
);

const ArrowRightIcon = ({ className = "h-4 w-4" }) => (
    <svg xmlns="http://www.w3.org/2000/svg" className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
    </svg>
);

// ─── Main Dashboard Component ─────────────────────────────────────────────────

export default function Dashboard({ pelanggan, statusLangganan, unpaid_invoices, pending_va, isolir_at }) {

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    const formatDate = (dateString) => {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    };

    const isActive = !isolir_at;
    const nextDueDate = unpaid_invoices && unpaid_invoices.length > 0
        ? formatDate(unpaid_invoices[0].due_date)
        : null;

    return (
        <AuthenticatedLayout>
            <Head title="Dashboard" />

            <div className="nn-root">

                {/* ── HERO HEADER ── */}
                <header className="nn-hero">
                    <div className="nn-hero-glow" />
                    <div className="nn-hero-glow-2" />
                    <div className="nn-container" style={{ position: 'relative', zIndex: 1 }}>

                        {/* Hero stats grid */}
                        <div className="nn-hero-grid">

                            {/* Welcome */}
                            <div className="nn-welcome-block">
                                <div className="nn-welcome-label">Selamat Datang Kembali</div>
                                <div className="nn-welcome-name">{pelanggan.nama}</div>
                                <div className="nn-welcome-id">
                                    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 12, height: 12 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                                    </svg>
                                    ID: {pelanggan.kode_unik}
                                </div>
                            </div>

                            {/* Speed stat */}
                            {/* <div className="nn-hero-stat">
                                <div className="nn-hero-stat-icon">
                                    <SpeedIcon className="h-5 w-5" />
                                </div>
                                <div>
                                    <div className="nn-hero-stat-label">Kecepatan Paket</div>
                                    <div className="nn-hero-stat-value">
                                        {pelanggan.package ? pelanggan.package.name : 'Tidak Ada Paket'}
                                    </div>
                                </div>
                            </div> */}

                            {/* Due date + support stacked */}
                            {/* <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
                                <div className="nn-hero-stat">
                                    <div className="nn-hero-stat-icon" style={{ background: 'linear-gradient(135deg,#f59e0b,#d97706)', boxShadow: '0 4px 15px rgba(245,158,11,0.35)' }}>
                                        <CalendarIcon className="h-5 w-5" />
                                    </div>
                                    <div>
                                        <div className="nn-hero-stat-label">Jatuh Tempo</div>
                                        <div className="nn-hero-stat-value" style={{ fontSize: '0.85rem' }}>
                                            {nextDueDate ? nextDueDate : 'Tidak ada tagihan'}
                                        </div>
                                    </div>
                                </div>
                                <div className="nn-hero-stat">
                                    <div className="nn-hero-stat-icon" style={{ background: 'linear-gradient(135deg,#10b981,#059669)', boxShadow: '0 4px 15px rgba(16,185,129,0.35)' }}>
                                        <PhoneIcon className="h-5 w-5" />
                                    </div>
                                    <div>
                                        <div className="nn-hero-stat-label">Support 24/7</div>
                                        <div className="nn-hero-stat-value" style={{ fontSize: '0.85rem' }}>Siap Membantu Anda</div>
                                    </div>
                                </div>
                            </div> */}

                        </div>
                    </div>
                </header>

                {/* ── MAIN CONTENT ── */}
                <main className="nn-main">
                    <div className="nn-container">

                        {/* Pending VA Alert */}
                        {pending_va && (
                            <div className="nn-alert-pending" role="alert">
                                <div className="nn-alert-pending-icon">
                                    <ExclamationIcon className="h-5 w-5" />
                                </div>
                                <div>
                                    <div className="nn-alert-pending-title">Pembayaran Belum Selesai</div>
                                    <div className="nn-alert-pending-sub">
                                        Selesaikan pembayaran untuk invoice #{pending_va.invoice.invoice_number} sebelum waktu habis.
                                    </div>
                                    <Link
                                        href={route('customer.pending-payment.show', pending_va.id)}
                                        className="nn-alert-link"
                                    >
                                        Lihat Detail Pembayaran <ArrowRightIcon className="h-3 w-3" />
                                    </Link>
                                </div>
                            </div>
                        )}

                        {/* ── ROW 2: Unpaid Invoices ── */}
                        {unpaid_invoices && unpaid_invoices.length > 0 && (
                            <>
                                <div className="nn-section-header">
                                    <div className="nn-section-title">Tagihan Belum Dibayar</div>
                                    <div className="nn-section-divider" />
                                </div>

                                <div className="nn-invoice-card">
                                    <div className="nn-invoice-header">
                                        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                                            <div style={{ width: 32, height: 32, borderRadius: 10, background: 'rgba(255,180,0,0.12)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#f59e0b' }}>
                                                <BillIcon className="h-4 w-4" />
                                            </div>
                                            <span style={{ fontSize: '0.85rem', fontWeight: 700, color: 'var(--text-secondary)' }}>
                                                {unpaid_invoices.length} Tagihan Tertunggak
                                            </span>
                                        </div>
                                        {unpaid_invoices.length > 1 && (
                                            <Link
                                                href={route('customer.invoices.index', { status: 'unpaid' })}
                                                className="nn-more-link"
                                            >
                                                Lihat Semua <ArrowRightIcon className="h-3 w-3" />
                                            </Link>
                                        )}
                                    </div>

                                    {/* First invoice row */}
                                    <div className="nn-invoice-row">
                                        <div>
                                            <div className="nn-due-badge">
                                                <CalendarIcon className="h-3 w-3" />
                                                Jatuh Tempo: {formatDate(unpaid_invoices[0].due_date)}
                                            </div>
                                            <div className="nn-invoice-amount">
                                                {formatRupiah(unpaid_invoices[0].balance_due)}
                                            </div>
                                        </div>
                                        <Link
                                            href={route('customer.invoices.show', unpaid_invoices[0].id)}
                                            className="nn-pay-btn"
                                        >
                                            <BillIcon className="h-4 w-4" />
                                            Bayar Sekarang
                                        </Link>
                                    </div>

                                    {unpaid_invoices.length > 1 && (
                                        <div className="nn-more-invoices">
                                            <Link
                                                href={route('customer.invoices.index', { status: 'unpaid' })}
                                                className="nn-more-link"
                                            >
                                                Lihat {unpaid_invoices.length - 1} tagihan lainnya
                                                <ArrowRightIcon className="h-3 w-3" />
                                            </Link>
                                        </div>
                                    )}
                                </div>
                            </>
                        )}

                        {/* ── ROW 1: Status + Account + Package ── */}
                        <div className="nn-section-header">
                            <div className="nn-section-title">Informasi Akun</div>
                            <div className="nn-section-divider" />
                        </div>

                        <div className="nn-top-row">

                            {/* Connection Status */}
                            <div className={`nn-status-card ${isActive ? 'active' : 'isolir'}`}>
                                <div className={`nn-status-icon-wrap ${isActive ? 'active' : 'isolir'}`}>
                                    <ShieldCheckIcon className="h-6 w-6" />
                                </div>
                                <div>
                                    <div className="nn-status-label">Status Koneksi</div>
                                    <div className={`nn-status-badge ${isActive ? 'active' : 'isolir'}`}>
                                        <span className={`nn-pulse-dot ${isActive ? 'active' : 'isolir'}`} />
                                        {isActive ? 'Aktif & Terhubung' : `Isolir: ${isolir_at}`}
                                    </div>
                                    <div style={{ marginTop: 6, fontSize: '0.72rem', color: 'var(--text-secondary)' }}>
                                        {isActive ? 'Koneksi berjalan normal' : 'Hubungi support untuk aktivasi'}
                                    </div>
                                </div>
                            </div>

                            {/* Account Info */}
                            {/* <div className="nn-card">
                                <div className="nn-info-card-header">
                                    <div className="nn-info-icon orange">
                                        <UserCircleIcon className="h-5 w-5" />
                                    </div>
                                    <div>
                                        <div className="nn-info-title">Informasi Akun</div>
                                        <div className="nn-info-value">{pelanggan.username}</div>
                                    </div>
                                </div>
                                <div style={{ borderTop: '1px solid rgba(255,255,255,0.06)', paddingTop: 12 }}>
                                    <div style={{ fontSize: '0.7rem', fontWeight: 600, color: 'rgba(255,255,255,0.35)', textTransform: 'uppercase', letterSpacing: '0.08em', marginBottom: 4 }}>
                                        ID Pelanggan
                                    </div>
                                    <div className="nn-id-chip">{pelanggan.kode_unik}</div>
                                </div>
                            </div> */}

                            {/* Package Info */}
                            <div className="nn-card">
                                <div className="nn-info-card-header">
                                    <div className="nn-info-icon amber">
                                        <PackageIcon className="h-5 w-5" />
                                    </div>
                                    <div>
                                        <div className="nn-info-title">Paket Aktif</div>
                                        <div className="nn-info-value">
                                            {pelanggan.package ? pelanggan.package.name : 'Tidak Ada Paket'}
                                        </div>
                                    </div>
                                </div>
                                {pelanggan.package && (
                                    <div style={{ borderTop: '1px solid var(--border-color)', paddingTop: 12 }}>
                                        <div style={{ fontSize: '0.7rem', fontWeight: 600, color: 'var(--text-secondary)', textTransform: 'uppercase', letterSpacing: '0.08em', marginBottom: 4 }}>
                                            Biaya Bulanan
                                        </div>
                                        <div className="nn-price-tag">
                                            <span className="nn-price-amount">{formatRupiah(pelanggan.package.price)}</span>
                                            <span className="nn-price-period">/ bulan</span>
                                        </div>
                                    </div>
                                )}
                            </div>

                        </div>

                        {/* ── ROW 3: Service Status Strip ── */}
                        <div className="nn-section-header">
                            <div className="nn-section-title">Status Layanan</div>
                            <div className="nn-section-divider" />
                        </div>

                        <div className="nn-service-strip">
                            <div className="nn-service-item">
                                <div className="nn-service-dot-wrap green">
                                    <ServerIcon className="h-4 w-4" />
                                </div>
                                <div>
                                    <div className="nn-service-name">Server Online</div>
                                    <div className="nn-service-desc">99.9% Uptime</div>
                                </div>
                            </div>
                            <div className="nn-service-item">
                                <div className="nn-service-dot-wrap blue">
                                    <WifiIcon className="h-4 w-4" />
                                </div>
                                <div>
                                    <div className="nn-service-name">Koneksi Stabil</div>
                                    <div className="nn-service-desc">Latensi Rendah</div>
                                </div>
                            </div>
                            <div className="nn-service-item">
                                <div className="nn-service-dot-wrap orange">
                                    <SupportIcon className="h-4 w-4" />
                                </div>
                                <div>
                                    <div className="nn-service-name">Support 24/7</div>
                                    <div className="nn-service-desc">Siap Membantu</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </main>

                {/* ── FOOTER STRIP ── */}
                <div className="nn-footer-strip">
                    © {new Date().getFullYear()} <span className="nn-footer-brand">Ningrat Net</span> — Your Premium Internet Provider. All rights reserved.
                </div>

            </div>
        </AuthenticatedLayout>
    );
}