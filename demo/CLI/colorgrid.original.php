<?php
/**
 *	by plasmarob (https://unix.stackexchange.com/users/172429/plasmarob)
 *	@see	https://unix.stackexchange.com/questions/124407/what-color-codes-can-i-use-in-my-ps1-prompt/285956#285956
 */
function colorgrid()
{
    $iter = 16;
    while( $iter < 52 ){
        $second	= $iter+36;
        $third	= $second+36;
        $four	= $third+36;
        $five	= $four+36;
        $six	= $five+36;
        $seven	= $six+36;
        if( $seven > 250 )
			$seven	= $seven-251;

        echo "\033[38;5;".$iter."m█ ";
        printf( "%03d", $iter );
        echo "   \033[38;5;".$second."m█ ";
        printf( "%03d", $second );
        echo "   \033[38;5;".$third."m█ ";
        printf( "%03d", $third );
        echo "   \033[38;5;".$four."m█ ";
        printf( "%03d", $four );
        echo "   \033[38;5;".$five."m█ ";
        printf( "%03d", $five );
        echo "   \033[38;5;".$six."m█ ";
        printf( "%03d", $six );
        echo "   \033[38;5;".$seven."m█ ";
        printf( "%03d", $seven );

        $iter ++;
        print( "\033[0m\r\n" );
    }
}

colorgrid();
