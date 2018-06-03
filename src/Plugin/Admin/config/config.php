<?php
declare(strict_types=1);

return [
    'session_whitelist' => [
        'required' => true,
        'type' => 'list',
        'default' => [
            '/admin/authorize',
        ],
    ],
];
