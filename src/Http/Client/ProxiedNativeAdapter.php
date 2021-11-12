<?php

declare(strict_types=1);

namespace Onix\Http\Client;

class ProxiedNativeAdapter extends NativeAdapter
{
    public function __construct(string $proxyUrl, array $options = [])
    {
        $options['http']['proxy'] = $proxyUrl;
        $options['http']['request_fulluri'] = true;
        parent::__construct($options);
    }
}
