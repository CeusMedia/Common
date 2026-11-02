<?php
//  Router script for PHP's built-in web server, used by CrawlerTest: a small web site.
$pages	= [
	'/'				=> '<a href="a.html">A</a> <a href="/sub/b.html#top">B</a> <a href="http://external.invalid/">X</a> <a href="#">Self</a> <a href="mailto:a@b.c">M</a> <a href="doc.pdf">PDF</a>',
	'/a.html'		=> '<a href="./">Home</a> <a href="text.txt">Text</a> <a href="a.html?x=1">Query</a>',
	'/sub/b.html'	=> '<a href="c.html">C</a> <a href="../a.html">A</a>',
	'/sub/c.html'	=> '<a href="deep/d.html">D</a>',
	'/sub/deep/d.html'	=> '<p>deepest</p>',
];
$path	= (string) parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
if( '/text.txt' === $path ){
	header( 'Content-Type: text/plain' );
	echo 'plain';
	return;
}
if( !isset( $pages[$path] ) ){
	http_response_code( 404 );
	header( 'Content-Type: text/html; charset=UTF-8' );
	echo 'not found';
	return;
}
header( 'Content-Type: text/html; charset=UTF-8' );
echo '<html><body>'.$pages[$path].'</body></html>';
