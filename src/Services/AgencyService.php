<?php

namespace App\Services;

use App\Services\Service;
use App\Repositories\AgencyRepository;

class AgencyService extends Service
{

    protected function getRepository(): object
    {
        return new AgencyRepository();
    }
}
