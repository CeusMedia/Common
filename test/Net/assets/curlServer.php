<?php
//  Router script for PHP's built-in web server, used by CURLTest.
if( '/redirect-file' === parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) ){
	header( 'Location: file://localhost'.__FILE__, TRUE, 302 );
	exit;
}
header( 'Content-Type: text/plain; charset=utf-8' );
echo 'hello';
