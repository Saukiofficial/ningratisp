import React from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { useFlash } from '@/Hooks/useFlash';
import { Sun, Moon, Laptop, ChevronDown } from 'lucide-react';
import ContentLoader from 'react-content-loader';
import './AuthenticatedLayout.css';

const HomeIcon = ({ isActive }) => (
    <svg className={`w-5 h-5 ${isActive ? 'text-[#ff8c00]' : 'text-current'}`} aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
    </svg>
);

const DocumentIcon = ({ isActive }) => (
    <svg className={`w-5 h-5 ${isActive ? 'text-[#ff8c00]' : 'text-current'}`} aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
        <path d="M16 14V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v15a3 3 0 0 0 3 3h12a1 1 0 0 0 0-2h-1v-2a2 2 0 0 0 2-2ZM4 2h2v12H4V2Zm8 16H3a1 1 0 0 1 0-2h9v2Z" />
    </svg>
);

const AccountIcon = ({ isActive }) => (
    <svg className={`w-5 h-5 ${isActive ? 'text-[#ff8c00]' : 'text-current'}`} aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
        <path d="M8 10a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm0 2c-4.42 0-8 2.24-8 5v1a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-1c0-2.76-3.58-5-8-5Z" />
    </svg>
);

const LogoutIcon = () => (
    <svg className="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
    </svg>
);

const WifiLogoIcon = () => (
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5} style={{ width: 22, height: 22, color: 'var(--logo-icon-color, white)' }}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z" />
    </svg>
);

// ─── Skeleton Component ──────────────────────────────────────────────────────

const PageSkeleton = () => {
    const isDark = document.documentElement.classList.contains('dark');
    const bgColor = isDark ? '#1a1a1a' : '#f1f5f9';
    const fgColor = isDark ? '#262626' : '#e2e8f0';

    return (
        <div className="nnl-page-skeleton">
            <ContentLoader
                speed={2}
                width="100%"
                height={600}
                viewBox="0 0 800 600"
                backgroundColor={bgColor}
                foregroundColor={fgColor}
                uniqueKey="page-skeleton"
            >
                {/* Header/Hero area */}
                <rect x="0" y="0" rx="24" ry="24" width="100%" height="200" />
                
                {/* Content blocks */}
                <rect x="0" y="230" rx="20" ry="20" width="31%" height="140" />
                <rect x="34.5%" y="230" rx="20" ry="20" width="31%" height="140" />
                <rect x="69%" y="230" rx="20" ry="20" width="31%" height="140" />
                
                <rect x="0" y="400" rx="24" ry="24" width="100%" height="200" />
            </ContentLoader>
        </div>
    );
};

export default function AuthenticatedLayout({ children }) {
    const { url, props } = usePage();
    const { appEnv, auth } = props;
    const user = auth.user;
    useFlash();

    const [isPageLoading, setIsPageLoading] = React.useState(false);
    const [theme, setTheme] = React.useState(() => {
        return localStorage.getItem('theme') || 'system';
    });
    const [isThemeMenuOpen, setIsThemeMenuOpen] = React.useState(false);

    const [showOnboarding, setShowOnboarding] = React.useState(false);
    const [onboardingStep, setOnboardingStep] = React.useState(0);

    const isProfileIncomplete = user?.is_customer && (!user?.full_name || !user?.whatsapp_number || !user?.latitude || !user?.longitude);
    const isAccountPage = url.startsWith('/account') || url.startsWith('/customer/account');

    React.useEffect(() => {
        const removeStartListener = router.on('start', () => setIsPageLoading(true));
        const removeFinishListener = router.on('finish', () => setIsPageLoading(false));
        return () => {
            removeStartListener();
            removeFinishListener();
        };
    }, []);

    React.useEffect(() => {
        const root = window.document.documentElement;

        const applyTheme = (targetTheme) => {
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const activeTheme = targetTheme === 'system' ? systemTheme : targetTheme;

            root.classList.remove('light', 'dark');
            root.classList.add(activeTheme);
            root.style.colorScheme = activeTheme;
        };

        applyTheme(theme);
        localStorage.setItem('theme', theme);

        if (theme === 'system') {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            const handleChange = () => applyTheme('system');
            mediaQuery.addEventListener('change', handleChange);
            return () => mediaQuery.removeEventListener('change', handleChange);
        }
    }, [theme]);

    React.useEffect(() => {
        if (isProfileIncomplete && !isAccountPage) {
            router.get(route('customer.account.edit'));
        }
    }, [isProfileIncomplete, isAccountPage]);

    React.useEffect(() => {
        const hasSeenOnboarding = localStorage.getItem(`onboarding_seen_${user?.id}`);
        if (!hasSeenOnboarding && user?.is_customer && !isProfileIncomplete) {
            setShowOnboarding(true);
        }
    }, [user, isProfileIncomplete]);

    const completeOnboarding = () => {
        localStorage.setItem(`onboarding_seen_${user?.id}`, 'true');
        setShowOnboarding(false);
    };

    const handleLogout = (e) => {
        e.preventDefault();
        router.post((route('customer.logout')));
    };

    const toggleTheme = (newTheme) => {
        setTheme(newTheme);
    };

    const onboardingSteps = [
        {
            title: "Selamat Datang di Ningrat Net!",
            content: "Terima kasih telah melengkapi profil Anda. Mari kita lihat sekilas fitur utama aplikasi ini.",
            target: null
        },
        {
            title: "Dashboard Utama",
            content: "Di sini Anda bisa melihat status koneksi, paket aktif, dan ringkasan tagihan Anda.",
            target: "dashboard"
        },
        {
            title: "Menu Tagihan",
            content: "Klik di sini untuk melihat riwayat tagihan dan melakukan pembayaran dengan berbagai metode.",
            target: "tagihan"
        },
        {
            title: "Pengaturan Akun",
            content: "Kelola informasi kontak, alamat, lokasi pemasangan, dan ganti password di menu ini.",
            target: "akun"
        }
    ];

    const nextStep = () => {
        if (onboardingStep < onboardingSteps.length - 1) {
            setOnboardingStep(onboardingStep + 1);
        } else {
            completeOnboarding();
        }
    };

    const isActive = (path) => {
        const currentPath = url.split('?')[0];
        
        // Exact matches for dashboard variations
        if (path === '/dashboard') {
            return currentPath === '/dashboard' || 
                   currentPath === '/customer/dashboard' || 
                   currentPath === '/customer' ||
                   currentPath === '/';
        }
        
        // Prefix matches for other sections
        if (path === '/tagihan') {
            return currentPath === '/tagihan' || 
                   currentPath.startsWith('/tagihan/') || 
                   currentPath === '/customer/invoices' || 
                   currentPath.startsWith('/customer/invoices/') ||
                   currentPath.startsWith('/pembayaran/'); // Related payment pages
        }
        
        if (path === '/account') {
            return currentPath === '/account' || 
                   currentPath.startsWith('/account/') || 
                   currentPath === '/customer/account' || 
                   currentPath.startsWith('/customer/account/');
        }

        return currentPath === path || currentPath.startsWith(path + '/');
    };

    return (
        <>
            <div className="nnl-root">

                {/* ── Profile Incomplete Alert ── */}
                {isProfileIncomplete && (
                    <div className="nnl-profile-alert">
                        Mohon lengkapi Nama, WhatsApp, Latitude, dan Longitude Anda untuk melanjutkan.
                    </div>
                )}

                {/* ── Onboarding Overlay ── */}
                {showOnboarding && (
                    <div className="nnl-onboard-overlay">
                        <div className="nnl-onboard-card">
                            <div className="nnl-onboard-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" style={{ width: 32, height: 32 }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                                </svg>
                            </div>
                            <h3 className="nnl-onboard-title">{onboardingSteps[onboardingStep].title}</h3>
                            <p className="nnl-onboard-content">{onboardingSteps[onboardingStep].content}</p>
                            <button onClick={nextStep} className="nnl-onboard-btn">
                                {onboardingStep === onboardingSteps.length - 1 ? 'Mulai Sekarang' : 'Lanjut'}
                            </button>
                            <div className="nnl-onboard-steps">
                                {onboardingSteps.map((_, i) => (
                                    <div key={i} className={`nnl-onboard-dot ${i === onboardingStep ? 'active' : ''}`} />
                                ))}
                            </div>
                        </div>
                    </div>
                )}

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
                                id="nav-dashboard"
                                href={route('customer.dashboard')}
                                className={`nnl-nav-link ${isActive('/dashboard') ? 'active' : ''} ${showOnboarding && onboardingSteps[onboardingStep].target === 'dashboard' ? 'nnl-highlight' : ''}`}
                            >
                                <HomeIcon isActive={isActive('/dashboard')} />
                                Dashboard
                            </Link>
                            <Link
                                id="nav-tagihan"
                                href={route('customer.invoices.index')}
                                className={`nnl-nav-link ${isActive('/tagihan') ? 'active' : ''} ${showOnboarding && onboardingSteps[onboardingStep].target === 'tagihan' ? 'nnl-highlight' : ''}`}
                            >
                                <DocumentIcon isActive={isActive('/tagihan')} />
                                Tagihan
                            </Link>
                            <Link
                                id="nav-akun"
                                href={route('customer.account.edit')}
                                className={`nnl-nav-link ${isActive('/account') ? 'active' : ''} ${showOnboarding && onboardingSteps[onboardingStep].target === 'akun' ? 'nnl-highlight' : ''}`}
                            >
                                <AccountIcon isActive={isActive('/account')} />
                                Akun
                            </Link>
                        </nav>

                        {/* Theme + Logout */}
                        <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                            <div className="nnl-theme-wrap">
                                <button
                                    onClick={() => setIsThemeMenuOpen(!isThemeMenuOpen)}
                                    className="nnl-theme-btn"
                                    aria-label="Switch Theme"
                                >
                                    {theme === 'light' && <Sun size={16} />}
                                    {theme === 'dark' && <Moon size={16} />}
                                    {theme === 'system' && <Laptop size={16} />}
                                    <ChevronDown size={12} style={{ opacity: 0.5 }} />
                                </button>

                                {isThemeMenuOpen && (
                                    <>
                                        <div
                                            style={{ position: 'fixed', inset: 0, zIndex: 55 }}
                                            onClick={() => setIsThemeMenuOpen(false)}
                                        />
                                        <div className="nnl-theme-menu">
                                            <button
                                                onClick={() => { toggleTheme('light'); setIsThemeMenuOpen(false); }}
                                                className={`nnl-theme-item ${theme === 'light' ? 'active' : ''}`}
                                            >
                                                <Sun size={14} /> Terang (Light)
                                            </button>
                                            <button
                                                onClick={() => { toggleTheme('dark'); setIsThemeMenuOpen(false); }}
                                                className={`nnl-theme-item ${theme === 'dark' ? 'active' : ''}`}
                                            >
                                                <Moon size={14} /> Gelap (Dark)
                                            </button>
                                            <button
                                                onClick={() => { toggleTheme('system'); setIsThemeMenuOpen(false); }}
                                                className={`nnl-theme-item ${theme === 'system' ? 'active' : ''}`}
                                            >
                                                <Laptop size={14} /> Sistem (Auto)
                                            </button>
                                        </div>
                                    </>
                                )}
                            </div>

                            <button onClick={handleLogout} type="button" className="nnl-logout-btn">
                                <LogoutIcon />
                                <span className="nnl-logout-text">Logout</span>
                            </button>
                        </div>

                    </div>
                </header>

                {/* ── Page Content ── */}
                <main className="nnl-main">
                    {isPageLoading ? <PageSkeleton /> : children}
                </main>

                {/* ── Bottom Nav (Mobile) ── */}
                <div className="nnl-bottom-nav">
                    <Link
                        id="mob-dashboard"
                        href={route('customer.dashboard')}
                        className={`nnl-bottom-link ${isActive('/dashboard') ? 'active' : ''} ${showOnboarding && onboardingSteps[onboardingStep].target === 'dashboard' ? 'nnl-highlight' : ''}`}
                    >
                        <div className="nnl-bottom-icon-wrap"><HomeIcon isActive={isActive('/dashboard')} /></div>
                        <span className="nnl-bottom-label">Dashboard</span>
                    </Link>
                    <Link
                        id="mob-tagihan"
                        href={route('customer.invoices.index')}
                        className={`nnl-bottom-link ${isActive('/tagihan') ? 'active' : ''} ${showOnboarding && onboardingSteps[onboardingStep].target === 'tagihan' ? 'nnl-highlight' : ''}`}
                    >
                        <div className="nnl-bottom-icon-wrap"><DocumentIcon isActive={isActive('/tagihan')} /></div>
                        <span className="nnl-bottom-label">Tagihan</span>
                    </Link>
                    <Link
                        id="mob-akun"
                        href={route('customer.account.edit')}
                        className={`nnl-bottom-link ${isActive('/account') ? 'active' : ''} ${showOnboarding && onboardingSteps[onboardingStep].target === 'akun' ? 'nnl-highlight' : ''}`}
                    >
                        <div className="nnl-bottom-icon-wrap"><AccountIcon isActive={isActive('/account')} /></div>
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
