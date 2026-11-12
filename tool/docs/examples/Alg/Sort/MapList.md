```php
use CeusMedia\Common\Alg\Sort\MapList;

$products	= [
	['name' => 'Pen',		'price' => 1.5,	'stock' => 10],
	['name' => 'Paper',		'price' => 1.2,	'stock' => 0],
	['name' => 'Folder',	'price' => 2.0,	'stock' => 10],
];

$list	= MapList::sort( $products, 'price' );                            // Paper, Pen, Folder
$list	= MapList::sort( $products, 'price', MapList::DIRECTION_DESC );   // Folder, Pen, Paper
$list	= MapList::sortByMultipleColumns( $products, [
	'stock'	=> MapList::DIRECTION_DESC,
	'name'	=> MapList::DIRECTION_ASC,
] );                                                                       // Folder, Pen, Paper
```

See guide: [Algorithms](../../../guide/algorithms.md)
