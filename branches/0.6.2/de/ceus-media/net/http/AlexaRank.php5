<?php
import( 'de.ceus-media.net.cURL' );
/**
 *	Alexa Rank Request.
 *	@package		net.http
 *	@uses			Net_cURL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.01.2007
 *	@version		0.5
 */
/**
 *	Alexa Rank Request.
 *	@package		net.http
 *	@uses			Net_cURL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
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