<?php

namespace App\Enum;

enum OrganizationEndpoints :string
{
    case GET_CHARGE_ACCOUNT_REQUESTS = 'get-charge-account-list';

    case UPDATE_CHARGE_ACCOUNT_REQUEST_STATUS = 'update-request-status';

    case GET_REISSUE_CARD_REQUESTS = 'get-reissue-card-requests';

    case UPDATE_REISSUE_CARD_REQUEST_STATUS = 'update-reissue-card-request-status';
}
