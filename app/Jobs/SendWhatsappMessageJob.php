<?php

namespace App\Jobs;

use App\Models\TemplateMessage;
use App\Models\Voucher;
use App\Services\WahaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsappMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Voucher $voucher
    ) {}

    /**
     * Execute the job.
     */
    public function handle(WahaService $wahaService): void
    {
        if (empty($this->voucher->whatsapp_number)) {
            return;
        }

        $template = TemplateMessage::where(
            'used_at',
            $this->voucher->getMorphClass()
        )->first();

        if (empty($template)) {
            return;
        }

        $data = [
            'name' => $this->voucher->customer->name ?? '',
            'voucher' => $this->voucher->code,
            'price' => 'Rp' . number_format($this->voucher->price, 0, ',', '.'),
        ];

        $wahaService->sendTemplatedMessage(
            $template,
            $this->voucher->whatsapp_number,
            $data
        );
    }
}
