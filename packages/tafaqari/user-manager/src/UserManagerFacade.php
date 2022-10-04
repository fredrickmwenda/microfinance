<?php

namespace Tafaqari\UserManager;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tafaqari\UserManager\Skeleton\SkeletonClass
 */
class UserManagerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'user-manager';
    }
}
