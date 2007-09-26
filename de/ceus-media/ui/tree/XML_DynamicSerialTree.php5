<?php
import( 'de.ceus-media.ui.tree.XML_DynamicTree' );
import( 'de.ceus-media.protocol.http.PartitionCookie' );
import( 'de.ceus-media.file.File' );
/**
 *	Builder for Navigation Tree out of a XML File.
 *	@package		ui
 *	@subpackage		tree
 *	@extends		XML_DynamicTree
 *	@uses			XML_DOM_FileReader
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2005
 *	@version		0.2
 */
/**
 *	Builder for Navigation Tree out of a XML File.
 *	@package		ui
 *	@subpackage		tree
 *	@extends		XML_DynamicTree
 *	@uses			XML_DOM_FileReader
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2005
 *	@version		0.2
 */
class XML_DynamicSerialTree extends XML_DynamicTree
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$xml_file		Filename of XML File with Navigation Data
	 *	@return		void
	 */
	public function __construct( $serial_file, $partition )
	{
		parent::__construct();
		$file				= new File( $serial_file );
		$this->_tree		= unserialize( $file->readString() );
		$this->defaults['open_first']	= false;
		$this->defaults['node_open']	= "nodeopen";
		$this->defaults['node_shut']	= "nodeshut";
		$this->defaults['rack_tree']	= "rt";
		$this->setDefaults();
		$this->_cookie	= new PartitionCookie( $partition );
	}
}
?>