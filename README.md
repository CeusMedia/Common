# CeusMedia Common

![Branch](https://img.shields.io/badge/Branch-1.1.x-blue?style=flat-square)
![Release](https://img.shields.io/badge/Release-1.1.1-blue?style=flat-square)
![PHP version](https://img.shields.io/badge/PHP-%5E8.3-blue?style=flat-square&color=777BB4)
![PHPStan level](https://img.shields.io/badge/PHPStan_level-6-darkgreen?style=flat-square)
[![Monthly downloads](https://img.shields.io/packagist/dt/ceus-media/common.svg?style=flat-square)](https://packagist.org/packages/ceus-media/common)
[![Package version](https://img.shields.io/packagist/v/ceus-media/common.svg?style=flat-square)](https://packagist.org/packages/ceus-media/common)
[![License](https://img.shields.io/packagist/l/ceus-media/common.svg?style=flat-square)](https://packagist.org/packages/ceus-media/common)

A comprehensive PHP library providing **400+ classes** for everyday development tasks — from file handling and network operations to CLI tools, data structures, and algorithms.

Built for PHP 8.3: strict types, typed properties, parameter and return types throughout.

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
| **Exceptions** (`Exception/`) | 25 | HTTP exceptions, SQL exceptions, template exceptions, JSON-serializable errors |

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

## Documentation

A manual with guides and an API reference is built by MkDocs from `doc/Manual/`: run `composer dev:docs`, then open `doc/Site/index.html` - or `composer dev:docs-serve` for a live preview. Changes per version are listed in the [changelog](CHANGELOG.md).

## Why Common?

- **No boilerplate** — Ready-to-use classes for tasks you'd otherwise reimplement
- **PSR-compliant** — Follows PSR-12 coding standards and PSR-16 Simple Cache
- **Modern PHP** — Strict types, typed properties, union types and match expressions
- **Well-tested** — 1,500 PHPUnit tests and PHPStan Level 6 static analysis
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

// Iterate folder entries: files, folders, without dot entries
foreach (new Iterator('/path/to/dir', showFiles: true, showFolders: true, stripDotEntries: true) as $entry) {
    echo $entry->getFilename() . PHP_EOL;
}
```

### Time Formatting

Human-readable time durations with custom phrases:

```php
use CeusMedia\Common\Alg\Time\DurationPhraser;

$phraser = DurationPhraser::fromArray([
    '0s' => 'just now',
    '1m' => '{m} minutes ago',
    '1h' => '{h} hours ago',
    '1D' => '{D} days ago',
]);

echo $phraser->getPhraseFromSeconds(7200);                      // "2 hours ago"
echo $phraser->getPhraseFromTimestamp(time() - 3 * 86400);      // "3 days ago"
```

### CLI Applications

Build interactive command-line tools with built-in prompts and output formatting:

```php
use CeusMedia\Common\CLI\ArgumentParser;
use CeusMedia\Common\CLI\Question;
use CeusMedia\Common\CLI\Output\Table;

// php tool.php import users.csv v=1 limit=10
[$commands, $parameters] = ArgumentParser::parseFromCurrentCall(['v' => 'verbose']);
// $commands: ['import', 'users.csv'], $parameters: ['verbose' => '1', 'limit' => '10']

$name = Question::askStatic('What is your name?');

$table = new Table();
$table->setData([
    ['name' => 'Alice', 'city' => 'Leipzig'],
    ['name' => 'Bob', 'city' => 'Berlin'],
]);
echo $table->render();
// ┌───────┬─────────┐
// │ name  │ city    │
// ├───────┼─────────┤
// │ Alice │ Leipzig │
// ...
```

### Data Structures

Rich collection types beyond PHP's built-in arrays:

```php
use CeusMedia\Common\ADT\Collection\PriorityQueue;
use CeusMedia\Common\ADT\URL;

// Priority queue: lower number first
$queue = new PriorityQueue();
$queue->enqueue('Later task', 9);
$queue->enqueue('Urgent task', 1);
echo $queue->dequeue(); // "Urgent task"

// URL parsing and manipulation
$url = new URL('https://example.com/path?query=value');
echo $url->getHost();   // "example.com"
echo $url->getQuery();  // "query=value"
```

### XML and Feeds

Parse and generate RSS, Atom, and OPML feeds:

```php
use CeusMedia\Common\XML\RSS\Reader;

$feed = Reader::readUrl('https://example.com/feed.xml');
echo $feed['channelData']['title'] . PHP_EOL;
foreach ($feed['itemList'] as $item) {
    echo $item['title'] . PHP_EOL;
}
```

### Image Processing

Generate images, charts, and diagrams:

```php
use CeusMedia\Common\UI\Image;
use CeusMedia\Common\UI\Image\Processing;

$image = new Image('/path/to/photo.jpg');
$processor = new Processing($image);
$processor->resize(400, 300);
$image->setType(IMAGETYPE_PNG);
$image->save('/path/to/output.png');
```

### HTTP Client

Fetch web resources via cURL – TLS certificates are verified, only HTTP and HTTPS are allowed:

```php
use CeusMedia\Common\Net\Reader;

$reader = new Reader('https://api.example.com/data.json');
$reader->setTimeout(10);
$data = json_decode($reader->read(), true);
echo $reader->getInfo('http_code'); // 200
```

### Password Strength

Analyze password complexity for validation:

```php
use CeusMedia\Common\Alg\Crypt\PasswordStrength;

$strength = PasswordStrength::getStrength('MyP@ssw0rd!');
echo "Strength: {$strength}/100";        // "Strength: 88/100", common passwords are negative
```

### Configuration Reader

Load and cache configuration files with support for multiple formats:

```php
use CeusMedia\Common\FS\File\Configuration\Reader;

$reader = new Reader('/path/to/config.ini');           // INI sections, YAML, XML or own JSON format
$database = $reader->get('database');                 // ['host' => 'db.local', 'port' => '3306']
$host = $reader->get('database.host', 'localhost');   // with default
```

### Event Handling

Implement the observer pattern with typed event data:

```php
use CeusMedia\Common\ADT\Event\Handler;
use CeusMedia\Common\ADT\Event\Data;

$handler = new Handler();
$handler->bind('user.login', function (Data $event) {
    echo 'User logged in: ' . $event->arguments['username'];
});

$handler->trigger('user.login', null, ['username' => 'alice']);
```

### Color Conversion

Convert between color spaces for UI and design tools:

```php
use CeusMedia\Common\Alg\ColorConverter;

// HTML color to RGB
$rgb = ColorConverter::html2rgb('#FF5733'); // [255, 87, 51]

// RGB to HSV
$hsv = ColorConverter::rgb2hsv($rgb); // [11, 80, 100]

// RGB to HTML color
$html = ColorConverter::rgb2html([220, 20, 60]); // 'DC143C'
```

## Code Quality

The codebase is maintained with strict static analysis and automated tooling:

- **PHPStan Level 6** — all 419 analysed source files pass with zero errors, without baseline
- **Rector** — automated code modernization rules applied
- **PHPUnit** — 1,508 tests in 207 test files
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
| `composer run dev:docs` | Generate API reference and build documentation (MkDocs) into `doc/Site/` |
| `composer run dev:docs-serve` | Live preview of documentation on http://localhost:8000 |
| `composer run dev:test-syntax` | Lint all PHP files for syntax errors |

> **Note:** Running `composer update` on feature branches requires `COMPOSER_ROOT_VERSION` because dev dependencies reference the root package. Use `composer run dev:update` instead.

## Upgrading

Version 1.1 requires PHP 8.3 and declares strict types in all classes. The compatibility layers for code written for 0.8.x and 0.9.x (`compat8.php`, `compat9.php`) have been removed in 1.1.0. See the guide page *Upgrading* in the manual (`doc/Manual/guide/upgrading.md`) and the [changelog](CHANGELOG.md) for all changes which may need changes in your code.

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

See [CHANGELOG.md](CHANGELOG.md) for a detailed list of changes in each version, including breaking changes. Release downloads are available on the [GitHub Releases](https://github.com/CeusMedia/Common/releases) page.

> **Version 1.1.x** — Current stable branch with PHP 8.3 support and modern PHP features.
