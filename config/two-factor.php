<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authority
    |--------------------------------------------------------------------------
    |
    | The id of the single user account whose authenticator app gates every
    | login on the site. Only this account can enable/disable two-factor
    | authentication or view its QR code; every other account's login is
    | challenged against this account's code once it is enabled.
    |
    */

    'authority_id' => (int) env('TWO_FACTOR_AUTHORITY_ID', 1),

];
