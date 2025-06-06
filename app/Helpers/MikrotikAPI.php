<?php

namespace App\Helpers;

use App\Models\LogMikrotik;
use App\Models\Voucher;
use Exception;
use Illuminate\Support\Facades\Http;

class MikrotikAPI
{
    private $baseUrl, $user, $password, $pathUrl, $action;

    public function __construct($baseUrl = null, $user = null, $password = null)
    {
        $this->baseUrl = $baseUrl ?? env('MIKROTIK_URL');
        $this->user = $user ?? env('MIKROTIK_USER');
        $this->password = $password ?? env('MIKROTIK_PASS');
    }

    public function request($data = null, $method = 'get')
    {
        $url = $this->baseUrl . $this->getPathUrl();

        try {
            $driver = Http::withBasicAuth($this->user, $this->password);
            $error = false;

            switch (strtolower($method)) {
                case 'delete':
                    $driver = $driver->delete($url, $data);
                    break;
                case 'put':
                    $driver = $driver->put($url, $data);
                    break;
                case 'patch':
                    $driver = $driver->patch($url, $data);
                    break;
                case 'post':
                    $driver = $driver->post($url, $data);
                    break;

                default:
                    $driver = $driver->get($url, $data);
                    break;
            }

            $response = $driver->json();
        } catch (Exception $e) {
            $error = true;
            $response = [
                'detail' => 'Exception Request',
                'error' => 500,
                'message' => $e->getMessage()
            ];
        }

        if (empty($response)) {
            $error = true;
            $response = [
                'detail' => 'Empty Response',
                'error' => 400,
                'message' => 'Empty Response'
            ];
        }

        $reqLog = is_array($data) ? json_encode($data) : (json_validate($data) ? $data : json_encode($data));
        $respLog = is_array($response) ? json_encode($response) : (json_validate($response) ? $data : json_encode($response));
        LogMikrotik::create([
            'action' => $this->action ?? str(__FUNCTION__)->snake('-'),
            'request' => $reqLog,
            'response' => $respLog,
            'status' => $error
        ]);

        return $response;
    }

    public function setPathUrl(string $path): void
    {
        $this->pathUrl = $path;
    }

    public function getPathUrl(): string
    {
        return $this->pathUrl;
    }

    public function test()
    {
        $this->setPathUrl('/system/resource');
        $response = $this->request();

        return $response;
    }

    /**
     * Create kode voucehr
     * 
     * @param string $code Kode voucher
     * @param int $uptime Limit aktif (Jam)
     * @param string $uptimeType Tipe uptime (d=Hari, h=Jam, m=Menit, s=Detik)
     * @param string $server Server hotspot
     * @param string $profile Profile hotspot
     */
    public function createVoucher($code, $uptime = 3, $uptimeType = 'h', $server = null, $profile = 'default')
    {
        if (!defined("App\Models\Voucher::" . strtoupper($uptimeType) . "_{$uptime}")) {
            return false;
        }

        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/ip/hotspot/user');
        $profile = $this->getProfileAndUptime($uptimeType . $uptime);
        $data = [
            'name' => $code,
            'profile' => strtoupper($profile['profile']),
            'limit-uptime' => $profile['limit']
        ];
        if (!empty($server)) {
            $data['server'] = $server;
        }
        $response = $this->request($data, 'put');

        return $response;
    }

    /**
     * Get detail profile and uptime voucher
     * 
     * @param int|string $uptime
     * @return array
     */
    public function getProfileAndUptime($uptime)
    {
        $list = [
            Voucher::H_3 => ['profile' => 'paket-3-jam', 'limit' => '3h'],
            Voucher::H_6 => ['profile' => 'paket-6-jam', 'limit' => '6h'],
            Voucher::D_1 => ['profile' => 'paket-harian', 'limit' => '1d'],
            Voucher::D_7 => ['profile' => 'paket-mingguan', 'limit' => '7d'],
            Voucher::D_30 => ['profile' => 'paket-bulanan', 'limit' => '30d']
        ];

        return isset($list[$uptime]) ? $list[$uptime] : ['profile' => 'default', 'limit' => '0h'];
    }
}
