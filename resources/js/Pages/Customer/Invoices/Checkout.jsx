import React, { useState, useEffect } from 'react';
import { Head, Link, router, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { toast } from 'react-toastify';
import { route } from 'ziggy-js';

// ─── Icons ────────────────────────────────────────────────────────────────────

const CheckCircleIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 20, height: 20 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
);

const PayIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 16, height: 16 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 21z" />
    </svg>
);

const TagIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 14, height: 14 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
        <path strokeLinecap="round" strokeLinejoin="round" d="M6 6h.008v.008H6V6z" />
    </svg>
);

const TrashIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 13, height: 13 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
    </svg>
);

const BackIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 14, height: 14 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
    </svg>
);

const SpinnerIcon = () => (
    <svg className="animate-spin" style={{ width: 18, height: 18 }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
    </svg>
);

const AlertIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 28, height: 28 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 21z" />
    </svg>
);

// ─── Confirmation Modal ───────────────────────────────────────────────────────

const ConfirmationModal = ({ isOpen, onClose, onConfirm, title, children, isProcessing }) => {
    if (!isOpen) return null;
    return (
        <div
            style={{
                position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.8)',
                backdropFilter: 'blur(6px)', display: 'flex', alignItems: 'center',
                justifyContent: 'center', zIndex: 9999, padding: 16,
            }}
            onClick={onClose}
        >
            <div
                style={{
                    background: '#1a1a1a', border: '1px solid rgba(255,255,255,0.1)',
                    borderRadius: 22, width: '100%', maxWidth: 440,
                    boxShadow: '0 24px 80px rgba(0,0,0,0.8)', overflow: 'hidden',
                    fontFamily: "'Plus Jakarta Sans', sans-serif",
                }}
                onClick={(e) => e.stopPropagation()}
            >
                {/* Modal header */}
                <div style={{
                    padding: '28px 28px 20px', textAlign: 'center',
                    borderBottom: '1px solid rgba(255,255,255,0.06)',
                }}>
                    <div style={{
                        width: 60, height: 60, borderRadius: 18,
                        background: 'linear-gradient(135deg, rgba(255,140,0,0.2), rgba(255,100,0,0.1))',
                        border: '1px solid rgba(255,140,0,0.25)',
                        display: 'flex', alignItems: 'center', justifyContent: 'center',
                        margin: '0 auto 16px', color: '#ff8c00',
                    }}>
                        <AlertIcon />
                    </div>
                    <h3 style={{
                        fontFamily: "'Sora', sans-serif", fontSize: '1.25rem',
                        fontWeight: 800, color: '#ffffff', letterSpacing: -0.5,
                    }}>{title}</h3>
                </div>

                {/* Modal body */}
                <div style={{ padding: '20px 28px' }}>{children}</div>

                {/* Modal footer */}
                <div style={{
                    padding: '16px 28px 24px',
                    display: 'flex', justifyContent: 'flex-end', gap: 10,
                }}>
                    <button
                        onClick={onClose}
                        disabled={isProcessing}
                        style={{
                            padding: '9px 20px', borderRadius: 10,
                            background: 'rgba(255,255,255,0.05)', border: '1px solid rgba(255,255,255,0.1)',
                            color: 'rgba(255,255,255,0.6)', fontSize: '0.85rem', fontWeight: 600,
                            cursor: isProcessing ? 'not-allowed' : 'pointer', opacity: isProcessing ? 0.5 : 1,
                            fontFamily: "'Plus Jakarta Sans', sans-serif",
                        }}
                    >
                        Batal
                    </button>
                    <button
                        onClick={onConfirm}
                        disabled={isProcessing}
                        style={{
                            padding: '9px 24px', borderRadius: 10, minWidth: 160,
                            background: 'linear-gradient(135deg, #ff8c00, #ff6a00)',
                            color: 'white', fontSize: '0.85rem', fontWeight: 700,
                            border: 'none', cursor: isProcessing ? 'not-allowed' : 'pointer',
                            opacity: isProcessing ? 0.7 : 1,
                            boxShadow: '0 4px 16px rgba(255,110,0,0.4)',
                            display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8,
                            fontFamily: "'Plus Jakarta Sans', sans-serif",
                        }}
                    >
                        {isProcessing ? <SpinnerIcon /> : <><PayIcon /> Konfirmasi & Bayar</>}
                    </button>
                </div>
            </div>
        </div>
    );
};

// ─── Order Summary Sidebar ────────────────────────────────────────────────────

const OrderSummary = ({ invoice, claimedDiscounts = [], selectedMethod, handleOpenConfirmation, isProcessing, summary }) => {
    const { data, setData, post, processing, errors, clearErrors } = useForm({ code: '' });

    const handleClaimVoucher = (e) => {
        e.preventDefault();
        post(route('customer.discounts.claim'), { onSuccess: () => setData('code', '') });
    };
    const handleApplyDiscount = (discountId) => {
        router.post(route('customer.invoices.apply-discount', { invoice: invoice.id }), { discount_id: discountId });
    };
    const handleRemoveDiscount = () => {
        router.post(route('customer.invoices.remove-discount', { invoice: invoice.id }));
    };

    const formatRupiah = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);

    const processedDiscounts = claimedDiscounts.map(claimed => {
        let discountAmount = 0;
        const subtotal = parseFloat(invoice.subtotal);
        if (claimed.discount.type === 'percentage') {
            discountAmount = (subtotal * parseFloat(claimed.discount.value)) / 100;
            if (claimed.discount.max_discount_amount && discountAmount > parseFloat(claimed.discount.max_discount_amount)) {
                discountAmount = parseFloat(claimed.discount.max_discount_amount);
            }
        } else if (claimed.discount.type === 'fixed_amount') {
            discountAmount = parseFloat(claimed.discount.value);
        }
        return { ...claimed, isApplicable: discountAmount < subtotal };
    });

    return (
        <div className="ck-summary-card">
            <div className="ck-summary-header">
                <div className="ck-summary-title">Ringkasan Pesanan</div>
            </div>

            {/* Line items */}
            <div className="ck-summary-lines">
                <div className="ck-summary-row">
                    <span className="ck-summary-label">Subtotal</span>
                    <span className="ck-summary-val">{formatRupiah(invoice.subtotal)}</span>
                </div>
                {invoice.discount_amount > 0 && (
                    <div className="ck-summary-row discount">
                        <span className="ck-summary-label">Diskon ({invoice.discount.name})</span>
                        <span className="ck-summary-val">−{formatRupiah(invoice.discount_amount)}</span>
                    </div>
                )}
                {summary.fee > 0 && (
                    <div className="ck-summary-row">
                        <span className="ck-summary-label">Biaya Admin</span>
                        <span className="ck-summary-val">{formatRupiah(summary.fee)}</span>
                    </div>
                )}
            </div>

            {/* Grand total */}
            <div className="ck-grand-total">
                <span className="ck-grand-label">Total</span>
                <span className="ck-grand-val">{formatRupiah(summary.grandTotal)}</span>
            </div>

            {/* Voucher section */}
            <div className="ck-voucher-section">
                {invoice.discount ? (
                    <div className="ck-discount-applied">
                        <div style={{ display: 'flex', alignItems: 'center', gap: 7 }}>
                            <TagIcon />
                            <span style={{ fontSize: '0.83rem', fontWeight: 700, color: '#34d399' }}>
                                {invoice.discount.name} diterapkan
                            </span>
                        </div>
                        <button onClick={handleRemoveDiscount} className="ck-remove-discount">
                            <TrashIcon /> Hapus
                        </button>
                    </div>
                ) : (
                    <>
                        <div className="ck-voucher-label">Punya Voucher?</div>
                        <div className="ck-voucher-row">
                            <input
                                type="text"
                                value={data.code}
                                onChange={e => setData('code', e.target.value.toUpperCase().replaceAll(' ', ''))}
                                onFocus={() => clearErrors('code')}
                                placeholder="Kode voucher"
                                className="ck-voucher-input"
                                maxLength={10}
                            />
                            <button type="button" onClick={handleClaimVoucher} disabled={processing} className="ck-voucher-btn">
                                {processing ? <SpinnerIcon /> : 'Claim'}
                            </button>
                        </div>
                        {errors.code && <p className="ck-field-error">{errors.code}</p>}

                        {processedDiscounts.length > 0 && (
                            <div style={{ marginTop: 14 }}>
                                <div className="ck-voucher-label" style={{ marginBottom: 8 }}>Voucher Tersedia</div>
                                <div style={{ display: 'flex', flexDirection: 'column', gap: 8, maxHeight: 160, overflowY: 'auto' }}>
                                    {processedDiscounts.map(pd => (
                                        <div
                                            key={pd.id}
                                            onClick={() => pd.isApplicable && handleApplyDiscount(pd.discount.id)}
                                            className={`ck-voucher-item ${pd.isApplicable ? 'applicable' : 'disabled'}`}
                                            title={!pd.isApplicable ? 'Diskon terlalu besar untuk invoice ini' : ''}
                                        >
                                            <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                                                <TagIcon />
                                                <span style={{ fontWeight: 700, fontSize: '0.83rem' }}>{pd.discount.name}</span>
                                            </div>
                                            <p style={{ fontSize: '0.75rem', marginTop: 2, color: 'rgba(255,255,255,0.4)' }}>{pd.discount.description}</p>
                                            {!pd.isApplicable && (
                                                <p style={{ fontSize: '0.7rem', color: '#f87171', fontWeight: 600, marginTop: 4 }}>Tidak berlaku untuk invoice ini</p>
                                            )}
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}
                    </>
                )}
            </div>

            {/* Pay button */}
            <div className="ck-pay-wrap">
                <button
                    onClick={handleOpenConfirmation}
                    disabled={!selectedMethod || isProcessing}
                    className="ck-pay-btn"
                >
                    {isProcessing ? <SpinnerIcon /> : <><PayIcon /> Bayar Sekarang</>}
                </button>
                {!selectedMethod && (
                    <p className="ck-pay-hint">Pilih metode pembayaran untuk melanjutkan</p>
                )}
            </div>
        </div>
    );
};

// ─── Main Checkout ────────────────────────────────────────────────────────────

export default function Checkout({ invoice, paymentMethods = [], claimedDiscounts = [], flash }) {
    const [selectedMethod, setSelectedMethod] = useState(paymentMethods[0] || null);
    const [isProcessing, setIsProcessing] = useState(false);
    const [isConfirmationModalOpen, setIsConfirmationModalOpen] = useState(false);
    const [confirmationData, setConfirmationData] = useState(null);
    const [summary, setSummary] = useState({ fee: 0, grandTotal: invoice.balance_due });

    const calculateFee = (fee) => {
        if (!fee) return 0;
        return fee.unit === 'p' ? (parseFloat(invoice.balance_due) * parseFloat(fee.amount)) / 100 : parseFloat(fee.amount);
    };

    useEffect(() => {
        if (selectedMethod) {
            const fee = calculateFee(selectedMethod.fee);
            setSummary({ fee, grandTotal: parseFloat(invoice.balance_due) + fee });
        } else {
            setSummary({ fee: 0, grandTotal: parseFloat(invoice.balance_due) });
        }
    }, [selectedMethod, invoice.balance_due]);

    const handleOpenConfirmation = () => {
        if (!selectedMethod) return;
        setConfirmationData({ method: selectedMethod, fee: summary.fee, total: summary.grandTotal });
        setIsConfirmationModalOpen(true);
    };

    const handlePayment = () => {
        if (!confirmationData) return;
        setIsProcessing(true);
        router.post(route('customer.invoices.pay', { invoice: invoice.id }), { payment_method: confirmationData.method.id }, {
            onSuccess: () => setIsConfirmationModalOpen(false),
            onError: (errors) => toast.error(Object.values(errors)[0] || 'Payment processing failed.'),
            onFinish: () => setIsProcessing(false),
        });
    };

    const formatRupiah = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);

    return (
        <AuthenticatedLayout>
            <Head title={`Checkout Invoice #${invoice.invoice_number}`} />

            <style>{`
                @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@400;600;700;800&display=swap');

                .ck-root {
                    font-family: 'Plus Jakarta Sans', sans-serif;
                    background: #0f0f0f;
                    min-height: 100vh;
                    padding: 28px 0 60px;
                }
                .ck-container {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 0 16px;
                }
                @media (min-width: 640px)  { .ck-container { padding: 0 24px; } }
                @media (min-width: 1024px) { .ck-container { padding: 0 32px; } }

                /* ── Back ── */
                .ck-back {
                    display: inline-flex; align-items: center; gap: 7px;
                    font-size: 0.8rem; font-weight: 600; color: rgba(255,255,255,0.4);
                    text-decoration: none; margin-bottom: 20px; transition: color 0.2s;
                }
                .ck-back:hover { color: #ff8c00; }

                /* ── Page heading ── */
                .ck-title {
                    font-family: 'Sora', sans-serif;
                    font-size: clamp(1.3rem, 3vw, 1.8rem);
                    font-weight: 800; color: #ffffff; letter-spacing: -0.5px;
                    margin-bottom: 4px;
                }
                .ck-subtitle {
                    font-size: 0.8rem; color: rgba(255,255,255,0.35); margin-bottom: 24px;
                }

                /* ── Layout grid ── */
                .ck-grid {
                    display: grid;
                    grid-template-columns: 1fr;
                    gap: 20px;
                    align-items: start;
                }
                @media (min-width: 1024px) {
                    .ck-grid { grid-template-columns: 1fr 380px; }
                }

                /* ── Section header ── */
                .ck-section-header { display: flex; align-items: center; margin-bottom: 16px; }
                .ck-section-title {
                    font-family: 'Sora', sans-serif; font-size: 0.78rem; font-weight: 700;
                    letter-spacing: 0.12em; text-transform: uppercase; color: #ff8c00; white-space: nowrap;
                }
                .ck-section-line {
                    flex: 1; height: 1px; margin-left: 12px;
                    background: linear-gradient(to right, rgba(255,140,0,0.4), transparent);
                }

                /* ── Payment methods card ── */
                .ck-methods-card {
                    background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07);
                    border-radius: 20px; padding: 22px; position: relative; overflow: hidden;
                }
                .ck-methods-card::before {
                    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
                    background: linear-gradient(90deg, transparent, rgba(255,140,0,0.3), transparent);
                }

                /* ── Method item ── */
                .ck-method-item {
                    display: flex; align-items: center; justify-content: space-between;
                    padding: 16px 18px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.07);
                    cursor: pointer; margin-bottom: 10px; transition: all 0.2s;
                    background: rgba(255,255,255,0.02);
                }
                .ck-method-item:last-child { margin-bottom: 0; }
                .ck-method-item:hover { border-color: rgba(255,140,0,0.25); background: rgba(255,140,0,0.03); }
                .ck-method-item.selected {
                    border-color: rgba(255,140,0,0.5);
                    background: rgba(255,140,0,0.07);
                    box-shadow: 0 0 0 1px rgba(255,140,0,0.25), inset 0 0 20px rgba(255,140,0,0.04);
                }
                .ck-method-left { flex: 1; }
                .ck-method-name {
                    font-size: 0.92rem; font-weight: 700; color: rgba(255,255,255,0.85);
                    margin-bottom: 3px;
                }
                .ck-method-item.selected .ck-method-name { color: #ffffff; }
                .ck-method-fee {
                    font-size: 0.75rem; font-weight: 500; color: rgba(255,255,255,0.35);
                }
                .ck-method-item.selected .ck-method-fee { color: rgba(255,180,80,0.6); }
                .ck-radio {
                    width: 22px; height: 22px; border-radius: 50%;
                    border: 2px solid rgba(255,255,255,0.15);
                    display: flex; align-items: center; justify-content: center;
                    flex-shrink: 0; transition: all 0.2s;
                }
                .ck-method-item.selected .ck-radio {
                    border-color: #ff8c00; background: rgba(255,140,0,0.15); color: #ff8c00;
                }

                /* ── Summary card ── */
                .ck-summary-card {
                    background: #1a1a1a; border: 1px solid rgba(255,255,255,0.07);
                    border-radius: 20px; overflow: hidden; position: relative;
                    position: sticky; top: 80px;
                }
                .ck-summary-card::before {
                    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
                    background: linear-gradient(90deg, transparent, rgba(255,140,0,0.35), transparent);
                }
                .ck-summary-header {
                    padding: 18px 20px 14px;
                    border-bottom: 1px solid rgba(255,255,255,0.05);
                }
                .ck-summary-title {
                    font-family: 'Sora', sans-serif; font-size: 0.78rem; font-weight: 700;
                    letter-spacing: 0.12em; text-transform: uppercase; color: #ff8c00;
                }
                .ck-summary-lines { padding: 16px 20px; display: flex; flex-direction: column; gap: 10px; }
                .ck-summary-row { display: flex; justify-content: space-between; align-items: center; }
                .ck-summary-label { font-size: 0.83rem; color: rgba(255,255,255,0.45); font-weight: 500; }
                .ck-summary-val { font-size: 0.83rem; color: rgba(255,255,255,0.75); font-weight: 600; }
                .ck-summary-row.discount .ck-summary-label { color: rgba(52,211,153,0.7); }
                .ck-summary-row.discount .ck-summary-val { color: #34d399; }
                .ck-grand-total {
                    display: flex; justify-content: space-between; align-items: center;
                    padding: 14px 20px; border-top: 1px solid rgba(255,255,255,0.06);
                    border-bottom: 1px solid rgba(255,255,255,0.06);
                }
                .ck-grand-label { font-size: 0.9rem; font-weight: 700; color: rgba(255,255,255,0.7); }
                .ck-grand-val {
                    font-family: 'Sora', sans-serif; font-size: 1.25rem;
                    font-weight: 800; color: #ff8c00; letter-spacing: -0.5px;
                }

                /* ── Voucher ── */
                .ck-voucher-section { padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,0.05); }
                .ck-voucher-label {
                    font-size: 0.72rem; font-weight: 700; letter-spacing: 0.06em;
                    text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 8px;
                }
                .ck-voucher-row { display: flex; gap: 8px; }
                .ck-voucher-input {
                    flex: 1; background: #111; border: 1px solid rgba(255,255,255,0.1);
                    border-radius: 9px; padding: 8px 12px; font-size: 0.83rem; font-weight: 600;
                    color: rgba(255,255,255,0.85); font-family: 'Courier New', monospace;
                    letter-spacing: 0.06em; outline: none; transition: border-color 0.2s;
                }
                .ck-voucher-input:focus { border-color: rgba(255,140,0,0.5); }
                .ck-voucher-input::placeholder { color: rgba(255,255,255,0.2); font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: 0; }
                .ck-voucher-btn {
                    padding: 8px 16px; border-radius: 9px; font-size: 0.8rem; font-weight: 700;
                    background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);
                    color: rgba(255,255,255,0.7); cursor: pointer; transition: all 0.2s;
                    font-family: 'Plus Jakarta Sans', sans-serif; display: flex; align-items: center;
                }
                .ck-voucher-btn:hover { background: rgba(255,255,255,0.14); color: #fff; }
                .ck-voucher-btn:disabled { opacity: 0.5; cursor: not-allowed; }
                .ck-field-error { font-size: 0.75rem; color: #f87171; font-weight: 600; margin-top: 6px; }
                .ck-discount-applied {
                    display: flex; align-items: center; justify-content: space-between;
                    padding: 10px 14px; border-radius: 10px;
                    background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2);
                }
                .ck-remove-discount {
                    display: inline-flex; align-items: center; gap: 4px;
                    font-size: 0.72rem; font-weight: 600; color: rgba(239,68,68,0.7);
                    background: none; border: none; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif;
                    transition: color 0.2s;
                }
                .ck-remove-discount:hover { color: #f87171; }
                .ck-voucher-item {
                    padding: 10px 12px; border-radius: 10px; border: 1px dashed;
                    transition: all 0.2s;
                }
                .ck-voucher-item.applicable {
                    border-color: rgba(255,140,0,0.25); background: rgba(255,140,0,0.04);
                    cursor: pointer; color: #ff8c00;
                }
                .ck-voucher-item.applicable:hover { border-color: rgba(255,140,0,0.5); background: rgba(255,140,0,0.09); }
                .ck-voucher-item.disabled {
                    border-color: rgba(255,255,255,0.07); background: rgba(255,255,255,0.02);
                    cursor: not-allowed; color: rgba(255,255,255,0.3); opacity: 0.6;
                }

                /* ── Pay button ── */
                .ck-pay-wrap { padding: 16px 20px 20px; }
                .ck-pay-btn {
                    width: 100%; padding: 12px; border-radius: 12px;
                    background: linear-gradient(135deg, #ff8c00, #ff6a00);
                    color: white; font-size: 0.9rem; font-weight: 700;
                    border: none; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif;
                    box-shadow: 0 4px 18px rgba(255,110,0,0.4); transition: all 0.2s;
                    display: flex; align-items: center; justify-content: center; gap: 8px;
                }
                .ck-pay-btn:hover:not(:disabled) { box-shadow: 0 6px 24px rgba(255,110,0,0.55); transform: translateY(-1px); }
                .ck-pay-btn:disabled { opacity: 0.45; cursor: not-allowed; box-shadow: none; transform: none; }
                .ck-pay-hint { font-size: 0.72rem; color: rgba(255,255,255,0.3); text-align: center; margin-top: 8px; font-weight: 500; }

                /* ── Modal internals ── */
                .ck-modal-lines {
                    background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07);
                    border-radius: 12px; padding: 14px 16px; margin-bottom: 12px;
                    display: flex; flex-direction: column; gap: 10px;
                }
                .ck-modal-row { display: flex; justify-content: space-between; align-items: center; }
                .ck-modal-key { font-size: 0.8rem; color: rgba(255,255,255,0.4); font-weight: 500; }
                .ck-modal-val { font-size: 0.83rem; color: rgba(255,255,255,0.8); font-weight: 700; }
                .ck-modal-total-box {
                    background: rgba(255,140,0,0.08); border: 1px solid rgba(255,140,0,0.2);
                    border-radius: 12px; padding: 14px 16px;
                    display: flex; justify-content: space-between; align-items: center;
                }
                .ck-modal-total-label { font-size: 0.85rem; font-weight: 700; color: rgba(255,255,255,0.7); }
                .ck-modal-total-val {
                    font-family: 'Sora', sans-serif; font-size: 1.2rem;
                    font-weight: 800; color: #ff8c00; letter-spacing: -0.5px;
                }
                .ck-modal-hint {
                    font-size: 0.78rem; color: rgba(255,255,255,0.35); text-align: center;
                    margin-bottom: 14px;
                }
            `}</style>

            {/* ── Confirmation Modal ── */}
            <ConfirmationModal
                isOpen={isConfirmationModalOpen}
                onClose={() => setIsConfirmationModalOpen(false)}
                onConfirm={handlePayment}
                title="Konfirmasi Pembayaran"
                isProcessing={isProcessing}
            >
                {confirmationData && (
                    <>
                        <p className="ck-modal-hint">Harap periksa detail sebelum melanjutkan pembayaran.</p>
                        <div className="ck-modal-lines">
                            <div className="ck-modal-row">
                                <span className="ck-modal-key">Metode</span>
                                <span className="ck-modal-val">{confirmationData.method.name}</span>
                            </div>
                            <div className="ck-modal-row">
                                <span className="ck-modal-key">Tagihan</span>
                                <span className="ck-modal-val">{formatRupiah(invoice.balance_due)}</span>
                            </div>
                            <div className="ck-modal-row">
                                <span className="ck-modal-key">Biaya Admin</span>
                                <span className="ck-modal-val">{formatRupiah(confirmationData.fee)}</span>
                            </div>
                        </div>
                        <div className="ck-modal-total-box">
                            <span className="ck-modal-total-label">Total Pembayaran</span>
                            <span className="ck-modal-total-val">{formatRupiah(confirmationData.total)}</span>
                        </div>
                    </>
                )}
            </ConfirmationModal>

            <div className="ck-root">
                <div className="ck-container">

                    {/* ── Back ── */}
                    <Link href={route('customer.invoices.show', { invoice: invoice.id })} className="ck-back">
                        <BackIcon /> Kembali ke Invoice
                    </Link>

                    {/* ── Heading ── */}
                    <div className="ck-title">Pilih Metode Pembayaran</div>
                    <div className="ck-subtitle">Invoice #{invoice.invoice_number}</div>

                    <div className="ck-grid">

                        {/* ── Payment Methods ── */}
                        <div>
                            <div className="ck-section-header">
                                <div className="ck-section-title">Metode Pembayaran</div>
                                <div className="ck-section-line" />
                            </div>
                            <div className="ck-methods-card">
                                {paymentMethods.length === 0 ? (
                                    <div style={{ textAlign: 'center', padding: '40px 20px', color: 'rgba(255,255,255,0.25)' }}>
                                        <p style={{ fontWeight: 600 }}>Tidak ada metode pembayaran tersedia</p>
                                    </div>
                                ) : (
                                    paymentMethods.map((method) => {
                                        const isSelected = selectedMethod && selectedMethod.id === method.id;
                                        const fee = calculateFee(method.fee);
                                        return (
                                            <div
                                                key={method.id}
                                                onClick={() => setSelectedMethod(method)}
                                                className={`ck-method-item ${isSelected ? 'selected' : ''}`}
                                            >
                                                <div className="ck-method-left">
                                                    <div className="ck-method-name">{method.name}</div>
                                                    <div className="ck-method-fee">
                                                        Biaya Admin: {formatRupiah(fee)}
                                                    </div>
                                                </div>
                                                <div className="ck-radio">
                                                    {isSelected && <CheckCircleIcon />}
                                                </div>
                                            </div>
                                        );
                                    })
                                )}
                            </div>
                        </div>

                        {/* ── Order Summary ── */}
                        <div>
                            <div className="ck-section-header">
                                <div className="ck-section-title">Ringkasan</div>
                                <div className="ck-section-line" />
                            </div>
                            <OrderSummary
                                invoice={invoice}
                                claimedDiscounts={claimedDiscounts}
                                selectedMethod={selectedMethod}
                                handleOpenConfirmation={handleOpenConfirmation}
                                isProcessing={isProcessing}
                                summary={summary}
                            />
                        </div>

                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}