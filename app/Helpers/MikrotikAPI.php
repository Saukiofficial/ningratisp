<?php

namespace App\Helpers;

use App\Models\LogMikrotik;
use App\Models\Voucher;
use App\Trait\HasMikrotikConfiguration;
use Exception;
use Illuminate\Support\Facades\Http;

class MikrotikAPI
{
    use HasMikrotikConfiguration;

    private $baseUrl;

    private $user;

    private $password;

    private $pathUrl;

    private $action;

    public function __construct($baseUrl = null, $user = null, $password = null)
    {
        $this->baseUrl = $baseUrl ?? config('app.mikrotik.url');
        $this->user = $user ?? config('app.mikrotik.user');
        $this->password = $password ?? config('app.mikrotik.password');
    }

    public function request($data = null, $method = HttpRequestOperation::GET)
    {
        $url = $this->baseUrl . $this->getPathUrl();

        try {
            $driver = Http::withBasicAuth($this->user, $this->password);
            $error = false;

            if (! empty($this->getRequestTimeout())) {
                $driver = $driver->connectTimeout($this->getRequestTimeout())->timeout($this->getRequestTimeout());
            }

            switch (strtolower($method)) {
                case HttpRequestOperation::DELETE:
                    $driver = $driver->delete($url, $data);
                    break;
                case HttpRequestOperation::PUT:
                    $driver = $driver->put($url, $data);
                    break;
                case HttpRequestOperation::PATCH:
                    $driver = $driver->patch($url, $data);
                    break;
                case HttpRequestOperation::POST:
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
                'message' => $e->getMessage(),
            ];
        }

        if (empty($response)) {
            $error = true;
            $response = [
                'detail' => 'Empty Response',
                'error' => 400,
                'message' => 'Empty Response',
            ];
        }

        if ($this->getShouldLog()) {
            $reqLog = is_array($data) ? json_encode($data) : (json_validate($data) ? $data : json_encode($data));
            $respLog = is_array($response) ? json_encode($response) : (json_validate($response) ? $data : json_encode($response));
            LogMikrotik::create([
                'action' => $this->action ?? str(__FUNCTION__)->snake('-'),
                'request' => $reqLog,
                'response' => $respLog,
                'status' => $error,
            ]);
        }

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

    public function isConnected(): bool
    {
        $url = $this->baseUrl . '/system/resource';
        $success = true;

        try {
            $response = Http::withBasicAuth($this->user, $this->password)->get($url);
            if (! $response->successful()) {
                throw new Exception('');
            }
        } catch (\Throwable $th) {
            $success = false;
        }

        return $success;
    }

    /**
     * Create kode voucehr
     *
     * @param  string  $code  Kode voucher
     * @param  int  $uptime  Limit aktif (Jam)
     * @param  string  $uptimeType  Tipe uptime (d=Hari, h=Jam, m=Menit, s=Detik)
     * @param  string  $server  Server hotspot
     * @param  string  $profile  Profile hotspot
     */
    public function createVoucher($code, $uptime = 3, $uptimeType = 'h', $server = null, $profile = 'default')
    {
        if (! defined("App\Models\Voucher::" . strtoupper($uptimeType) . "_{$uptime}")) {
            return false;
        }

        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/ip/hotspot/user');
        $profile = $this->getProfileAndUptime($uptimeType . $uptime);
        $data = [
            'name' => $code,
            'profile' => strtoupper($profile['profile']),
            'limit-uptime' => $profile['limit'],
        ];
        if (! empty($server)) {
            $data['server'] = $server;
        }
        $response = $this->request($data, HttpRequestOperation::PUT);

        return $response;
    }

    /**
     * Get detail profile and uptime voucher
     *
     * @param  int|string  $uptime
     * @return array
     */
    public function getProfileAndUptime($uptime)
    {
        $list = [
            Voucher::H_3 => ['profile' => 'paket-3-jam', 'limit' => '3h'],
            Voucher::H_6 => ['profile' => 'paket-6-jam', 'limit' => '6h'],
            Voucher::D_1 => ['profile' => 'paket-harian', 'limit' => '1d'],
            Voucher::D_7 => ['profile' => 'paket-mingguan', 'limit' => '7d'],
            Voucher::D_30 => ['profile' => 'paket-bulanan', 'limit' => '30d'],
        ];

        return isset($list[$uptime]) ? $list[$uptime] : ['profile' => 'default', 'limit' => '0h'];
    }

    public function getSystemResource()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/system/resource');
        $this->setShouldLog(false);

        return $this->request();
    }

    public function getSystemRouterboard()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/system/routerboard');
        $this->setShouldLog(false);

        return $this->request();
    }

    public function getIpAddresses()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/ip/address');
        $this->setShouldLog(false);

        return $this->request();
    }

    public function getIpRoutes()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/ip/route');
        $this->setShouldLog(false);

        return $this->request();
    }

    public function getIpDns()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/ip/dns');
        $this->setShouldLog(false);

        return $this->request();
    }

    public function getPppSecrets()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/ppp/secret');
        $this->setShouldLog(false);

        return $this->request();
    }

    public function getPppActive()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/ppp/active');
        $this->setShouldLog(false);
        $this->setRequestTimeout(1);

        return $this->request();
    }

    public function getPppProfiles()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/ppp/profile');
        $this->setShouldLog(false);

        return $this->request();
    }

    public function getHotspotUsers()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/ip/hotspot/user');
        $this->setShouldLog(false);

        return $this->request();
    }

    public function getHotspotActive()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/ip/hotspot/active');
        $this->setShouldLog(false);

        return $this->request();
    }

    public function getHotspotProfiles()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/ip/hotspot/profile');
        $this->setShouldLog(false);

        return $this->request();
    }

    public function getHotspotServers()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/ip/hotspot/server');
        $this->setShouldLog(false);

        return $this->request();
    }

    public function getIpProxy()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/ip/proxy');
        $this->setShouldLog(false);

        return $this->request();
    }

    public function getIpFirewallNat()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/ip/firewall/nat');
        $this->setShouldLog(false);

        return $this->request();
    }

    public function getIpFirewallFilter()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/ip/firewall/filter');
        $this->setShouldLog(false);

        return $this->request();
    }

    public function getInterfaceTraffic(string $interface)
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/interface/monitor-traffic');
        $this->setShouldLog(false);
        $this->setRequestTimeout(1);

        return $this->request(['interface' => $interface, 'once' => ''], 'post');
    }

    public function ping(string $ipAddress, $count = 5)
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setPathUrl('/tool/ping');
        $this->setShouldLog(false);
        // $this->setRequestTimeout(5);

        return $this->request(['address' => $ipAddress, 'count' => $count], 'post');
    }

    public function isolirClient(string $username, bool $enabled = true, ?string $additionalNotes = null)
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(true);

        $this->setPathUrl('/ppp/secret');
        $secrets = $this->request(['name' => $username]);

        if (empty($secrets) || ! isset($secrets[0]['.id'])) {
            return;
        }

        $secretId = $secrets[0]['.id'];

        $this->setPathUrl("/ppp/secret/{$secretId}");

        // $comment = $enabled ? 'EXPIRED' : '';
        $comment = $enabled ? 'Coba auto comment' : '';
        if ($enabled && $additionalNotes) {
            $comment .= ' - ' . $additionalNotes;
        }

        $data = [
            'comment' => $comment,
        ];

        $response = $this->request($data, HttpRequestOperation::PATCH);

        return $response;
    }
}
