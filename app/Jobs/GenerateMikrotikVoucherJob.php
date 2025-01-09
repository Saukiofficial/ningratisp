<?php

namespace App\Jobs;

use App\Helpers\MikrotikAPI;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateMikrotikVoucherJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $code, $uptime, $uptimeType, $server, $profile;

    /**
     * Create kode voucehr
     * 
     * @param string $code Kode voucher
     * @param int $uptime Limit aktif (Jam)
     * @param string $uptimeType Tipe uptime (d=Hari, h=Jam, m=Menit, s=Detik)
     * @param string $server Server hotspot
     * @param string $profile Profile hotspot
     */
    public function __construct($code, $uptime = 1, $uptimeType = 'h', $server = null, $profile = 'default')
    {
        $this->code = $code;
        $this->uptime = $uptime;
        $this->uptimeType = $uptimeType;
        $this->server = $server;
        $this->profile = $profile;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = new MikrotikAPI();
        $service->createVoucher($this->code, $this->uptime, $this->uptimeType, $this->server, $this->profile);
    }
}
