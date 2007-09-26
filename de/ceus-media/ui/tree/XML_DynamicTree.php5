<?php
import( 'de.ceus-media.ui.tree.XML_Tree' );
import( 'de.ceus-media.protocol.http.PartitionCookie' );
/**
 *	Builder for Navigation Tree out of a XML File.
 *	@package		ui
 *	@subpackage		tree
 *	@extends		XML_Tree
 *	@uses			XML_DOM_FileReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2005
 *	@version		0.1
 */
/**
 *	Builder for Navigation Tree out of a XML File.
 *	@package		ui
 *	@subpackage		tree
 *	@extends		XML_Tree
 *	@uses			XML_DOM_FileReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2005
 *	@version		0.1
 */
class XML_DynamicTree extends XML_Tree
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
		$this->defaults['open_first']	= false;
		$this->defaults['node_open']	= "nodeopen";
		$this->defaults['node_shut']	= "nodeshut";
		$this->defaults['rack_tree']	= "rt";
		parent::__construct( $xml_file );
		$this->_cookie	= new PartitionCookie( $partition );
	}
	
	/**
	 *	Builds HTML Navigation.
	 *	@access		public
	 *	@return		string
	 */
/*	function buildTree()
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
		$this->__class_icon	= $this->getOption( 'class_icon' );
		$this->__class_label	= $this->getOption( 'class_label' );

		//  Tree Node Information
		$this->__first			= $this->getOption( 'open_first' );
		$this->__open		= $this->getOption( 'node_open' );
		$this->__shut			= $this->getOption( 'node_shut' );

		$code	= $this->_buildTree();
		return $code;
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
			$onclick	= $node_id ? " onClick=\"".$this->__rack_tree.".change( 'node_".$node_id."' );\"" : "";
			$alt		= $node->getAttribute( $this->__attr_title );
			$code	= "<span class='".$this->__class_icon."'".$onclick."><img src='".$this->__image_path.$source."' alt='".$alt."'/></span>";	
		}
		return $code;
		
	}

	/**
	 *	Builds HTML Navigation recursive.
	 *	@access		private
	 *	@param		XML_DOM_Node	$node	Current Node of XML File
	 *	@param		int				$level		Current Node Level
	 *	@return		string
	 */
	function _buildTreeRecursive( $node, $level, &$counter )
	{
		$code	= "";
		foreach( $node->getChildren() as $child )
		{
			$counter++;
			if( $this->proveAccess( $child->getAttribute( $this->__attr_id ) ) )
			{
				$classes	= array();
				if( $child->getAttribute( $this->__attr_link ) )
					$classes[]	= "link";
				if( $this->__current && $this->__current == $child->getAttribute( $this->__attr_link ) )
					$classes[]	= "current";
				$class	= count( $classes ) ? " class='".implode( " ", $classes )."'" : "";

				if( $child->hasChildren() )
				{
					$node_class	= $this->__node_shut;
					if( NULL !== ( $state = $this->_cookie->get( "node_".$counter ) ) )
					{
						if( $state == "open" )
							$node_class	= $this->__node_open;
					}
					else if( $this->__open_first && $counter == 1 )
						$node_class	= $this->__node_open;
					$code	.= $this->_buildIndent( $level )."<li".$class.">".$this->_buildNodeIcon( $child, $counter ).$this->_buildNodeLabel( $child )."</li>";
					$code	.= $this->_buildIndent( $level )."<ul name='node' id='node_".$counter."' class='".$node_class."'>".$this->_buildTreeRecursive( $child, $level+1, $counter )."</ul>";
				}
				else
					$code	.= $this->_buildIndent( $level )."<li".$class.">".$this->_buildNodeIcon( $child ).$this->_buildNodeLabel( $child )."</li>";
			}
		}
		return $code;
	}
}
?>