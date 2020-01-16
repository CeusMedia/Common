<?php
require_once __DIR__.'/../../vendor/autoload.php';
new UI_DevOutput;

$text	= ' This is a test. ';
$color	= new CLI_Color();

for( $i=0; $i<10; $i++){
	$fg = rand(16, 232);
	$bg = rand(16, 232);

	print( $color->colorize256( $text, $fg, $bg ).PHP_EOL );
}
print( PHP_EOL );

