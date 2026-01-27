<x-filament-panels::page>
    @if ($status === 'WORKING')
        <div>
            <h2 class="text-lg font-semibold">WhatsApp is Connected</h2>

            <div class="mt-4">
                <h3 class="text-md font-semibold">My Information</h3>
                <pre>{{ json_encode($me, JSON_PRETTY_PRINT) }}</pre>
            </div>

            <div class="mt-4">
                <h3 class="text-md font-semibold">Sessions</h3>
                <pre>{{ json_encode($sessions, JSON_PRETTY_PRINT) }}</pre>
            </div>

            <div class="mt-4">
                <h3 class="text-md font-semibold">Screenshot</h3>
                @if ($screenshot)
                    <img src="data:image/png;base64,{{ $screenshot }}" alt="Screenshot">
                @else
                    <p>No screenshot available.</p>
                @endif
            </div>
        </div>
    @elseif ($status === 'SCAN_QR_CODE')
        <div>
            <h2 class="text-lg font-semibold">Scan QR Code</h2>
            <p>Scan the QR code to connect WhatsApp.</p>
            @if ($qrCode)
                <div class="mt-4">
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
                </div>
            @endif
        </div>
    @else
        <div>
            <h2 class="text-lg font-semibold">WhatsApp is not connected</h2>
            <p>Status: {{ $status }}</p>
        </div>
    @endif
</x-filament-panels::page>
