<?php

return [
    'pdf' => [
        'enabled' => true,
        'binary'  => env('WKHTMLTOPDF_PATH', 'C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe'),
        'timeout' => false,
        'options' => [
            // contoh opsi tambahan
            'page-size' => 'A4',
            'margin-top' => 10,
            'margin-bottom' => 10,
            'margin-left' => 10,
            'margin-right' => 10,
        ],
    ],
];
