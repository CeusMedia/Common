```php
use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\Net\CURL;
use CeusMedia\Common\Net\Reader;

$html	= Reader::readUrl( 'https://example.com/' );

$reader	= new Reader( 'https://api.example.com/status' );
$reader->setTimeout( 30 )->setConnectTimeout( 10 );
try{
	$body	= $reader->read();
	$type	= $reader->getInfo( CURL::INFO_CONTENT_TYPE );
}
catch( IoException $e ){			//  HTTP status like 404, code = status
	echo 'HTTP '.$e->getCode();
}
catch( RuntimeException $e ){		//  connection or TLS failure
	echo $e->getMessage();
}
```

TLS certificates and hosts are verified by default; `setVerifyPeer( FALSE )` and `setVerifyHost( FALSE )` disable this (development only).

See guide: [Network](../../guide/network.md)
