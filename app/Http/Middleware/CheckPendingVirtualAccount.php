<?php

namespace App\Http\Middleware;

use App\Models\VirtualAccount;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPendingVirtualAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('customers')->user();

        if ($user) {
            $pendingVA = VirtualAccount::query()
                ->whereHas(
                    'invoice.customerPackage.customer',
                    fn($query) => $query->where('id', $user->id)
                )
                ->where('status', VirtualAccount::STATUS_PENDING)
                ->where('expired_at', '>', now())
                ->first();

            $request->attributes->set('pending_va', $pendingVA);

            if ($pendingVA) {
                return to_route('customer.pending-payment.show', $pendingVA);
            }
        }

        return $next($request);
    }
}
