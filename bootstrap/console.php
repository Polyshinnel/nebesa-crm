<?php

use App\Jobs\TestJob;

return [
    'console' => [
        'commands' => [
            'Название комманды' => TestJob::class
        ]
    ]
];