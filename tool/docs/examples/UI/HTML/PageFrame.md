```php
use CeusMedia\Common\UI\HTML\PageFrame;
use CeusMedia\Common\UI\HTML\Tag;

$page	= new PageFrame( 'HTML_5', 'en' );
$page->setTitle( 'Products' );
$page->addMetaTag( 'name', 'viewport', 'width=device-width, initial-scale=1' );
$page->addStylesheet( 'css/style.css' );
$page->addJavaScript( 'js/app.js' );
$page->addBody( Tag::create( 'h1', 'Products' ) );
echo $page->build( ['class' => 'page-products'] );
```

See guide: [User interface](../../../guide/ui.md)
