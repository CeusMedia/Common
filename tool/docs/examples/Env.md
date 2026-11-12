```php
use CeusMedia\Common\Env;

$mode	= Env::get( 'APP_MODE', 'production' );          // environment variable or default
$host	= Env::getServerVariable( 'HTTP_HOST' );          // environment, then $_SERVER
$ip		= Env::getServerVariable( 'REMOTE_ADDR', '127.0.0.1' );

if( Env::isCli() && !Env::isHeadless() )
	echo 'Running in a terminal'.PHP_EOL;
```

See guide: [Core](../guide/core.md)
