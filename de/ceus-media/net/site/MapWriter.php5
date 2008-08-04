<?php
import( 'de.ceus-media.net.site.MapBuilder' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Google Sitemap XML Writer.
 *	@package		net.site
 *	@uses			GoogleSitemapBuilder
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.12.2006
 *	@version		0.2
 */
/**
 *	Google Sitemap XML Writer.
 *	@package		net.site
 *	@uses			GoogleSitemapBuilder
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.12.2006
 *	@version		0.2
 */
class Net_Site_MapWriter
{
	/**	@var		string		$fileName			File Name of Sitemap XML File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName			File Name of Sitemap XML File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;
	}
	
	/**
	 *	Writes Sitemap for List of URLs.
	 *	@access		public
	 *	@param		array		$urls				List of URLs for Sitemap
	 *	@param		int			$mode				Right Mode
	 *	@return		int
	 */
	public function write( $urls, $mode = 0755 )
	{
		return $this->save( $this->fileName, $urls, $mode );
	}
	
	/**
	 *	Saves Sitemap for List of URLs statically.
	 *	@access		public
	 *	@param		string		$fileName			File Name of Sitemap XML File
	 *	@param		array		$urls				List of URLs for Sitemap
	 *	@param		int			$mode				Right Mode
	 *	@return		int
	 */
	public static function save( $fileName, $urls, $mode = 0777 )
	{
		$builder	= new Net_Site_MapBuilder();
		$file		= new File_Writer( $fileName, $mode );
		$xml		= $builder->build( $urls );
		return $file->writeString( $xml );
	}
}
?>