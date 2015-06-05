<?php
/**
 *	Builder for HTML tags.
 *
 *	Copyright (c) 2007-2015 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			22.04.2008
 *	@version		$Id$
 */
/**
 *	Builder for HTML tags.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			22.04.2008
 *	@version		$Id$
 */
class UI_HTML_Tag
{
	/**	@var		array		$attributes		Attributes of tag */
	protected $attributes		= array();
	/**	@var		array		$data			Data attributes of tag */
	protected $data				= array();
	/**	@var		string		$name			Node name of tag */
	protected $name;
	/**	@var		array		$content		Content of tag */
	protected $content;

	public static $shortTagExcludes	= array(
		'style',
		'script',
		'div',
		'textarea'
	);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$name			Node name of tag
	 *	@param		string		$content		Content of tag
	 *	@param		array		$attributes		Attributes of tag
	 *	@param		array		$data			Data attributes of tag
	 *	@return		void
	 */
	public function __construct( $name, $content = NULL, $attributes = array(), $data = array() )
	{
		if( !is_array( $attributes ) )
			throw new InvalidArgumentException( 'Parameter "attributes" must be an array' );
		if( !is_array( $data ) )
			throw new InvalidArgumentException( 'Parameter "data" must be an array' );
		$this->name		= $name;
		$this->setContent( $content );
		foreach( $attributes as $key => $value )
			$this->setAttribute( $key, $value );
		foreach( $data as $key => $value )
			$this->setData( $key, $value );
	}

	/**
	 *	Builds HTML tags as string.
	 *	@access		public
	 *	@return		string
	 */
	public function build()
	{
		return $this->create( $this->name, $this->content, $this->attributes, $this->data );
	}

	/**
	 *	Creates Tag statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$name			Node name of tag
	 *	@param		string		$content		Content of tag
	 *	@param		array		$attributes		Attributes of tag
	 *	@param		array		$data			Data attributes of tag
	 *	@return		void
	 */
	public static function create( $name, $content = NULL, $attributes = array(), $data = array() )
	{
		if( !strlen( $name	= trim( $name ) ) )
			throw new InvalidArgumentException( 'Missing tag name' );
		$name		= strtolower( $name );
		try{
			$attributes	= self::renderAttributes( $attributes );
			$data		= self::renderData( $data );
		}
		catch( InvalidArgumentException $e ) {
			if( version_compare( PHP_VERSION, '5.3.0', '>=' ) )
				throw new RuntimeException( 'Invalid attributes', NULL, $e );						//  throw exception and transport inner exception
			throw new RuntimeException( 'Invalid attributes', NULL );								//  throw exception
		}
		if( $content === NULL || $content === FALSE )												//  no node content defined, not even an empty string
			if( !in_array( $name, self::$shortTagExcludes ) )										//  node name is allowed to be a short tag
				return "<".$name.$attributes.$data."/>";											//  build and return short tag
		if( is_array( $content ) )																	//  content is an array, may be nested
			$content	= self::flattenArray( $content );
		if( is_numeric( $content ) )
			$content	= (string) $content;
		if( is_object( $content ) )
			$content	= (string) $content;
		if( !is_null( $content ) && !is_string( $content ) )										//  content is neither NULL nor string so far
			throw new InvalidArgumentException( 'Content is not a string' );						//  which is not acceptable
		return "<".$name.$attributes.$data.">".$content."</".$name.">";								//  build and return full tag
	}

	static protected function flattenArray( $array, $delimiter = " ", $path = NULL )
	{
		foreach( $array as $key => $value )
			if( is_array( $value ) )
				$array[$key]	= self::flattenArray( $value );
		return join( $delimiter, $array );
	}

	/**
	 *	Returns value of tag attribute if set.
	 *	@access		public
	 *	@param		string		$key		Key of attribute to get
	 *	@return		mixed|NULL
	 */
	public function getAttribute( $key ){
		if( !array_key_exists( $key, $this->attributes ) )
			return NULL;
		return $this->attributes[$key];
	}

	/**
	 *	Returns map of tag attributes.
	 *	@access		public
	 *	@return		array
	 */
	public function getAttributes(){
		return $this->attributes;
	}

	/**
	 *	Returns value of tag data if set or map of all data if not key is set.
	 *	@access		public
	 *	@param		string		$key		Key of data to get
	 *	@return		mixed|array|NULL
	 */
	public function getData( $key = NULL ){
		if( is_null( $key ) )
			return $this->data ;
		if( !array_key_exists( $key, $this->data ) )
			return NULL;
		return $this->data[$key];
	}

	protected static function renderData( $data = array() ){
		$list	= array();
		foreach( $data as $key => $value ){
			$key	= 'data-'.Alg_Text_CamelCase::decode( $key, '-' );
			$list[$key]	= (string) $value;
		}
		return self::renderAttributes( $list, TRUE );
	}

	protected static function renderAttributes( $attributes = array(), $allowOverride = FALSE )
	{
		if( !is_array( $attributes ) )
			throw new InvalidArgumentException( 'Parameter "attributes" must be an Array.' );
		$list	= array();
		foreach( $attributes as $key => $value )
		{
			if( empty( $key ) )																		//  no valid attribute key defined
				continue;																			//  skip this pair
			$key	= strtolower( $key );
			if( !preg_match( '/^[a-z][a-z0-9.:_-]*$/', $key ) )										//  key is not a valid lowercase ID (namespaces supported)
				throw new InvalidArgumentException( 'Invalid attribute key' );						//  throw exception
			if( array_key_exists( $key, $list ) && !$allowOverride )								//  attribute is already defined
				throw new InvalidArgumentException( 'Attribute "'.$key.'" is already set' );		//  throw exception
			if( is_array( $value ) ){																//  attribute is an array
				if( !count( $value ) )
					continue;
				$valueList	= join( ' ', $value );													//  just combine value items with whitespace
				if( $key == 'style' ){																//  special case: style attribute
					$valueList	= '';																//  reset list
					foreach( $value as $k => $v )													//  iterate value items
						$valueList	.= ( $valueList ? '; ' : '' ).( $k.': '.$v );					//  extend list with style definition
				}
				$value	= $valueList;
			}
			if( !( is_string( $value ) || is_numeric( $value ) ) )									//  attribute is neither string nor numeric
				continue;																			//  skip this pair
//			if( preg_match( '/[^\\\]"/', $value ) )													//  value contains unescaped (double) quotes
//				$value	= addslashes( $value );
#				throw new InvalidArgumentException( 'Invalid attribute value "'.$value.'"' );		//  throw exception
			$value	= htmlentities( $value, ENT_QUOTES, 'UTF-8', FALSE );							//  encode HTML entities and quotes
			$list[$key]	= strtolower( $key ).'="'.$value.'"';
		}
		return $list ? " ".implode( " ", $list ) : "";
	}

	/**
	 *	Sets attribute of tag.
	 *	@access		public
	 *	@param		string		$key			Key of attribute
	 *	@param		string		$value			Value of attribute
	 *	@param		boolean		$strict			Flag: deny to override attribute
	 *	@return		void
	 */
	public function setAttribute( $key, $value = NULL, $strict = TRUE )
	{
		if( empty( $key ) )																			//  no valid attribute key defined
			throw new InvalidArgumentException( 'Key must have content' );							//  throw exception
		$key	= strtolower( $key );
		if( array_key_exists( $key, $this->attributes ) && $strict )								//  attribute key already has a value
			throw new RuntimeException( 'Attribute "'.$key.'" is already set' );					//  throw exception
		if( !preg_match( '/^[a-z0-9:_-]+$/', $key ) )												//  key is invalid
			throw new InvalidArgumentException( 'Invalid attribute key "'.$key.'"' );				//  throw exception

		if( $value === NULL || $value === FALSE ){													//  no value available
			if( array_key_exists( $key, $this->attributes ) )										//  attribute exists
				unset( $this->attributes[$key] );													//  remove attribute
		}
		else
		{
//			if( is_string( $value ) || is_numeric( $value ) )										//  value is string or numeric
//				if( preg_match( '/[^\\\]"/', $value ) )												//  detect injection
//					throw new InvalidArgumentException( 'Invalid attribute value' );				//  throw exception
			$this->attributes[$key]	= $value;														//  set attribute
		}
	}

	/**
	 *	Sets multiple attributes of tag.
	 *	@access		public
	 *	@param		array		$attributes		Map of attributes to set
	 *	@param		boolean		$strict			Flag: deny to override attribute
	 *	@return		void
	 */
	public function setAttributes( $attributes, $strict = TRUE )
	{
		foreach( $attributes as $key => $value )													//  iterate attributes map
			$this->setAttribute( $key, $value, $strict );											//  set each attribute
	}

	/**
	 *	Sets data attribute of tag.
	 *	@access		public
	 *	@param		string		$key			Key of data attribute
	 *	@param		string		$value			Value of data attribute
	 *	@param		boolean		$strict			Flag: deny to override data
	 *	@return		void
	 */
	public function setData( $key, $value = NULL, $strict = TRUE ){
		if( empty( $key ) )																			//  no valid data key defined
			throw new InvalidArgumentException( 'Data key is required' );							//  throw exception
		if( array_key_exists( $key, $this->data ) && $strict )										//  data key already has a value
			throw new RuntimeException( 'Data attribute "'.$key.'" is already set' );				//  throw exception
		if( !preg_match( '/^[a-z0-9:_-]+$/i', $key ) )												//  key is invalid
			throw new InvalidArgumentException( 'Invalid data key "'.$key.'"' );					//  throw exception

		if( $value === NULL || $value === FALSE ){													//  no value available
			if( array_key_exists( $key, $this->data ) )												//  data exists
				unset( $this->data[$key] );															//  remove attribute
		}
		else
		{
			if( is_string( $value ) || is_numeric( $value ) )										//  value is string or numeric
				if( preg_match( '/[^\\\]"/', $value ) )												//  detect injection
					throw new InvalidArgumentException( 'Invalid data attribute value' );			//  throw exception
			$this->attributes[$key]	= $value;														//  set attribute
		}
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
//		return $this->create( $this->name, $this->content, $this->attributes, $this->data );
		return $this->build();
	}
}
?>
