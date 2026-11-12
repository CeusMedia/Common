```php
use CeusMedia\Common\FS\File;

$file	= new File( 'data/notes.txt' );
if( !$file->exists() )
	$file->setContent( "first line\nsecond line\n" );

echo $file->getContent();
echo $file->getSize();              // 23
echo $file->getSize( 2 );           // "23 B"
echo $file->getExtension();         // txt
echo $file->getMimeType();          // text/plain

$missing	= ( new File( 'data/missing.txt' ) )->getContent( FALSE );   // NULL instead of an exception
```

See guide: [File system](../../guide/filesystem.md)
