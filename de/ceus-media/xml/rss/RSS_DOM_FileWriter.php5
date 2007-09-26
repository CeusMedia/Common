<?php
import( 'de.ceus-media.file.File' );
import( 'de.ceus-media.xml.rss.RSS_DOM_Builder' );
/**
 *	Writer for built RSS Feeds.
 *	@package		xml
 *	@subpackage		rss
 *	@uses			File
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
/**
 *	Writer for built RSS Feeds.
 *	@package		xml
 *	@subpackage		rss
 *	@uses			File
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
class RSS_DOM_FileWriter
{
	/**	@var	RSS_DOM_Builder	_builder			Instance of RSS_DOM_Builder */
	var $_builder;
	/**	@var	array			_items			Array of items */
	var $_items;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->_builder	= new RSS_DOM_Builder();
		$this->setOption( 'timezone', '+0000' );
	}

	/**
	 *	Adds an item to RSS Feed.
	 *	@access		public
	 *	@param		array		item			Item information to add
	 *	@return		void
	 */
	function addItem( $item )
	{
		$this->_builder->addItem( $item );
	}
	
	/**
	 *	Sets options of internal builder.
	 *	@access		public
	 *	@param		string		key			Option key
	 *	@param		string		value		Option value
	 *	@return		void
	 */
	function setOption( $key, $value )
	{
		$this->_builder->setOption( $key, $value );
	}

	/**
	 *	Writes built RSS Feed.
	 *	@access		public
	 *	@param		string		_filename		URI of RSS Feed File
	 *	@return		void
	 */
	function write( $filename )
	{
		$xml	= $this->_builder->build();
		$file	= new File( $filename, 0777 );
		$file->writeString( $xml );
	}
}
?>