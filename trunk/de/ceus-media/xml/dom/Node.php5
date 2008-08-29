<?php
/**
 *	Simplified XML Node DOM Implementation.
 *	@package		xml.dom
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Simplified XML Node DOM Implementation.
 *	@package		xml.dom
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class XML_DOM_Node
{
	/**	@var		string		$nodeName		Name of XML Node */
	protected $nodeName;
	/**	@var		array		$attributes		Map of XML Node Attributes */
	protected $attributes		= array();
	/**	@var		array		$children		List of Child Nodes  */
	protected $children			= array();
	/**	@var		string		$content		Content of XML Node */
	protected $content			= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$nodeName		Tag Name of XML Node
	 *	@param		string		$content		Content of XML Node
	 *	@param		array		$attributes		Array of Node Attributes
	 *	@return		void
	 */
	public function __construct( $nodeName, $content = NULL, $attributes = array() )
	{
		$this->setNodeName( $nodeName );
		if( $content !== NULL )
			$this->setContent( $content );
		if( !is_array( $attributes ) )
			throw new InvalidArgumentException( 'Attributes must be given as Array.' );
		if( count( $attributes ) )
			foreach( $attributes as $key => $value )
				$this->setAttribute( $key, $value );
	}

	public function __get( $key )
	{
		switch( $key )
		{
			case 'name':
			case 'nodeName':
				return $this->nodeName;
			case 'content':
			case 'nodeContent':
			case 'nodeValue':
				return $this->content;
			case 'attr':
			case 'attributes':
				return $this->attributes;
			default:
				throw new InvalidArgumentException( 'Property "'.$key.'" not defined.' );
		}
	}

	/**
	 *	Adds a Child Node, returns the Node just added.
	 *	@access		public
	 *	@param		XML_DOM_Node	$xmlNode	XML Node to add
	 *	@return		XML_DOM_Node
	 */
	public function addChild( $xmlNode )
	{
		$this->children[] = $xmlNode;
		return $xmlNode;
	}

	/**
	 *	Returns an Attribute if it is set.
	 *	@access		public
	 *	@param		string		$key			Key of Attribute
	 *	@return		string
	 */
	public function getAttribute( $key )
	{
		if( $this->hasAttribute( $key ) )
			return $this->attributes[$key];
		return "";
	}

	/**
	 *	Returns Array of all Attributes.
	 *	@access		public
	 *	@param		string		$key			Key of attribute
	 *	@return		array
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
	public function & getChild( $nodeName )
	{
		for( $i=0; $i<count( $this->children ); $i++ )
			if( $this->children[$i]->getNodeName() == $nodeName )
				return $this->children[$i];
		throw new InvalidArgumentException( 'Child Node with Node Name "'.$nodeName.'" is not existing.' );
	}

	/**
	 *	Returns a Child Node by its Index.
	 *	@access		public
	 *	@param		int			$index			Index of Child, starting with 0
	 *	@return		XML_DOM_Node
	 *	@todo		write Unit Test
	 */
	public function & getChildByIndex( $index )
	{
		if( $index > count( $this->children ) )
			throw new InvalidArgumentException( 'Child Node with Index "'.$index.'" is not existing.' );
		return $this->children[$index];
	}
	
	/**
	 *	Returns all Child Nodes.
	 *	@access		public
	 *	@param		string		$nodeName		Name of Child Node
	 *	@return		array
	 */
	public function & getChildren( $nodeName = NULL )
	{
		if( !$nodeName )
			return $this->children;
		$list	= array();
		for( $i=0; $i<count( $this->children ); $i++ )
			if( $this->children[$i]->getNodeName() == $nodeName )
				$list[]	=& $this->children[$i];
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
		return $this->content != NULL;
	}

	/**
	 *	Removes an attribute by its name.
	 *	@access		public
	 *	@param		string		$key			Key of attribute to be removed
	 *	@return		bool
	 */
	public function removeAttribute( $key )
	{
		if( !$this->hasAttribute( $key ) )
			return FALSE;
		unset( $this->attributes[$key] );
		return TRUE;
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
				$found	= TRUE;
				continue;
			}
			$children[] = $child;
		}
		if( $children == $this->children )
			return FALSE;
		$this->children = $children;
		return TRUE;		
	}

	/**
	 *	Removes content of XML Node.
	 *	@access		public
	 *	@return		bool
	 */
	public function removeContent()
	{
		if( !$this->hasContent() )
			return FALSE;
		$this->setContent( "" );
		return TRUE;
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
		if( $this->getAttribute( $key ) === (string) $value )
			return FALSE;
		$this->attributes[$key] = (string) $value;
		return TRUE;
	}
	
	/**
	 *	Sets content of XML Node.
	 *	@access		public
	 *	@param		string		$content		Content of XML Node
	 *	@return		bool
	 */
	public function setContent( $content )
	{
		if( $this->content === (string) $content )
			return FALSE;
		$this->content = (string) $content;
		return TRUE;
	}

	/**
	 *	Sets Name of XML Leaf Node.
	 *	@access		public
	 *	@param		string		$name			Name of XML Node
	 *	@return		bool
	 */
	public function setNodeName( $name )
	{
		if( $this->nodeName == (string) $name )
			return FALSE;
		$this->nodeName = (string) $name;
		return TRUE;
	}
}
?>