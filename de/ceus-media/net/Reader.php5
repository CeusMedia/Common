<?php
import( 'de.ceus-media.net.cURL' );
/**
 *	Reader for Contents from the Net.
 *	@package		net
 *	@uses			Net_cURL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.6
 */
/**
 *	Reader for Contents from the Net.
 *	@package		net
 *	@uses			Net_cURL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.6
 */
class Net_Reader
{
	/**	@var		string		$url		URL to read */
	protected $url;
	/**	@var		string		$agent		User Agent */
	protected static $userAgent	= "cmClasses:Net_Reader/0.6";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url		URL to read
	 *	@return		void
	 */
	public function __construct( $url )
	{
		$this->setUrl( $url );
	}
	
	public function getUrl()
	{
		return $this->url;
	}

	public function getUserAgent()
	{
		return self::$userAgent;
	}

	/**
	 *	@todo		Auth
	 */
	public function read()
	{
		return $this->readUrl( $this->url );
	}

	public function setUserAgent( $title )
	{
		self::$userAgent	= $title;
	}

	public function setUrl( $url )
	{
		$this->url	= $url;		
	}

	/**
	 *	@todo		Auth
	 */
	public static function readUrl( $url )
	{
		$curl		= new Net_cURL($url );
		if( self::$userAgent )
			$curl->setOption( CURLOPT_USERAGENT, self::$userAgent );
		$response	= $curl->exec();
		$code		= $curl->getStatus( 'http_code' );
	
		if( !in_array( $code, array( '200', '304' ) ) )
			throw new Exception( 'URL "'.$url.'" can not be accessed ('.$code.').' );

		return $response;
	}
}
?>