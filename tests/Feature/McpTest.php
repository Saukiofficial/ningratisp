<?php

use Laravel\Boost\BoostServiceProvider;

it('has boost service provider registered', function () {
    expect(app()->getProvider(BoostServiceProvider::class))->not->toBeNull();
});

it('has mcp configured in boost.json', function () {
    $boostJsonPath = base_path('boost.json');

    expect(file_exists($boostJsonPath))->toBeTrue();

    $config = json_decode(file_get_contents($boostJsonPath), true);

    expect($config)->toHaveKey('mcp');
    expect($config['mcp'])->toBeTrue();
});

it('has laravel-boost configured in mcp.json files', function () {
    $paths = [
        base_path('.cursor/mcp.json'),
        base_path('.vscode/mcp.json'),
    ];

    foreach ($paths as $path) {
        expect(file_exists($path))->toBeTrue();

        $config = json_decode(file_get_contents($path), true);
        $key = str_contains($path, '.cursor') ? 'mcpServers' : 'servers';

        expect($config)->toHaveKey($key);
        expect($config[$key])->toHaveKey('laravel-boost');
        expect($config[$key]['laravel-boost']['command'])->toBe('php');
        expect($config[$key]['laravel-boost']['args'])->toContain('boost:mcp');
    }
});

it('has laravel-boost configured in antigravity mcp_config.json', function () {
    $path = '/home/rizalabulfata/.gemini/antigravity-cli/mcp_config.json';

    expect(file_exists($path))->toBeTrue();

    $config = json_decode(file_get_contents($path), true);

    expect($config)->toHaveKey('mcpServers');
    expect($config['mcpServers'])->toHaveKey('laravel-boost');
    expect($config['mcpServers']['laravel-boost']['command'])->toBe('/usr/bin/php');
    expect($config['mcpServers']['laravel-boost']['args'])->toContain('/large-file/Developer/PHP/Laravel/ningratisp/artisan');
    expect($config['mcpServers']['laravel-boost']['args'])->toContain('boost:mcp');
});
