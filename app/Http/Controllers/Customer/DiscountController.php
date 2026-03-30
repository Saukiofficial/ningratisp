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
        $customer = auth('customers')->user();

        $result = $this->discountService->validateDiscountForCustomer($discount, $customer);

        if (empty($result['valid'])) {
            return back()->with('error', $result['message']);
        }

        $customer->discounts()->attach($discount->id, [
            'is_active' => true,
            'applied_at' => now(),
            'notes' => 'Claimed via portal customer',
        ]);

        return back()->with('success', 'Discount has been added to your account.');
    }
}
