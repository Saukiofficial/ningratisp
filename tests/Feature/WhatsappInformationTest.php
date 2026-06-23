<?php

use App\Models\User;
use App\Filament\Pages\WhatsappInformation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Bypass authorization/HasPageShield gates in tests
    Gate::before(fn () => true);
    
    // Set a dummy URL for testing
    config(['app.waha.url' => 'http://waha-test.local']);
});

test('whatsapp information page can be rendered', function () {
    $user = User::factory()->create();

    Http::fake([
        'http://waha-test.local/api/sessions/default' => Http::response([
            'status' => 'WORKING',
        ], 200),
        'http://waha-test.local/api/sessions/default/me' => Http::response([
            'pushname' => 'John Doe',
            'id' => ['user' => '628123456789'],
            'platform' => 'android',
        ], 200),
        'http://waha-test.local/api/sessions' => Http::response([
            ['name' => 'default', 'status' => 'WORKING']
        ], 200),
        'http://waha-test.local/api/screenshot*' => Http::response('fake-screenshot-bytes', 200),
    ]);

    $this->actingAs($user);

    $test = Livewire::test(WhatsappInformation::class);
    dd($test->html());
});

test('whatsapp information page handles offline state gracefully', function () {
    $user = User::factory()->create();

    // Force connection failure exception
    Http::fake([
        'http://waha-test.local/api/sessions/default' => function () {
            throw new \Illuminate\Http\Client\ConnectionException('Connection refused');
        },
    ]);

    $this->actingAs($user);

    Livewire::test(WhatsappInformation::class)
        ->assertStatus(200)
        ->assertSee('WAHA API Connection Failure')
        ->assertSee('Connection refused')
        ->assertSee('Diagnostic Steps:');
});

test('whatsapp information page handles stopped state', function () {
    $user = User::factory()->create();

    Http::fake([
        'http://waha-test.local/api/sessions/default' => Http::response([
            'status' => 'STOPPED',
        ], 200),
    ]);

    $this->actingAs($user);

    Livewire::test(WhatsappInformation::class)
        ->assertStatus(200)
        ->assertSee('WhatsApp Session is Stopped')
        ->assertSee('Start WhatsApp Session');
});

test('whatsapp information page handles scan qr code state', function () {
    $user = User::factory()->create();

    Http::fake([
        'http://waha-test.local/api/sessions/default' => Http::response([
            'status' => 'SCAN_QR_CODE',
        ], 200),
        'http://waha-test.local/api/default/auth/qr' => Http::response('fake-qr-code-bytes', 200),
    ]);

    $this->actingAs($user);

    Livewire::test(WhatsappInformation::class)
        ->assertStatus(200)
        ->assertSee('Scan WhatsApp QR Code')
        ->assertSee('Open WhatsApp on your phone')
        ->assertSee('Refresh QR Code');
});

test('whatsapp information page can start session', function () {
    $user = User::factory()->create();

    Http::fake([
        // status check on mount
        'http://waha-test.local/api/sessions/default' => Http::sequence()
            ->push(['status' => 'STOPPED'], 200) // first call on mount
            ->push(['status' => 'STARTING'], 200), // second call after startSession -> refreshStatus
        
        'http://waha-test.local/api/sessions/default/start' => Http::response(['status' => 'STARTING'], 200),
    ]);

    $this->actingAs($user);

    Livewire::test(WhatsappInformation::class)
        ->assertStatus(200)
        ->call('startSession')
        ->assertNotified()
        ->assertSet('status', 'STARTING')
        ->assertSee('Starting WhatsApp Engine');

    Http::assertSent(function ($request) {
        return $request->url() === 'http://waha-test.local/api/sessions/default/start' && $request->isMethod('post');
    });
});

test('whatsapp information page can stop session', function () {
    $user = User::factory()->create();

    Http::fake([
        // status check on mount
        'http://waha-test.local/api/sessions/default' => Http::sequence()
            ->push(['status' => 'WORKING'], 200) // first call on mount
            ->push(['status' => 'STOPPED'], 200), // second call after stopSession -> refreshStatus

        'http://waha-test.local/api/sessions/default/me' => Http::response([
            'pushname' => 'John Doe',
            'id' => ['user' => '628123456789'],
        ], 200),
        'http://waha-test.local/api/sessions' => Http::response([
            ['name' => 'default', 'status' => 'WORKING']
        ], 200),
        'http://waha-test.local/api/screenshot*' => Http::response('fake-screenshot-bytes', 200),
        
        'http://waha-test.local/api/sessions/default/stop' => Http::response(['status' => 'STOPPED'], 200),
    ]);

    $this->actingAs($user);

    Livewire::test(WhatsappInformation::class)
        ->assertStatus(200)
        ->call('stopSession')
        ->assertNotified()
        ->assertSet('status', 'STOPPED');

    Http::assertSent(function ($request) {
        return $request->url() === 'http://waha-test.local/api/sessions/default/stop' && $request->isMethod('post');
    });
});

test('whatsapp information page can restart session', function () {
    $user = User::factory()->create();

    Http::fake([
        'http://waha-test.local/api/sessions/default' => Http::sequence()
            ->push(['status' => 'WORKING'], 200)
            ->push(['status' => 'STARTING'], 200),

        'http://waha-test.local/api/sessions/default/me' => Http::response([
            'pushname' => 'John Doe',
            'id' => ['user' => '628123456789'],
        ], 200),
        'http://waha-test.local/api/sessions' => Http::response([
            ['name' => 'default', 'status' => 'WORKING']
        ], 200),
        'http://waha-test.local/api/screenshot*' => Http::response('fake-screenshot-bytes', 200),
        
        'http://waha-test.local/api/sessions/default/restart' => Http::response(['status' => 'STARTING'], 200),
    ]);

    $this->actingAs($user);

    Livewire::test(WhatsappInformation::class)
        ->assertStatus(200)
        ->call('restartSession')
        ->assertNotified()
        ->assertSet('status', 'STARTING');

    Http::assertSent(function ($request) {
        return $request->url() === 'http://waha-test.local/api/sessions/default/restart' && $request->isMethod('post');
    });
});

test('whatsapp information page can disconnect session', function () {
    $user = User::factory()->create();

    Http::fake([
        'http://waha-test.local/api/sessions/default' => Http::sequence()
            ->push(['status' => 'WORKING'], 200)
            ->push(['status' => 'STOPPED'], 200),

        'http://waha-test.local/api/sessions/default/me' => Http::response([
            'pushname' => 'John Doe',
            'id' => ['user' => '628123456789'],
        ], 200),
        'http://waha-test.local/api/sessions' => Http::response([
            ['name' => 'default', 'status' => 'WORKING']
        ], 200),
        'http://waha-test.local/api/screenshot*' => Http::response('fake-screenshot-bytes', 200),
        
        'http://waha-test.local/api/sessions/default/logout' => Http::response(['status' => 'STOPPED'], 200),
    ]);

    $this->actingAs($user);

    Livewire::test(WhatsappInformation::class)
        ->assertStatus(200)
        ->call('disconnect')
        ->assertNotified()
        ->assertSet('status', 'STOPPED');

    Http::assertSent(function ($request) {
        return $request->url() === 'http://waha-test.local/api/sessions/default/logout' && $request->isMethod('post');
    });
});
