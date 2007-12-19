<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.xml.dom.FileReader' );
/**
 *	Builder for Tree with Icons out of a XML File.
 *	@package		ui.tree
 *	@extends		ADT_OptionObject
 *	@uses			XML_DOM_FileReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2005
 *	@version		0.5
 */
/**
 *	Builder for Tree with Icons out of a XML File.
 *	@package		ui.tree
 *	@extends		ADT_OptionObject
 *	@uses			XML_DOM_FileReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2005
 *	@version		0.5
 */
class XML_Tree extends ADT_OptionObject
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
		$this->tree	= XML_DOM_FileReader::load( $xml_file );
		$this->setDefaults();
	}
	
	/**
	 *	Sets Options by Defaults.
	 *	@access		public
	 *	@return		void
	 */
	public function setDefaults()
	{
		foreach( $this->defaults as $key => $value )
			$this->setOption( $key, $value );
	}
	
	/**
	 *	Builds HTML Navigation.
	 *	@access		public
	 *	@return		string
	 */
	public function buildTree()
	{
		$this->setShortCuts();
		$counter	= 0;
		$tree	= $this->buildTreeRecursive( $this->tree, 0, $counter );
		$code	= "<ul>".$tree."</ul>";
		return $code;
	}

	/**
	 *	Builds Icon of Node.
	 *	@access		protected
	 *	@param		XML_DOM_Node	$node		Current Node of XML File
	 *	@param		int				$node_id		ID of Child Node for dynamic extension
	 *	@return		string
	 */
	protected function buildNodeIcon( $node, $node_id = false )
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
	 *	@access		protected
	 *	@param		XML_DOM_Node	$node	Current Node of XML File
	 *	@return		string
	 */
	protected function buildNodeLabel( $node )
	{
		$label 	= utf8_decode( $node->getAttribute( $this->__attr_label ) );
		if( $link		= $node->getAttribute( $this->__attr_link ) )
		{
			$target	= $this->__target ? " target='".$this->__target."'" : "";
			$param	= $node->getAttribute( $this->__attr_param );
			$label	= "<a href='".$this->__url.$this->__carrier.$link.$param."'".$target.">".$label."</a>";
		}
		$code	= "<span class='".$this->__class_label."'>".$label."</span>";	
		return $code;
	}

	/**
	 *	Builds HTML indent for a Navigation Node.
	 *	@access		private
	 *	@param		int				$level		Current Node Level
	 *	@return		string
	 */
	protected function buildIndent( $level )
	{
		return  "\n".str_repeat( "  ", $level );
	}
	
	/**
	 *	Indicates access zu Link, to be overwritten.
	 *	@access		protected
	 *	@return		bool
	 */
	protected function proveAccess()
	{
		return true;
	}

	/**
	 *	Builds HTML Navigation recursive.
	 *	@access		protected
	 *	@param		XML_DOM_Node	$node	Current Node of XML File
	 *	@param		int				$level		Current Node Level
	 *	@return		string
	 */
	protected function buildTreeRecursive( $node, $level )
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

				$code	.= $this->buildIndent( $level )."<li".$class.">".$this->buildNodeIcon( $child ).$this->buildNodeLabel( $child )."</li>";
				if( $child->hasChildren() )
					$code	.= $this->buildIndent( $level )."<ul name='node'>".$this->buildTreeRecursive( $child, $level+1 )."</ul>";
			}
		}
		return $code;
	}
	
	/**
	 *	Sets short Members from Options.
	 *	@access		protected
	 *	@return		void
	 */
	protected function setShortCuts()
	{
		foreach( array_keys( $this->defaults ) as $key )
			$this->{"__".$key}	= $this->getOption( $key );
	}
}
?>