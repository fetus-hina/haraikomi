<?php

declare(strict_types=1);

use yii\base\Event;
use yii\db\Connection;

return [
    'class' => Connection::class,
    'dsn' => 'sqlite:@app/runtime/db.sqlite',
    'charset' => 'utf8',
    'on ' . Connection::EVENT_AFTER_OPEN => function (Event $ev): void {
        $db = $ev->sender;
        assert($db instanceof Connection);
        if ($db->getDriverName() === 'sqlite') {
            $db->createCommand('PRAGMA foreign_keys = ON')->execute();
        }
    },
];
