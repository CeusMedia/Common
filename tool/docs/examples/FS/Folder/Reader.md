```php
use CeusMedia\Common\FS\Folder\Reader as FolderReader;

$reader	= new FolderReader( 'project/src' );
foreach( $reader->getNestedFileList( '/\.php$/' ) as $entry )
	echo $entry->getPathname(), PHP_EOL;

$reader->getFileCount();                                       // files in this folder, without dot files
$reader->getNestedSize();                                      // bytes of all nested files
$reader->getSize( NULL, FolderReader::SIZE_KILOBYTE, 3 );      // eg. 0.018
```

See guide: [File system](../../../guide/filesystem.md)
