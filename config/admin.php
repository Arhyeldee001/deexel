<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Secret Admin Path
    |--------------------------------------------------------------------------
    | The whole admin panel lives under this URL segment. Change ADMIN_PATH in
    | your .env to move it (e.g. niuq -> some-other-secret) with no code edits.
    | Guessed paths like /admin return a normal 404.
    */
    'path' => env('ADMIN_PATH', 'niuq'),

    /*
    | Roles allowed to enter the admin panel. Anyone authenticated without one
    | of these roles receives a 404 (so the panel stays invisible).
    */
    'roles' => ['super-admin', 'admin', 'editor'],
];
