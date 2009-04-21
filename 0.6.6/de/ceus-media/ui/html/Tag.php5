<?php
/**
 *	Builder for HTML Tags.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@package		ui.html
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			22.04.2008
 *	@version		0.6
 */
/**
 *	Builder for HTML Tags.
 *	@package		ui.html
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
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
		if( !is_array( $attributes ) )
			throw new InvalidArgumentException( 'Parameter "attributes" must be an Array.' );
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
	 *	@static
	 *	@param		string		$name			Name of Tag
	 *	@param		string		$content		Content of Tag
	 *	@param		array		$attributes		Attributes of Tag
	 *	@return		void
	 */
	public static function create( $name, $content = NULL, $attributes = array() )
	{
		if( !is_array( $attributes ) )
			throw new InvalidArgumentException( 'Parameter "attributes" must be an Array.' );
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