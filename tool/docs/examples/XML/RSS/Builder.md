```php
use CeusMedia\Common\XML\RSS\Builder;

$builder	= new Builder();
$builder->setChannelData( [
	'title'			=> 'Example News',
	'link'			=> 'https://example.com/',
	'description'	=> 'Latest news',
] );
$builder->addItem( [
	'title'		=> 'Release 1.1.1',
	'link'		=> 'https://example.com/news/111',
	'pubDate'	=> time(),
] );
$xml	= $builder->build();
```

See guide: [XML](../../../guide/xml.md)
