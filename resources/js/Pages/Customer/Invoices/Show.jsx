import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

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

export default function Show({ invoice }) {

    const formatRupiah = (number) => new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
    }).format(number);

    const formatDate = (dateString) => {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
    };

    const isPaid = invoice.status === 'paid';

    return (
        <AuthenticatedLayout>
            <Head title={`Invoice #${invoice.invoice_number}`} />

            <style>{`
                @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@400;600;700;800&display=swap');

                .sh-root {
                    font-family: 'Plus Jakarta Sans', sans-serif;
                    background: #0f0f0f;
                    min-height: 100vh;
                    padding: 28px 0 60px;
                }
                .sh-container {
                    max-width: 860px;
                    margin: 0 auto;
                    padding: 0 16px;
                }
                @media (min-width: 640px)  { .sh-container { padding: 0 24px; } }
                @media (min-width: 1024px) { .sh-container { padding: 0 32px; } }

                /* ── Back nav ── */
                .sh-back {
                    display: inline-flex;
                    align-items: center;
                    gap: 7px;
                    font-size: 0.8rem;
                    font-weight: 600;
                    color: rgba(255,255,255,0.4);
                    text-decoration: none;
                    margin-bottom: 20px;
                    transition: color 0.2s;
                }
                .sh-back:hover { color: #ff8c00; }

                /* ── Invoice header card ── */
                .sh-header-card {
                    background: linear-gradient(135deg, #1e0e00 0%, #2a1400 50%, #1a1a1a 100%);
                    border: 1px solid rgba(255,140,0,0.2);
                    border-radius: 22px;
                    padding: 24px 24px 20px;
                    margin-bottom: 16px;
                    position: relative;
                    overflow: hidden;
                }
                .sh-header-card::before {
                    content: '';
                    position: absolute;
                    top: 0; left: 0; right: 0; height: 1px;
                    background: linear-gradient(90deg, transparent, rgba(255,140,0,0.5), transparent);
                }
                .sh-header-glow {
                    position: absolute;
                    width: 300px; height: 300px; border-radius: 50%;
                    background: radial-gradient(circle, rgba(255,140,0,0.12) 0%, transparent 70%);
                    top: -100px; right: -80px; pointer-events: none;
                }
                .sh-header-top {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 16px;
                    flex-wrap: wrap;
                }
                .sh-invoice-label {
                    font-size: 0.7rem;
                    font-weight: 700;
                    letter-spacing: 0.12em;
                    text-transform: uppercase;
                    color: rgba(255,180,80,0.7);
                    margin-bottom: 4px;
                }
                .sh-invoice-number {
                    font-family: 'Sora', sans-serif;
                    font-size: clamp(1.3rem, 3vw, 1.8rem);
                    font-weight: 800;
                    color: #ffffff;
                    letter-spacing: -0.5px;
                }
                .sh-dates {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 12px;
                    margin-top: 14px;
                }
                .sh-date-chip {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 5px 12px;
                    border-radius: 8px;
                    background: rgba(0,0,0,0.3);
                    border: 1px solid rgba(255,255,255,0.08);
                    font-size: 0.75rem;
                    font-weight: 600;
                    color: rgba(255,255,255,0.6);
                }
                .sh-date-chip span { color: rgba(255,255,255,0.85); }

                /* ── Status badge ── */
                .sh-status-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 6px 14px;
                    border-radius: 50px;
                    font-size: 0.8rem;
                    font-weight: 700;
                    flex-shrink: 0;
                }
                .sh-status-badge.paid   { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }
                .sh-status-badge.unpaid { background: rgba(239,68,68,0.15);  color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
                .sh-status-dot { width: 7px; height: 7px; border-radius: 50%; }
                .sh-status-badge.paid   .sh-status-dot { background: #10b981; }
                .sh-status-badge.unpaid .sh-status-dot { background: #ef4444; }
                .sh-paid-on {
                    font-size: 0.72rem;
                    font-weight: 600;
                    color: rgba(52,211,153,0.7);
                    margin-top: 6px;
                    text-align: right;
                }

                /* ── Cards ── */
                .sh-card {
                    background: #1a1a1a;
                    border: 1px solid rgba(255,255,255,0.07);
                    border-radius: 20px;
                    margin-bottom: 16px;
                    position: relative;
                    overflow: hidden;
                }
                .sh-card::before {
                    content: '';
                    position: absolute;
                    top: 0; left: 0; right: 0; height: 1px;
                    background: linear-gradient(90deg, transparent, rgba(255,140,0,0.25), transparent);
                }
                .sh-card-header {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 16px 20px;
                    border-bottom: 1px solid rgba(255,255,255,0.05);
                }
                .sh-card-icon {
                    width: 32px; height: 32px; border-radius: 9px;
                    display: flex; align-items: center; justify-content: center;
                    background: rgba(255,140,0,0.1); color: #ff8c00; flex-shrink: 0;
                }
                .sh-card-title {
                    font-family: 'Sora', sans-serif;
                    font-size: 0.78rem;
                    font-weight: 700;
                    letter-spacing: 0.1em;
                    text-transform: uppercase;
                    color: #ff8c00;
                }

                /* ── From info ── */
                .sh-from-body { padding: 18px 20px; }
                .sh-from-name {
                    font-family: 'Sora', sans-serif;
                    font-size: 1rem; font-weight: 800; color: #ffffff;
                    margin-bottom: 6px;
                }
                .sh-from-line {
                    font-size: 0.8rem; color: rgba(255,255,255,0.45);
                    line-height: 1.7;
                }

                /* ── Items table ── */
                .sh-table { width: 100%; border-collapse: collapse; }
                .sh-table th {
                    padding: 12px 20px; text-align: left;
                    font-size: 0.68rem; font-weight: 700; letter-spacing: 0.1em;
                    text-transform: uppercase; color: rgba(255,255,255,0.3);
                }
                .sh-table th:last-child { text-align: right; }
                .sh-table tbody tr { border-top: 1px solid rgba(255,255,255,0.04); }
                .sh-table td {
                    padding: 14px 20px; font-size: 0.85rem; color: rgba(255,255,255,0.75);
                }
                .sh-table td:last-child { text-align: right; font-family: 'Sora', sans-serif; font-weight: 600; color: #fff; }

                /* ── Totals ── */
                .sh-totals { padding: 18px 20px; }
                .sh-total-row {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 9px 0;
                    border-bottom: 1px solid rgba(255,255,255,0.04);
                    font-size: 0.85rem;
                }
                .sh-total-row:last-child { border-bottom: none; }
                .sh-total-label { color: rgba(255,255,255,0.5); font-weight: 500; }
                .sh-total-value { color: rgba(255,255,255,0.8); font-weight: 600; }
                .sh-total-row.discount .sh-total-value { color: #34d399; }
                .sh-total-row.paid-row .sh-total-value { color: #34d399; }
                .sh-balance-row {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 14px 16px;
                    margin-top: 10px;
                    background: rgba(255,140,0,0.08);
                    border: 1px solid rgba(255,140,0,0.2);
                    border-radius: 12px;
                }
                .sh-balance-label {
                    font-family: 'Sora', sans-serif;
                    font-size: 0.9rem; font-weight: 800; color: rgba(255,255,255,0.85);
                }
                .sh-balance-value {
                    font-family: 'Sora', sans-serif;
                    font-size: 1.15rem; font-weight: 800; color: #ff8c00;
                    letter-spacing: -0.5px;
                }
                .sh-balance-row.settled .sh-balance-value { color: #34d399; }
                .sh-balance-row.settled { background: rgba(16,185,129,0.06); border-color: rgba(16,185,129,0.2); }

                /* ── Payment history table ── */
                .sh-pay-table { width: 100%; border-collapse: collapse; }
                .sh-pay-table th {
                    padding: 12px 20px; text-align: left;
                    font-size: 0.68rem; font-weight: 700; letter-spacing: 0.1em;
                    text-transform: uppercase; color: rgba(255,255,255,0.3);
                }
                .sh-pay-table th:last-child { text-align: right; }
                .sh-pay-table tbody tr { border-top: 1px solid rgba(255,255,255,0.04); }
                .sh-pay-table td {
                    padding: 13px 20px; font-size: 0.83rem; color: rgba(255,255,255,0.7);
                }
                .sh-pay-table td:last-child { text-align: right; font-weight: 600; color: #34d399; }
                .sh-method-chip {
                    display: inline-flex; align-items: center; gap: 5px;
                    padding: 3px 9px; border-radius: 6px;
                    background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
                    font-size: 0.72rem; font-weight: 700; color: rgba(255,255,255,0.6);
                    text-transform: uppercase; letter-spacing: 0.05em;
                }

                /* ── VA / Payment instruction card ── */
                .sh-va-card {
                    background: rgba(59,130,246,0.07);
                    border: 1px solid rgba(59,130,246,0.25);
                    border-left: 4px solid #3b82f6;
                    border-radius: 16px;
                    padding: 18px 20px;
                    margin-bottom: 16px;
                }
                .sh-va-title {
                    display: flex; align-items: center; gap: 8px;
                    font-size: 0.85rem; font-weight: 700; color: rgba(150,190,255,0.9);
                    margin-bottom: 12px;
                }
                .sh-va-row {
                    display: flex; gap: 8px;
                    font-size: 0.82rem; margin-bottom: 8px;
                }
                .sh-va-key { color: rgba(255,255,255,0.4); font-weight: 600; min-width: 170px; flex-shrink: 0; }
                .sh-va-val { color: rgba(255,255,255,0.8); font-weight: 600; }
                .sh-va-number {
                    font-family: 'Courier New', monospace;
                    font-size: 1rem; font-weight: 700; color: #93c5fd;
                    background: rgba(59,130,246,0.1);
                    border: 1px solid rgba(59,130,246,0.2);
                    padding: 6px 14px; border-radius: 8px;
                    letter-spacing: 0.1em;
                }

                /* ── Footer actions ── */
                .sh-footer {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 12px;
                    flex-wrap: wrap;
                    margin-top: 8px;
                    padding-top: 20px;
                    border-top: 1px solid rgba(255,255,255,0.06);
                }
                .sh-back-btn {
                    display: inline-flex; align-items: center; gap: 7px;
                    padding: 9px 18px; border-radius: 10px;
                    background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
                    color: rgba(255,255,255,0.6); font-size: 0.82rem; font-weight: 600;
                    text-decoration: none; transition: all 0.2s;
                }
                .sh-back-btn:hover { color: #fff; background: rgba(255,255,255,0.09); }
                .sh-pay-btn {
                    display: inline-flex; align-items: center; gap: 8px;
                    padding: 10px 24px; border-radius: 11px;
                    background: linear-gradient(135deg, #ff8c00, #ff6a00);
                    color: white; font-size: 0.88rem; font-weight: 700;
                    text-decoration: none;
                    box-shadow: 0 4px 16px rgba(255,110,0,0.4);
                    transition: all 0.2s;
                }
                .sh-pay-btn:hover { box-shadow: 0 6px 22px rgba(255,110,0,0.55); transform: translateY(-1px); }
            `}</style>

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
                                <CalendarIcon /> Tanggal Invoice: <span>{formatDate(invoice.invoice_date)}</span>
                            </div>
                            <div className="sh-date-chip">
                                <CalendarIcon /> Jatuh Tempo: <span>{formatDate(invoice.due_date)}</span>
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
                        <div className="sh-totals" style={{ borderTop: '1px solid rgba(255,255,255,0.05)' }}>
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
                                <span style={{ color: 'rgba(255,255,255,0.75)', fontWeight: 700 }}>Total</span>
                                <span style={{ color: '#fff', fontFamily: "'Sora',sans-serif", fontWeight: 800 }}>{formatRupiah(invoice.balance_due ?? invoice.subtotal)}</span>
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