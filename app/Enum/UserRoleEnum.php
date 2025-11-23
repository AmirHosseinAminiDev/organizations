<?php

namespace App\Enum;

enum UserRoleEnum:string
{
    case SUPER_ADMIN         = 'super_admin';
    case ORGANIZATION_ADMIN  = 'organization_admin';
    case ORGANIZATION_USER   = 'organization_user';
    case OPERATOR            = 'operator';
}
