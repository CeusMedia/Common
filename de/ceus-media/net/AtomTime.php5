<?php
import ("de.ceus-media.net.cURL");
/**
 *	Connects Server to request Atom Time.
 *	@package		net
 *	@uses			Net_cURL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.07.2005
 *	@version		0.6
 */
/**
 *	Connects Server to request Atom Time.
 *	@package		net
 *	@uses			Net_cURL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.07.2005
 *	@version		0.6
 */
class Net_AtomTime
{
	/**	@var		string		$url			URL for Server Request */
	protected static $url		= "http://www.uni-leipzig.de/cgi-bin/date/index.htm";

	/**
	 *	Returns timestamp.
	 *	@access		public
	 *	@return		int
	 *	@link		http://www.php.net/time
	 */
	public static function getTimestamp()
	{
		$curl	= new Net_cURL( self::$url );
		$result	= $curl->exec();
		$status	= $curl->getStatus();
		if( $status['http_code'] != 200 )
			throw new Exception( "Service URL is not reachable." );
		$parts	= explode( "\n", $result );
		$date	= trim( $parts[2] );
		$time	= strtotime( $date );
		return $time;
	}
	
	/**
	 *	Returns date as formatted string.
	 *	@access		public
	 *	@param		string		$format			Date format
	 *	@return		string
	 *	@link		http://www.php.net/date
	 */
	public static function getDate( $format = "d.m.Y - H:i:s" )
	{
		return date( $format, self::getTimestamp() );
	}
}
?>