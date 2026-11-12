```php
use CeusMedia\Common\CLI\Output\Progress;

$progress	= new Progress();
$progress->setTotal( count( $items ) );
$progress->start();
foreach( $items as $nr => $item ){
	//  ... do the work
	$progress->update( $nr + 1 );
}
$progress->finish();
```

See guide: [CLI](../../../guide/cli.md)
