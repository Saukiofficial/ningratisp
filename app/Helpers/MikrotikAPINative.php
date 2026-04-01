<?php

namespace App\Helpers;

use App\Models\LogMikrotik;
use App\Models\PppProfile;
use App\Models\Voucher;
use App\Trait\HasMikrotikConfiguration;
use Exception;
use Illuminate\Support\Facades\Cache;

class MikrotikAPINative
{
    use HasMikrotikConfiguration;

    private $host;
    private $user;
    private $password;
    private $port;
    private $ssl;
    private $action;
    private $api;

    public function __construct($host = null, $user = null, $password = null, $port = null, $ssl = null)
    {
        $this->host = $host ?? config('app.mikrotik.host');
        $this->user = $user ?? config('app.mikrotik.user');
        $this->password = $password ?? config('app.mikrotik.password');
        $this->port = $port ?? config('app.mikrotik.port', 8728);
        $this->ssl = $ssl ?? config('app.mikrotik.ssl', false);

        $this->api = new RouterosAPI;
        $this->api->port = $this->port;
        $this->api->ssl = $this->ssl;
        $this->api->attempts = 1;
        $this->api->timeout = ! empty($this->getRequestTimeout()) ?
            $this->getRequestTimeout() : 3;
    }

    private function connect()
    {
        if (! $this->api->connected) {
            return $this->api->connect($this->host, $this->user, $this->password);
        }

        return true;
    }

    public function request($command, $params = [])
    {
        $error = false;
        $response = [];

        try {
            if (! $this->connect()) {
                throw new Exception('Failed to connect to MikroTik');
            }

            $response = $this->api->comm($command, $params);

            if (isset($response['!trap'])) {
                $error = true;
                $response = [
                    'detail' => 'MikroTik Error',
                    'error' => 400,
                    'message' => $response['!trap'][0]['message'] ?? 'Unknown error',
                    'status' => $error,
                ];
            }
        } catch (Exception $e) {
            $error = true;
            $response = [
                'detail' => 'Exception Request',
                'error' => 500,
                'message' => $e->getMessage(),
                'status' => $error,
            ];
        }

        if (empty($response)) {
            $error = false;
            $response = [
                'detail' => 'Empty Response',
                'error' => 400,
                'message' => 'Empty Response',
                'status' => $error,
            ];
        }

        if ($this->getShouldLog()) {
            $reqLog = json_encode(['command' => $command, 'params' => $params]);
            $respLog = is_array($response) ? json_encode($response) : $response;

            LogMikrotik::create([
                'action' => $this->action ?? 'api-request',
                'request' => $reqLog,
                'response' => $respLog,
                'status' => $error,
            ]);
        }

        return $response;
    }

    public function test()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $response = $this->request('/system/resource/print');

        return $response;
    }

    public function isConnected(): bool
    {
        try {
            $api = new RouterosAPI;
            $api->port = $this->port;
            $api->ssl = $this->ssl;
            $api->timeout = 3;

            $connected = $api->connect($this->host, $this->user, $this->password);
            $api->disconnect();

            return $connected;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Create kode voucher
     *
     * @param  string  $code  Kode voucher
     * @param  int  $uptime  Limit aktif (Jam)
     * @param  string  $uptimeType  Tipe uptime (d=Hari, h=Jam, m=Menit, s=Detik)
     * @param  string  $server  Server hotspot
     * @param  string  $profile  Profile hotspot
     * @param  string  $password  Profile hotspot
     */
    public function createVoucher($code, $uptime = 3, $uptimeType = 'h', $server = null, $profile = 'default', $password = null)
    {
        if (! defined("App\Models\Voucher::" . strtoupper($uptimeType) . "_{$uptime}")) {
            return false;
        }

        $this->action = str(__FUNCTION__)->snake('-');
        $profile = $this->getProfileAndUptime($uptimeType . $uptime);

        $params = [
            'name' => $code,
            'profile' => strtoupper($profile['profile']),
            'limit-uptime' => $profile['limit'],
        ];

        if (! empty($server)) {
            $params['server'] = $server;
        }

        if (! empty($password)) {
            $params['password'] = $password;
        }

        $response = $this->request('/ip/hotspot/user/add', $params);

        if (is_string($response)) {
            $response = $this->request('/ip/hotspot/user/print', [
                '?.id' => $response,
            ]);
            $response = $this->parseFirstResponse($response);
        }

        return $response;
    }

    /**
     * Create kode voucher
     *
     * @param  string  $code  Kode voucher
     * @param  int  $uptime  Limit aktif (Jam)
     * @param  string  $uptimeType  Tipe uptime (d=Hari, h=Jam, m=Menit, s=Detik)
     * @param  string  $server  Server hotspot
     * @param  string  $profile  Profile hotspot
     * @param  string  $password  Profile hotspot
     */
    public function createPpoeCustomer($username, PppProfile $pppProfile, $password = 12345, $localIp = null, $remoteIp = null)
    {
        $this->action = str(__FUNCTION__)->snake('-');

        $params = [
            'name' => $username,
            'password' => $password,
            'service' => 'pppoe',
            'profile' => $pppProfile->profile_name,
            'local-address' => $localIp,
            'remote-address' => $remoteIp,
        ];

        $response = $this->request('/ppp/secret/add', $params);

        if (is_string($response)) {
            $response = $this->request('/ppp/secret/print', [
                '?.id' => $response,
            ]);
            $response = $this->parseFirstResponse($response);
        }

        return $response;
    }

    public function deletePpoeCustomer($username)
    {
        $user = $this->getPppUser($username);
        if (empty($user['.id'])) {
            $user['status'] = true;
            return $user;
        }

        $response = $this->request('/ppp/secret/remove', [
            '.id' => $user['.id'],
        ]);

        return $this->parseFirstResponse($response);
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
        $this->setShouldLog(false);

        return $this->parseFirstResponse(
            $this->request('/system/resource/print')
        );
    }

    public function getSystemRouterboard()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);

        return $this->parseFirstResponse(
            $this->request('/system/routerboard/print')
        );
    }

    public function getIpAddresses()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);

        return $this->request('/ip/address/print');
    }

    public function getIpRoutes()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);

        return $this->request('/ip/route/print');
    }

    public function getIpDns()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);

        return $this->parseFirstResponse(
            $this->request('/ip/dns/print')
        );
    }

    public function getPppSecrets($fromCache = true)
    {
        $cacheKey = 'ppp_secrets';
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);

        $response = ! $fromCache ? $this->request('/ppp/secret/print') : cache($cacheKey);
        if (empty($response) && $fromCache) {
            $response = $this->request('/ppp/secret/print');

            if (is_array($response) && ! isset($response['error'])) {
                Cache::remember($cacheKey, now()->addHour(), fn() => $response);
            }
        }

        if (! $fromCache && (is_array($response) && ! isset($response['error']))) {
            Cache::delete($cacheKey);
            Cache::remember($cacheKey, now()->addHour(), fn() => $response);
        }

        return $response;
    }

    public function getPppActive()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);
        $this->api->timeout = 1;

        return $this->request('/ppp/active/print');
    }

    public function getPppProfiles()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);

        return $this->request('/ppp/profile/print');
    }

    public function getHotspotUsers()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);

        return $this->request('/ip/hotspot/user/print');
    }

    public function getHotspotActive()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);

        return $this->request('/ip/hotspot/active/print');
    }

    public function getHotspotProfiles()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);

        return $this->request('/ip/hotspot/user/profile/print');
    }

    public function getHotspotServers()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);

        return $this->request('/ip/hotspot/print');
    }

    public function getIpProxy()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);

        return $this->request('/ip/proxy/print');
    }

    public function getIpFirewallNat()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);

        return $this->request('/ip/firewall/nat/print');
    }

    public function getIpFirewallFilter()
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);

        return $this->request('/ip/firewall/filter/print');
    }

    public function getInterfaceTraffic(string $interface)
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);
        $this->api->timeout = 1;

        return $this->request('/interface/monitor-traffic', [
            'interface' => $interface,
            'once' => '',
        ]);
    }

    public function ping(string $ipAddress, $count = 5)
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(false);

        return $this->request('/ping', [
            'address' => $ipAddress,
            'count' => (string) $count,
        ]);
    }

    // NOTE : Test
    public function isolirClient(string $username, bool $enabled = true, ?string $additionalNotes = null)
    {
        $this->action = str(__FUNCTION__)->snake('-');
        $this->setShouldLog(true);

        // Find the secret by username
        $secrets = $this->request('/ppp/secret/print', [
            '?name' => $username,
        ]);

        if (empty($secrets) || ! isset($secrets[0]['.id'])) {
            return [
                'error' => 404,
                'message' => 'User not found',
            ];
        }

        $secretId = $secrets[0]['.id'];

        $comment = $enabled ? 'isolir' : 'lunas';
        if ($enabled && $additionalNotes) {
            $comment .= ' - ' . $additionalNotes;
        }

        $response = $this->request('/ppp/secret/set', [
            '.id' => $secretId,
            'comment' => $comment,
        ]);

        if (empty($response['status'])) {
            $response = $this->request('/ppp/secret/print', [
                '?name' => $username,
            ]);
            $response = $this->parseFirstResponse($response);
        }

        return $response;
    }

    public function getPppUser($username)
    {
        $response = $this->request('/ppp/secret/print', [
            '?name' => $username,
        ]);

        return $this->parseFirstResponse($response);
    }

    public function __destruct()
    {
        if ($this->api && $this->api->connected) {
            $this->api->disconnect();
        }
    }

    private function parseFirstResponse($response)
    {
        return ! empty($response) && ! isset($response['error']) ? $response[0] : $response;
    }
}
