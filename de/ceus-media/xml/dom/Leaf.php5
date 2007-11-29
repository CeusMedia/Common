<?php
/**
 *	XML Leaf Node DOM Implementation.
 *	@package		xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	XML Leaf Node DOM Implementation.
 *	@package		xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class XML_DOM_Leaf
{
	/**	@var	string	$nodeName		Name of XML Leaf Node */
	protected $nodeName;
	/**	@var	array	$attributes		Map of XML Leaf Node attributes */
	protected $attributes		= array ();
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
	 *	Returns an attribute if it is set.
	 *	@access		public
	 *	@param		string		$key			Key of attribute
	 *	@return		string
	 */
	public function getAttribute( $key )
	{
		if( $this->hasAttribute( $key ) )
			return $this->attributes[$key];
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
		return count( $this->attributes ) > 0;
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
	 *	Indicated whether XML Node has content.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasContent()
	{
		return $this->content != "";
	}

	/**
	 *	Removes an attribute by its name.
	 *	@access		public
	 *	@param		string		$key			Key of attribute to be removed
	 *	@return		void
	 */
	public function removeAttribute( $key )
	{
		if( $this->hasAttribute( $key ) )
			unset( $this->attributes[$key] );
	}

	/**
	 *	Removes content of XML Node.
	 *	@access		public
	 *	@return		void
	 */
	public function removeContent()
	{
		if( $this->hasContent() )
			$this->setContent( "" );
	}
	
	/**
	 *	Sets an attribute.
	 *	@access		public
	 *	@param		string		$key			Key of attribute
	 *	@param		string		$value			Value of attribute
	 *	@return		void
	 */
	public function setAttribute( $key, $value )
	{
		if( $this->getAttribute( $key ) !== $value )
			$this->attributes[$key] = $value;
	}
	
	/**
	 *	Sets content of XML Node.
	 *	@access		public
	 *	@param		string		$content		Content of XML Node
	 *	@return		void
	 */
	public function setContent( $content )
	{
		$this->content = $content;
	}

	/**
	 *	Sets Name of XML Leaf Node.
	 *	@access		public
	 *	@param		string		$name			Name of XML Node
	 *	@return		void
	 */
	public function setNodeName( $name )
	{
		$this->nodeName = $name;
	}
}
?>