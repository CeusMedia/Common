<?php
require_once __DIR__.'/../../vendor/autoload.php';
new UI_DevOutput;

$text	= 'This is a test';
$color	= new CLI_Color();


print( PHP_EOL );
print( "Normal text".PHP_EOL );
print( $color->bold( "Bold text" ).PHP_EOL );
print( $color->light( "Light text" ).PHP_EOL );
print( $color->italic( "Italic text" ).PHP_EOL );
print( $color->underscore( "Underscored text" ).PHP_EOL );
print( $color->underscore( $color->bold( $color->italic( "Underscored bold italic text" ) ) ).PHP_EOL );
print( PHP_EOL );

foreach( $color->getBackgroundColors() as $bgColor ){
	foreach( $color->getForegroundColors() as $fgColor ){
		print( $color->colorize( ' # ', $fgColor, $bgColor ) );
	}
	print( PHP_EOL );
}
print( PHP_EOL );

print( $color->asError( ' This is an error message. ' ).PHP_EOL );
print( $color->asWarning( ' This is a warning. ' ).PHP_EOL );
print( $color->asInfo( ' This is an info message. ' ).PHP_EOL );
print( $color->asSuccess( ' This is a success message. ' ).PHP_EOL );
print( PHP_EOL );

$text	= vsprintf( 'Text can be %s, %s, %s or %s.', array(
	$color->bold( 'bold' ),
	$color->italic( 'italic' ),
	$color->light( 'light' ),
	$color->underscore( 'underscored' ),
) );
print( $text.PHP_EOL );
print( $color->colorize( $text, NULL, 'blue' ).PHP_EOL );
print( PHP_EOL );



