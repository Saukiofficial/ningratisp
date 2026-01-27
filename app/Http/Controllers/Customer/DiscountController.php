<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Services\DiscountService;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function __construct(protected DiscountService $discountService) {}

    public function claim(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:discounts,code',
        ]);

        $discount = Discount::where('code', $request->code)->first();
        $customer = auth()->user();

        if (!$this->discountService->validateDiscountForCustomer($discount, $customer)) {
            return back()->with('error', 'This discount is not applicable to you.');
        }

        $customer->discounts()->attach($discount->id, [
            'is_active' => true,
            'applied_at' => now(),
            'notes' => 'Claimed via portal customer'
        ]);


        return back()->with('success', 'Discount has been added to your account.');
    }
}
