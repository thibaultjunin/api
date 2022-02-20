# API

# WORK IN PROGESS

Init the library:

```php 
$api = new \Thibaultjunin\Api\Api();
$api->setAuth(new AuthInterfaceImpl());
$api->setUser(UserInterfaceImpl::class);
$api->setDevMode(true);
```

To create a helper make a class extending `\Thibaultjunin\Api\Helpers\Helper`.

To create a controller make a class extending `\Thibaultjunin\Api\Controllers\ApiController`