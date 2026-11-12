# Network

The `Net` namespace contains HTTP clients (based on cURL), helpers for answering HTTP requests,
an FTP client, a site crawler and a few clients for web services.
Most HTTP clients need the PHP extension `curl`, the FTP classes need `ftp`.

| Task | Class |
|------|-------|
| read a URL, quick and simple | [`Net\Reader`](../api/Net/Reader.md) |
| GET and POST with headers, response objects | [`Net\HTTP\Reader`](../api/Net/HTTP/Reader.md) |
| send a POST request, get the body | [`Net\HTTP\Post`](../api/Net/HTTP/Post.md) |
| full control over cURL | [`Net\CURL`](../api/Net/CURL.md) |
| handle the current request, send a response | [`Net\HTTP\Request`](../api/Net/HTTP/Request.md), [`Net\HTTP\Response`](../api/Net/HTTP/Response.md) |
| send a file for download | [`Net\HTTP\Download`](../api/Net/HTTP/Download.md) |
| FTP | [`Net\FTP\Client`](../api/Net/FTP/Client.md) |

## Reading URLs

[`Net\Reader`](../api/Net/Reader.md) reads a URL and returns the body.
Redirects are followed, TLS certificates are verified.

```php
use CeusMedia\Common\Net\Reader;

$html	= Reader::readUrl( 'https://example.com/' );
```

With options:

```php
use CeusMedia\Common\Net\CURL;
use CeusMedia\Common\Net\Reader;

$reader	= new Reader( 'https://api.example.com/status' );
$reader->setTimeout( 30 )						//  seconds for the whole request
	->setConnectTimeout( 10 )					//  seconds to connect
	->setBasicAuth( 'user', 'secret' )
	->setProxy( 'proxy.example.com:3128' );		//  type CURLPROXY_HTTP (default) or CURLPROXY_SOCKS5
$body	= $reader->read();

$code	= $reader->getInfo( CURL::INFO_HTTP_CODE );		//  200
$type	= $reader->getInfo( CURL::INFO_CONTENT_TYPE );	//  "application/json"
```

`read()` and `readUrl()` accept a map of additional cURL options, given as constant or as constant name:

```php
$body	= $reader->read( [CURLOPT_ENCODING => '', 'CURLOPT_REFERER' => 'https://example.com/'] );
```

!!! info "Timeouts"
    Without `setTimeout()` and `setConnectTimeout()`, no timeouts are set, so the defaults of cURL apply
    (no limit for the request) – or a global timeout set by `Net\CURL::setTimeOut()`.
    Set timeouts for every request to a foreign server.

### Errors

| Situation | Exception |
|-----------|-----------|
| HTTP status other than 200, 301, 303, 304, 307 (after redirects) | [`Exception\IO`](../api/Exception/IO.md), code = HTTP status, `getResource()` = URL |
| connection failed (DNS, refused, timeout, TLS) | `RuntimeException` with cURL error message and number |
| URL without valid scheme, e.g. `file:///etc/passwd` | `InvalidArgumentException` |

`Exception\IO` is a `RuntimeException`, so one `catch` handles both:

```php
use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\Net\Reader;

try{
	$body	= Reader::readUrl( 'https://example.com/missing' );
}
catch( IoException $e ){
	echo 'HTTP '.$e->getCode().' for '.$e->getResource();		//  HTTP 404 for https://…
}
catch( RuntimeException $e ){
	echo 'Request failed: '.$e->getMessage();
}
```

??? info "User agent and response headers"
    The default user agent is `CeusMedia-Common/1.1 Net\Reader`. `setUserAgent()` changes it for **all**
    readers, since it is stored statically – other classes using `Net\Reader` (like `Net\Site\Crawler`
    or `Net\API\Dyn`) change it, too. To set it for one request only, pass `CURLOPT_USERAGENT` to `read()`.

    `getHeaders()` returns the response header fields as list of
    [`Net\HTTP\Header\Field`](../api/Net/HTTP/Header/Field.md) objects. `getHeader()` with a header name
    does not find any header in 1.1.1 – loop over `getHeaders()` instead:

    ```php
    foreach( $reader->getHeaders() as $field )
    	if( 'content-type' === $field->getName() )			//  names are lower case
    		$type	= $field->getValue();
    ```

`Net\Reader` is also used by [`XML\DOM\UrlReader`](../api/XML/DOM/UrlReader.md),
[`XML\RSS\Reader`](../api/XML/RSS/Reader.md), [`XML\Atom\Reader`](../api/XML/Atom/Reader.md) and
[`XML\FeedIdentifier`](../api/XML/FeedIdentifier.md), see [XML](xml.md).

## HTTP client

[`Net\HTTP\Reader`](../api/Net/HTTP/Reader.md) sends GET and POST requests with request headers
and returns a [`Net\HTTP\Response`](../api/Net/HTTP/Response.md). Compressed responses (gzip, deflate)
are decoded transparently. HTTP error statuses do not throw; only connection errors throw a `RuntimeException`.

=== "GET"

    ```php
    use CeusMedia\Common\Net\HTTP\Reader as HttpReader;

    $reader		= new HttpReader();
    $response	= $reader->get(
    	'https://api.example.com/items',
    	['Accept' => 'application/json'],			//  request headers
    	[CURLOPT_TIMEOUT => 30]						//  cURL options
    );
    $code		= $reader->getCurlInfo( 'http_code' );			//  e.g. 200 or 404
    $type		= $reader->getResponseHeader( 'content-type' );	//  case-insensitive, NULL if missing
    $items		= json_decode( $response->getBody(), TRUE );
    ```

=== "POST"

    ```php
    use CeusMedia\Common\Net\HTTP\Reader as HttpReader;

    $reader		= new HttpReader();
    $response	= $reader->post( 'https://example.com/form', ['name' => 'Alice', 'age' => 42] );
    echo $response->getStatus();									//  "200 OK"
    echo $response->getHeader( 'Content-Type', TRUE )->getValue();	//  first field of this name
    echo $response->getBody();
    ```

Headers can also be given as [`Net\HTTP\Header\Section`](../api/Net/HTTP/Header/Section.md):

```php
use CeusMedia\Common\Net\HTTP\Header\Section as HeaderSection;

$headers	= HeaderSection::getInstance()
	->addFieldPair( 'Accept', 'text/plain' )
	->addFieldPair( 'Accept-Language', 'de' );
$response	= $reader->get( 'https://example.com/', $headers );
```

| Method | Purpose |
|--------|---------|
| `setBasicAuth( $username, $password )` | HTTP basic authentication |
| `setUserAgent( $agent )` | user agent, default `CeusMediaCommon:Net.HTTP.Reader/1.0` |
| `setProxy( $address, $type, $auth )` | proxy server |
| `setEncoding( $method )` | accepted compression, e.g. `gzip`; all supported by default |
| `setCurlOption( $key, $value )` | cURL option for all requests |
| `setVerify( $host, $peer, $caPath, $caInfo )` | TLS verification, see [below](#tls-and-security) |
| `getCurlInfo( $key )` | cURL info of last request, e.g. `http_code`, `total_time` |
| `getResponseHeader( $name )` | response header of last GET request, repeated fields joined by comma |

!!! warning "Known limitations in 1.1.1"
    - The response of `get()` has no headers and always the status `200 OK` –
      use `getCurlInfo( 'http_code' )` and `getResponseHeader()`. Responses of `post()` are complete.
    - Headers and options given to `get()` or `post()` stay set for later requests of the same
      reader – a `get()` after a `post()` is sent as POST. Use a new reader for each kind of request.
    - `get()` damages binary bodies (images, archives); use `Net\Reader` for those.
    - `post()` prefixes values starting with `@` by a backslash.

### Sending POST data

[`Net\HTTP\Post`](../api/Net/HTTP/Post.md) sends form data or any other content and returns the trimmed
body of the response. It uses cURL, or PHP streams if cURL is not available. Redirects are not followed,
the connect timeout is 15 seconds. HTTP error statuses do not throw.

```php
use CeusMedia\Common\Net\HTTP\Post;

//  form data (application/x-www-form-urlencoded)
$result	= Post::sendData( 'https://example.com/api/subscribe', ['email' => 'alice@example.com'] );

//  JSON
$post	= new Post( 'https://example.com/api/items' );
$post->setContentType( 'application/json' )
	->setContent( json_encode( ['name' => 'Widget'] ) )
	->setUserAgent( 'MyApp/1.0' )
	->setDataMaxLength( 1024 * 1024 );			//  OutOfBoundsException for larger content
$result	= $post->send( [CURLOPT_TIMEOUT => 30] );
```

### cURL wrapper

[`Net\CURL`](../api/Net/CURL.md) is a thin wrapper around a cURL handle, used by the classes above.
Only HTTP and HTTPS are allowed, for requests and redirects.

```php
use CeusMedia\Common\Net\CURL;

$curl	= new CURL( 'https://example.com/data.json' );
$curl->setOption( CURLOPT_TIMEOUT, 30 );
$curl->setOption( 'CURLOPT_FOLLOWLOCATION', TRUE );		//  constant name works, too
$body	= $curl->exec( TRUE );			//  TRUE: throw RuntimeException on connection errors

$code	= $curl->getInfo( CURL::INFO_HTTP_CODE );
$types	= $curl->getHeader( 'content-type' );			//  list of values, e.g. ['application/json']
$curl->close();
```

Without `exec( TRUE )`, failures return an empty string and `hasError()` returns the cURL error message.
`CURL::setTimeOut( $seconds )` sets request and connect timeout for all instances created afterwards.

!!! note
    With header parsing (the default), `exec()` damages bodies which are not valid UTF-8.
    For binary content, disable headers by `setOption( CURLOPT_HEADER, FALSE )` or use `Net\Reader`.

## TLS and security

Since 1.1.1, all HTTP clients verify TLS certificates and host names by default:

| Class | Default | Change |
|-------|---------|--------|
| `Net\Reader` (and all classes using it) | verify certificate and host | `setVerifyPeer( FALSE )`, `setVerifyHost( FALSE )` |
| `Net\HTTP\CrossDomainProxy` | verify certificate and host | `setVerifySsl( FALSE )` or last argument of `requestUrl()` |
| `Net\HTTP\Reader`, `Net\HTTP\Post`, `Net\CURL` | defaults of cURL (verify) | `setVerify()` resp. cURL options |

`Net\CURL` only allows the protocols HTTP and HTTPS, so URLs like `file://`, `ftp://` or `gopher://` – and
redirects to them – are refused. Still, never read URLs taken from user input without checking them
(e.g. against requests to internal hosts).

To trust an own certificate authority instead of switching verification off:

```php
use CeusMedia\Common\Net\HTTP\Reader as HttpReader;

$reader	= new HttpReader();
$reader->setVerify( TRUE, TRUE, NULL, '/etc/ssl/own-ca.pem' );	//  host, peer, CA path, CA file
```

!!! warning
    All parameters of `Net\HTTP\Reader::setVerify()` default to `FALSE` – calling `setVerify()` without
    arguments **disables** verification.

!!! danger "Disabling verification"
    Without verification, any server can pretend to be the requested one (man-in-the-middle).
    Disable it for development with self-signed certificates only, never in production:
    ```php
    use CeusMedia\Common\Net\Reader;

    $reader	= new Reader( 'https://dev.local/' );
    $reader->setVerifyPeer( FALSE )->setVerifyHost( FALSE );
    ```

## Answering requests

On the server side, [`Net\HTTP\Request`](../api/Net/HTTP/Request.md) collects the current request
and a [`Net\HTTP\Response`](../api/Net/HTTP/Response.md) is sent by
[`Net\HTTP\Response\Sender`](../api/Net/HTTP/Response/Sender.md), which compresses it if the client accepts it.

```php
use CeusMedia\Common\Net\HTTP\Request;
use CeusMedia\Common\Net\HTTP\Response;
use CeusMedia\Common\Net\HTTP\Response\Sender as ResponseSender;

$request	= new Request();
$request->setTrustedProxies( ['10.0.0.0/8'] );		//  only these may set X-Forwarded-For
$request->fromEnv();								//  GET, POST, FILES, headers, IP, raw body

$name		= $request->get( 'name', 'World' );		//  from GET or POST
$isPost		= $request->getMethod()->isPost();
$isAjax		= $request->isAjax();					//  X-Requested-With: XMLHttpRequest
$clientIp	= $request->getIp();
$query		= $request->getAllFromSource( 'get' );

$response	= new Response();
$response->setStatus( 200 )
	->addHeaderPair( 'Content-Type', 'text/plain; charset=utf-8' )
	->setBody( 'Hello '.$name );
ResponseSender::sendResponseForRequest( $response, $request );		//  gzip/deflate if accepted
```

`fromEnv( TRUE, TRUE )` includes session and cookie values. `getHeader( $name )` returns the last
header field of this name and throws a `RuntimeException` if missing; `getHeader( $name, FALSE )` returns `NULL` instead.

| Class | Purpose |
|-------|---------|
| [`Net\HTTP\Status`](../api/Net/HTTP/Status.md) | status texts: `getText( 404 )` → `Not Found`, `getCode( 'Not Found' )`, `isCode()`, `sendHeader()` |
| [`Net\HTTP\Method`](../api/Net/HTTP/Method.md) | request method, `isGet()`, `isPost()`, … and `METHOD_*` constants |
| [`Net\HTTP\Cookie`](../api/Net/HTTP/Cookie.md) | read and set cookies: `set( $key, $value, $expiresInSeconds )`, `get()`, `remove()` |
| [`Net\HTTP\PartitionCookie`](../api/Net/HTTP/PartitionCookie.md) | several values stored in one cookie |
| [`Net\HTTP\Session`](../api/Net/HTTP/Session.md), [`PartitionSession`](../api/Net/HTTP/PartitionSession.md) | PHP session (partitioned) |
| [`Net\HTTP\Sniffer\Language`](../api/Net/HTTP/Sniffer/Language.md), [`Charset`](../api/Net/HTTP/Sniffer/Charset.md), [`Encoding`](../api/Net/HTTP/Sniffer/Encoding.md), [`MimeType`](../api/Net/HTTP/Sniffer/MimeType.md), [`Client`](../api/Net/HTTP/Sniffer/Client.md) | content negotiation by `Accept-*` headers |
| [`Net\HTTP\UploadErrorHandler`](../api/Net/HTTP/UploadErrorHandler.md) | throws exceptions for failed file uploads (by `$_FILES` error code) |
| [`Net\HTTP\Request\Receiver`](../api/Net/HTTP/Request/Receiver.md) | older request collector (dictionary) |
| [`Net\HTTP\Request\QueryParser`](../api/Net/HTTP/Request/QueryParser.md) | query string to array |

```php
use CeusMedia\Common\Net\HTTP\Sniffer\Language;

$language	= Language::getLanguage( ['en', 'de'], 'en' );		//  by Accept-Language, else "en"
```

### Downloads

[`Net\HTTP\Download`](../api/Net/HTTP/Download.md) sends a file or a string as attachment and exits by default.
Non-ASCII file names are encoded for the `Content-Disposition` header.

```php
use CeusMedia\Common\Net\HTTP\Download;

Download::sendString( $csv, 'export.csv' );
Download::sendFile( '/var/data/reports/2026-11.pdf', 'Report November.pdf' );
```

!!! warning
    `sendFile()` removes all `../` from the path, so give a resolved path (`realpath()`).
    It does not restrict the path otherwise – never pass user input unchecked.

### Headers and raw responses

| Class | Purpose |
|-------|---------|
| [`Net\HTTP\Header\Field`](../api/Net/HTTP/Header/Field.md) | one header field; `getValue( TRUE )` decodes qualified values like `de;q=0.9` |
| [`Net\HTTP\Header\Section`](../api/Net/HTTP/Header/Section.md) | collection of fields: `addFieldPair()`, `getField()`, `getFieldsByName()`, `render()` |
| [`Net\HTTP\Header\Parser`](../api/Net/HTTP/Header/Parser.md), [`Field\Parser`](../api/Net/HTTP/Header/Field/Parser.md), [`Renderer`](../api/Net/HTTP/Header/Renderer.md) | parse and render header blocks |
| [`Net\HTTP\Response\Parser`](../api/Net/HTTP/Response/Parser.md) | raw HTTP response to `Response` object |
| [`Net\HTTP\Response\Decompressor`](../api/Net/HTTP/Response/Decompressor.md), [`Compressor`](../api/Net/HTTP/Response/Compressor.md) | gzip and deflate |

```php
use CeusMedia\Common\Net\HTTP\Response\Decompressor;
use CeusMedia\Common\Net\HTTP\Response\Parser as ResponseParser;

$response	= ResponseParser::fromString( $rawResponse );	//  last header block wins (redirects)
Decompressor::decompressResponse( $response );				//  by Content-Encoding header
echo $response->getStatus();								//  "200 OK"
echo $response->getBody();
```

### Cross-domain proxy

[`Net\HTTP\CrossDomainProxy`](../api/Net/HTTP/CrossDomainProxy.md) forwards the current GET or POST request
(query string and POST data) to a service and returns its response – e.g. for browser scripts which may not
call the service directly.

```php
use CeusMedia\Common\Net\HTTP\CrossDomainProxy;

$proxy	= new CrossDomainProxy( 'https://service.example.com/api', 'user', 'secret' );
echo $proxy->forward();
```

Make sure the proxy cannot be misused to reach arbitrary services: the service URL is fixed, only the query is forwarded.

## FTP

[`Net\FTP\Client`](../api/Net/FTP/Client.md) connects and logs in on construction
(a `RuntimeException` if that fails) and closes the connection on destruction.
The connection is plain FTP, without TLS.

```php
use CeusMedia\Common\Net\FTP\Client as FtpClient;

$ftp	= new FtpClient( 'ftp.example.com', 21, NULL, 'user', 'secret' );	//  host, port, path, user, password
$ftp->createFolder( 'reports' );
$ftp->putFile( '/local/report.txt', 'reports/report.txt' );
foreach( $ftp->getFileList( 'reports' ) as $file )
	echo $file['name'].' '.$file['size'].' '.$file['octal'].PHP_EOL;	//  report.txt 14 0664
$ftp->getFile( 'reports/report.txt', '/local/copy.txt' );
$ftp->renameFile( 'reports/report.txt', 'reports/old.txt' );
$ftp->removeFile( 'reports/old.txt' );
```

Most methods return `TRUE` on success and `FALSE` on failure. Lists (`getList()`, `getFileList()`,
`getFolderList()`, `searchFile()`, `searchFolder()`) can be read recursively and contain maps with
`name`, `size`, `isdir`, `permissions`, `octal`, `timestamp`, `datetime` and more.

For more control, combine [`Net\FTP\Connection`](../api/Net/FTP/Connection.md) (`login()`, `setPassiveMode()`,
`setTimeout()`, `setTransferMode()`) with [`Net\FTP\Reader`](../api/Net/FTP/Reader.md) and
[`Net\FTP\Writer`](../api/Net/FTP/Writer.md).

## Crawling and sitemaps

[`Net\Site\Crawler`](../api/Net/Site/Crawler.md) follows the links of a website up to a depth
and collects all HTML pages:

```php
use CeusMedia\Common\Net\Site\Crawler;
use CeusMedia\Common\Net\Site\MapBuilder;

$crawler	= new Crawler( 'https://example.com/', 3 );		//  base URL, depth
$crawler->crawl( 'https://example.com/' );
foreach( $crawler->getLinks() as $link )
	echo $link['url'].' ('.$link['references'].')'.PHP_EOL;	//  also: $link['content']
$errors		= $crawler->getErrors();						//  map of URL => error message

$xml	= MapBuilder::build( array_column( $crawler->getLinks(), 'url' ) );
```

Links not starting with the base URL are ignored unless `crawl()` gets `TRUE` as second argument. Files with extensions
listed in the public property `$denied` (images, PDF, office documents, media) are skipped.
[`Net\Site\MapCreator`](../api/Net/Site/MapCreator.md) crawls and writes the sitemap file in one step,
[`Net\Site\MapWriter`](../api/Net/Site/MapWriter.md) writes a sitemap for a list of URLs.

## More network classes

| Class | Purpose |
|-------|---------|
| [`Net\Connectivity`](../api/Net/Connectivity.md) | online check by socket (port 443) or `ping`, with callback on status change |
| [`Net\AtomTime`](../api/Net/AtomTime.md) | current time from the time server of PTB (NTP) |
| [`Net\AtomServerTime`](../api/Net/AtomServerTime.md) | server time corrected by a stored difference to the atom time |
| [`Net\XMPP\MessageSender`](../api/Net/XMPP/MessageSender.md), [`Net\XMPP\JID`](../api/Net/XMPP/JID.md) | send Jabber/XMPP messages |
| [`Net\SVN\Client`](../api/Net/SVN/Client.md) | Subversion working copy, needs the PECL extension `svn` |

```php
use CeusMedia\Common\Net\Connectivity;

$net	= new Connectivity();
$net->setTarget( 'example.com' )
	->setMethod( Connectivity::METHOD_SOCKET )
	->setCallbackOnChange( fn( int $status ) => print( 'Status: '.$status.PHP_EOL ) );
if( $net->isOffline() )
	echo 'No connection', PHP_EOL;
```

```php
use CeusMedia\Common\Net\XMPP\JID;
use CeusMedia\Common\Net\XMPP\MessageSender;

$sender	= new MessageSender();
$sender->connect( new JID( 'example.com', 'bot' ), 'secret' );		//  domain, node
$sender->sendMessageTo( 'Backup finished', 'alice@example.com' );
$sender->disconnect();
```

### Web service clients

| Class | Purpose | Status |
|-------|---------|--------|
| [`Net\API\Gravatar`](../api/Net/API/Gravatar.md) | avatar URL and `<img>` tag for an email address | URL methods usable |
| [`Net\API\Dyn`](../api/Net/API/Dyn.md) | dynamic DNS update at dyn.com, with IP cache file | |
| [`Net\API\DDNSS`](../api/Net/API/DDNSS.md) | dynamic DNS update at ddnss.de | |
| [`Net\API\Google\Maps\Geocoder`](../api/Net/API/Google/Maps/Geocoder.md) | address to geo coordinates, needs API key | |
| [`Net\API\Google\ClosureCompiler`](../api/Net/API/Google/ClosureCompiler.md) | minify JavaScript | **deprecated**: service shut down |
| [`Net\API\Google\Sitemap\Submit`](../api/Net/API/Google/Sitemap/Submit.md) | ping Google with a sitemap | **deprecated**: endpoint turned off |
| [`Net\API\Premailer`](../api/Net/API/Premailer.md) | inline CSS of HTML mails | **deprecated**: service shut down |

The deprecated classes trigger a deprecation error and will throw a `DeprecationException` from 1.2 on.

```php
use CeusMedia\Common\Net\API\Gravatar;

$gravatar	= new Gravatar( 120 );							//  size in pixels
echo $gravatar->renderImage( 'alice@example.com', ['alt' => 'Alice'] );
$url		= $gravatar->getUrl( 'alice@example.com' );		//  HTML-escaped (&amp;)
```
