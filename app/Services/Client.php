<?php declare(strict_types=1);

namespace App\Services;

class Client extends \GuzzleHttp\Client
{
    /**
     * Constructor which instantiates the Guzzle client.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config = array_merge([
            'http_errors' => config('services.api.client.http_errors'),
            'connect_timeout' => config('services.api.client.connect_timeout'),
            'timeout' => config('services.api.client.timeout'),
            'max_retries' => config('services.api.client.max_retries'),
            'retry_delay' => config('services.api.client.retry_delay'),
            'verify' => config('services.api.client.verify')
        ], $config);

        parent::__construct($config);
    }
}
