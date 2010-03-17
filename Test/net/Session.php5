<?php
require_once( "../../../useClasses.php5" );
import( 'de.ceus-media.net.http.Session' );
import( 'de.ceus-media.net.http.PartitionSession' );
import( 'de.ceus-media.ui.DevOutput' );



if( 0 )
{
	$session	= new Net_HTTP_Session();
	$session->set( 'key1', "value1" );

	remark( "key1: ".$session['key1'] );

	remark( "cookie: " );
	print_m( $_COOKIE );

	remark( "session: " );
	print_m( $session->getAll() );

	$session->clear();
}
else
{

	$session	= new Net_HTTP_PartitionSession( "test" );
	$session->set( 'key1', "value1" );

	remark( "session: " );
	print_m( $session->getAll() );
	remark( "key1: ".$session['key1'] );

	remark( "session: " );
	print_m( $_SESSION );
}
?>