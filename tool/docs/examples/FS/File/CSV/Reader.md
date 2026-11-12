```php
use CeusMedia\Common\FS\File\CSV\Reader as CsvReader;

$reader	= new CsvReader( 'products.csv', TRUE, ';' );     // TRUE: first line holds headers
$reader->getHeaders();                                  // ['sku', 'title', 'price']
count( $reader );                                       // number of data rows
foreach( $reader->toArray() as $row )
	echo $row['sku'], ': ', $row['title'], PHP_EOL;
```

See guide: [File system](../../../../guide/filesystem.md)
