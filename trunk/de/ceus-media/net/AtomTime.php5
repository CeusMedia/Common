<?php
/**
 *	Connects Server to request Atom Time.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@package		net
 *	@uses			Net_cURL
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.07.2005
 *	@version		0.6
 */
import ("de.ceus-media.net.cURL");
/**
 *	Connects Server to request Atom Time.
 *	@package		net
 *	@uses			Net_cURL
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
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