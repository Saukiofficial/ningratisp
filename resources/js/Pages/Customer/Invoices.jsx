import React, { useState } from 'react';
import { Head, Link, usePage, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

const FilterIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
    </svg>
);

const ChevronIcon = ({ open }) => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}
        style={{ transform: open ? 'rotate(180deg)' : 'rotate(0deg)', transition: 'transform 0.2s' }}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M19 9l-7 7-7-7" />
    </svg>
);

const BillIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
    </svg>
);

const PrintIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
    </svg>
);

const CalendarIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5" />
    </svg>
);

const CheckIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
);

const WarningIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
    </svg>
);

export default function Tagihan({ tagihans, filters, invoice_statuses }) {
    const { flash } = usePage().props;
    const totalBelumLunas = tagihans.total > 0 ? tagihans.data.filter(t => t.status === invoice_statuses.unpaid).length : 0;

    const [isFilterOpen, setIsFilterOpen] = useState(false);
    const [filterState, setFilterState] = useState({
        status: filters.status || '',
        start_date: filters.start_date || '',
        end_date: filters.end_date || '',
    });

    const handleFilterChange = (e) => {
        setFilterState({ ...filterState, [e.target.name]: e.target.value });
    };

    const applyFilters = () => {
        router.get(route('customer.invoices.index'), filterState, {
            preserveState: true,
            replace: true,
        });
    };

    const formatRupiah = (number) => new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
    }).format(number);

    const formatDate = (dateString) => {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
    };

    return (
        <AuthenticatedLayout>
            <Head title="Daftar Tagihan" />

            <style>{`
                @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@400;600;700;800&display=swap');

                .inv-root {
                    font-family: 'Plus Jakarta Sans', sans-serif;
                    background: #0f0f0f;
                    min-height: 100vh;
                    padding: 28px 0 60px;
                }
                .inv-container {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 0 16px;
                }
                @media (min-width: 640px)  { .inv-container { padding: 0 24px; } }
                @media (min-width: 1024px) { .inv-container { padding: 0 32px; } }

                /* ── Page heading ── */
                .inv-heading {
                    margin-bottom: 24px;
                }
                .inv-title {
                    font-family: 'Sora', sans-serif;
                    font-size: clamp(1.4rem, 3vw, 1.9rem);
                    font-weight: 800;
                    color: #ffffff;
                    letter-spacing: -0.5px;
                    line-height: 1.1;
                }
                .inv-subtitle {
                    font-size: 0.8rem;
                    color: rgba(255,255,255,0.4);
                    margin-top: 4px;
                }

                /* ── Alert banners ── */
                .inv-alert {
                    display: flex;
                    align-items: flex-start;
                    gap: 12px;
                    border-radius: 14px;
                    padding: 14px 18px;
                    margin-bottom: 16px;
                    border: 1px solid;
                }
                .inv-alert.warning {
                    background: rgba(255,140,0,0.08);
                    border-color: rgba(255,140,0,0.3);
                    border-left: 4px solid #ff8c00;
                }
                .inv-alert.success {
                    background: rgba(16,185,129,0.08);
                    border-color: rgba(16,185,129,0.3);
                    border-left: 4px solid #10b981;
                }
                .inv-alert.info {
                    background: rgba(59,130,246,0.08);
                    border-color: rgba(59,130,246,0.3);
                    border-left: 4px solid #3b82f6;
                }
                .inv-alert-icon {
                    width: 28px;
                    height: 28px;
                    border-radius: 8px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                    margin-top: 1px;
                }
                .inv-alert.warning .inv-alert-icon { background: rgba(255,140,0,0.15); color: #ff8c00; }
                .inv-alert.success .inv-alert-icon { background: rgba(16,185,129,0.15); color: #10b981; }
                .inv-alert.info    .inv-alert-icon { background: rgba(59,130,246,0.15); color: #3b82f6; }
                .inv-alert-text {
                    font-size: 0.85rem;
                    font-weight: 600;
                }
                .inv-alert.warning .inv-alert-text { color: rgba(255,200,100,0.9); }
                .inv-alert.success .inv-alert-text { color: rgba(100,220,180,0.9); }
                .inv-alert.info    .inv-alert-text { color: rgba(150,190,255,0.9); }

                /* ── Filter card ── */
                .inv-filter-card {
                    background: #1a1a1a;
                    border: 1px solid rgba(255,255,255,0.07);
                    border-radius: 20px;
                    padding: 18px 20px;
                    margin-bottom: 24px;
                    position: relative;
                    overflow: hidden;
                }
                .inv-filter-card::before {
                    content: '';
                    position: absolute;
                    top: 0; left: 0; right: 0;
                    height: 1px;
                    background: linear-gradient(90deg, transparent, rgba(255,140,0,0.3), transparent);
                }
                .inv-filter-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }
                .inv-filter-title {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    font-size: 0.82rem;
                    font-weight: 700;
                    letter-spacing: 0.08em;
                    text-transform: uppercase;
                    color: #ff8c00;
                }
                .inv-filter-toggle {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    padding: 6px 12px;
                    border-radius: 8px;
                    background: rgba(255,140,0,0.08);
                    border: 1px solid rgba(255,140,0,0.2);
                    color: rgba(255,255,255,0.6);
                    font-size: 0.75rem;
                    font-weight: 600;
                    cursor: pointer;
                    font-family: 'Plus Jakarta Sans', sans-serif;
                    transition: all 0.2s;
                }
                .inv-filter-toggle:hover { background: rgba(255,140,0,0.14); color: #ff8c00; }
                .inv-filter-body {
                    margin-top: 16px;
                    display: grid;
                    grid-template-columns: 1fr;
                    gap: 12px;
                }
                @media (min-width: 480px) { .inv-filter-body { grid-template-columns: 1fr 1fr; } }
                @media (min-width: 768px) { .inv-filter-body { grid-template-columns: 1fr 1fr 1fr auto; align-items: end; } }

                .inv-field-label {
                    font-size: 0.72rem;
                    font-weight: 600;
                    letter-spacing: 0.06em;
                    text-transform: uppercase;
                    color: rgba(255,255,255,0.4);
                    margin-bottom: 6px;
                }
                .inv-field-input {
                    width: 100%;
                    background: #111111;
                    border: 1px solid rgba(255,255,255,0.1);
                    border-radius: 10px;
                    padding: 9px 12px;
                    font-size: 0.83rem;
                    font-weight: 500;
                    color: rgba(255,255,255,0.8);
                    font-family: 'Plus Jakarta Sans', sans-serif;
                    transition: border-color 0.2s;
                    outline: none;
                    box-sizing: border-box;
                }
                .inv-field-input:focus { border-color: rgba(255,140,0,0.5); }
                .inv-field-input option { background: #1a1a1a; }
                .inv-field-input::-webkit-calendar-picker-indicator { filter: invert(0.5); }

                .inv-filter-btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    padding: 9px 22px;
                    background: linear-gradient(135deg, #ff8c00, #ff6a00);
                    color: white;
                    font-weight: 700;
                    font-size: 0.82rem;
                    border-radius: 10px;
                    border: none;
                    cursor: pointer;
                    font-family: 'Plus Jakarta Sans', sans-serif;
                    box-shadow: 0 4px 14px rgba(255,110,0,0.35);
                    transition: all 0.2s;
                    white-space: nowrap;
                    width: 100%;
                }
                @media (min-width: 768px) { .inv-filter-btn { width: auto; } }
                .inv-filter-btn:hover {
                    box-shadow: 0 6px 20px rgba(255,110,0,0.5);
                    transform: translateY(-1px);
                }

                /* ── Section header ── */
                .inv-section-header {
                    display: flex;
                    align-items: center;
                    margin-bottom: 14px;
                }
                .inv-section-title {
                    font-family: 'Sora', sans-serif;
                    font-size: 0.78rem;
                    font-weight: 700;
                    letter-spacing: 0.12em;
                    text-transform: uppercase;
                    color: #ff8c00;
                    white-space: nowrap;
                }
                .inv-section-line {
                    flex: 1;
                    height: 1px;
                    background: linear-gradient(to right, rgba(255,140,0,0.4), transparent);
                    margin-left: 12px;
                }

                /* ── Desktop table ── */
                .inv-table-wrap {
                    display: none;
                    background: #1a1a1a;
                    border: 1px solid rgba(255,255,255,0.07);
                    border-radius: 20px;
                    overflow: hidden;
                    position: relative;
                }
                .inv-table-wrap::before {
                    content: '';
                    position: absolute;
                    top: 0; left: 0; right: 0;
                    height: 1px;
                    background: linear-gradient(90deg, transparent, rgba(255,140,0,0.3), transparent);
                }
                @media (min-width: 640px) { .inv-table-wrap { display: block; } }

                .inv-table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .inv-table thead tr {
                    border-bottom: 1px solid rgba(255,255,255,0.06);
                }
                .inv-table th {
                    padding: 14px 20px;
                    text-align: left;
                    font-size: 0.68rem;
                    font-weight: 700;
                    letter-spacing: 0.1em;
                    text-transform: uppercase;
                    color: rgba(255,255,255,0.35);
                }
                .inv-table tbody tr {
                    border-bottom: 1px solid rgba(255,255,255,0.04);
                    transition: background 0.15s;
                }
                .inv-table tbody tr:last-child { border-bottom: none; }
                .inv-table tbody tr:hover { background: rgba(255,140,0,0.03); }
                .inv-table td {
                    padding: 16px 20px;
                    font-size: 0.85rem;
                    color: rgba(255,255,255,0.75);
                    vertical-align: middle;
                }
                .inv-table td.date {
                    font-weight: 600;
                    color: rgba(255,255,255,0.85);
                }
                .inv-table td.amount {
                    font-family: 'Sora', sans-serif;
                    font-weight: 700;
                    color: #ffffff;
                }

                /* ── Badges ── */
                .inv-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 5px;
                    padding: 4px 10px;
                    border-radius: 50px;
                    font-size: 0.72rem;
                    font-weight: 700;
                    letter-spacing: 0.03em;
                }
                .inv-badge.paid {
                    background: rgba(16,185,129,0.12);
                    color: #34d399;
                    border: 1px solid rgba(16,185,129,0.25);
                }
                .inv-badge.unpaid {
                    background: rgba(239,68,68,0.12);
                    color: #f87171;
                    border: 1px solid rgba(239,68,68,0.25);
                }
                .inv-badge-dot {
                    width: 6px;
                    height: 6px;
                    border-radius: 50%;
                }
                .inv-badge.paid .inv-badge-dot { background: #10b981; }
                .inv-badge.unpaid .inv-badge-dot { background: #ef4444; }

                /* ── Action buttons ── */
                .inv-print-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 7px 14px;
                    border-radius: 9px;
                    background: rgba(255,255,255,0.05);
                    border: 1px solid rgba(255,255,255,0.1);
                    color: rgba(255,255,255,0.7);
                    font-size: 0.78rem;
                    font-weight: 600;
                    text-decoration: none;
                    transition: all 0.2s;
                }
                .inv-print-btn:hover {
                    background: rgba(255,255,255,0.1);
                    color: #fff;
                    border-color: rgba(255,255,255,0.2);
                }
                .inv-pay-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 7px 16px;
                    border-radius: 9px;
                    background: linear-gradient(135deg, #ff8c00, #ff6a00);
                    color: white;
                    font-size: 0.78rem;
                    font-weight: 700;
                    text-decoration: none;
                    box-shadow: 0 4px 12px rgba(255,110,0,0.35);
                    transition: all 0.2s;
                    white-space: nowrap;
                }
                .inv-pay-btn:hover {
                    box-shadow: 0 6px 18px rgba(255,110,0,0.5);
                    transform: translateY(-1px);
                }

                /* ── Mobile cards ── */
                .inv-mobile-list {
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                }
                @media (min-width: 640px) { .inv-mobile-list { display: none; } }

                .inv-mobile-card {
                    background: #1a1a1a;
                    border: 1px solid rgba(255,255,255,0.07);
                    border-radius: 18px;
                    padding: 16px 18px;
                    position: relative;
                    overflow: hidden;
                    transition: border-color 0.2s;
                }
                .inv-mobile-card::before {
                    content: '';
                    position: absolute;
                    top: 0; left: 0; right: 0;
                    height: 1px;
                    background: linear-gradient(90deg, transparent, rgba(255,140,0,0.25), transparent);
                }
                .inv-mobile-card:hover { border-color: rgba(255,140,0,0.2); }

                .inv-mobile-card-top {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 10px;
                    margin-bottom: 14px;
                }
                .inv-mobile-date {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    font-size: 0.72rem;
                    font-weight: 600;
                    color: rgba(255,255,255,0.4);
                    margin-bottom: 4px;
                }
                .inv-mobile-amount {
                    font-family: 'Sora', sans-serif;
                    font-size: 1.1rem;
                    font-weight: 800;
                    color: #ffffff;
                    letter-spacing: -0.3px;
                }
                .inv-mobile-card-bottom {
                    display: flex;
                    align-items: center;
                    justify-content: flex-end;
                    padding-top: 12px;
                    border-top: 1px solid rgba(255,255,255,0.05);
                }

                /* ── Empty state ── */
                .inv-empty {
                    text-align: center;
                    padding: 60px 20px;
                    color: rgba(255,255,255,0.25);
                }
                .inv-empty-icon {
                    width: 56px;
                    height: 56px;
                    background: rgba(255,255,255,0.04);
                    border-radius: 16px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 14px;
                    color: rgba(255,255,255,0.2);
                }
                .inv-empty-text {
                    font-size: 0.9rem;
                    font-weight: 600;
                }

                /* ── Pagination ── */
                .inv-pagination {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 6px;
                    margin-top: 24px;
                    flex-wrap: wrap;
                }
                .inv-page-btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 36px;
                    height: 36px;
                    padding: 0 10px;
                    border-radius: 9px;
                    font-size: 0.8rem;
                    font-weight: 600;
                    text-decoration: none;
                    transition: all 0.2s;
                    border: 1px solid rgba(255,255,255,0.08);
                    color: rgba(255,255,255,0.5);
                    background: #1a1a1a;
                }
                .inv-page-btn:hover { color: #ff8c00; border-color: rgba(255,140,0,0.3); }
                .inv-page-btn.active {
                    background: linear-gradient(135deg, #ff8c00, #ff6a00);
                    color: white;
                    border-color: transparent;
                    box-shadow: 0 4px 12px rgba(255,110,0,0.35);
                }
                .inv-page-btn.disabled { opacity: 0.3; pointer-events: none; }
            `}</style>

            <div className="inv-root">
                <div className="inv-container">

                    {/* ── Heading ── */}
                    <div className="inv-heading">
                        <div className="inv-title">Riwayat Tagihan</div>
                        <div className="inv-subtitle">Kelola dan pantau semua tagihan internet Anda</div>
                    </div>

                    {/* ── Alerts ── */}
                    {flash.success && (
                        <div className="inv-alert success">
                            <div className="inv-alert-icon"><CheckIcon /></div>
                            <div className="inv-alert-text">{flash.success}</div>
                        </div>
                    )}
                    {totalBelumLunas > 0 && (
                        <div className="inv-alert warning">
                            <div className="inv-alert-icon"><WarningIcon /></div>
                            <div className="inv-alert-text">Anda memiliki {totalBelumLunas} tagihan yang belum dibayar.</div>
                        </div>
                    )}
                    {totalBelumLunas === 0 && !flash.success && tagihans.total > 0 && (
                        <div className="inv-alert info">
                            <div className="inv-alert-icon"><CheckIcon /></div>
                            <div className="inv-alert-text">Semua tagihan Anda sudah lunas!</div>
                        </div>
                    )}

                    {/* ── Filter card ── */}
                    <div className="inv-filter-card">
                        <div className="inv-filter-header">
                            <div className="inv-filter-title">
                                <FilterIcon /> Filter Tagihan
                            </div>
                            <button
                                className="inv-filter-toggle sm:hidden"
                                onClick={() => setIsFilterOpen(!isFilterOpen)}
                            >
                                {isFilterOpen ? 'Tutup' : 'Buka'} <ChevronIcon open={isFilterOpen} />
                            </button>
                        </div>

                        <div className={`${isFilterOpen ? '' : 'hidden'} sm:block`}>
                            <div className="inv-filter-body" style={{ marginTop: 16 }}>
                                <div>
                                    <div className="inv-field-label">Status</div>
                                    <select name="status" value={filterState.status} onChange={handleFilterChange} className="inv-field-input">
                                        <option value="">Semua Status</option>
                                        <option value={invoice_statuses.unpaid}>Belum Dibayar</option>
                                        <option value={invoice_statuses.paid}>Lunas</option>
                                    </select>
                                </div>
                                <div>
                                    <div className="inv-field-label">Dari Tanggal</div>
                                    <input type="date" name="start_date" value={filterState.start_date} onChange={handleFilterChange} className="inv-field-input" />
                                </div>
                                <div>
                                    <div className="inv-field-label">Sampai Tanggal</div>
                                    <input type="date" name="end_date" value={filterState.end_date} onChange={handleFilterChange} className="inv-field-input" />
                                </div>
                                <div>
                                    <button onClick={applyFilters} className="inv-filter-btn">
                                        <FilterIcon /> Terapkan Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* ── Section header ── */}
                    <div className="inv-section-header">
                        <div className="inv-section-title">Daftar Tagihan</div>
                        <div className="inv-section-line" />
                    </div>

                    {/* ── Desktop Table ── */}
                    <div className="inv-table-wrap">
                        {tagihans.data.length === 0 ? (
                            <div className="inv-empty">
                                <div className="inv-empty-icon"><BillIcon /></div>
                                <div className="inv-empty-text">Tidak ada tagihan ditemukan</div>
                            </div>
                        ) : (
                            <table className="inv-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Total Tagihan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {tagihans.data.map((tagihan) => (
                                        <tr key={tagihan.id}>
                                            <td className="date">
                                                <div style={{ display: 'flex', alignItems: 'center', gap: 7, color: 'rgba(255,255,255,0.5)', fontSize: '0.75rem', marginBottom: 2 }}>
                                                    <CalendarIcon /> Tanggal Tagihan
                                                </div>
                                                {formatDate(tagihan.invoice_date)}
                                            </td>
                                            <td className="amount">{formatRupiah(tagihan.balance_due)}</td>
                                            <td>
                                                <span className={`inv-badge ${tagihan.status === invoice_statuses.paid ? 'paid' : 'unpaid'}`}>
                                                    <span className="inv-badge-dot" />
                                                    {tagihan.status === invoice_statuses.paid ? 'Lunas' : 'Belum Dibayar'}
                                                </span>
                                            </td>
                                            <td>
                                                {tagihan.status === invoice_statuses.paid ? (
                                                    <a href={`/tagihan/${tagihan.id}/struk`} target="_blank" rel="noopener noreferrer" className="inv-print-btn">
                                                        <PrintIcon /> Cetak Struk
                                                    </a>
                                                ) : (
                                                    <Link href={`/pembayaran/${tagihan.id}`} className="inv-pay-btn">
                                                        <BillIcon /> Bayar Sekarang
                                                    </Link>
                                                )}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        )}
                    </div>

                    {/* ── Mobile Cards ── */}
                    <div className="inv-mobile-list">
                        {tagihans.data.length === 0 ? (
                            <div className="inv-empty">
                                <div className="inv-empty-icon"><BillIcon /></div>
                                <div className="inv-empty-text">Tidak ada tagihan ditemukan</div>
                            </div>
                        ) : (
                            tagihans.data.map((tagihan) => (
                                <div key={tagihan.id} className="inv-mobile-card">
                                    <div className="inv-mobile-card-top">
                                        <div>
                                            <div className="inv-mobile-date">
                                                <CalendarIcon /> {formatDate(tagihan.invoice_date)}
                                            </div>
                                            <div className="inv-mobile-amount">{formatRupiah(tagihan.balance_due)}</div>
                                        </div>
                                        <span className={`inv-badge ${tagihan.status === invoice_statuses.paid ? 'paid' : 'unpaid'}`}>
                                            <span className="inv-badge-dot" />
                                            {tagihan.status === invoice_statuses.paid ? 'Lunas' : 'Belum Dibayar'}
                                        </span>
                                    </div>
                                    <div className="inv-mobile-card-bottom">
                                        {tagihan.status === invoice_statuses.paid ? (
                                            <a href={`/tagihan/${tagihan.id}/struk`} target="_blank" rel="noopener noreferrer" className="inv-print-btn">
                                                <PrintIcon /> Cetak Struk
                                            </a>
                                        ) : (
                                            <Link href={`/pembayaran/${tagihan.id}`} className="inv-pay-btn">
                                                <BillIcon /> Bayar Sekarang
                                            </Link>
                                        )}
                                    </div>
                                </div>
                            ))
                        )}
                    </div>

                    {/* ── Pagination ── */}
                    {tagihans.links && tagihans.links.length > 3 && (
                        <div className="inv-pagination">
                            {tagihans.links.map((link, i) => (
                                link.url ? (
                                    <Link
                                        key={i}
                                        href={link.url}
                                        className={`inv-page-btn ${link.active ? 'active' : ''}`}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ) : (
                                    <span
                                        key={i}
                                        className="inv-page-btn disabled"
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                )
                            ))}
                        </div>
                    )}

                </div>
            </div>
        </AuthenticatedLayout>
    );
}