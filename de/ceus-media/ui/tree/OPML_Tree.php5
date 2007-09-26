<?php
import( 'de.ceus-media.ui.tree.XML_Tree' );
import( 'de.ceus-media.xml.opml.OPML_DOM_FileReader' );
/**
 *	Builder for Tree with Icons out of a OPML File.^
 *	@package		ui
 *	@subpackage		tree
 *	@extends		XML_Tree
 *	@uses			OPML_DOM_FileReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.02.2006
 *	@version		0.1
 */
/**
 *	@package		ui
 *	@subpackage		tree
 *	Builder for Tree with Icons out of a OPML File.
 *	@extends		XML_Tree
 *	@uses			OPML_DOM_FileReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.02.2006
 *	@version		0.1
 */
class OPML_Tree extends XML_Tree
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$xml_file		Filename of XML File with Navigation Data
	 *	@return		void
	 */
	public function __construct( $xml_file )
	{
		parent::__construct();
		$opml_reader	= new OPML_DOM_FileReader( $xml_file );
		$opml_reader->parse();
		$this->_tree		= $opml_reader->getOutlineTree();
		
		$this->defaults['attr_label']	= "text";
		$this->defaults['attr_link']	= "htmlUrl";

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