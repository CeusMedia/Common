<?php
/**
 *	Alexa Rank Request.
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
 *	@category		cmClasses
 *	@package		net.http
 *	@uses			Net_cURL
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			22.01.2007
 *	@version		0.5
 */
import( 'de.ceus-media.net.cURL' );
/**
 *	Alexa Rank Request.
 *	@category		cmClasses
 *	@package		net.http
 *	@uses			Net_cURL
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			22.01.2007
 *	@version		0.5
 *	@todo			broken due to Changes on Alexa.com
 *	@deprecated		not working anymore
 */
class Net_HTTP_AlexaRank
{

	/**
	 *	Returns Alexa Rank of Host.
	 *	@access		public
	 *	@param		string		$host			URI of Host (e.g. google.com)
	 *	@param		int			$cache_time		Duration of Cache File in seconds (0 - Cache disabled)
	 *	@return		string
	 */
	public function getRank( $host, $cacheTime = 86400 )
	{
		$cacheFile = "cache_".$host.".html";
		if( $cacheTime && file_exists( $cacheFile ) && filemtime( $cacheFile ) >= time() - $cacheTime )
		{
			$result = file_get_contents( $cacheFile );
		}
		else
		{
			$curl	= new Net_cURL( "http://alexa.com/search?q=".$host );
			$result	= $curl->exec();
			$status	= $curl->getStatus();
			if( $status['http_code'] == 200 )
			{
				if( $cacheTime )
				{
					$cache	= fopen( $cacheFile, "w" );
					fputs( $cache, $result );
					fclose( $cache );
				}
			}
			else
				return -1;
		}
		return $this->decodeRank( $result );
	}
	
	/**
	 *	Return decodes Alexa Rank from HTML of Alexa Site.
	 *	@access		protected
	 *	@param		string		$html			HTML of Alexa Site
	 *	@return		string
	 */
	protected function decodeRank( $html )
	{
		$html	= substr( $html, strpos( $html, 'Rank:' )+6 );
		$html	= substr( $html, 0, strpos( $html, "</div>" ) );
		$html	= substr( $html, strpos( $html, ">" )+1 );
		$html	= substr( $html, 0, strpos( $html, "</a>" ) );
		$html	= preg_replace( "@(<!--.*-->)@u", "", $html );
		$html	= trim( $html );
		xmp( $html );
		$rank	= trim( preg_replace( "@<[^>]+>@", "", $html ) );
		remark( $rank );
		die;
		return $rank;
	}
}
?>