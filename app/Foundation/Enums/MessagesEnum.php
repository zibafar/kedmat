<?php

namespace App\Foundation\Enums;

/**
 * Includes a list of all translatable strings that
 * are also keys in 'lang/en.json' file.
 *
 * If you insert any new message please
 * sync keys here with 'en.json' for consistency
 */
enum MessagesEnum: string
{

    /**
     * Success messages
     */

    /* General */
    case SUCCESS_RESOURCE_CREATED = 'success_resource_created';
    case SUCCESS_RESOURCE_RESTORE = 'success_resource_restore';
    case SUCCESS_RESOURCE_EXISTS = 'resource_already_exists';
    case SUCCESS_RESOURCE_UPDATED = 'success_resource_updated';
    case SUCCESS_RESOURCE_DELETED = 'success_resource_deleted';
    case SUCCESS_SET_STATUS_RESOURCE = 'success_set_status_resource';
    case SUCCESS_RESOURCE_LIST_RETRIEVED = 'success_resource_list_retrieved';

    // generic error messages (have no parameters)
    case ERROR_GENERIC = 'error_generic';
    case ERROR_UNAUTHORIZED_GENERIC = 'error_unauthorized_generic';
    case ERROR_NOT_FOUND_GENERIC = 'error_not_found_generic';
    case ERROR_UNAUTHENTICATED = 'error_unauthenticated';
    case ERROR_UNAUTHORIZED = 'error_unauthorized';
    case ERROR_UNAUTHORIZED_LVL = 'error_unauthorized_lvl';
    case ERROR_NOT_FOUND = 'error_not_found';
    case ERROR_NOT_FOUND_NOT_ALLOW = 'error_not_found_or_not_allow';
    case ERROR_UNKNOWN = 'error_unknown';


}
