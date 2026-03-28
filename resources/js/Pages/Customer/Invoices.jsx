import React, { useState } from 'react';
import { Head, Link, usePage, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import './Invoices.css';

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
                                                <div style={{ display: 'flex', alignItems: 'center', gap: 7, color: 'var(--text-secondary)', fontSize: '0.75rem', marginBottom: 2 }}>
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
