```php
use CeusMedia\Common\FS\File\INI\Editor as IniEditor;

$editor	= new IniEditor( 'config.ini', TRUE );                  // TRUE: file uses sections
$editor->setProperty( 'host', 'db.example.com', 'database' );
$editor->setProperty( 'user', 'shop', 'database' );           // added, if not existing
$editor->activateProperty( 'cache', 'app' );                  // removes leading ";"
$editor->deactivateProperty( 'debug', 'app' );
$editor->addProperty( 'sender', 'shop@example.com', 'default sender', TRUE, 'mail' );
$editor->removeProperty( 'port', 'database' );
```

Each call writes the file at once; only changed lines are rewritten, comments stay untouched.

See guide: [File system](../../../../guide/filesystem.md)
