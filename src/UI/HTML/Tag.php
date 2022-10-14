<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Builder for HTML tags.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\HTML;

use CeusMedia\Common\Renderable;
use CeusMedia\Common\Alg\Text\CamelCase as CamelCase;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Builder for HTML tags.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Tag implements Renderable
{
	/**	@var		array		$attributes		Attributes of tag */
	protected $attributes		= [];

	/**	@var		array		$data			Data attributes of tag */
	protected $data				= [];

	/**	@var		string		$name			Node name of tag */
	protected $name;

	/**	@var		mixed		$content		Content of tag */
	protected $content;

	public static $shortTagExcludes	= [
		'style',
		'script',
		'div',
		'textarea'
	];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$name			Node name of tag
	 *	@param		mixed		$content		Content of tag
	 *	@param		array		$attributes		Attributes of tag
	 *	@param		array		$data			Data attributes of tag
	 *	@return		void
	 */
	public function __construct( string $name, $content = NULL, array $attributes = [], array $data = [] )
	{
		$this->name		= $name;
		$this->setContent( $content );
		foreach( $attributes as $key => $value )
			$this->setAttribute( $key, $value );
		foreach( $data as $key => $value )
			$this->setData( $key, $value );
	}

	/**
	 *	String Representation.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString(): string
	{
		return $this->build();
	}

	/**
	 *	Builds HTML tags as string.
	 *	@access		public
	 *	@return		string
	 */
	public function build(): string
	{
		return $this->create( $this->name, $this->content, $this->attributes, $this->data );
	}

	/**
	 *	Creates Tag statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$name			Node name of tag
	 *	@param		mixed		$content		Content of tag
	 *	@param		array		$attributes		Attributes of tag
	 *	@param		array		$data			Data attributes of tag
	 *	@return		string
	 */
	public static function create( string $name, $content = NULL, array $attributes = [], array $data = [] ): string
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
				//  throw exception and transport inner exception
				throw new RuntimeException( 'Invalid attributes', 0, $e );
			//  throw exception
			throw new RuntimeException( 'Invalid attributes', 0 );
		}
		//  no node content defined, not even an empty string
		if( $content === NULL || $content === FALSE )
			//  node name is allowed to be a short tag
			if( !in_array( $name, self::$shortTagExcludes ) )
				//  build and return short tag
				return "<".$name.$attributes.$data."/>";
		//  content is an array, may be nested
		if( is_array( $content ) )
			$content	= self::flattenArray( $content, '' );
		if( is_numeric( $content ) )
			$content	= (string) $content;
		if( is_object( $content ) ){
			//  content is not a renderable object
			if( !method_exists( $content, '__toString' ) ){
				//  prepare message about not renderable object
				$message	= 'Object of class "'.get_class( $content ).'" cannot be rendered';
				//  break with error message
				throw new InvalidArgumentException( $message );
			}
			//  render object to string
			$content	= (string) $content;
		}
		//  content is neither NULL nor string so far
		if( !is_null( $content ) && !is_string( $content ) ){
			//  prepare message about wrong content data type
			$message	= 'Content type "'.gettype( $content ).'" is not supported';
			//  break with error message
			throw new InvalidArgumentException( $message );
		}
		//  build and return full tag
		return "<".$name.$attributes.$data.">".$content."</".$name.">";
	}

	/**
	 *	Returns value of tag attribute if set.
	 *	@access		public
	 *	@param		string		$key		Key of attribute to get
	 *	@return		mixed|NULL
	 */
	public function getAttribute( string $key )
	{
		if( !array_key_exists( $key, $this->attributes ) )
			return NULL;
		return $this->attributes[$key];
	}

	/**
	 *	Returns map of tag attributes.
	 *	@access		public
	 *	@return		array
	 */
	public function getAttributes(): array
	{
		return $this->attributes;
	}

	/**
	 *	Returns value of tag data if set or map of all data if not key is set.
	 *	@access		public
	 *	@param		string|NULL		$key		Key of data to get
	 *	@return		mixed|array|NULL
	 */
	public function getData( ?string $key = NULL )
	{
		if( is_null( $key ) )
			return $this->data ;
		if( !array_key_exists( $key, $this->data ) )
			return NULL;
		return $this->data[$key];
	}

	public function render(): string
	{
		return $this->build();
	}

	/**
	 *	Sets attribute of tag.
	 *	@access		public
	 *	@param		string		$key			Key of attribute
	 *	@param		mixed		$value			Value of attribute
	 *	@param		boolean		$strict			Flag: deny to override attribute
	 *	@return		self
	 */
	public function setAttribute( string $key, $value = NULL, bool $strict = TRUE ): self
	{
		//  no valid attribute key defined
		if( empty( $key ) )
			//  throw exception
			throw new InvalidArgumentException( 'Key must have content' );
		$key	= strtolower( $key );
		//  attribute key already has a value
		if( array_key_exists( $key, $this->attributes ) && $strict )
			//  throw exception
			throw new RuntimeException( 'Attribute "'.$key.'" is already set' );
		//  key is invalid
		if( !preg_match( '/^[a-z0-9:_-]+$/', $key ) )
			//  throw exception
			throw new InvalidArgumentException( 'Invalid attribute key "'.$key.'"' );

		//  no value available
		if( $value === NULL || $value === FALSE ){
			//  attribute exists
			if( array_key_exists( $key, $this->attributes ) )
				//  remove attribute
				unset( $this->attributes[$key] );
		}
		else
		{
//  value is string or numeric
//			if( is_string( $value ) || is_numeric( $value ) )
//  detect injection
//				if( preg_match( '/[^\\\]"/', $value ) )
//  throw exception
//					throw new InvalidArgumentException( 'Invalid attribute value' );
			//  set attribute
			$this->attributes[$key]	= $value;
		}
		return $this;
	}

	/**
	 *	Sets multiple attributes of tag.
	 *	@access		public
	 *	@param		array		$attributes		Map of attributes to set
	 *	@param		boolean		$strict			Flag: deny to override attribute
	 *	@return		self
	 */
	public function setAttributes( array $attributes, bool $strict = TRUE ): self
	{
		//  iterate attributes map
		foreach( $attributes as $key => $value )
			//  set each attribute
			$this->setAttribute( $key, $value, $strict );
		return $this;
	}

	/**
	 *	Sets data attribute of tag.
	 *	@access		public
	 *	@param		string		$key			Key of data attribute
	 *	@param		mixed		$value			Value of data attribute
	 *	@param		boolean		$strict			Flag: deny to override data
	 *	@return		self
	 */
	public function setData( string $key, $value = NULL, bool $strict = TRUE ): self
	{
		//  no valid data key defined
		if( empty( $key ) )
			//  throw exception
			throw new InvalidArgumentException( 'Data key is required' );
		//  data key already has a value
		if( array_key_exists( $key, $this->data ) && $strict )
			//  throw exception
			throw new RuntimeException( 'Data attribute "'.$key.'" is already set' );
		//  key is invalid
		if( !preg_match( '/^[a-z0-9:_-]+$/i', $key ) )
			//  throw exception
			throw new InvalidArgumentException( 'Invalid data key "'.$key.'"' );

		//  no value available
		if( $value === NULL || $value === FALSE ){
			//  data exists
			if( array_key_exists( $key, $this->data ) )
				//  remove attribute
				unset( $this->data[$key] );
		}
		else{
			//  value is string or numeric
			if( is_string( $value ) || is_numeric( $value ) )
				//  detect injection
				if( preg_match( '/[^\\\]"/', $value ) )
					//  throw exception
					throw new InvalidArgumentException( 'Invalid data attribute value' );
			//  set attribute
			$this->attributes[$key]	= $value;
		}
		return $this;
	}

	/**
	 *	Sets Content of Tag.
	 *	@access		public
	 *	@param		mixed		$content	Content of Tag or renderable object
	 *	@return		self
	 *	@throws		InvalidArgumentException	if given object has no __toString method
	 */
	public function setContent( $content = NULL ): self
	{
		if( is_object( $content ) ){
			//  content is not a renderable object
			if( !method_exists( $content, '__toString' ) ){
				//  prepare message about not renderable object
				$message	= 'Object of class "'.get_class( $content ).'" cannot be rendered';
				//  break with error message
				throw new InvalidArgumentException( $message );
			}
			//  render object to string
			$content	= (string) $content;
		}
		$this->content	= $content;
		return $this;
	}

	//  --  PROTECTED  --  //

	static protected function flattenArray( array $array, string $delimiter = " " ): string
	{
		foreach( $array as $key => $value )
			if( is_array( $value ) )
				$array[$key]	= self::flattenArray( $value, $delimiter );
		return join( $delimiter, $array );
	}

	protected static function renderData( array $data = [] ): string
	{
		$list	= [];
		foreach( $data as $key => $value ){
			$key	= 'data-'.CamelCase::decode( $key, '-' );
			$list[$key]	= (string) $value;
		}
		return self::renderAttributes( $list, TRUE );
	}

	protected static function renderAttributes( $attributes = [], $allowOverride = FALSE ): string
	{
		if( !is_array( $attributes ) )
			throw new InvalidArgumentException( 'Parameter "attributes" must be an Array.' );
		$list	= [];
		foreach( $attributes as $key => $value )
		{
			//  no valid attribute key defined
			if( empty( $key ) )
				//  skip this pair
				continue;
			$key	= strtolower( $key );
			//  key is not a valid lowercase ID (namespaces supported)
			if( !preg_match( '/^[a-z][a-z0-9.:_-]*$/', $key ) )
				//  throw exception
				throw new InvalidArgumentException( 'Invalid attribute key' );
			//  attribute is already defined
			if( array_key_exists( $key, $list ) && !$allowOverride )
				//  throw exception
				throw new InvalidArgumentException( 'Attribute "'.$key.'" is already set' );
			//  attribute is an array
			if( is_array( $value ) ){
				if( !count( $value ) )
					continue;
				//  just combine value items with whitespace
				$valueList	= join( ' ', $value );
				//  special case: style attribute
				if( $key == 'style' ){
					//  reset list
					$valueList	= '';
					//  iterate value items
					foreach( $value as $k => $v )
						//  extend list with style definition
						$valueList	.= ( $valueList ? '; ' : '' ).( $k.': '.$v );
				}
				$value	= $valueList;
			}
			//  attribute is neither string nor numeric
			if( !( is_string( $value ) || is_numeric( $value ) ) )
				//  skip this pair
				continue;
//  value contains unescaped (double) quotes
//			if( preg_match( '/[^\\\]"/', $value ) )
//				$value	= addslashes( $value );
//  throw exception
#				throw new InvalidArgumentException( 'Invalid attribute value "'.$value.'"' );
			//  encode HTML entities and quotes
			$value	= htmlentities( $value, ENT_QUOTES, 'UTF-8', FALSE );
			$list[$key]	= strtolower( $key ).'="'.$value.'"';
		}
		return $list ? ' '.join( ' ', $list ) : '';
	}
}
