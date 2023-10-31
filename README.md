# CeusMedia Common

A library of PHP classes for common tasks.

![Branch](https://img.shields.io/badge/Branch-0.9-blue?style=flat-square)
![Release](https://img.shields.io/badge/Release-0.9.0-blue?style=flat-square)
![PHP version](https://img.shields.io/badge/PHP-7.4+-blue?style=flat-square&color=777BB4)
![PHPStan level](https://img.shields.io/badge/PHPStan_level-6-darkgreen?style=flat-square)
[![Total downloads](http://img.shields.io/packagist/dt/ceus-media/common.svg?style=flat-square)](https://packagist.org/packages/ceus-media/common)
[![Package version](http://img.shields.io/packagist/v/ceus-media/common.svg?style=flat-square)](https://packagist.org/packages/ceus-media/common)
[![License](https://img.shields.io/packagist/l/ceus-media/common.svg?style=flat-square)](https://packagist.org/packages/ceus-media/common)

## Features

- Categorized classes for different basic tasks
  - File and Folder indexing
  - File Handling (CSV, iCal, INI, JSON, vCard, YAML, XML etc.)
  - HTTP & FTP handling
  - CLI handling
  - HTML & image
  - Data types and algorithms
- Class names with namespaces
- Typed properties
- Types method arguments
- Code quality
  - PHPStan level 9 complete + extra strict rules
  - Rector 7.4 rule set complete
  - PHPUnit 9.5 & some unit tests
- Composer scripts for development

## Usage

Installing the library via composer and packagist.
```
composer require ceus-media/common
```

Usage:
```
require_once 'vendor/autoload.php';

$atomDateTime = CeusMedia\Common\Net\AtomTime::get();
print 'Atom Time: '.$atomDateTime->format( DATE_COOKIE ).PHP_EOL;
```
*This will show the current Atom Time.*

### Example script
```
require_once 'vendor/autoload.php';

use CeusMedia\Common\Alg\Time\DurationPhraser;
use CeusMedia\Common\FS\File;

$file = new File( __FILE__ );

$timeRangePhrases = [
    0       => '{s} seconds',
    60      => '{m} minutes',
    3600    => '{h} hours',
    24*3600 => 'ages'
];

$phraser	= DurationPhraser::fromArray( $timeRangePhrases );
$duration	= $phraser->getPhraseFromTimestamp( $file->getTime() );

echo vsprintf( 'This file (%s) has been modified %s ago.'.PHP_EOL, [
	$file->getName(),
	$duration,
] );
```
*This will show the age of this script file.  
From here, you could use the <code>DurationPhraser</code> on other entity timestamps, like a comment, stored in a database.
Also, this example shows basic file access.  
This is really just the tip of the iceberg.*


### Migration from 0.8.x

During migrating older projects, based on version 0.8.x, you can enable a backwards compatibility mode:
```
require 'vendor/ceus-media/common/src/compat8.php';
```
But you should not do this in production to have the best performance.
