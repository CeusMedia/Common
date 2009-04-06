<?php
$parameters	= "";
if( isset( $_SERVER['QUERY_STRING'] ) )
	if( $_SERVER['QUERY_STRING'] )
		$parameters	= "?".$_SERVER['QUERY_STRING'];
header( "Location: public/".$parameters );
?>