<?php

namespace App\Http\Controllers;

use App\Models\VirtualAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QrisProxyController extends Controller
{
    public function __invoke(string $transactionId)
    {
        $virtualAccount = VirtualAccount::where('transaction_id', $transactionId)->firstOrFail();

        if (!$virtualAccount->qris_url) {
            abort(404, 'QRIS URL not found.');
        }

        try {
            $response = Http::get($virtualAccount->qris_url);

            if ($response->successful()) {
                return response($response->body())
                    ->header('Content-Type', $response->header('Content-Type'))
                    ->header('Content-Disposition', 'inline; filename="qris.png"');
            } else {
                abort($response->status(), 'Failed to fetch QRIS image from Midtrans.');
            }
        } catch (\Exception $e) {
            abort(500, 'Error fetching QRIS image: ' . $e->getMessage());
        }
    }
}
