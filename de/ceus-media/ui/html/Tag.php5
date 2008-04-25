<?php
/**
 *	Builder for HTML Tags.
 *	@package		ui.html
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.04.2008
 *	@version		0.6
 */
/**
 *	Builder for HTML Tags.
 *	@package		ui.html
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.04.2008
 *	@version		0.6
 */
class UI_HTML_Tag
{
	/**	@var		array		$attributes		Attributes of Tag */
	protected $attributes		= array();
	/**	@var		string		$name			Name of Tag */
	protected $name;
	/**	@var		array		$value			Value of Tag */
	protected $value;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$name			Name of Tag
	 *	@param		string		$value			Value of Tag
	 *	@param		array		$attributes		Attributes of Tag
	 *	@return		void
	 */
	public function __construct( $name, $value = NULL, $attributes = array() )
	{
		$this->name		= $name;
		$this->setValue( $value );
		if( is_array( $attributes ) && count( $attributes ) )
			foreach( $attributes as $key => $value )
				$this->setAttribute( $key, $value );
	}
	
	/**
	 *	Builds HTML Tags as String.
	 *	@access		public
	 *	@return		string
	 */
	public function build()
	{
		return $this->create( $this->name, $this->value, $this->attributes );
	}

	/**
	 *	Creates Tag statically.
	 *	@access		public
	 *	@param		string		$name			Name of Tag
	 *	@param		string		$value			Value of Tag
	 *	@param		array		$attributes		Attributes of Tag
	 *	@return		void
	 */
	public static function create( $name, $value, $attributes = array() )
	{
		$list	= array();
		foreach( $attributes as $attributeKey => $attributeValue )
			if( $attributeValue )
				$list[]	= $attributeKey.'="'.$attributeValue.'"';
		$attributes	= implode( " ", $list );
		if( $attributes )
			$attributes	= " ".$attributes;
		$tag	= "<".$name.$attributes.">".$value."</".$name.">";
		return $tag;
	}

	/**
	 *	Sets Attribute of Tag.
	 *	@access		public
	 *	@param		string		$key			Key of Attribute
	 *	@param		string		$value			Value of Attribute
	 *	@return		void
	 */
	public function setAttribute( $key, $value = NULL )
	{
		if( isset( $this->attributes[$key] ) )
			unset( $this->attributes[$key] );
		$this->attributes[$key]	= $value;	
	}
	
	/**
	 *	Sets Value of Tag.
	 *	@access		public
	 *	@param		string		$value			Value of Tag
	 *	@return		void
	 */
	public function setValue( $value = NULL )
	{
		if( $value === NULL || $value === FALSE )
			$value	= "";
		$this->value	= $value;
	}

	/**
	 *	String Representation.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		return $this->create( $this->name, $this->value, $this->attributes );
	}
}
?>