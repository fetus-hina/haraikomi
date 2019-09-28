<?php

declare(strict_types=1);

use yii\db\Connection;

return [
    'class' => Connection::class,
    'dsn' => 'sqlite:@app/runtime/db.sqlite',
    'charset' => 'utf8',
];
