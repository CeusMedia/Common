```php
use CeusMedia\Common\UI\Image;
use CeusMedia\Common\UI\Image\Processing;

$image		= new Image( 'photo.jpg' );
$processor	= new Processing( $image );
$processor->scaleDownToLimit( 800, 600 );     // keeps ratio, FALSE if already smaller
$processor->crop( 0, 0, 400, 300 );
$processor->rotate( 90 );                     // clockwise
$image->setQuality( 85 );
$image->save( 'photo-small.jpg' );
```

See guide: [User interface](../../../guide/ui.md)
