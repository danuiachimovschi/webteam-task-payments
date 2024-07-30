Start the docker containers with the following command:
```php
docker-compose up -d
```

SSL KEY CONFIGURATION
---------------------
To enable SSL, you need to generate a self-signed certificate and key. You can do this by running the following command:

In folder /config/secret.php
```php
<?php

declare(strict_types=1);

<?php

declare(strict_types=1);

return [
    'private_key' => file_get_contents(YOUR_PRIVATE_KEY_PATH),
];
```