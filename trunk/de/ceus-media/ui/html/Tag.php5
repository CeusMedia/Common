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
	/**	@var		array		$content		Content of Tag */
	protected $content;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$name			Name of Tag
	 *	@param		string		$content		Content of Tag
	 *	@param		array		$attributes		Attributes of Tag
	 *	@return		void
	 */
	public function __construct( $name, $content = NULL, $attributes = array() )
	{
		$this->name		= $name;
		$this->setContent( $content );
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
		return $this->create( $this->name, $this->content, $this->attributes );
	}

	/**
	 *	Creates Tag statically.
	 *	@access		public
	 *	@param		string		$name			Name of Tag
	 *	@param		string		$content		Content of Tag
	 *	@param		array		$attributes		Attributes of Tag
	 *	@return		void
	 */
	public static function create( $name, $content = NULL, $attributes = array() )
	{
		$name	= strtolower( $name );
		$list	= array();
		foreach( $attributes as $key => $value )
			if( $value !== NULL && $value !== FALSE )
#			if( !empty( $value ) )
				$list[]	= strtolower( $key ).'="'.$value.'"';
		$attributes	= implode( " ", $list );
		if( $attributes )
			$attributes	= " ".$attributes;
		$unsetContent	= !( $content !== NULL && $content !== FALSE );
		if( $unsetContent && $name !== "style" )
			$tag	= "<".$name.$attributes."/>";
		else
			$tag	= "<".$name.$attributes.">".$content."</".$name.">";
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
	 *	Sets Content of Tag.
	 *	@access		public
	 *	@param		string		$content		Content of Tag
	 *	@return		void
	 */
	public function setContent( $content = NULL )
	{
		$this->content	= $content;
	}

	/**
	 *	String Representation.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		return $this->create( $this->name, $this->content, $this->attributes );
	}
}
?>