<?php

namespace App\Foundation\Enums;


enum MiddlewareAliasesEnum
{
    const IS_VIP = 'isVip';
    const IS_DEVELOPER = 'isDeveloper';
    const THROTTLE = 'throttle';
    const THROTTLE_3 = 'throttle:rate_limit,3';
    const THROTTLE_5 = 'throttle:rate_limit,5';
    const RECAPTCHA = 'recaptcha';
    const AUTH = "auth:sanctum";
}
