<?php

namespace App\Service;

use App\Repository\ConfigRepository;

class ConfigService
{

    public function __construct(private ConfigRepository $configRepository)
    {

    }



    public function getActiveConfig(): array
    {
        $configs = $this->configRepository->getActiveConfig();
        return $configs;
    }


}
