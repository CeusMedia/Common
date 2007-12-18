<?php
import( 'de.ceus-media.service.ServicePoint' );
/**
 *	Service Point with YAML Definition File.
 *	@package		service
 *	@implements		Service_Interface_Point
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 */
/**
 *	Service Point with YAML Definition File.
 *	@package		service
 *	@implements		Service_Interface_Point
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 *	@todo			Finish Implementation
 */
class Service_XmlPoint implements Service_Interface_Point
{
	/**
	 *	Constructor Method.
	 *	@access		public
	 *	@param		string			Service Definition File Name
	 *	@param		string			Service Definition Cache File Name
	 *	@return		void
	 */
	public function loadServices( $fileName, $cacheFile = false )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( "Definition File '".$fileName."' is not existing." );
		if( $cacheFile && file_exists( $cacheFile ) && filemtime( $fileName ) <= filemtime( $cacheFile ) )
			return $this->services	= unserialize( file_get_contents( $cacheFile ) );

		$xml	= file_get_contents( $fileName );
		$doc	= DOMDocument::loadXML( $xml );
		print_m( $doc );
		die;

		$this->services	= self::loadXml( $fileName );
		if( $cacheFile )
			file_put_contents( $cacheFile, serialize( $this->services ) );
	}
}
?>