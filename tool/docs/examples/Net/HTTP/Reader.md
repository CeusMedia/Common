```php
use CeusMedia\Common\Net\HTTP\Reader as HttpReader;

$reader		= new HttpReader();
$response	= $reader->get( 'https://api.example.com/items', ['Accept' => 'application/json'], [CURLOPT_TIMEOUT => 30] );
$code		= $reader->getCurlInfo( 'http_code' );
$type		= $reader->getResponseHeader( 'content-type' );
$items		= json_decode( $response->getBody(), TRUE );

$reader		= new HttpReader();
$response	= $reader->post( 'https://example.com/form', ['name' => 'Alice'] );
echo $response->getStatus();		//  "200 OK"
```

See guide: [Network](../../../guide/network.md)
