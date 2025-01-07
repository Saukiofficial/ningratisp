<?php

namespace App\Http\Requests;

use App\Models\Voucher;
use Illuminate\Foundation\Http\FormRequest;

class VoucherRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'hour' => 'required|int|in:' . implode(',', array_keys(Voucher::availPrices()))
        ];
        if ($this->path() == 'api/midtrans/requestvoucher') {
            $rules['channel_id'] = 'required';
        }

        return $rules;
    }
}
