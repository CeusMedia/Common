# File system

The `FS` namespace wraps files and folders in small objects, and offers readers and writers for many
file formats: INI, JSON, YAML, CSV, log files, archives, CSS, vCard, iCal and more.
Most classes work on a path given to the constructor and throw exceptions if something goes wrong.

## Files

[`FS\File`](../api/FS/File.md) represents a single file – existing or not yet:

```php
use CeusMedia\Common\FS\File;

$file	= new File( 'data/notes.txt' );
if( !$file->exists() )
	$file->setContent( "first line\nsecond line\n" );    // creates the file

echo $file->getContent();
echo $file->getSize();              // 23
echo $file->getSize( 2 );           // "23 B" – formatted, if a precision is given
echo $file->getName();              // notes.txt
echo $file->getName( FALSE );       // notes
echo $file->getExtension();         // txt
echo $file->getMimeType();          // text/plain
echo $file->getEncoding();          // us-ascii
echo $file->getTime();              // modification time as UNIX timestamp
echo $file->getPermissions()->getAsString();    // rw-r--r--
```

`new File( $path, TRUE )` (or `File::new( $path, TRUE )`) creates an empty file if it does not exist.
`getMimeType()` and `getEncoding()` use the PHP extension `fileinfo` (or the `file` command as fallback).

### Strict mode

Most methods take a last parameter `$strict`. By default, problems throw exceptions:
[`Exception\FileNotExisting`](../api/Exception/FileNotExisting.md) for missing files (or folders given as file),
[`Exception\IO`](../api/Exception/IO.md) for files which are not readable or writable.
Both carry the path name of the file:

```php
use CeusMedia\Common\Exception\FileNotExisting;
use CeusMedia\Common\FS\File;

try{
	$content	= ( new File( 'data/missing.txt' ) )->getContent();
}
catch( FileNotExisting $e ){
	echo $e->getMessage(), ': ', $e->getResource();   // File is not existing: data/missing.txt
}

$content	= ( new File( 'data/missing.txt' ) )->getContent( FALSE );    // NULL, no exception
```

## Readers, writers and editors

For quick one-liners and for code working with file names, use the static methods of
[`FS\File\Reader`](../api/FS/File/Reader.md) and [`FS\File\Writer`](../api/FS/File/Writer.md):

```php
use CeusMedia\Common\FS\File\Reader;
use CeusMedia\Common\FS\File\Writer;

Writer::save( 'data/list.txt', "a\nb\nc" );           // returns number of written bytes
Writer::saveArray( 'data/list.txt', ['a', 'b', 'c'] );   // lines joined by "\n"

$content	= Reader::load( 'data/list.txt' );          // "a\nb\nc"
$lines		= Reader::loadArray( 'data/list.txt' );     // ['a', 'b', 'c']
```

Instances offer more: a reader has the informational methods of `FS\File` (`getSize()`, `getMimeType()`, …),
a writer can append, set permissions and remove the file. [`FS\File\Editor`](../api/FS/File/Editor.md) combines both,
plus `copy()` and `rename()`:

```php
use CeusMedia\Common\FS\File\Editor;
use CeusMedia\Common\FS\File\Writer;

$writer	= new Writer( 'data/import.log' );     // creates the file if not existing
$writer->appendString( "one\n" );
$writer->appendString( "two\n" );
$writer->setPermissions( 0600 );

$editor	= new Editor( 'data/import.log' );
$editor->writeString( strtoupper( $editor->readString() ) );
$editor->rename( 'data/import.old.log' );     // editor continues with the new name
$editor->copy( 'data/import.copy.log' );
$editor->remove();
```

!!! warning "Creation mode is not applied"
    The constructors of `Writer` and `Editor` take a creation mode (default `0640`), owner and group.
    In 1.1.1 the mode is **not** applied – new files get the permissions of your `umask` (eg. `0644`).
    Call `setPermissions()` after creating a file, if its permissions matter.

`readArray()` splits lines at `\n` (and `\r\n`). A trailing line break results in a last empty line.

## Folders

[`FS\Folder`](../api/FS/Folder.md) is the folder counterpart of `FS\File`:

```php
use CeusMedia\Common\FS;
use CeusMedia\Common\FS\Folder;

$folder	= new Folder( 'project/src', TRUE );            // created recursively, if not existing
$folder->createFile( 'index.php', '<?php echo 1;' );    // returns FS\File
$folder->createFolder( 'lib/helpers' );                 // returns FS\Folder

foreach( $folder->index() as $name => $node )           // FS\File and FS\Folder objects, sorted by name
	echo $name, PHP_EOL;

$folder->count();                                       // number of files and folders
$folder->count( FS::TYPE_FILE, TRUE );                  // number of files, recursive
$folder->has( 'index.php', FS::TYPE_FILE );             // TRUE
$folder->getFile( 'index.php' )->getContent();
$folder->rename( 'project/source' );                    // full target path
```

`index()` returns a [`Dictionary`](../api/ADT/Collection/Dictionary.md) of nodes, including hidden files.
Type filters are `FS::TYPE_ALL`, `FS::TYPE_FILE` and `FS::TYPE_FOLDER`.

### Listing and sizes

[`FS\Folder\Reader`](../api/FS/Folder/Reader.md) lists, counts and measures folder contents, optionally filtered by a
regular expression on the entry name. Entries starting with a dot are skipped:

```php
use CeusMedia\Common\FS\Folder\Reader as FolderReader;

$reader	= new FolderReader( 'project/src' );

foreach( $reader->getFileList() as $entry )                    // files in this folder
	echo $entry->getFilename(), PHP_EOL;
foreach( $reader->getNestedFileList( '/\.php$/' ) as $entry )  // PHP files in all sub folders
	echo $entry->getPathname(), PHP_EOL;
foreach( $reader->getFileListByExtensions( ['php', 'txt'] ) as $entry )
	echo $entry->getFilename(), PHP_EOL;

$reader->getFileCount();                                       // also: getFolderCount(), getNestedFileCount(), …
$reader->getSize();                                            // bytes of files in this folder
$reader->getNestedSize();                                      // bytes of all nested files
$reader->getSize( NULL, FolderReader::SIZE_KILOBYTE, 3 );      // eg. 0.018 (float, in KB)
```

The lists are iterators of `SplFileInfo`-like entries (`getFilename()`, `getPathname()`, `getSize()`, …),
in file system order. The lists of the current folder (`getFileList()`, `getFolderList()`, `getList()`) return the
same `DirectoryIterator` object in each loop step – read what you need inside the loop instead of collecting the
entries with `iterator_to_array()`.

[`Folder\Lister`](../api/FS/Folder/Lister.md) and [`Folder\RecursiveLister`](../api/FS/Folder/RecursiveLister.md)
are the configurable variants:

```php
use CeusMedia\Common\FS\Folder\RecursiveLister;

$lister	= new RecursiveLister( 'project/src' );
$lister->setExtensions( ['php'] )->showFolders( FALSE );
foreach( $lister->getList() as $entry )
	echo $entry->getPathname(), PHP_EOL;
```

To find files by their content, [`FS\File\RecursiveRegexFilter`](../api/FS/File/RecursiveRegexFilter.md) takes a
pattern for file names and an optional one for the content:

```php
use CeusMedia\Common\FS\File\RecursiveRegexFilter;

foreach( new RecursiveRegexFilter( 'project/src', '/\.php$/', '/TODO/' ) as $entry )
	echo $entry->getPathname(), PHP_EOL;
```

### Copy, move, remove

[`FS\Folder\Editor`](../api/FS/Folder/Editor.md) works on whole folder trees:

```php
use CeusMedia\Common\FS\Folder\Editor as FolderEditor;

FolderEditor::createFolder( 'var/cache/pages', 0775 );         // recursive, default mode: 0770
$count	= FolderEditor::copyFolder( 'project/src', 'backup/src' );   // number of copied files and folders, dot entries skipped

$editor	= new FolderEditor( 'backup/src', 0775 );
$editor->rename( 'src-old' );                                  // now backup/src-old
$editor->remove( TRUE );                                       // with content; without TRUE only if empty

FolderEditor::moveFolder( 'project/tmp', 'archive' );          // into archive/tmp, archive/ created if missing
FolderEditor::removeFolder( 'var/cache/pages', TRUE );         // returns number of removed files and folders
```

`$editor->move( 'archive' )` moves the folder of the editor into `archive/` as well; afterwards the editor
works on the moved folder `archive/<name>`.

!!! warning "Pass a creation mode to `Folder\Editor`"
    The constructor creates a missing folder with mode `0640` by default. Folders without the `x` flag cannot be
    entered, so new files cannot be created inside. Always pass a mode like `0775` or `0755`.

## INI files

[`FS\File\INI\Reader`](../api/FS/File/INI/Reader.md) reads INI files with or without sections, and keeps comments and
disabled properties (lines starting with `;`):

```ini
; Application settings

[app]
name		= "My App"
debug		= no		; enable only locally
;cache		= yes

[database]
host		= localhost
port		= 3306
```

```php
use CeusMedia\Common\FS\File\INI\Reader as IniReader;

$reader	= new IniReader( 'config.ini', TRUE );            // TRUE: file uses sections
$reader->getSections();                                 // ['app', 'database']
$reader->getProperty( 'host', 'database' );             // "localhost"
$reader->getProperties( TRUE, 'app' );                  // ['name' => 'My App', 'debug' => FALSE]
$reader->getComment( 'debug', 'app' );                  // "enable only locally"
$reader->isActiveProperty( 'cache', 'app' );            // FALSE – disabled by ";"
$reader->toArray();                                     // all active properties, by section

$flat	= IniReader::loadArray( 'shop.ini' );             // file without sections
```

Values are strings. `yes`/`true` and `no`/`false` become booleans, `null` becomes `NULL` (switch off by the third
constructor argument `$reservedWords`). `getProperty()` returns a string, so a boolean comes back as `"1"` or `""` –
use `getProperties()` or `toArray()` to get real booleans.

[`FS\File\INI\Editor`](../api/FS/File/INI/Editor.md) changes INI files in place. Every call writes the file at once;
only the affected lines are rewritten, so comments and formatting of other lines are kept:

```php
use CeusMedia\Common\FS\File\INI\Editor as IniEditor;

$editor	= new IniEditor( 'config.ini', TRUE );
$editor->setProperty( 'host', 'db.example.com', 'database' );
$editor->setProperty( 'user', 'shop', 'database' );     // added, if not existing
$editor->activateProperty( 'cache', 'app' );            // removes leading ";"
$editor->deactivateProperty( 'debug', 'app' );
$editor->addProperty( 'sender', 'shop@example.com', 'default sender', TRUE, 'mail' );   // adds section, too
$editor->renameProperty( 'user', 'username', 'database' );
$editor->removeProperty( 'port', 'database' );
```

??? info "More INI classes"
    | Class | Use |
    |-------|-----|
    | [`INI\Creator`](../api/FS/File/INI/Creator.md) | builds a new INI file from properties, sections and comments |
    | [`INI\SectionReader`](../api/FS/File/INI/SectionReader.md), [`INI\SectionEditor`](../api/FS/File/INI/SectionEditor.md) | sectioned files via `parse_ini_file()`, without comments |
    | [`FS\File\INI`](../api/FS/File/INI.md) | minimal key-value store: `get()`, `has()`, `set()`, `remove()` write the whole file |
    | [`Configuration\Reader`](../api/FS/File/Configuration/Reader.md) | reads INI (and its own JSON, YAML, XML formats) into keys like `db.host`, with optional cache file |

## JSON and YAML

[`FS\File\JSON\Writer`](../api/FS/File/JSON/Writer.md) and [`FS\File\JSON\Reader`](../api/FS/File/JSON/Reader.md):

```php
use CeusMedia\Common\FS\File\JSON\Reader as JsonReader;
use CeusMedia\Common\FS\File\JSON\Writer as JsonWriter;

JsonWriter::save( 'settings.json', ['theme' => 'dark', 'pageSize' => 20], TRUE );   // TRUE: pretty print

$settings	= JsonReader::load( 'settings.json' );          // object: $settings->theme
$settings	= JsonReader::load( 'settings.json', TRUE );    // array
```

The reader strips `/* … */` comments before parsing. Invalid JSON throws a `RuntimeException`
(eg. `Decoding JSON failed (JSON_ERROR_SYNTAX): Syntax error`); afterwards, an instance offers `getError()`,
`getError( TRUE )` (constant name) and `getMessage()`.

[`FS\File\JSON\Config`](../api/FS/File/JSON/Config.md) edits a JSON file as a tree of
[`MagicNode`](../api/ADT/Tree/MagicNode.md)s:

```php
use CeusMedia\Common\FS\File\JSON\Config as JsonConfig;

$config	= new JsonConfig( 'app.json' );     // {"theme": "light", "mail": {"smtp": {"port": 25}}}
echo $config->theme->value();               // light
echo $config->mail->smtp->port->value();    // 25
$config->theme				= 'dark';
$config->mail->smtp->port	= '587';
$config->save();                            // pretty printed by default
```

!!! warning
    Values are set as strings. Setting a key below a node which does not exist yet (eg. `$config->a->b = 'x'` without `a`)
    is silently lost – create the structure in the file first.

YAML is read and written by [`FS\File\YAML\Reader`](../api/FS/File/YAML/Reader.md) and
[`FS\File\YAML\Writer`](../api/FS/File/YAML/Writer.md), based on the bundled Spyc parser:

```php
use CeusMedia\Common\FS\File\YAML\Reader as YamlReader;
use CeusMedia\Common\FS\File\YAML\Writer as YamlWriter;

YamlWriter::save( 'data.yml', ['name' => 'Shop', 'db' => ['host' => 'localhost', 'port' => 3306]] );
$data	= YamlReader::load( 'data.yml' );    // ['name' => 'Shop', 'db' => ['host' => 'localhost', 'port' => 3306]]
```

## CSV files

```php
use CeusMedia\Common\FS\File\CSV\Iterator as CsvIterator;
use CeusMedia\Common\FS\File\CSV\Reader as CsvReader;
use CeusMedia\Common\FS\File\CSV\Writer as CsvWriter;

$writer	= new CsvWriter( 'products.csv', ';' );
$writer->write( [
	['A-100', 'Coffee; dark roast', 12.5],
	['B-200', 'Tea', 4],
], ['sku', 'title', 'price'] );

$reader	= new CsvReader( 'products.csv', TRUE, ';' );     // TRUE: first line holds headers
$reader->getHeaders();                                  // ['sku', 'title', 'price']
count( $reader );                                       // 2
$rows	= $reader->toArray();                           // [['sku' => 'A-100', 'title' => 'Coffee; dark roast', 'price' => '12.5'], …]

foreach( new CsvIterator( 'products.csv', TRUE, ';' ) as $lineNr => $row )   // line by line, for large files
	echo $lineNr, ': ', $row['title'], PHP_EOL;
```

[`CSV\Reader`](../api/FS/File/CSV/Reader.md) and [`CSV\Iterator`](../api/FS/File/CSV/Iterator.md) skip empty lines,
trim values, remove a byte order mark and return all values as strings. Without headers, rows are lists.

!!! warning "Simple CSV only"
    Files are read line by line (up to `CsvReader::$maxRowSize` = 4096 bytes), so values must not contain line breaks.
    [`CSV\Writer`](../api/FS/File/CSV/Writer.md) only encloses values containing the separator and escapes quotes in
    them with a backslash, which the reader keeps (`Say \"hi\"`). For full RFC 4180 data, use PHP's `fputcsv()`.

## Log files

| Class | Format |
|-------|--------|
| [`Log\Writer`](../api/FS/File/Log/Writer.md), [`Log\Reader`](../api/FS/File/Log/Reader.md) | text lines: timestamp, human-readable date, message |
| [`Log\JSON\Writer`](../api/FS/File/Log/JSON/Writer.md), [`Log\JSON\Reader`](../api/FS/File/Log/JSON/Reader.md) | one JSON object per line, with a `timestamp` key |
| [`Log\ShortWriter`](../api/FS/File/Log/ShortWriter.md), [`Log\ShortReader`](../api/FS/File/Log/ShortReader.md) | `|` separated request data (time, IP, URI, referer, user agent) |

```php
use CeusMedia\Common\FS\File\Log\JSON\Reader as JsonLogReader;
use CeusMedia\Common\FS\File\Log\JSON\Writer as JsonLogWriter;
use CeusMedia\Common\FS\File\Log\Reader as LogReader;
use CeusMedia\Common\FS\File\Log\Writer as LogWriter;

$log	= new LogWriter( 'logs/app.log' );     // folder must exist
$log->note( 'User logged in' );         // 1794493909 [12.11.2026 - 14:31:49] User logged in
$lines	= LogReader::load( 'logs/app.log', -10 );     // last lines (the last one is empty)

JsonLogWriter::noteData( 'logs/events.log', ['event' => 'login', 'user' => 12] );   // creates folder, if missing
$latest	= JsonLogReader::read( 'logs/events.log', TRUE, 5 );   // newest 5 entries as arrays
```

## Archives

=== "ZIP"

    [`Arc\Zip`](../api/FS/File/Arc/Zip.md) creates or opens an archive (needs the extension `zip`):

    ```php
    use CeusMedia\Common\FS\File\Arc\Zip;

    $zip	= new Zip( 'docs.zip' );                  // opened or created
    $zip->addFile( 'docs/a.txt' );
    $zip->addFile( 'docs/b.txt', 'renamed.txt' );   // name inside the archive
    $zip->save();                                   // archive stays usable
    $zip->save( 'copy.zip' );                       // save as, original unchanged

    foreach( $zip->index() as $entry )              // stat arrays of ZipArchive
    	echo $entry['name'], ' ', $entry['size'], PHP_EOL;
    ```

    There is no extract method – use `ZipArchive` for this.

=== "TAR"

    [`Arc\Tar`](../api/FS/File/Arc/Tar.md) is implemented in PHP and holds the whole archive in memory:

    ```php
    use CeusMedia\Common\FS\File\Arc\Tar;

    $tar	= new Tar();
    $tar->addFolder( 'docs' );
    $tar->addFile( 'docs/a.txt' );      // path relative to working directory
    $tar->save( 'docs.tar' );

    $tar	= new Tar( 'docs.tar' );
    $tar->getFileList();                // ['docs/a.txt' => 6]
    $tar->extract( 'out' );             // number of extracted files
    ```

=== "Single files"

    [`Arc\Gzip`](../api/FS/File/Arc/Gzip.md) and [`Arc\Bzip`](../api/FS/File/Arc/Bzip.md) are file editors which
    compress on write and decompress on read:

    ```php
    use CeusMedia\Common\FS\File\Arc\Bzip;

    $file	= new Bzip( 'notes.bz2' );
    $file->writeString( str_repeat( 'Lorem ipsum ', 100 ) );
    $text	= $file->readString();
    ```

`Arc\Gzip` and therefore [`Arc\TarGzip`](../api/FS/File/Arc/TarGzip.md) write the gzip format, readable by
`gunzip` and `tar -xz`, and read files of these tools. Files in zlib format, written by versions before 1.1.1,
are read as well. `Arc\Bzip` and [`Arc\TarBzip`](../api/FS/File/Arc/TarBzip.md) use the bzip2 format.

## Backups and locks

[`FS\File\Backup`](../api/FS/File/Backup.md) keeps numbered copies next to a file, named like Emacs backups:
`config.php~` (version 0), `config.php.~1~`, `config.php.~2~`, …

```php
use CeusMedia\Common\FS\File\Backup;

$backup	= new Backup( 'config.php' );     // file must exist
$backup->store();                       // version 0
// … change config.php …
$backup->store();                       // version 1
$backup->getVersion();                  // 1 – latest, NULL if no backup exists
$backup->getVersions();                 // [0 => mtime, 1 => mtime]
$backup->getContent( 0 );
$backup->restore();                     // latest version back into config.php
$backup->restore( 0, TRUE );            // version 0, then remove it (later versions move down)
```

Timestamps are preserved by default. `new Backup( $path, TRUE, TRUE )` keeps only one backup (version 0).
`restore()` without any backup throws a `RuntimeException`.
[`BackupCleaner`](../api/FS/File/BackupCleaner.md) thins out dated backup files (eg. keep the last of each month).

[`FS\File\Lock`](../api/FS/File/Lock.md) uses a lock file to keep jobs from running twice:

```php
use CeusMedia\Common\FS\File\Lock;

$lock	= new Lock( 'var/import.lock', 3600, 5 );   // expires after 1 hour, wait max. 5 seconds
if( !$lock->lock( FALSE ) )                       // FALSE: return FALSE instead of throwing on timeout
	exit( 'Import is already running' );
try{
	// … import …
}
finally{
	$lock->unlock();
}
```

An expiration of `0` never expires, a timeout of `0` waits forever. Checking and creating the lock file are two
steps, so the lock is not atomic – fine for cron jobs, not for heavy concurrency.

## Other file types and tools

| Area | Classes |
|------|---------|
| CSS | [`CSS\Reader`](../api/FS/File/CSS/Reader.md), [`CSS\Writer`](../api/FS/File/CSS/Writer.md), [`CSS\Editor`](../api/FS/File/CSS/Editor.md) (as [`ADT\CSS\Sheet`](../api/ADT/CSS/Sheet.md)), [`CSS\Compressor`](../api/FS/File/CSS/Compressor.md), [`CSS\Combiner`](../api/FS/File/CSS/Combiner.md) (resolves `@import`), [`CSS\Relocator`](../api/FS/File/CSS/Relocator.md), [`CSS\Converter`](../api/FS/File/CSS/Converter.md), [`CSS\Parser`](../api/FS/File/CSS/Parser.md) |
| vCard | [`VCard\Reader`](../api/FS/File/VCard/Reader.md), [`VCard\Writer`](../api/FS/File/VCard/Writer.md), [`VCard\Parser`](../api/FS/File/VCard/Parser.md), [`VCard\Builder`](../api/FS/File/VCard/Builder.md) – data object [`ADT\VCard`](../api/ADT/VCard.md) |
| iCalendar | [`ICal\Parser`](../api/FS/File/ICal/Parser.md), [`ICal\Builder`](../api/FS/File/ICal/Builder.md) – between iCal strings and XML node trees |
| Text blocks | [`Block\Reader`](../api/FS/File/Block/Reader.md), [`Block\Writer`](../api/FS/File/Block/Writer.md) – text blocks under `[name]` headers |
| Lists | [`Collection\Reader`](../api/FS/File/Collection/Reader.md), [`Collection\Writer`](../api/FS/File/Collection/Writer.md), [`Collection\Editor`](../api/FS/File/Collection/Editor.md), [`Collection\SectionReader`](../api/FS/File/Collection/SectionReader.md), [`Collection\SectionWriter`](../api/FS/File/Collection/SectionWriter.md) – one item per line |
| Configuration | [`Configuration\Reader`](../api/FS/File/Configuration/Reader.md), [`Configuration\Converter`](../api/FS/File/Configuration/Converter.md) |
| Permissions | [`Permissions`](../api/FS/File/Permissions.md) – read and set as octal (`0644`) or string (`rw-r--r--`) |
| Encoding | [`Unicoder`](../api/FS/File/Unicoder.md) – converts a file to UTF-8 |
| Search | [`Iterator`](../api/FS/File/Iterator.md), [`RecursiveIterator`](../api/FS/File/RecursiveIterator.md), [`NameFilter`](../api/FS/File/NameFilter.md), [`RecursiveNameFilter`](../api/FS/File/RecursiveNameFilter.md), [`RegexFilter`](../api/FS/File/RegexFilter.md), [`Folder\RegexFilter`](../api/FS/Folder/RegexFilter.md), [`Folder\RecursiveRegexFilter`](../api/FS/Folder/RecursiveRegexFilter.md), [`TodoLister`](../api/FS/File/TodoLister.md), [`RecursiveTodoLister`](../api/FS/File/RecursiveTodoLister.md) |
| PHP code | [`SyntaxChecker`](../api/FS/File/SyntaxChecker.md), [`Folder\SyntaxChecker`](../api/FS/Folder/SyntaxChecker.md), [`CodeLineCounter`](../api/FS/File/CodeLineCounter.md), [`Folder\CodeLineCounter`](../api/FS/Folder/CodeLineCounter.md), [`PHP\Lister`](../api/FS/File/PHP/Lister.md), [`PHP\Encoder`](../api/FS/File/PHP/Encoder.md), [`PHP\Check\MethodOrder`](../api/FS/File/PHP/Check/MethodOrder.md), [`PHP\Check\MethodVisibility`](../api/FS/File/PHP/Check/MethodVisibility.md), [`Folder\MethodSortCheck`](../api/FS/Folder/MethodSortCheck.md), [`Folder\MethodVisibilityCheck`](../api/FS/Folder/MethodVisibilityCheck.md), [`PHP\Test\Creator`](../api/FS/File/PHP/Test/Creator.md) |
| Autoloading | [`Autoloader\Psr0`](../api/FS/Autoloader/Psr0.md), [`Autoloader\Psr4`](../api/FS/Autoloader/Psr4.md) |
| Misc | [`PdfToImage`](../api/FS/File/PdfToImage.md), [`Folder\Treeview\Json`](../api/FS/Folder/Treeview/Json.md), [`Gantt\MeetingReader`](../api/FS/File/Gantt/MeetingReader.md) and other Gantt classes, [`FS\Link`](../api/FS/Link.md) |

A vCard round trip, as an example:

```php
use CeusMedia\Common\ADT\VCard;
use CeusMedia\Common\FS\File\VCard\Reader as VCardReader;
use CeusMedia\Common\FS\File\VCard\Writer as VCardWriter;

$card	= new VCard();
$card->setName( 'Doe', 'Jane' )
	->setFormattedName( 'Jane Doe' )
	->addEmail( 'jane@example.com', ['work'] )
	->addPhone( '+49 30 1234567', ['cell'] );
VCardWriter::save( 'jane.vcf', $card );

$card	= ( new VCardReader() )->readFile( 'jane.vcf' );
echo $card->getFormattedName();       // Jane Doe
```
