import React, { useEffect, useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { toast } from 'react-toastify';
// import { useEcho } from '@laravel/echo-react';
import { route } from 'ziggy-js';
import {
    Clock,
    Copy,
    Check,
    XCircle,
    Download,
    ShieldCheck,
    RefreshCcw,
    ArrowLeft,
    QrCode,
    CreditCard
} from 'lucide-react';
import './PendingPayment.css';

// ─── Sub-Components ──────────────────────────────────────────────────────────

const ConfirmationModal = ({ isOpen, onClose, onConfirm, title, children, isProcessing }) => {
    if (!isOpen) return null;
    return (
        <div className="fixed inset-0 bg-black/80 backdrop-blur-sm flex justify-center items-center z-[9999] p-4" onClick={onClose}>
            <div className="pp-modal-card" onClick={(e) => e.stopPropagation()}>
                <div className="pp-modal-header">
                    <div className="pp-modal-icon red">
                        <XCircle size={32} />
                    </div>
                    <h3 className="pp-modal-title">{title}</h3>
                </div>
                <div className="pp-modal-body">{children}</div>
                <div className="pp-modal-footer">
                    <button onClick={onClose} disabled={isProcessing} className="pp-btn-ghost">Tidak, Kembali</button>
                    <button onClick={onConfirm} disabled={isProcessing} className="pp-btn-danger">
                        {isProcessing ? <RefreshCcw className="animate-spin" size={18} /> : 'Ya, Batalkan'}
                    </button>
                </div>
            </div>
        </div>
    );
};

const PaymentSuccessModal = ({ isOpen, onClose, countdown }) => {
    if (!isOpen) return null;
    const progress = (countdown / 10) * 100;
    return (
        <div className="fixed inset-0 bg-black/80 backdrop-blur-sm flex justify-center items-center z-[9999] p-4">
            <div className="pp-modal-card success">
                <div className="pp-modal-header success">
                    <div className="pp-modal-icon success">
                        <Check size={40} strokeWidth={3} />
                    </div>
                    <h3 className="pp-modal-title">Pembayaran Berhasil!</h3>
                </div>
                <div className="pp-modal-body">
                    <p>Terima kasih! Pembayaran Anda telah kami terima dan tagihan telah otomatis dilunasi.</p>
                    <div className="pp-success-timer">
                        <p>Mengalihkan Anda dalam <strong>{countdown}</strong> detik...</p>
                        <div className="pp-progress-bar">
                            <div className="pp-progress-fill" style={{ width: `${progress}%` }} />
                        </div>
                    </div>
                </div>
                <div className="pp-modal-footer">
                    <button onClick={onClose} className="pp-btn-success">Lihat Invoice Sekarang</button>
                </div>
            </div>
        </div>
    );
};

// ─── Main Component ───────────────────────────────────────────────────────────

export default function PendingPayment({ virtualAccount, flash }) {
    const [timeLeft, setTimeLeft] = useState(null);
    const [isNearExpiry, setIsNearExpiry] = useState(false);
    const [isCancelling, setIsCancelling] = useState(false);
    const [isCheckingStatus, setIsCheckingStatus] = useState(false);
    const [isCopied, setIsCopied] = useState(false);
    const [isCancelModalOpen, setIsCancelModalOpen] = useState(false);
    const [isPaymentSuccess, setIsPaymentSuccess] = useState(false);
    const [paidInvoiceId, setPaidInvoiceId] = useState(null);
    const [countdown, setCountdown] = useState(10);

    const { auth } = usePage().props;

    // useEcho(`InvoicePaid.${auth.user.id}`, 'CustomerInvoicePaidEvent', (e) => {
    //     if (e.va && e.va.id === virtualAccount.id) {
    //         setPaidInvoiceId(e.va.invoice_id);
    //         setIsPaymentSuccess(true);
    //     }
    // });

    useEffect(() => {
        const calculateTimeLeft = () => {
            const expiryTime = new Date(virtualAccount.expired_at).getTime();
            const now = new Date().getTime();
            const distance = expiryTime - now;

            if (distance < 0) {
                setTimeLeft('Expired');
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if (distance < 1000 * 60 * 30) setIsNearExpiry(true);

            setTimeLeft(`${hours}j ${minutes}m ${seconds}d`);
        };

        const timer = setInterval(calculateTimeLeft, 1000);
        calculateTimeLeft();
        return () => clearInterval(timer);
    }, [virtualAccount.expired_at]);

    useEffect(() => {
        let timer;
        let countdownInterval;
        if (isPaymentSuccess) {
            timer = setTimeout(() => router.visit(route('customer.invoices.show', paidInvoiceId)), 10000);
            setCountdown(10);
            countdownInterval = setInterval(() => {
                setCountdown(prev => prev > 0 ? prev - 1 : 0);
            }, 1000);
        }
        return () => {
            clearTimeout(timer);
            clearInterval(countdownInterval);
        };
    }, [isPaymentSuccess, paidInvoiceId]);

    const formatRupiah = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);

    const handleCancel = () => {
        setIsCancelling(true);
        router.post(route('customer.virtual-accounts.cancel', virtualAccount.id), {}, {
            onSuccess: () => {
                setIsCancelling(false);
                setIsCancelModalOpen(false);
            },
            onError: (err) => {
                setIsCancelling(false);
                toast.error(Object.values(err)[0] || 'Gagal membatalkan.', {
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                });
            },
        });
    };

    const handleCheckStatus = () => {
        setIsCheckingStatus(true);
        router.post(route('customer.virtual-accounts.check-status', virtualAccount.id), {}, {
            onFinish: () => {
                setIsCheckingStatus(false);
            },
        });
    };

    const copyToClipboard = () => {
        navigator.clipboard.writeText(virtualAccount.va_number).then(() => {
            setIsCopied(true);
            toast.success('Nomor Virtual Account disalin!', {
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
            });
            setTimeout(() => setIsCopied(false), 2000);
        });
    };

    const downloadQris = (url, invoiceNumber) => {
        fetch(url)
            .then(res => res.blob())
            .then(blob => {
                const bUrl = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = bUrl; a.download = `QRIS_${invoiceNumber}.png`;
                document.body.appendChild(a); a.click();
                document.body.removeChild(a); URL.revokeObjectURL(bUrl);
                toast.success('QRIS berhasil diunduh!', {
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                });
            });
    };

    return (
        <AuthenticatedLayout>
            <Head title={`Menunggu Pembayaran #${virtualAccount.invoice.invoice_number}`} />

            <PaymentSuccessModal isOpen={isPaymentSuccess} onClose={() => router.visit(route('customer.invoices.show', paidInvoiceId))} countdown={countdown} />

            <ConfirmationModal
                isOpen={isCancelModalOpen}
                onClose={() => setIsCancelModalOpen(false)}
                onConfirm={handleCancel}
                title="Batalkan Pembayaran?"
                isProcessing={isCancelling}
            >
                <p>Tindakan ini akan membatalkan nomor tagihan Virtual Account Anda. Apakah Anda yakin ingin melanjutkan?</p>
            </ConfirmationModal>

            <div className="pp-root">
                <div className="pp-container">

                    {/* Back Link */}
                    <Link href={route('customer.invoices.index')} className="sh-back" style={{ marginBottom: 24, display: 'inline-flex', alignItems: 'center', gap: 8, textDecoration: 'none' }}>
                        <ArrowLeft size={16} /> <span>Kembali ke Tagihan</span>
                    </Link>

                    {/* Main Card */}
                    <div className="pp-card">
                        <div className="pp-header">
                            <div className="pp-brand-badge">
                                <ShieldCheck size={12} /> Pembayaran Aman
                            </div>
                            <h1 className="pp-title">Selesaikan Pembayaran</h1>
                            <p className="pp-subtitle">Invoice #{virtualAccount.invoice.invoice_number}</p>

                            <div className={`pp-timer-box ${isNearExpiry ? 'pulse' : ''}`}>
                                <div className="pp-timer-label">
                                    <Clock size={12} style={{ display: 'inline', marginRight: 4, verticalAlign: 'middle' }} />
                                    Batas Waktu
                                </div>
                                <div className={`pp-timer-value ${isNearExpiry ? 'pulse' : ''}`}>{timeLeft}</div>
                            </div>
                        </div>

                        <div className="pp-body">
                            <div className="pp-amount-card">
                                <p className="pp-amount-label">Total Pembayaran</p>
                                <div className="pp-amount-value">{formatRupiah(virtualAccount.total_amount)}</div>
                            </div>

                            {/* QRIS Display */}
                            {virtualAccount.payment_type === 'gopay' && virtualAccount.qris_url && (
                                <div className="pp-qris-wrap">
                                    <div className="pp-brand-badge" style={{ background: 'rgba(52,211,153,0.1)', color: '#10b981', marginBottom: 20 }}>
                                        <QrCode size={12} /> Scan QRIS
                                    </div>
                                    <div className="pp-qris-img-container">
                                        <img src={route('qris.proxy', virtualAccount.transaction_id)} alt="QRIS Code" className="pp-qris-img" />
                                    </div>
                                    <button
                                        onClick={() => downloadQris(route('qris.proxy', virtualAccount.transaction_id), virtualAccount.invoice.invoice_number)}
                                        className="pp-btn-primary" style={{ margin: '0 auto', minWidth: 180 }}
                                    >
                                        <Download size={18} /> Unduh QRIS
                                    </button>
                                </div>
                            )}

                            {/* VA Display */}
                            {(virtualAccount.payment_type === 'bank_transfer' || virtualAccount.payment_type === 'echannel') && virtualAccount.va_number && (
                                <div className="pp-va-box">
                                    <div className="pp-va-header">
                                        <div className="pp-va-method">
                                            <CreditCard size={14} style={{ display: 'inline', marginRight: 6 }} />
                                            {virtualAccount.payment_method.name} (VA)
                                        </div>
                                        <div className="pp-brand-badge" style={{ margin: 0 }}>Bank Transfer</div>
                                    </div>
                                    <div className="pp-va-number-row">
                                        <div className="pp-va-number">{virtualAccount.va_number}</div>
                                        <button onClick={copyToClipboard} className="pp-copy-btn" title="Salin VA">
                                            {isCopied ? <Check size={18} /> : <Copy size={18} />}
                                        </button>
                                    </div>
                                </div>
                            )}

                            {/* Actions */}
                            <div className="pp-actions">
                                <button
                                    onClick={handleCheckStatus}
                                    disabled={isCheckingStatus}
                                    className="pp-btn-primary"
                                >
                                    {isCheckingStatus ? <RefreshCcw className="animate-spin" size={18} /> : <Check size={18} />}
                                    Cek Status
                                </button>
                                <button
                                    onClick={() => setIsCancelModalOpen(true)}
                                    className="pp-btn-secondary"
                                >
                                    <XCircle size={18} />
                                    Batalkan
                                </button>
                            </div>
                        </div>
                    </div>

                    {/* Instruction Link */}
                    <Link href={route('customer.invoices.index')} className="pp-footer-nav">
                        <ArrowLeft size={14} /> Lihat daftar tagihan lainnya
                    </Link>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
