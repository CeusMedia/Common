---
title: Home
hide:
  - navigation
  - toc
---

<div class="hero" markdown>

# CeusMedia Common

A PHP library with **400+ classes** for everyday tasks – files and folders, HTTP and other protocols,
HTML and images, XML and feeds, command line tools, data structures and algorithms.

[Get started](guide/installation.md){ .md-button .md-button--primary }
[Guide](guide/index.md){ .md-button }
[API Reference](api/index.md){ .md-button }
[GitHub](https://github.com/CeusMedia/Common){ .md-button }

</div>

=== "Files"

    ```php
    use CeusMedia\Common\FS\File;
    use CeusMedia\Common\FS\File\INI\Reader as IniReader;

    $file	= new File( 'notes.txt', TRUE );         // create if missing
    $file->setContent( "first line\nsecond line\n" );
    echo $file->getSize();                          // 23

    $ini	= new IniReader( 'app.ini', TRUE );      // with sections
    echo $ini->getProperty( 'host', 'db' );         // "localhost"
    ```

=== "HTTP"

    ```php
    use CeusMedia\Common\Net\Reader;

    $reader	= new Reader( 'https://api.example.com/status.json' );
    $reader->setTimeout( 10 );                      // TLS certificate and host are verified
    $data	= json_decode( $reader->read(), TRUE );
    echo $reader->getInfo( 'http_code' );           // 200
    ```

=== "HTML"

    ```php
    use CeusMedia\Common\UI\HTML\Tag;

    echo Tag::create( 'a', 'Menu', ['href' => '/menu?a=1&b=2', 'class' => 'link'] );
    // <a href="/menu?a=1&amp;b=2" class="link">Menu</a>
    ```

=== "Data types"

    ```php
    use CeusMedia\Common\ADT\URL;
    use CeusMedia\Common\Alg\UnitParser;

    $url	= new URL( 'https://example.com/shop/list?page=2' );
    echo $url->getHost();                           // "example.com"
    echo $url->getQuery();                          // "page=2"

    echo UnitParser::parse( '2 MiB' );              // 2097152
    echo UnitParser::parse( '1.5 GB' );             // 1500000000
    ```

## What is inside

<div class="grid cards" markdown>

-   :material-folder-outline:{ .lg .middle } __File system__

    ---

    Files and folders, INI, CSV, JSON and YAML, logs, archives, backups, locks, iCal and vCard.

    [:octicons-arrow-right-24: File system](guide/filesystem.md)

-   :material-web:{ .lg .middle } __Network__

    ---

    Read URLs with cURL, HTTP requests and responses, downloads, cookies, FTP, crawler and sitemaps.

    [:octicons-arrow-right-24: Network](guide/network.md)

-   :material-xml:{ .lg .middle } __XML__

    ---

    DOM nodes, parser and builder, XPath, RSS and Atom feeds, OPML, XML-RPC and XSLT.

    [:octicons-arrow-right-24: XML](guide/xml.md)

-   :material-palette-outline:{ .lg .middle } __User interface__

    ---

    HTML tags and elements, page frames, pagination, templates, image processing and SVG charts.

    [:octicons-arrow-right-24: User interface](guide/ui.md)

-   :material-console:{ .lg .middle } __Command line__

    ---

    Arguments, colored output, tables and progress, questions, forked workers, daemons and cron jobs.

    [:octicons-arrow-right-24: CLI](guide/cli.md)

-   :material-code-braces:{ .lg .middle } __Data types__

    ---

    Collections, registries, bitmasks, trees and graphs, URLs, JSON, time and events.

    [:octicons-arrow-right-24: Data types](guide/data-types.md)

-   :material-function-variant:{ .lg .middle } __Algorithms__

    ---

    Text tools, sorting and searching, colors, durations, units, validation and password strength.

    [:octicons-arrow-right-24: Algorithms](guide/algorithms.md)

-   :material-cog-outline:{ .lg .middle } __Core__

    ---

    Environment, autoloader, deprecations, exceptions and global helper functions.

    [:octicons-arrow-right-24: Core](guide/core.md)

-   :material-api:{ .lg .middle } __API reference__

    ---

    Every class and method, generated from the code.

    [:octicons-arrow-right-24: API reference](api/index.md)

</div>

## Independent tools

Each namespace contains focused classes, which can be used on their own – there is no framework to
set up and no bootstrapping besides Composer's autoloader. The only required dependency is
[PSR-16 Simple Cache](https://www.php-fig.org/psr/psr-16/); PHP extensions needed by single classes
are suggested by Composer (see [Installation](guide/installation.md)).

!!! info "New in version 1.1.1"
    A bugfix and security release: TLS certificates are verified by default, `Net\CURL` only allows
    HTTP and HTTPS, and many HTML renderers escape their values now. See [Upgrading](guide/upgrading.md)
    and the [changelog](changelog.md).

## Requirements

- PHP 8.3 or newer
- Composer

```sh
composer require ceus-media/common
```

License: [GPL-3.0-or-later](https://www.gnu.org/licenses/gpl-3.0.txt)
