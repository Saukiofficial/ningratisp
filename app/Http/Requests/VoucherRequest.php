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
            'pointer' => 'required|string|in:' . implode(',', array_keys(Voucher::pricesDetail()))
        ];
        if (in_array($this->path(), ['api/midtrans/requestvoucher', 'api/midtrans/requestvoucherqris'])) {
            $rules['channel_id'] = 'required';
            $rules['seal_code'] = 'required';
        } elseif ($this->path() == 'api/voucherdetails') {
            $rules = ['seal_code' => 'required|string'];
        }

        return $rules;
    }
}
