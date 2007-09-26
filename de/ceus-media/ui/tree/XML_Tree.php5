<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.xml.dom.XML_DOM_FileReader' );
/**
 *	Builder for Tree with Icons out of a XML File.
 *	@package		ui
 *	@subpackage		tree
 *	@extends		OptionObject
 *	@uses			XML_DOM_FileReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2005
 *	@version		0.1
 */
/**
 *	Builder for Tree with Icons out of a XML File.
 *	@package		ui
 *	@subpackage		tree
 *	@extends		OptionObject
 *	@uses			XML_DOM_FileReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2005
 *	@version		0.1
 */
class XML_Tree extends OptionObject
{
	/**	@var		array		$defaults		Default Options */
	var $defaults	= array(
		//  XML Node Attribute Keys
		"attr_label"	=> "label",
		"attr_icon"		=> "icon",
		"attr_title"	=> "title",
		"attr_link"		=> "link",
		"attr_param"	=> "param",
		"attr_id"		=> "id",
		//  Link Information
		"url"			=> "",
		"current"		=> "",
		"carrier"		=> "?",
		"target"		=> "",
		//  Image Information
		"image_path"	=> "",
		"class_icon"	=> "",
		"class_label"	=> "",
		"class_entry"	=> "",
		);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$xml_file		Filename of XML File with Navigation Data
	 *	@return		void
	 */
	public function __construct( $xml_file )
	{
		parent::__construct();
		$xml_reader	= new XML_DOM_FileReader( $xml_file );
		$this->_tree	= $xml_reader->parse();
		$this->setDefaults();
	}
	
	/**
	 *	Sets Options by Defaults.
	 *	@access		public
	 *	@return		void
	 */
	function setDefaults()
	{
		foreach( $this->defaults as $key => $value )
			$this->setOption( $key, $value );
	}
	
	/**
	 *	Builds HTML Navigation.
	 *	@access		public
	 *	@return		string
	 */
	function buildTree()
	{
		$this->_setShortCuts();
		$counter	= 0;
		$tree	= $this->_buildTreeRecursive( $this->_tree, 0, $counter );
		$code	= "<ul>".$tree."</ul>";
		return $code;
	}

/*	function setShortCuts()
	{
		//  XML Node Attribute Keys
		$this->__attr_label		= $this->getOption( 'attr_label' );
		$this->__attr_icon		= $this->getOption( 'attr_icon' );
		$this->__attr_title		= $this->getOption( 'attr_title' );
		$this->__attr_link		= $this->getOption( 'attr_link' );
		$this->__attr_param	= $this->getOption( 'attr_param' );
		$this->__attr_id		= $this->getOption( 'attr_id' );

		//  Link Information
		$this->__url			= $this->getOption( 'url' );
		$this->__carrier		= $this->getOption( 'carrier' );
		$this->__current		= $this->getOption( 'current' );
		$this->__target		= $this->getOption( 'target' );

		//  Image Information
		$this->__image_path	= $this->getOption( 'image_path' );
		$this->__class_entry	= $this->getOption( 'class_entry' );
		$this->__class_icon	= $this->getOption( 'class_icon' );
		$this->__class_label	= $this->getOption( 'class_label' );
	
	
	}
*/
	//  --  PRIVATE METHODS  --  //
	/**
	 *	Builds Icon of Node.
	 *	@access		private
	 *	@param		XML_DOM_Node	$node		Current Node of XML File
	 *	@param		int				$node_id		ID of Child Node for dynamic extension
	 *	@return		string
	 */
	function _buildNodeIcon( $node, $node_id = false )
	{
		$code	= "";
		if( $this->__image_path && $source = $node->getAttribute( $this->__attr_icon ) )
		{
			$alt		= $node->getAttribute( $this->__attr_title );
			$code	= "<span class='".$this->__class_icon."'><img src='".$this->__image_path.$source."' alt='".$alt."'/></span>";	
		}
		return $code;
		
	}

	/**
	 *	Builds linked Label of Node.
	 *	@access		private
	 *	@param		XML_DOM_Node	$node	Current Node of XML File
	 *	@return		string
	 */
	function _buildNodeLabel( $node )
	{
		$label 	= utf8_decode( $node->getAttribute( $this->__attr_label ) );
/*xmp( $label );
print_m( $node );
remark( $this->__attr_link );
remark( $node->getAttribute( $this->__attr_link ) );

die;
*/		if( $link		= $node->getAttribute( $this->__attr_link ) )
		{


			$target	= $this->__target ? " target='".$this->__target."'" : "";
			$param	= $node->getAttribute( $this->__attr_param );
			$label	= "<a href='".$this->__url.$this->__carrier.$link.$param."'".$target.">".$label."</a>";
		}
//		echo "<br/>Label: ".$label." - Link: ".$link;
		$code	= "<span class='".$this->__class_label."'>".$label."</span>";	
		return $code;
	}

	/**
	 *	Builds HTML indent for a Navigation Node.
	 *	@access		private
	 *	@param		int				$level		Current Node Level
	 *	@return		string
	 */
	function _buildIndent( $level )
	{
		return  "\n".str_repeat( "  ", $level );
	}
	
	/**
	 *	Indicates access zu Link, to be overwritten.
	 *	@access		private
	 *	@return		bool
	 */
	function proveAccess()
	{
		return true;
	}

	/**
	 *	Builds HTML Navigation recursive.
	 *	@access		private
	 *	@param		XML_DOM_Node	$node	Current Node of XML File
	 *	@param		int				$level		Current Node Level
	 *	@return		string
	 */
	function _buildTreeRecursive( $node, $level )
	{
		$code	= "";
		foreach( $node->getChildren() as $child )
		{
			if( $this->proveAccess( $child->getAttribute( $this->__attr_id ) ) )
			{
				$classes	= array();
				if( $child->getAttribute( $this->__attr_link ) )
					$classes[]	= "link";
				if( $this->__current == $child->getAttribute( $this->__attr_link ) )
					$classes[]	= "current";
				if( $class_entry	= $child->getAttribute( $this->__class_entry ) )
					$classes[]	= $class_entry;
				$class	= count( $classes ) ? " class='".implode( " ", $classes )."'" : "";

				$code	.= $this->_buildIndent( $level )."<li".$class.">".$this->_buildNodeIcon( $child ).$this->_buildNodeLabel( $child )."</li>";
				if( $child->hasChildren() )
					$code	.= $this->_buildIndent( $level )."<ul name='node'>".$this->_buildTreeRecursive( $child, $level+1 )."</ul>";
			}
		}
		return $code;
	}
	
	/**
	 *	Sets short Members from Options.
	 *	@access		private
	 *	@return		void
	 */
	function _setShortCuts()
	{
		foreach( array_keys( $this->defaults ) as $key )
			$this->{"__".$key}	= $this->getOption( $key );
	}
}
?>