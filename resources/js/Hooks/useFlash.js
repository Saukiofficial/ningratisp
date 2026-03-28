import { usePage } from '@inertiajs/react';
import { useEffect } from 'react';
import { toast } from 'react-toastify';

export function useFlash() {
    const { flash } = usePage().props;

    useEffect(() => {
        const getTheme = () => document.documentElement.classList.contains('dark') ? 'dark' : 'light';

        if (flash && flash.success) {
            toast.success(flash.success, { theme: getTheme() });
        }
        if (flash && flash.error) {
            toast.error(flash.error, { theme: getTheme() });
        }
    }, [flash]);
}
