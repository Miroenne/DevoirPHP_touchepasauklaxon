<?php

namespace App\Services;

use App\Services\Service;
use App\Repositories\AgencyRepository;
use App\Exceptions\RessourceNotFoundException;

class AgencyService extends Service
{

    protected function getRepository(): object
    {
        return new AgencyRepository();
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function findByNameService(string $name): ?array
    {
        $repository = new AgencyRepository();

        $entity = $repository->findByName($name);

        return $entity !== [] ? $entity : throw new RessourceNotFoundException();
    }
}
