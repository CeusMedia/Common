<?php
import( 'de.ceus-media.service.Point' );
import( 'de.ceus-media.file.yaml.Reader' );
/**
 *	Service Point with YAML Definition File.
 *	@package		service
 *	@implements		Service_Interface_Point
 *	@uses			File_YAML_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 */
/**
 *	Service Point with YAML Definition File.
 *	@package		service
 *	@implements		Service_Interface_Point
 *	@uses			File_YAML_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 */
class Service_YamlPoint extends Service_Point implements Service_Interface_Point
{
	/**
	 *	Loads Service Definition.
	 *	@access		protected
	 *	@param		string		$fileName		Service Definition File Name
	 *	@param		string		$cacheFile		Service Definition Cache File Name
	 *	@return		void
	 */
	protected function loadServices( $fileName, $cacheFile = false )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( "Definition File '".$fileName."' is not existing." );
		if( $cacheFile && file_exists( $cacheFile ) && filemtime( $fileName ) <= filemtime( $cacheFile ) )
			return $this->services	= unserialize( file_get_contents( $cacheFile ) );
		$this->services	= File_YAML_Reader::load( $fileName );
		if( $cacheFile )
			file_put_contents( $cacheFile, serialize( $this->services ) );
	}
}
?>