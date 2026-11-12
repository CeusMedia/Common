```php
use CeusMedia\Common\CLI\Command\ArgumentParser;

$parser	= new ArgumentParser();
$parser->setNumberOfMandatoryArguments( 1 );
$parser->setPossibleOptions( [
	'verbose'	=> FALSE,			//  flag without value
	'limit'		=> '/^\d+$/',		//  value must match pattern
	'format'	=> TRUE,			//  any value
] );
$parser->setShortcuts( ['v' => 'verbose', 'l' => 'limit'] );

$parser->parse( 'import users.csv -v --limit=10 --format csv' );
$parser->getArguments();    // ['import', 'users.csv']
$parser->getOptions();      // ['limit' => '10', 'format' => 'csv', 'verbose' => TRUE]
```

See guide: [CLI](../../../guide/cli.md)
