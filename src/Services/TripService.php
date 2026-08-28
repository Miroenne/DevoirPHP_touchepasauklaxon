<?php

namespace App\Services;

use App\Services\Service;
use App\Models\Trip;
use App\Repositories\TripRepository;
use App\Services\AgencyService;
use App\Services\UserService;

class TripService extends Service
{

    protected function getRepository(): object
    {
        return new TripRepository;
    }
}
