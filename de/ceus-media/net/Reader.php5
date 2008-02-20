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
	
	public function read()
	{
		return $self->readUrl( $this->url );
	}

	public function setUrl( $url )
	{
		$this->url	= $url;		
	}

	public static function readUrl( $url )
	{
		$curl		= new Net_cURL($url );
		$response	= $curl->exec();
		$code		= $curl->getStatus( 'http_code' );
	
		if( !in_array( $code, array( '200', '304' ) ) )
			throw new Exception( 'URL "'.$url.'" can not be accessed ('.$code.').' );

		return $response;
	}
}
?>