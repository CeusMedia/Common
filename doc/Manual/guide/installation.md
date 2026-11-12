# Installation

## Requirements

| Requirement | Purpose |
|-------------|---------|
| PHP **8.3** or newer | |
| `psr/simple-cache` | installed by Composer automatically |
| PHP extensions | only for the classes needing them, see below |

## Composer

```sh
composer require ceus-media/common
```

The library follows PSR-4, all classes live in namespace `CeusMedia\Common`.
Include Composer's autoloader and you are ready:

```php
require_once 'vendor/autoload.php';

use CeusMedia\Common\FS\File;

$file	= new File( 'notes.txt', TRUE );
$file->setContent( 'Hello' );
```

Composer also loads `src/global.php`, which declares some global helper functions for debugging –
see [Core](core.md).

## Optional extensions

Most classes only need PHP itself. Some depend on extensions, which Composer lists as suggestions:

| Extension | Needed by |
|-----------|-----------|
| `bz2` | `FS\File\Arc\Bzip` |
| `curl` | `Net\Reader`, `Net\CURL`, `Net\HTTP\Reader`, `Net\HTTP\CrossDomainProxy`, `CLI\Downloader` and all classes reading URLs, like the feed readers |
| `dom` | `XML\DOM`, `XML\XSL` and `XML\Namespaces` |
| `exif` | `UI\Image\Exif` |
| `fileinfo` | detecting MIME types in `FS\File` |
| `ftp` | `Net\FTP` |
| `gd` | `UI\Image` – processing, thumbnails, watermarks |
| `iconv` | `Alg\Text\EncodingConverter`, `ADT\String_` and `XML\DOM\Formater` |
| `imagick` | `FS\File\PdfToImage` |
| `pcntl` | `CLI\Fork` |
| `posix` | `CLI\Fork` and file owner information in `FS\File` |
| `simplexml` | `XML\Element`, `XML\RSS\SimpleParser` and `XML\Namespaces` |
| `sockets` | `CLI\Fork\Server` |
| `xml` | `XML\Parser` and `XML\Validator` |
| `xsl` | `XML\XSL\Transformator` |
| `zip` | `FS\File\Arc\Zip` |
| `zlib` | `FS\File\Arc\Gzip` and HTTP response compression |

```sh
composer suggests ceus-media/common     # list suggested extensions
```

## Versions

| Branch | PHP | |
|--------|-----|---|
| **1.1.x** | 8.3+ | current branch, strict types |
| 1.0.x | 8.1+ | |

The library adheres to [Semantic Versioning](https://semver.org/) since 1.0.0.
Updating from an older version? See [Upgrading](upgrading.md).
