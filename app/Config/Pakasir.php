<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pakasir extends BaseConfig
{
    public string $slug = 'alatberat';
    public string $api_key = 'your_pakasir_api_key';

    public function __construct()
    {
        parent::__construct();

        // Read values from .env using env() helper
        $this->slug = env('pakasir.slug', $this->slug);
        $this->api_key = env('pakasir.api_key', $this->api_key);
    }
}
