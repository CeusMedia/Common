<?php
require_once __DIR__.'/../../vendor/autoload.php';

/**
 *	by plasmarob (https://unix.stackexchange.com/users/172429/plasmarob)
 *	@see	https://unix.stackexchange.com/questions/124407/what-color-codes-can-i-use-in-my-ps1-prompt/285956#285956
 */
function colorgrid()
{
	$color		= new CLI_Color();
    $iteration	= 16;
    while( $iteration < 52 ){
        $second	= $iteration+36;
        $third	= $second+36;
        $four	= $third+36;
        $five	= $four+36;
        $six	= $five+36;
        $seven	= $six+36;
        if( $seven > 250 )
			$seven	= $seven-251;

        printf( $color->colorize256( "███", $iteration )." %03d   ", $iteration );
        printf( $color->colorize256( "███", $second )." %03d   ", $second );
        printf( $color->colorize256( "███", $third )." %03d   ", $third );
        printf( $color->colorize256( "███", $four )." %03d   ", $four );
        printf( $color->colorize256( "███", $five )." %03d   ", $five );
        printf( $color->colorize256( "███", $six )." %03d   ", $six );
        printf( $color->colorize256( "███", $seven )." %03d   ", $seven );

        $iteration ++;
        print( "\033[0m\r\n" );
    }
}

colorgrid();
