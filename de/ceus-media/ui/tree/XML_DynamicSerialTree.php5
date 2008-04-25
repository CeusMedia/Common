<?php
import( 'de.ceus-media.ui.tree.XML_DynamicTree' );
import( 'de.ceus-media.file.Reader' );
/**
 *	Builder for Navigation Tree out of a XML File.
 *	@package		ui
 *	@subpackage		tree
 *	@extends		XML_DynamicTree
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2005
 *	@version		0.5
 */
/**
 *	Builder for Navigation Tree out of a XML File.
 *	@package		ui
 *	@subpackage		tree
 *	@extends		XML_DynamicTree
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2005
 *	@version		0.5
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
		$file				= new File_Reader( $serial_file );
		$this->tree		= unserialize( $file->readString() );
		$this->defaults['open_first']	= false;
		$this->defaults['node_open']	= "nodeopen";
		$this->defaults['node_shut']	= "nodeshut";
		$this->defaults['rack_tree']	= "rt";
		$this->setDefaults();
	}
}
?>