import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { route } from 'ziggy-js';
import './Show.css';

const BackIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 14, height: 14 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
    </svg>
);

const PayIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 16, height: 16 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
    </svg>
);

const BuildingIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 16, height: 16 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
    </svg>
);

const ReceiptIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 16, height: 16 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
    </svg>
);

const HistoryIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 16, height: 16 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
);

const BankIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 16, height: 16 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
    </svg>
);

const CalendarIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 13, height: 13 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5" />
    </svg>
);

const WarningIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 18, height: 18 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
    </svg>
);

const ArrowRightIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 13, height: 13 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
    </svg>
);

export default function Show({ invoice }) {

    const formatRupiah = (number) => new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
    }).format(number);

    const formatDate = (dateString, showTime = true) => {
        if (!dateString) return '-';

        const date = new Date(dateString);

        const datePart = date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });

        if (!showTime) return datePart;

        const pad = (n) => String(n).padStart(2, '0');

        const timePart = `${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;

        return `${datePart}, ${timePart}`;
    };

    const isPaid = invoice.status === 'paid';

    return (
        <AuthenticatedLayout>
            <Head title={`Invoice #${invoice.invoice_number}`} />

            <div className="sh-root">
                <div className="sh-container">

                    {/* ── Back ── */}
                    <Link href={route('customer.invoices.index')} className="sh-back">
                        <BackIcon /> Kembali ke Daftar Tagihan
                    </Link>

                    {/* ── Invoice Header Card ── */}
                    <div className="sh-header-card">
                        <div className="sh-header-glow" />
                        <div className="sh-header-top">
                            <div>
                                <div className="sh-invoice-label">Invoice</div>
                                <div className="sh-invoice-number">#{invoice.invoice_number}</div>
                            </div>
                            <div style={{ textAlign: 'right' }}>
                                <span className={`sh-status-badge ${isPaid ? 'paid' : 'unpaid'}`}>
                                    <span className="sh-status-dot" />
                                    {isPaid ? 'Lunas' : 'Belum Dibayar'}
                                </span>
                                {isPaid && (
                                    <div className="sh-paid-on">Lunas pada {formatDate(invoice.paid_date)}</div>
                                )}
                            </div>
                        </div>
                        <div className="sh-dates">
                            <div className="sh-date-chip">
                                <CalendarIcon /> Tanggal Invoice: <span>{formatDate(invoice.invoice_date, false)}</span>
                            </div>
                            <div className="sh-date-chip">
                                <CalendarIcon /> Jatuh Tempo: <span>{formatDate(invoice.due_date, false)}</span>
                            </div>
                        </div>
                    </div>

                    {/* ── From ── */}
                    <div className="sh-card">
                        <div className="sh-card-header">
                            <div className="sh-card-icon"><BuildingIcon /></div>
                            <div className="sh-card-title">Ditagihkan Oleh</div>
                        </div>
                        <div className="sh-from-body">
                            <div className="sh-from-name">NingratNet</div>
                            <div className="sh-from-line">
                                Dusun Gutoguh, Poreh Kec. Lenteng<br />
                                Kab. Sumenep, 69461<br />
                                ningratisp@gmail.com
                            </div>
                        </div>
                    </div>

                    {/* ── Items ── */}
                    <div className="sh-card">
                        <div className="sh-card-header">
                            <div className="sh-card-icon"><ReceiptIcon /></div>
                            <div className="sh-card-title">Rincian Tagihan</div>
                        </div>
                        <div style={{ overflowX: 'auto' }}>
                            <table className="sh-table">
                                <thead>
                                    <tr>
                                        <th>Deskripsi</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {invoice.items.map((item) => (
                                        <tr key={item.id}>
                                            <td>{item.description}</td>
                                            <td>{formatRupiah(item.amount)}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        {/* Totals */}
                        <div className="sh-totals">
                            <div className="sh-total-row">
                                <span className="sh-total-label">Subtotal</span>
                                <span className="sh-total-value">{formatRupiah(invoice.subtotal)}</span>
                            </div>
                            {invoice.discount_amount > 0 && (
                                <div className="sh-total-row discount">
                                    <span className="sh-total-label">Diskon</span>
                                    <span className="sh-total-value">−{formatRupiah(invoice.discount_amount)}</span>
                                </div>
                            )}
                            <div className="sh-total-row" style={{ fontWeight: 700 }}>
                                <span style={{ color: 'var(--text-secondary)', fontWeight: 700 }}>Total</span>
                                <span style={{ color: 'var(--text-primary)', fontFamily: "'Sora',sans-serif", fontWeight: 800 }}>{formatRupiah(invoice.balance_due ?? invoice.subtotal)}</span>
                            </div>
                            <div className="sh-total-row paid-row">
                                <span className="sh-total-label">Sudah Dibayar</span>
                                <span className="sh-total-value">{formatRupiah(invoice.paid_amount)}</span>
                            </div>

                            <div className={`sh-balance-row ${isPaid ? 'settled' : ''}`}>
                                <span className="sh-balance-label">Sisa Tagihan</span>
                                <span className="sh-balance-value">{formatRupiah(invoice.balance_due)}</span>
                            </div>
                        </div>
                    </div>

                    {/* ── Payment History ── */}
                    {invoice.payment_allocations && invoice.payment_allocations.length > 0 && (
                        <div className="sh-card">
                            <div className="sh-card-header">
                                <div className="sh-card-icon" style={{ background: 'rgba(16,185,129,0.1)', color: '#10b981' }}><HistoryIcon /></div>
                                <div className="sh-card-title">Riwayat Pembayaran</div>
                            </div>
                            <div style={{ overflowX: 'auto' }}>
                                <table className="sh-pay-table">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Metode</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {invoice.payment_allocations.map((payment) => (
                                            <tr key={payment.id}>
                                                <td>{formatDate(payment.payment_date)}</td>
                                                <td>
                                                    <span className="sh-method-chip">
                                                        <BankIcon /> {payment.payment_method}
                                                    </span>
                                                </td>
                                                <td>{formatRupiah(payment.amount)}</td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    )}

                    {/* ── VA / Payment Instructions ── */}
                    {invoice.midtrans_transaction_id && invoice.status === 'unpaid' && (
                        <div className="sh-va-card">
                            <div className="sh-va-title">
                                <WarningIcon /> Instruksi Pembayaran
                            </div>
                            <p style={{ fontSize: '0.8rem', color: 'rgba(150,190,255,0.7)', marginBottom: 14 }}>
                                Segera selesaikan pembayaran Anda sebelum tanggal jatuh tempo.
                            </p>
                            <div className="sh-va-row">
                                <span className="sh-va-key">Metode Pembayaran</span>
                                <span className="sh-va-val">{invoice.midtrans_payment_type.replace('_', ' ').toUpperCase()}</span>
                            </div>
                            {invoice.midtrans_va_number && (
                                <div className="sh-va-row" style={{ alignItems: 'center' }}>
                                    <span className="sh-va-key">Nomor Virtual Account</span>
                                    <span className="sh-va-number">{invoice.midtrans_va_number}</span>
                                </div>
                            )}
                            <div className="sh-va-row">
                                <span className="sh-va-key">Total Pembayaran</span>
                                <span className="sh-va-val" style={{ color: '#ff8c00', fontFamily: "'Sora',sans-serif", fontSize: '1rem', fontWeight: 800 }}>{formatRupiah(invoice.balance_due)}</span>
                            </div>
                            <div className="sh-va-row">
                                <span className="sh-va-key">Batas Waktu</span>
                                <span className="sh-va-val">{formatDate(invoice.midtrans_expiry_time)}</span>
                            </div>
                        </div>
                    )}

                    {/* ── Footer Actions ── */}
                    <div className="sh-footer">
                        <Link href={route('customer.invoices.index')} className="sh-back-btn">
                            <BackIcon /> Kembali
                        </Link>
                        {invoice.status === 'unpaid' && (
                            <Link href={route('customer.invoices.checkout', { invoice: invoice.id })} className="sh-pay-btn">
                                <PayIcon /> Pilih Metode Pembayaran
                            </Link>
                        )}
                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
