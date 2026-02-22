<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Permissions\Query;

use N1ebieski\KSEFClient\Contracts\Resources\Permissions\Query\Authorizations\AuthorizationsResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Permissions\Query\Personal\PersonalResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Permissions\Query\Subunits\SubunitsResourceInterface;

interface QueryResourceInterface
{
    public function authorizations(): AuthorizationsResourceInterface;

    public function personal(): PersonalResourceInterface;

    public function subunits(): SubunitsResourceInterface;
}
