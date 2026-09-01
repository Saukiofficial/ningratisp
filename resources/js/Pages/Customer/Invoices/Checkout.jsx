import React, { useState, useEffect } from 'react';
import { Head, Link, router, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { toast } from 'react-toastify';
import { route } from 'ziggy-js';
import './Checkout.css';

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
                    background: 'var(--card-bg)', border: '1px solid var(--border-color)',
                    borderRadius: 22, width: '100%', maxWidth: 440,
                    boxShadow: '0 24px 80px rgba(0,0,0,0.8)', overflow: 'hidden',
                    fontFamily: "'Plus Jakarta Sans', sans-serif",
                }}
                onClick={(e) => e.stopPropagation()}
            >
                {/* Modal header */}
                <div style={{
                    padding: '28px 28px 20px', textAlign: 'center',
                    borderBottom: '1px solid var(--border-color)',
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
                        fontWeight: 800, color: 'var(--text-primary)', letterSpacing: -0.5,
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
                            background: 'var(--nav-hover-bg)', border: '1px solid var(--border-color)',
                            color: 'var(--text-secondary)', fontSize: '0.85rem', fontWeight: 600,
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

const OrderSummary = ({ invoice, claimedDiscounts = [], selectedMethod, handleOpenConfirmation, isProcessing, summary, selectedDiscount, setSelectedDiscount, onRemoveDiscountTrigger }) => {
    const { data, setData, post, processing, errors, clearErrors } = useForm({ code: '' });

    const handleClaimVoucher = (e) => {
        e.preventDefault();
        if (!data.code.trim()) return;
        post(route('customer.discounts.claim'), {
            preserveScroll: true,
            onSuccess: () => setData('code', ''),
        });
    };
    const handleApplyDiscount = (discount) => {
        setSelectedDiscount(discount);
    };
    const handleRemoveDiscount = () => {
        if (invoice.discount_id && selectedDiscount?.id === invoice.discount_id) {
            onRemoveDiscountTrigger();
        } else {
            setSelectedDiscount(null);
        }
    };

    const formatRupiah = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);

    const processedDiscounts = claimedDiscounts.map(claimed => {
        let discountAmount = 0;
        const subtotal = parseFloat(invoice.subtotal);
        if (claimed.discount.type === 'percentage') {
            discountAmount = (subtotal * parseFloat(claimed.discount.value)) / 100;
            const maxCap = parseFloat(claimed.discount.max_discount_amount);
            if (maxCap > 0 && discountAmount > maxCap) {
                discountAmount = maxCap;
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
                {summary.discount > 0 && (
                    <div className="ck-summary-row discount">
                        <span className="ck-summary-label">Diskon ({selectedDiscount?.name})</span>
                        <span className="ck-summary-val">−{formatRupiah(summary.discount)}</span>
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
                {selectedDiscount ? (
                    <div className="ck-discount-applied">
                        <div style={{ display: 'flex', alignItems: 'center', gap: 7 }}>
                            <TagIcon />
                            <span style={{ fontSize: '0.83rem', fontWeight: 700, color: '#34d399' }}>
                                {selectedDiscount.name} diterapkan
                            </span>
                        </div>
                        <button onClick={handleRemoveDiscount} className="ck-remove-discount">
                            <TrashIcon /> Hapus
                        </button>
                    </div>
                ) : (
                    <>
                        <div className="ck-voucher-label">Punya Voucher?</div>
                        <form onSubmit={handleClaimVoucher} className="ck-voucher-row">
                            <input
                                type="text"
                                value={data.code}
                                onChange={e => setData('code', e.target.value.toUpperCase().replaceAll(' ', ''))}
                                onFocus={() => clearErrors('code')}
                                placeholder="Kode voucher"
                                className="ck-voucher-input"
                                maxLength={10}
                            />
                            <button type="submit" disabled={processing || !data.code.trim()} className="ck-voucher-btn">
                                {processing ? <SpinnerIcon /> : 'Claim'}
                            </button>
                        </form>
                        {errors.code && <p className="ck-field-error">{errors.code}</p>}

                        {processedDiscounts.length > 0 && (
                            <div style={{ marginTop: 14 }}>
                                <div className="ck-voucher-label" style={{ marginBottom: 8 }}>Voucher Tersedia</div>
                                <div style={{ display: 'flex', flexDirection: 'column', gap: 8, maxHeight: 160, overflowY: 'auto' }}>
                                    {processedDiscounts.map(pd => (
                                        <div
                                            key={pd.id}
                                            onClick={() => pd.isApplicable && handleApplyDiscount(pd.discount)}
                                            className={`ck-voucher-item ${pd.isApplicable ? 'applicable' : 'disabled'}`}
                                            title={!pd.isApplicable ? 'Diskon terlalu besar untuk invoice ini' : ''}
                                        >
                                            <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                                                <TagIcon />
                                                <span style={{ fontWeight: 700, fontSize: '0.83rem' }}>{pd.discount.name}</span>
                                            </div>
                                            <p style={{ fontSize: '0.75rem', marginTop: 2, color: 'var(--text-secondary)', opacity: 0.7 }}>{pd.discount.description}</p>
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
    const [selectedDiscount, setSelectedDiscount] = useState(invoice.discount || null);
    const [isProcessing, setIsProcessing] = useState(false);
    const [isConfirmationModalOpen, setIsConfirmationModalOpen] = useState(false);
    const [isRemoveDiscountModalOpen, setIsRemoveDiscountModalOpen] = useState(false);
    const [confirmationData, setConfirmationData] = useState(null);
    const [summary, setSummary] = useState({ fee: 0, discount: 0, grandTotal: invoice.balance_due });

    const handleConfirmRemoveDiscount = () => {
        setIsProcessing(true);
        router.post(route('customer.invoices.remove-discount', { invoice: invoice.id }), {}, {
            onSuccess: () => {
                setSelectedDiscount(null);
                setIsRemoveDiscountModalOpen(false);
            },
            onError: () => toast.error('Gagal menghapus diskon'),
            onFinish: () => setIsProcessing(false),
        });
    };

    const calculateDiscountAmount = (discount, subtotal) => {
        if (!discount) return 0;
        let amount = 0;
        if (discount.type === 'percentage') {
            amount = (subtotal * parseFloat(discount.value)) / 100;
            const maxCap = parseFloat(discount.max_discount_amount);
            if (maxCap > 0 && amount > maxCap) {
                amount = maxCap;
            }
        } else if (discount.type === 'fixed_amount') {
            amount = parseFloat(discount.value);
        }
        return Math.min(subtotal, amount);
    };

    const calculateFee = (fee, currentBalance) => {
        if (!fee) return 0;
        return fee.unit === 'p' ? (currentBalance * parseFloat(fee.amount)) / 100 : parseFloat(fee.amount);
    };

    useEffect(() => {
        const subtotal = parseFloat(invoice.subtotal);
        const discountAmount = calculateDiscountAmount(selectedDiscount, subtotal);
        const balanceAfterDiscount = subtotal - discountAmount;

        if (selectedMethod) {
            const fee = calculateFee(selectedMethod.fee, balanceAfterDiscount);
            setSummary({
                fee,
                discount: discountAmount,
                grandTotal: balanceAfterDiscount + fee
            });
        } else {
            setSummary({
                fee: 0,
                discount: discountAmount,
                grandTotal: balanceAfterDiscount
            });
        }
    }, [selectedMethod, selectedDiscount, invoice.subtotal]);

    const handleOpenConfirmation = () => {
        if (!selectedMethod) return;
        setConfirmationData({ method: selectedMethod, fee: summary.fee, total: summary.grandTotal });
        setIsConfirmationModalOpen(true);
    };

    const handlePayment = () => {
        if (!confirmationData) return;
        setIsProcessing(true);
        router.post(route('customer.invoices.pay', { invoice: invoice.id }), {
            payment_method: confirmationData.method.id,
            discount_id: selectedDiscount?.id
        }, {
            onSuccess: () => setIsConfirmationModalOpen(false),
            onError: (errors) => toast.error(Object.values(errors)[0] || 'Payment processing failed.', {
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
            }),
            onFinish: () => setIsProcessing(false),
        });
    };

    const formatRupiah = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);

    return (
        <AuthenticatedLayout>
            <Head title={`Checkout Invoice #${invoice.invoice_number}`} />

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

            {/* ── Remove Discount Confirmation Modal ── */}
            <ConfirmationModal
                isOpen={isRemoveDiscountModalOpen}
                onClose={() => setIsRemoveDiscountModalOpen(false)}
                onConfirm={handleConfirmRemoveDiscount}
                title="Hapus Diskon?"
                isProcessing={isProcessing}
            >
                <p className="ck-modal-hint">Apakah Anda yakin ingin menghapus diskon yang sudah terpasang pada invoice ini?</p>
                <div style={{
                    padding: '12px 16px', borderRadius: 12, background: 'rgba(239,68,68,0.08)',
                    border: '1px solid rgba(239,68,68,0.2)', color: '#f87171', fontSize: '0.8rem',
                    textAlign: 'center', fontWeight: 500
                }}>
                    Tindakan ini akan mengatur ulang total tagihan Anda.
                </div>
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
                                    <div style={{ textAlign: 'center', padding: '40px 20px', color: 'var(--text-secondary)', opacity: 0.5 }}>
                                        <p style={{ fontWeight: 600 }}>Tidak ada metode pembayaran tersedia</p>
                                    </div>
                                ) : (
                                    paymentMethods.map((method) => {
                                        const isSelected = selectedMethod && selectedMethod.id === method.id;
                                        const feeLabel = method.fee.unit == 'p' ? method.fee.amount + ' %' : formatRupiah(method.fee.amount)
                                        return (
                                            <div
                                                key={method.id}
                                                onClick={() => setSelectedMethod(method)}
                                                className={`ck-method-item ${isSelected ? 'selected' : ''}`}
                                            >
                                                <div className="ck-method-left">
                                                    <div className="ck-method-name">{method.name}</div>
                                                    <div className="ck-method-fee">
                                                        Biaya Admin: {feeLabel}
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
                                selectedDiscount={selectedDiscount}
                                setSelectedDiscount={setSelectedDiscount}
                                onRemoveDiscountTrigger={() => setIsRemoveDiscountModalOpen(true)}
                            />
                        </div>

                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
