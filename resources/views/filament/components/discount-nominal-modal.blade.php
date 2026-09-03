<style>
    .nom-card {
        border-radius: 12px;
        padding: 16px;
        border: 1px solid var(--border, #e5e7eb);
        background: var(--surface-1, #f9fafb);
    }
    .nom-card-before {
        border-color: #d1d5db;
        background-color: #f3f4f6;
    }
    .nom-card-discount {
        border-color: #fecdd3;
        background-color: #fff1f2;
    }
    .nom-card-after {
        border-color: #a7f3d0;
        background-color: #ecfdf5;
    }
    .dark .nom-card-before {
        border-color: #374151;
        background-color: #1f2937;
    }
    .dark .nom-card-discount {
        border-color: #881337;
        background-color: #4c0519;
    }
    .dark .nom-card-after {
        border-color: #065f46;
        background-color: #022c22;
    }
</style>

<div class="space-y-5 p-1" style="font-family: inherit;">
    <!-- Customer Header Info -->
    <div style="display: flex; align-items: center; justify-content: space-between; border-radius: 12px; padding: 14px 18px; border: 1px solid #e5e7eb; background: #f9fafb;" class="dark:border-gray-700 dark:bg-gray-800">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 44px; height: 44px; border-radius: 50%; background: #dbeafe; color: #1d4ed8; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;" class="dark:bg-blue-950 dark:text-blue-300">
                {{ strtoupper(substr($customer->name ?? $customer->username, 0, 2)) }}
            </div>
            <div>
                <h4 style="font-weight: 700; font-size: 15px; margin: 0; color: #111827;" class="dark:text-white">
                    {{ $customer->name ?? $customer->full_name }}
                </h4>
                <p style="font-size: 12px; margin: 2px 0 0 0; color: #6b7280;" class="dark:text-gray-400">
                    Username: <span style="font-family: monospace; font-weight: 600; color: #374151;" class="dark:text-gray-200">{{ $customer->username }}</span>
                </p>
            </div>
        </div>

        <div>
            @if ($isUsed)
                <span style="display: inline-flex; align-items: center; gap: 6px; border-radius: 9999px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; padding: 4px 12px; font-size: 12px; font-weight: 600;" class="dark:bg-emerald-950 dark:color-emerald-300 dark:border-emerald-800">
                    ✓ Used for Payment
                </span>
            @else
                <span style="display: inline-flex; align-items: center; gap: 6px; border-radius: 9999px; background: #fffbeb; color: #b45309; border: 1px solid #fde68a; padding: 4px 12px; font-size: 12px; font-weight: 600;" class="dark:bg-amber-950 dark:color-amber-300 dark:border-amber-800">
                    ⏱ Claimed (Unpaid)
                </span>
            @endif
        </div>
    </div>

    <!-- Voucher Metadata -->
    <div style="border-radius: 10px; border: 1px dashed #d1d5db; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; font-size: 13px; background: #ffffff;" class="dark:border-gray-700 dark:bg-gray-900">
        <div>
            <span style="color: #6b7280;" class="dark:text-gray-400">Voucher/Diskon:</span>
            <strong style="color: #111827; margin-left: 4px;" class="dark:text-white">{{ $discount->name }}</strong>
            <span style="font-family: monospace; background: #eff6ff; color: #2563eb; padding: 2px 8px; border-radius: 4px; font-weight: 700; margin-left: 6px;" class="dark:bg-blue-900 dark:text-blue-200">{{ $discount->code }}</span>
        </div>
        <div>
            <span style="color: #6b7280;" class="dark:text-gray-400">Tipe:</span>
            <span style="font-weight: 600; color: #374151; margin-left: 4px;" class="dark:text-gray-200">
                @if ($discount->type === 'percentage')
                    Diskon Persentase ({{ intval($discount->value) }}%)
                @else
                    Potongan Tetap (Rp {{ number_format($discount->value, 0, ',', '.') }})
                @endif
            </span>
        </div>
    </div>

    <!-- 3 Nominal Cards -->
    <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px;">
        <!-- Nominal Sebelum -->
        <div class="nom-card nom-card-before">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 12px; font-weight: 600; color: #4b5563;" class="dark:text-gray-300">Nominal Sebelum</span>
                <span style="font-size: 10px; font-weight: 700; background: #e5e7eb; padding: 2px 6px; border-radius: 4px; color: #374151;">SEBELUM</span>
            </div>
            <div style="font-size: 20px; font-weight: 700; text-decoration: line-through; color: #6b7280; margin-top: 8px;">
                Rp {{ number_format($nominals['before'], 0, ',', '.') }}
            </div>
            <p style="font-size: 11px; color: #9ca3af; margin: 4px 0 0 0;">Harga sebelum potongan</p>
        </div>

        <!-- Potongan Diskon -->
        <div class="nom-card nom-card-discount">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 12px; font-weight: 600; color: #be123c;" class="dark:text-rose-300">Potongan Diskon</span>
                <span style="font-size: 10px; font-weight: 700; background: #fecdd3; padding: 2px 6px; border-radius: 4px; color: #9f1239;">DISKON</span>
            </div>
            <div style="font-size: 20px; font-weight: 800; color: #e11d48; margin-top: 8px;">
                - Rp {{ number_format($nominals['discount_amount'], 0, ',', '.') }}
            </div>
            <p style="font-size: 11px; color: #fb7185; margin: 4px 0 0 0;">Total hemat didapatkan</p>
        </div>

        <!-- Nominal Sesudah -->
        <div class="nom-card nom-card-after">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 12px; font-weight: 700; color: #047857;" class="dark:text-emerald-300">Nominal Sesudah</span>
                <span style="font-size: 10px; font-weight: 800; background: #a7f3d0; padding: 2px 6px; border-radius: 4px; color: #065f46;">TOTAL BAYAR</span>
            </div>
            <div style="font-size: 22px; font-weight: 900; color: #059669; margin-top: 8px;">
                Rp {{ number_format($nominals['after'], 0, ',', '.') }}
            </div>
            <p style="font-size: 11px; color: #10b981; margin: 4px 0 0 0;">Tagihan bersih dibayar</p>
        </div>
    </div>

    <!-- Related Invoice Info -->
    @if ($nominals['has_invoice'])
        <div style="border-radius: 10px; border: 1px solid #e5e7eb; padding: 14px; background: #ffffff;" class="dark:border-gray-700 dark:bg-gray-900">
            <h5 style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; margin: 0 0 10px 0;">Informasi Tagihan Terkait</h5>
            <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; font-size: 12px;">
                <div>
                    <span style="color: #9ca3af; display: block;">No. Invoice</span>
                    <strong style="font-family: monospace; color: #111827;" class="dark:text-white">{{ $nominals['invoice_number'] }}</strong>
                </div>
                <div>
                    <span style="color: #9ca3af; display: block;">Tanggal Invoice</span>
                    <span style="color: #374151; font-weight: 500;" class="dark:text-gray-200">{{ $nominals['invoice_date'] ?? '-' }}</span>
                </div>
                <div>
                    <span style="color: #9ca3af; display: block;">Status Pembayaran</span>
                    <span style="color: #059669; font-weight: 600; text-transform: capitalize;">{{ $nominals['payment_status'] }}</span>
                </div>
            </div>
        </div>
    @endif
</div>
