<?php
import( 'net.sourceforge.snoopy.Snoopy' );
/**
 *	Alexa Rank Request.
 *	@package		protocol
 *	@subpackage		http
 *	@uses			Snoopy
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.01.2007
 *	@version		0.1
 */
/**
 *	Alexa Rank Request.
 *	@package		protocol
 *	@subpackage		http
 *	@uses			Snoopy
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.01.2007
 *	@version		0.1
 */
class AlexaRank
{

	/**
	 *	Returns Alexa Rank of Host.
	 *	@access		public
	 *	@param		string		$host			URI of Host (e.g. google.com)
	 *	@param		int			$cache_time		Duration of Cache File in seconds (0 - Cache disabled)
	 *	@return		string
	 */
	function getRank( $host, $cache_time = 86400 )
	{
		$cachefile = "cache_".$host.".html";
		if( $cache_time && file_exists( $cachefile ) && filemtime( $cachefile ) >= time() - $cache_time )
		{
			$content = file_get_contents( $cachefile );
		}
		else
		{
			$s	= new Snoopy;
			$s->fetch( "http://alexa.com/search?q=".$host );
			if( $s->status == 200 )
			{
				if( $cache_time )
				{
					$cache	= fopen( $cachefile, "w" );
					fputs( $cache, $s->results );
					fclose( $cache );
				}
				$content	= $s->results;
			}
			else
				return -1;
		}
		return $this->_decodeRank( $content );
	}
	
	/**
	 *	Return decodes Alexa Rank from HTML of Alexa Site.
	 *	@access		public
	 *	@param		string		$html			HTML of Alexa Site
	 *	@return		string
	 */
	function _decodeRank( $html )
	{
		$html	= substr( $html, strpos( $html, "<div class=\"site_stats\">" )+24 );
		$html	= substr( $html, 0, strpos( $html, "</div>" ) );
		$html	= substr( $html, strpos( $html, ">" )+1 );
		$html	= substr( $html, 0, strpos( $html, "</a>" ) );
		$html	= preg_replace( "@(<!--.*-->)@u", "", $html );
		$html	= preg_replace( "@Rank:@", "", $html );
		$html	= trim( $html );
		$rank	= trim( preg_replace( "@<[^>]+>@", "", $html ) );
		return $rank;
	}
}
?>