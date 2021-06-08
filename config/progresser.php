<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Progresser Table
    |--------------------------------------------------------------------------
    |
    | This option controls the default table that's user by progresser in order
    | to store the progress information. This is used by the migrations and the
    | model to determine the table to look at. If you ever need to change this
    | after you've migratied, please rollback first, then change the value, then
    | migrate again.
    |
    */

    'table' => 'progresser',

    /*
    |--------------------------------------------------------------------------
    | Progresser Default Statuses
    |--------------------------------------------------------------------------
    |
    | This option controls the default default values of the progress states.
    | That means the statuses for the failed and complete states.
    |
    */

    'statuses' => [
        'complete' => 'Complete',
        'failed' => 'Failed',
    ],
];
