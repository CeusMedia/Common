<?php
import( 'de.ceus-media.ui.tree.XML_Tree' );
import( 'de.ceus-media.net.http.PartitionCookie' );
/**
 *	Builder for Navigation Tree out of a XML File.
 *	@package		ui
 *	@subpackage		tree
 *	@extends		XML_Tree
 *	@uses			Net_HTTP_PartitionCookie
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2005
 *	@version		0.5
 */
/**
 *	Builder for Navigation Tree out of a XML File.
 *	@package		ui
 *	@subpackage		tree
 *	@extends		XML_Tree
 *	@uses			Net_HTTP_PartitionCookie
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2005
 *	@version		0.5
 */
class XML_DynamicTree extends XML_Tree
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$xmlFile		Filename of XML File with Navigation Data
	 *	@param		string		$partition		Partition Name of Cookie
	 *	@return		void
	 */
	public function __construct( $xmlFile, $partition )
	{
		$this->defaults['open_first']	= false;
		$this->defaults['node_open']	= "nodeopen";
		$this->defaults['node_shut']	= "nodeshut";
		$this->defaults['rack_tree']	= "rt";
		parent::__construct( $xmlFile );
		$this->cookie	= new Net_HTTP_PartitionCookie( $partition );
	}
	
	//  --  PRIVATE METHODS  --  //
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
			$onclick	= $node_id ? " onClick=\"".$this->__rack_tree.".change( 'node_".$node_id."' );\"" : "";
			$alt		= $node->getAttribute( $this->__attr_title );
			$code	= "<span class='".$this->__class_icon."'".$onclick."><img src='".$this->__image_path.$source."' alt='".$alt."'/></span>";	
		}
		return $code;
		
	}

	/**
	 *	Builds HTML Navigation recursive.
	 *	@access		protected
	 *	@param		XML_DOM_Node	$node	Current Node of XML File
	 *	@param		int				$level		Current Node Level
	 *	@return		string
	 */
	protected function buildTreeRecursive( $node, $level, &$counter )
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
					if( NULL !== ( $state = $this->cookie->get( "node_".$counter ) ) )
					{
						if( $state == "open" )
							$node_class	= $this->__node_open;
					}
					else if( $this->__open_first && $counter == 1 )
						$node_class	= $this->__node_open;
					$code	.= $this->buildIndent( $level )."<li".$class.">".$this->buildNodeIcon( $child, $counter ).$this->buildNodeLabel( $child )."</li>";
					$code	.= $this->buildIndent( $level )."<ul name='node' id='node_".$counter."' class='".$node_class."'>".$this->buildTreeRecursive( $child, $level+1, $counter )."</ul>";
				}
				else
					$code	.= $this->buildIndent( $level )."<li".$class.">".$this->buildNodeIcon( $child ).$this->buildNodeLabel( $child )."</li>";
			}
		}
		return $code;
	}
}
?>