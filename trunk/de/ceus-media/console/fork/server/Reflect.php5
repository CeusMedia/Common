<?php
require_once( "Abstract.php5" );
class Fork_Server_Reflect extends Server_Fork_Abstract
{
	protected function handleRequest( $request )
	{
		$buffer		= array( "\n" );
		$buffer[]	= "Total requests: ".$this->statSeenTotal;
		$buffer[]	= "Maximum simultaneous: ".$this->statSeenMax;
		$buffer[]	= "Currently active: " . ( count( $this->childrenMap ) + 1 );
		$buffer[]	= "Running since: " . date( "D, d M Y H:i:s T", $this->timeStarted );
		$buffer[]	= "Server time: " . date( "D, d M Y H:i:s T", time() );
		$buffer[]	= "";
		$buffer[]	= "Your request: ".$request;
		$buffer		= implode( "\n", $buffer );
		return $buffer;
	}
}
?>