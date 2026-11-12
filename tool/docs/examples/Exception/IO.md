```php
use CeusMedia\Common\Exception\IO as IoException;

try{
	if( !file_exists( 'config.ini' ) )
		throw new IoException( 'Config file is missing', 0, NULL, 'config.ini' );
}
catch( IoException $e ){
	echo $e->getResource();       // config.ini
	echo $e->render();            // plain text on the console, HTML otherwise
}
```

See guide: [Core](../../guide/core.md)
