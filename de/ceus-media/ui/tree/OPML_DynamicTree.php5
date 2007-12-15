<?php
import( 'de.ceus-media.ui.tree.XML_DynamicTree' );
import( 'de.ceus-media.xml.opml.OPML_DOM_FileReader' );
/**
 *	Builder for Tree with Icons out of a OPML File.
 *	@package		ui.tree
 *	@extends		XML_DynamicTree
 *	@uses			OPML_DOM_FileReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.02.2006
 *	@version		0.5
 */
/**
 *	Builder for Tree with Icons out of a OPML File.
 *	@package		ui.tree
 *	@extends		XML_DynamicTree
 *	@uses			OPML_DOM_FileReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.02.2006
 *	@version		0.5
 */
class OPML_DynamicTree extends XML_DynamicTree
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$xml_file		Filename of XML File with Navigation Data
	 *	@param		string		$partition		Partition Name of Cookie
	 *	@return		void
	 */
	public function __construct( $xml_file, $partition )
	{
		$this->defaults['attr_label']		= "text";
		$this->defaults['attr_link']		= "htmlUrl";
		$this->defaults['open_first']		= false;
		$this->defaults['node_open']		= "nodeopen";
		$this->defaults['node_shut']		= "nodeshut";
		parent::__construct();
		$opml_reader	= new OPML_DOM_FileReader( $xml_file );
		$opml_reader->parse();
		$this->tree	= $opml_reader->getOutlineTree();
		
		foreach( $this->defaults as $key => $value )
			$this->setOption( $key, $value );

	/*	$options	= $opml_reader->getOptions();
		foreach( $this->_defaults as $key => $value )
		{
			$value	= isset( $options['opml_'.$key] ) ? $options['opml_'.$key] : $value;
			$this->setOption( $key, $value );
		}*/
	}
}
?>