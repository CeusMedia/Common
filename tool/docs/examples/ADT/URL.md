```php
use CeusMedia\Common\ADT\URL;

$url	= new URL( 'https://example.com/shop/list.php?page=2#top' );
$url->getHost();                            // example.com
$url->getPath();                            // /shop/list.php

$url->setPath( 'products/42' )
	->setQuery( ['color' => 'red', 'size' => 'L'] )
	->setFragment( 'details' );
echo $url;                                  // https://example.com/products/42?color=red&size=L#details
$url->get( FALSE );                         // products/42?color=red&size=L#details

$logo	= new URL( 'images/logo.png', 'https://example.com/' );
echo $logo;                                 // https://example.com/images/logo.png
```

See guide: [Data types](../../guide/data-types.md)
