<?php

declare(strict_types=1);

return (function (array $db): array {
    $db['dsn'] = 'sqlite:@app/runtime/test-db.sqlite';
    return $db;
})(require(__DIR__ . '/db.php'));
