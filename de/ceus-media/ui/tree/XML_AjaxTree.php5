<?php
import( 'de.ceus-media.ui.tree.XML_DynamicTree' );
/**
 *	Builder for Navigation Tree out of a XML File.
 *	@package		ui
 *	@subpackage		tree
 *	@extends		XML_DynamicTree
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2005
 *	@version		0.1
 */
/**
 *	Builder for Navigation Tree out of a XML File.
 *	@package		ui
 *	@subpackage		tree
 *	@extends		XML_DynamicTree
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2005
 *	@version		0.1
 */
class XML_AjaxTree extends XML_DynamicTree
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
		parent::__construct( $xml_file, $partition );
	}
	
	/**
	 *	Builds linked Label of Node.
	 *	@access		private
	 *	@param		XML_DOM_Node	$node	Current Node of XML File
	 *	@return		string
	 */
	function _buildNodeLabel( $node, $link = "" )
	{
		$label 	= utf8_decode( $node->getAttribute( $this->__attr_label ) );
		if( $link		= $node->getAttribute( $this->__attr_link ) )
		{
			$target	= $this->__target ? " target='".$this->__target."'" : "";
			$param	= $node->getAttribute( $this->__attr_param );
			$click	= $link ? " onClick=\"loadTree('".$link."');\"" : "";
			$label	= "<a href='".$this->__url.$this->__carrier.$link.$param."'".$target.$click.">".$label."</a>";
		}
//		echo "<br/>Label: ".$label." - Link: ".$link;
		$code	= "<span class='".$this->__class_label."'>".$label."</span>";	
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
					$code	.= $this->_buildIndent( $level )."<li".$class.">".$this->_buildNodeIcon( $child, $counter ).$this->_buildNodeLabel( $child, $child->getAttribute( $this->__attr_link ) )."</li>";
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