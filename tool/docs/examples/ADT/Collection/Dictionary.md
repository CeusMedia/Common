```php
use CeusMedia\Common\ADT\Collection\Dictionary;

$config	= new Dictionary( ['mail.host' => 'localhost', 'mail.port' => 25, 'debug' => FALSE] );

$config->get( 'mail.port' );                // 25
$config->get( 'mail.user', 'nobody' );      // nobody
$config['mail.user']	= 'admin';
$config->getAll( 'mail.' );                 // ['host' => 'localhost', 'port' => 25, 'user' => 'admin']
$config->cast( '587', 'mail.port' );        // 587
foreach( $config as $key => $value )
	echo $key, PHP_EOL;
```

See guide: [Data types](../../../guide/data-types.md)
