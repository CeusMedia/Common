# CeusMedia Common

![Branch](https://img.shields.io/badge/Branch-1.1.x-blue?style=flat-square)
![Release](https://img.shields.io/badge/Release-1.1.2-blue?style=flat-square)
![PHP version](https://img.shields.io/badge/PHP-%5E8.3-blue?style=flat-square&color=777BB4)
![PHPStan level](https://img.shields.io/badge/PHPStan_level-6-darkgreen?style=flat-square)
[![Monthly downloads](https://img.shields.io/packagist/dt/ceus-media/common.svg?style=flat-square)](https://packagist.org/packages/ceus-media/common)
[![Package version](https://img.shields.io/packagist/v/ceus-media/common.svg?style=flat-square)](https://packagist.org/packages/ceus-media/common)
[![License](https://img.shields.io/packagist/l/ceus-media/common.svg?style=flat-square)](https://packagist.org/packages/ceus-media/common)

A comprehensive PHP library providing **400+ classes** for everyday development tasks — from file handling and network operations to CLI tools, data structures, and algorithms.

Built with modern PHP 8.3 features: typed properties, strict types, enums, and return types throughout.

## At a Glance

| Category | Classes | What It Covers |
|---|---|---|
| **File System** (`FS/`) | 103 | File/folder operations, archives, backups, locks, permissions, syntax checking, TODO parsing |
| **Network** (`Net/`) | 60 | HTTP/FTP clients, CURL wrapper, XMPP/Jabber, Google APIs, Atom time sync, site readers |
| **UI** (`UI/`) | 53 | HTML generation, image processing, SVG, Graphviz, templates, output buffering |
| **Algorithms** (`Alg/`) | 51 | Sorting, searching, password strength, color conversion, time formatting, validation, text processing |
| **Data Types** (`ADT/`) | 46 | Collections, trees, graphs, registries, singletons, URL/URN parsing, event dispatching |
| **XML** (`XML/`) | 44 | DOM manipulation, RSS/Atom feeds, OPML, XML-RPC, XSLT, validation |
| **CLI** (`CLI/`) | 31 | Argument parsing, prompts, questions, output formatting, forked workers, servers |
| **Exceptions** (`Exception/`) | 24 | HTTP exceptions, SQL exceptions, template exceptions, JSON-serializable errors |

## Installation

```bash
composer require ceus-media/common
```

Requires **PHP 8.3+** and [PSR-16 Simple Cache](https://www.php-fig.org/psr/psr-16/).

## Quick Start

```php
require_once 'vendor/autoload.php';

use CeusMedia\Common\Net\AtomTime;

$now = AtomTime::get();
echo 'Current Atom time: ' . $now->format(DATE_COOKIE) . PHP_EOL;
```

## Why Common?

- **No boilerplate** — Ready-to-use classes for tasks you'd otherwise reimplement
- **PSR-compliant** — Follows PSR-12 coding standards and PSR-16 Simple Cache
- **Modern PHP** — Fully leverages PHP 8.3 features: typed properties, enums, match expressions, readonly classes
- **Well-tested** — 171 PHPUnit tests and PHPStan Level 6 static analysis
- **Single dependency** — Only requires `psr/simple-cache`, no heavy framework dependencies
- **Namespace-organized** — Clear categorization makes finding the right class intuitive

## Highlights

### File Operations

Read, filter, and process files with a fluent API:

```php
use CeusMedia\Common\FS\File;
use CeusMedia\Common\FS\Folder\Iterator;

$file = new File(__FILE__);
echo 'Size: ' . $file->getSize() . ' bytes' . PHP_EOL;
echo 'Modified: ' . date('Y-m-d H:i:s', $file->getTime()) . PHP_EOL;

// Iterate folders with filtering
$iterator = new Iterator('/path/to/dir', [
    'showFolders' => true,
    'showFiles' => true,
    'stripDotEntries' => true,
]);
```

### Time Formatting

Human-readable time durations with custom phrases:

```php
use CeusMedia\Common\Alg\Time\DurationPhraser;

$phrases = [
    0       => '{s} seconds',
    60      => '{m} minutes',
    3600    => '{h} hours',
    24*3600 => '{d} days',
];

$phraser = DurationPhraser::fromArray($phrases);
echo $phraser->getPhraseFromTimestamp(filemtime(__FILE__));
// "3 hours"
```

### CLI Applications

Build interactive command-line tools with built-in prompts and output formatting:

```php
use CeusMedia\Common\CLI\ArgumentParser;
use CeusMedia\Common\CLI\Question;
use CeusMedia\Common\CLI\Output\Table;

$parser = new ArgumentParser($argv);
$name = (new Question('What is your name?'))->ask();

$table = new Table();
$table->setHeaders(['Name', 'Value']);
$table->addRow(['Key', 'Data']);
echo $table->render();
```

### Data Structures

Rich collection types beyond PHP's built-in arrays:

```php
use CeusMedia\Common\ADT\Collection\PriorityQueue;
use CeusMedia\Common\ADT\URL;

// Priority queue
$queue = new PriorityQueue();
$queue->add('Low task', 1);
$queue->add('High task', 10);
echo $queue->extract(); // "High task"

// URL parsing and manipulation
$url = new URL('https://example.com/path?query=value');
echo $url->getHost();   // "example.com"
echo $url->getQuery();  // "query=value"
```

### XML and Feeds

Parse and generate RSS, Atom, and OPML feeds:

```php
use CeusMedia\Common\XML\RSS\Reader;

$reader = new Reader('https://example.com/feed.xml');
$items = $reader->getItems();
foreach ($items as $item) {
    echo $item['title'] . PHP_EOL;
}
```

### Image Processing

Generate images, charts, and diagrams:

```php
use CeusMedia\Common\UI\Image;

$image = new Image();
$image->create(800, 600);
// ... draw, resize, convert formats ...
$image->save('/path/to/output.png');
```

### HTTP Client

Fetch web resources with a simple, chainable API:

```php
use CeusMedia\Common\Net\HTTP\Client;

$client = new Client();
$response = $client->get('https://api.example.com/data.json');
$data = json_decode($response->getBody(), true);
echo $response->getStatusCode(); // 200
```

### Password Strength

Analyze password complexity for validation:

```php
use CeusMedia\Common\Alg\Crypt\PasswordStrength;

$strength = PasswordStrength::getScore('MyP@ssw0rd!');
echo "Score: {$strength['score']}/100";
```

### Configuration Reader

Load and cache configuration files with support for multiple formats:

```php
use CeusMedia\Common\FS\File\Configuration\Reader;

$reader = new Reader('/path/to/config.ini');
$database = $reader->get('database');
$host = $reader->get('database.host', 'localhost'); // with default
```

### Event Handling

Implement the observer pattern with typed event data:

```php
use CeusMedia\Common\ADT\Event\Handler;
use CeusMedia\Common\ADT\Event\Data;

$handler = new Handler();
$handler->attach('user.login', function (Data $event) {
    echo "User logged in: " . $event->get('username');
});

$handler->dispatch('user.login', new Data(['username' => 'alice']));
```

### Color Conversion

Convert between color spaces for UI and design tools:

```php
use CeusMedia\Common\Alg\ColorConverter;

// Hex to RGB
$rgb = ColorConverter::hex2rgb('#FF5733'); // [255, 87, 51]

// RGB to HSL
$hsl = ColorConverter::rgb2hsv($rgb); // [14, 80, 100]

// CSS color name to hex
$hex = ColorConverter::name2hex('crimson'); // '#DC143C'
```

## Code Quality

The codebase is maintained with strict static analysis and automated tooling:

- **PHPStan Level 6** — all 418 source files pass with zero errors
- **Rector** — automated code modernization rules applied
- **PHPUnit** — 171 test files covering core functionality
- **PHP Parallel Lint** — syntax validation across the entire codebase

## Development

All development tasks are available as Composer scripts:

| Command | Description |
|---|---|
| `composer run dev:all` | Run all checks: syntax, PHPStan, PHPUnit, and docs |
| `composer run dev:install` | Install dependencies for development |
| `composer run dev:update` | Update dependencies (with root version fix) |
| `composer run dev:phpstan` | Run PHPStan static analysis |
| `composer run dev:phpunit` | Run PHPUnit tests |
| `composer run dev:rector` | Dry-run Rector code modernization |
| `composer run dev:rector-fix` | Apply Rector fixes |
| `composer run dev:create-docs` | Generate API documentation |
| `composer run dev:test-syntax` | Lint all PHP files for syntax errors |

> **Note:** Running `composer update` on feature branches requires `COMPOSER_ROOT_VERSION` because dev dependencies reference the root package. Use `composer run dev:update` instead.

## Migration from 0.8.x

When migrating older projects based on version `0.8.x`, enable backwards compatibility:

```php
require 'vendor/ceus-media/common/src/compat8.php';
```

> Avoid using compatibility mode in production for best performance.

## Project Structure

The library is organized into clear namespaces that mirror the directory structure:

```
src/
├── ADT/          — Abstract Data Types (collections, trees, graphs, registries)
├── Alg/          — Algorithms (sorting, crypto, color, time, validation)
├── CLI/          — Command-line interface tools (parsing, output, workers)
├── Exception/    — Custom exception classes (HTTP, SQL, templates)
├── FS/           — File system operations (files, folders, archives)
├── Net/          — Network utilities (HTTP, FTP, XMPP, APIs)
├── UI/           — User interface helpers (HTML, images, SVG)
└── XML/          — XML processing (DOM, RSS, OPML, XSLT)
```

Each namespace contains focused, single-purpose classes that can be used independently.

## Contributing

Contributions are welcome! Here's how you can help:

1. **Fork** the repository on [GitHub](https://github.com/CeusMedia/Common)
2. **Create** a feature branch (`git checkout -b feature/amazing-feature`)
3. **Commit** your changes with clear messages
4. **Push** to your branch and open a **Pull Request**

### Before Submitting

- Run `composer run dev:all` to ensure all checks pass
- Follow the existing code style (PSR-12)
- Add tests for new functionality
- Update documentation as needed

### Reporting Issues

Found a bug or have a feature request? Open an issue on [GitHub](https://github.com/CeusMedia/Common/issues) with:

- A clear description of the problem
- Steps to reproduce (for bugs)
- Your PHP version and environment details

## Resources

- **📖 API Documentation** — Generated via PHPDoc, available at [ceusmedia.de](https://ceusmedia.de/)
- **🐛 Bug Reports** — [GitHub Issues](https://github.com/CeusMedia/Common/issues)
- **💡 Feature Requests** — [GitHub Discussions](https://github.com/CeusMedia/Common/discussions)
- **📦 Packagist** — [ceus-media/common](https://packagist.org/packages/ceus-media/common)
- **🔒 Security** — Please report security issues privately via [ceusmedia.de](https://ceusmedia.de/)

## License

This project is licensed under the **GNU General Public License v3** (GPL-3.0-or-later).

See the [LICENSE](license.md) file for details.

## Author

Developed by [Christian Würker](https://ceusmedia.de/) at [CeusMedia](https://ceusmedia.de/).

- 🌐 Website: [ceusmedia.de](https://ceusmedia.de/)
- 🐙 GitHub: [CeusMedia](https://github.com/CeusMedia)

## Changelog

See the [GitHub Releases](https://github.com/CeusMedia/Common/releases) page for a detailed list of changes in each version.

> **Version 1.1.x** — Current stable branch with PHP 8.3 support and modern PHP features.
