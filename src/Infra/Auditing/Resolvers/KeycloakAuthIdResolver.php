<?php

namespace Infra\Auditing\Resolvers;

use Illuminate\Support\Facades\Auth;
use JsonException;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Resolver;

class KeycloakAuthIdResolver implements Resolver
{
    /**
     * @throws JsonException
     */
    public static function resolve(Auditable $auditable = null)
    {
        if(Auth::check()) {
            return json_decode(Auth::token(), false, 512, JSON_THROW_ON_ERROR)->sub;
        }

        return null;
    }
}
