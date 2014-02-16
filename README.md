```php
require 'vendor/autoload.php';

$app = new Silex\Application();

$app['mheap.security.open_routes'] = array(
    "index" => "^/$"
);

$app['mheap.security.pages'] = array(
    "login" => "/login",
    "logout" => "/logout",
    "login_redirects_to" => "/"
);

$app['mheap.security.user_provider'] = $app->share(function() use ($app) {
    return new mheap\UserProvider;
});

$app->register(new mheap\SecurityServiceProvider());

$app->get('/login', function () use ($app) {
    return "FOO";
});
```
