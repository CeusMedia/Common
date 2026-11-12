```php
use CeusMedia\Common\XML\DOM\Storage;

$storage	= new Storage( 'settings.xml' );				//  created if missing
$storage->set( 'db.host', 'localhost' );
$storage->set( 'db.port', 3306, TRUE );						//  TRUE: write file now
$port		= $storage->get( 'db.port' );					//  int(3306)
$database	= $storage->get( 'db' );						//  ['host' => 'localhost', 'port' => 3306]
$storage->remove( 'db.host', TRUE );
```

See guide: [XML](../../../guide/xml.md)
