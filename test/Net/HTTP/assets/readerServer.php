<?php
//  Router script for PHP's built-in web server, used by ReaderTest.
header( 'Content-Type: text/plain; charset=utf-8' );
header( 'X-Test: '.( $_SERVER['HTTP_X_REQUEST'] ?? 'none' ) );
if( 'POST' === $_SERVER['REQUEST_METHOD'] )
	echo 'posted:'.( $_POST['key'] ?? '' );
else
	echo 'hello';
