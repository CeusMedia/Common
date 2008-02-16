<?php
/**
 *	XML Node DOM Implementation.
 *	@package		xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	XML Node DOM Implementation.
 *	@package		xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class XML_DOM_Node
{
	/**	@var	string	$nodeName		Name of XML Leaf Node */
	protected $nodeName;
	/**	@var	array	$attributes		Map of XML Leaf Node attributes */
	protected $attributes		= array ();
	/**	@var	array	$children		List of Child Nodes  */
	protected $children	= array();
	
	/**	@var	string	$content		Content of XML Leaf Node */
	protected $content;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		nodeName	Name of XML Leaf Node
	 *	@param		string		content		Content of XML Leaf Node
	 *	@return		void
	 */
	public function __construct( $nodeName, $content = NULL )
	{
		$this->setNodeName( $nodeName );
		if( $content !== NULL )
			$this->setContent( $content );
	}
	
	/**
	 *	Adds a Child Node, returns the Node just added.
	 *	@access		public
	 *	@param		XML_DOM_Node	$xmlNode		XML Node to add
	 *	@return		XML_DOM_Node
	 */
	public function addChild( $xmlNode )
	{
		$this->children[] = $xmlNode;
		return $xmlNode;
	}

	/**
	 *	Returns an attribute if it is set.
	 *	@access		public
	 *	@param		string		$key			Key of attribute
	 *	@return		string
	 */
	public function getAttribute( $key )
	{
		if( $this->hasAttribute( $key ) )
			return $this->attributes[$key];
		return NULL;
	}

	/**
	 *	Returns all attributes as associative array.
	 *	@access		public
	 *	@param		string		$key			Key of attribute
	 *	@return		string
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}
	
	/**
	 *	Returns a Child Nodes by its name.
	 *	@access		public
	 *	@param		string		$nodeName		Name of Child Node
	 *	@return		XML_DOM_Node
	 */
	public function getChild( $nodeName )
	{
		for( $i=0; $i<count( $this->children ); $i++ )
			if( $this->children[$i]->getNodeName() == $nodeName )
				return $this->children[$i];
		return NULL;
	}
	
	/**
	 *	Returns all Child Nodes.
	 *	@access		public
	 *	@param		string		$nodeName		Name of Child Node
	 *	@return		array
	 */
	public function getChildren( $nodeName = "" )
	{
		if( !$nodeName )
			return $this->children;
		$list	= array();
		for( $i=0; $i<count( $this->children ); $i++ )
			if( $this->children[$i]->getNodeName() == $nodeName )
				$list[]	= $this->children[$i];
		return $list;
	}
	
	/**
	 *	Returns Content if it is set.
	 *	@access		public
	 *	@return		string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 *	Returns nodeName.
	 *	@access		public
	 *	@return		string
	 */
	public function getNodeName()
	{
		return $this->nodeName;
	}
	
	/**
	 *	Indicates whether XML Node has attributes.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasAttributes()
	{
		return (bool) count( $this->attributes );
	}
	
	/**
	 *	Indicates whether XML Node has an attributes by its name.
	 *	@access		public
	 *	@param		string		$key			Name of attribute to check
	 *	@return		bool
	 */
	public function hasAttribute( $key )
	{
		return array_key_exists( $key, $this->attributes );
	}

	/**
	 *	Indicates whether XML Node has an attributes by its name.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasChild( $nodeName )
	{
		return (bool) count( $this->getChildren( $nodeName ) );
	}

	/**
	 *	Indicates whether XML Node has an attributes by its name.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasChildren()
	{
		return (bool) count( $this->children );
	}

	/**
	 *	Indicated whether XML Node has content.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasContent()
	{
		return (bool) strlen( trim( $this->content ) );
	}

	/**
	 *	Removes an attribute by its name.
	 *	@access		public
	 *	@param		string		$key			Key of attribute to be removed
	 *	@return		bool
	 */
	public function removeAttribute( $key )
	{
		if( $this->hasAttribute( $key ) )
		{
			unset( $this->attributes[$key] );
			return true;
		}
		return false;
	}

	/**
	 *	Remove first found Child Nodes with given name.
	 *	@access		public
	 *	@param		string		$nodeName		Name of Child Node to be removed
	 *	@return		bool
	 */
	public function removeChild( $nodeName )
	{
		$found		= false;
		$children	= array();
		foreach( $this->children as $child )
		{
			if( !$found && $child->getNodeName() == $nodeName )
			{
				$found	= true;
				continue;
			}
			$children[] = $child;
		}
		if( $children == $this->children )
			return false;
		$this->children = $children;
		return true;		
	}

	/**
	 *	Removes content of XML Node.
	 *	@access		public
	 *	@return		bool
	 */
	public function removeContent()
	{
		if( $this->hasContent() )
		{
			$this->setContent( "" );
			return true;
		}
		return false;
	}
	
	/**
	 *	Sets an attribute.
	 *	@access		public
	 *	@param		string		$key			Key of attribute
	 *	@param		string		$value			Value of attribute
	 *	@return		bool
	 */
	public function setAttribute( $key, $value )
	{
		if( $this->getAttribute( $key ) !== $value )
		{
			$this->attributes[$key] = $value;
			return true;
		}
		return false;
	}
	
	/**
	 *	Sets content of XML Node.
	 *	@access		public
	 *	@param		string		$content		Content of XML Node
	 *	@return		bool
	 */
	public function setContent( $content )
	{
		if( $this->content != $content )
		{
			$this->content = $content;
			return true;
		}
		return false;
	}

	/**
	 *	Sets Name of XML Leaf Node.
	 *	@access		public
	 *	@param		string		$name			Name of XML Node
	 *	@return		bool
	 */
	public function setNodeName( $name )
	{
		if( $this->nodeName != $name )
		{
			$this->nodeName = $name;
			return true;
		}
		return false;
	}
}
?>