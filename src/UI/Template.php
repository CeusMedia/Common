<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Template Class.
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
 *	@package		CeusMedia_Common_UI
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@deprecated		use CeusMedia::TemplateEngine instead
 *	@todo			to be removed in 0.9.1
 */

namespace CeusMedia\Common\UI;

use CeusMedia\Common\ADT\Collection\Dictionary;
use CeusMedia\Common\Alg\Obj\MethodFactory as ObjectMethodFactory;
use CeusMedia\Common\Deprecation;
use CeusMedia\Common\Exception\Template as TemplateException;
use ArrayObject;
use InvalidArgumentException;
use ReflectionObject;

/**
 *	Template Class.
 *	@category		Library
 *	@package		CeusMedia_Common_UI
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *
 *	<b>Syntax of a template file</b>
 *	- comment <%--comment--%>				 | will be removed on render
 *	- optional tag <%?tagName%>              | will be replaced, even with empty string
 *	- non optional tag <%tagName%>           | will be replaced but must be defined and have content
 *	- optional content <%?--optional--%>     | content will be shown or removed depending on ::$removeOptional
 *  - load(file.html)                        | load another template relatively to this one an insert here
 *
 *	<b>Example</b>
 *	<code>
 *	<html>
 *		<head>
 *			<title><%?pageTitle%></title>
 *			<%load(meta.html)%>
 *		</head>
 *		<body>
 *			<%-- this is a comment --%>
 *			<h1><%title%></h1>
 *			<p><%text%></p>
 *			<%-- just an other comment --%>
 *			<%?-- this content is optional and will be show if $removeOptional is not set to true --%>
 *		</body>
 *	</html>
 *	</code>
 */
class Template
{
	public static $removeComments	= FALSE;

	public static $removeOptional	= FALSE;

	/**	@var		string		$className		Name of template class */
	protected $className;

	/**	@var		array		the first dimension holds all added labels, the second dimension holds elements for each label */
	protected $elements;

	/**	@var		string		content of a specified template file */
	protected $fileName;

	/**	@var		string		content of a specified template file */
	protected $template;

	/**	@var		array		$plugins		List of Template Plugin Instances */
	protected $plugins			= [];

	/**
	 *	Constructor
	 *	@access		public
	 *	@param		string|NULL		$fileName		File Name of Template File
	 *	@param		array			$elements		List of Elements {@link add()}
	 *	@return		void
	 *	@throws		InvalidArgumentException
	 *	@throws		TemplateException				if given template file is not existing
	 */
	public function __construct( ?string $fileName = NULL, array $elements = [] )
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		Deprecation::getInstance()
			->setErrorVersion( '0.9' )
			->setExceptionVersion( '0.9.1' )
			->message( 'Please use CeusMedia::TemplateEngine instead' );

		$this->elements		= [];
		$this->className	= get_class( $this );
		if( !is_null( $fileName ) ){
			$this->fileName		= $fileName;
			$this->setTemplate( $fileName );
		}
		$this->add( $elements );
	}

	/**
	 *	Adds an associative array with labels and elements to the template and returns number of added elements.
	 *	@param		array 		$elements		Array where the <b>key</b> can be a string, integer or
	 *											float and is the <b>label</b>. The <b>value</b> can be a
	 *											string, integer, float or a template object and represents
	 *											the element to add.
	 *	@param		boolean		$overwrite		if TRUE and a tag is already used, it will overwrite it
	 *	@return		integer
	 *	@throws 	InvalidArgumentException
	 */
	public function add( array $elements, bool $overwrite = FALSE ): int
	{
		$number	= 0;
		foreach( $elements as $key => $value ){
			if( !( is_string( $key ) || is_int( $key ) || is_float( $key ) ) )
				throw new InvalidArgumentException( 'Invalid key type "'.gettype( $key ).'"' );
			if( !strlen( trim( $key ) ) )
				throw new InvalidArgumentException( 'Key cannot be empty' );

			$isListObject	= $value instanceof ArrayObject || $value instanceof Dictionary;
			$isPrimitive	= is_string( $value ) || is_int( $value ) || is_float( $value ) || is_bool( $value );
			$isTemplate		= $value instanceof $this->className;
			if( is_null( $value ) )
				continue;
			else if( is_array( $value ) || $isListObject )
				$number	+= $this->addArrayRecursive( $key, $value, [], $overwrite );
			else if( $isPrimitive || $isTemplate ){
//				if( $overwrite == TRUE )
//					$this->elements[$key] = [];
//				$this->elements[$key][] = $value;
				$this->elements[$key] = $value;
				$number	++;
			}
			else if( is_object( $value ) )
				$this->addObject( $key, $value, [], $overwrite );
			else
				throw new InvalidArgumentException( 'Unsupported type '.gettype( $value ).' for "'.$key.'"' );
		}
		return $number;
	}

	/**
	 *	Adds an array recursive and returns number of added elements.
	 *	@access		public
	 *	@param		string			$name			Key of array
	 *	@param		array|object		$data			Values of array
	 *	@param		array			$steps			Steps within recursion
	 *	@param		bool			$overwrite		Flag: overwrite existing tag
	 *	@return		int
	 */
	public function addArrayRecursive( string $name, $data, array $steps = [], bool $overwrite = FALSE ): int
	{
		$number		= 0;
		$steps[]	= $name;
		foreach( $data as $key => $value ){
			$isListObject	= $value instanceof ArrayObject || $value instanceof Dictionary;
			if( is_array( $value ) || $isListObject  ){
				$number	+= $this->addArrayRecursive( $key, $value, $steps, $overwrite );
			}
			else{
				$key	= implode( ".", $steps ).".".$key;
				$this->addElement( $key, $value, $overwrite );
				$number ++;
			}
		}
		return $number;
	}

	/**
	 *	Adds one Element.
	 *	@param		string		$tag			Tag name
	 *	@param		mixed		$element
	 *	@param		bool		$overwrite		Flag: overwrite existing tag
	 *	@return		void
	 */
	public function addElement( string $tag, $element, bool $overwrite = FALSE )
	{
		$this->add( [$tag => $element], $overwrite );
	}

	/**
	 *	Adds a data object with get methods.
	 *	@param		string		$name			Key of array
	 *	@param		mixed		$object
	 *	@param		array		$steps			Steps within recursion
	 *	@param		bool		$overwrite		Flag: overwrite existing tag
	 *	@return		int
	 */
	public function addObject( string $name, $object, array $steps = [], bool $overwrite = FALSE ): int
	{
		$number		= 0;
		$steps[]	= $name;
		$reflection	= new ReflectionObject( $object );
		foreach( $reflection->getProperties() as $property ){
			$key		= $property->getName();
			$methodName	= 'get'.ucfirst( $key );
			if( $property->isPublic() )
				$value	= $property->getValue( $object );
			else if( $reflection->hasMethod( $methodName ) )
				$value	= ObjectMethodFactory::staticCallObjectMethod( $object, $methodName );
			else
				continue;
			$label	= implode( ".", $steps ).".".$key;
			$this->addElement( $label, $value, $overwrite );
			$number ++;
		}
		return $number;
	}

	/**
	 *	Adds another Template.
	 *	@param		string		$tag			Tag name
	 *	@param		string		$fileName		Template file path
	 *	@param		array		$elements		array containing elements {@link add()}
	 *	@param		boolean		$overwrite		if set to TRUE, it will overwrite an existing element with the same label
	 *	@return		void
	 */
	public function addTemplate( string $tag, string $fileName, array $elements = [], bool $overwrite = FALSE )
	{
		$this->addElement( $tag, new self( $fileName, $elements ), $overwrite );
	}

	/**
	 *	Creates an output string from the template file where all labels will be replaced with appropriate elements.
	 *	If a non-optional label wasn't specified, the method will throw a Template Exception
	 *	@access		public
	 *	@return		string
	 */
	public function create(): string
	{
		//  local copy of set template
		$out	= $this->template;
		//  search for nested templates and load them
		$out	= $this->loadNestedTemplates( $out );

		foreach( $this->plugins as $plugin )
			if( $plugin->type == 'pre' )
				$out	= $plugin->work( $out );

 		//  remove template engine style comments
 		$out	= preg_replace( '/<%--.*--%>/sU', '', $out );
 		//  HTML comments should be removed
 		if( self::$removeComments )
			//  find and remove all HTML comments
			$out	= preg_replace( '/<!--.+-->/sU', '', $out );
 		//  optional parts should be removed
 		if( self::$removeOptional )
			//  find and remove optional parts
			$out	= preg_replace( '/<%\?--.+--%>/sU', '', $out );
		//  otherwise
		else
			//  find, remove markup but keep content
			$out	= preg_replace( '/<%\?--(.+)--%>/sU', '\\1', $out );

		//  iterate over all registered element containers
		foreach( $this->elements as $label => $element ){
			$tmp = '';																				//
			//  element is an object
			if( is_object( $element ) ){
				//  object is not a template of this template engine
				if( !( $element instanceof $this->className ) )
					//  skip this one
					continue;
				//  render template before concat
				$element = $element->create();
			}
			$tmp	.= $element;
			//  find placeholder and set in content
			$out	= preg_replace( '/<%(\?)?' . preg_quote( $label, '/' ) . '%>/', $tmp, $out );
 		}
		//  remove left over optional placeholders
		$out = preg_replace( '/<%\?.*%>/U', '', $out );
//		remove double line breaks
#		$out = preg_replace( '/\n\s+\n/', "\n", $out );

		foreach( $this->plugins as $plugin )
			if( $plugin->type == 'post' )
				$out	= $plugin->work( $out );

		//  create container for left over placeholders
		$tags = [];
		//  no more placeholders left over
		if( preg_match_all( '/<%.*%>/U', $out, $tags ) === 0 )
		    //  return final result
		    return $out;

		$tags	= array_shift( $tags );																//
		foreach( $tags as $key => $value )
			$tags[$key]	= preg_replace( '@(<%\??)|%>@', "", $value );
		if( $this->fileName )
			throw new TemplateException( TemplateException::FILE_LABELS_MISSING, $this->fileName, $tags );
		throw new TemplateException( TemplateException::LABELS_MISSING, NULL, $tags );
	}

	/**
	 *	Returns all registered Elements.
	 *	@access		public
	 *	@return		array		all set elements
	 */
	public function getElements(): array
	{
		return $this->elements;
	}

	/**
	 *	Returns all marked elements from a comment.
	 *	@param		string		$comment		Comment Tag
	 *	@param		boolean		$unique			Flag: unique Keys only
	 *	@return		array						containing Elements or empty
	 */
	public function getElementsFromComment( string $comment, bool $unique = TRUE ): array
	{
		$list		= [];
		$content	= $this->getTaggedComment( $comment );
		if( NULL !== $content ){
			foreach( explode( "\n", $content ) as $row ){
				if( preg_match( '/\s*@(\S+)?\s+(.*)/', $row, $out ) ){
					if( $unique )
						$list[$out[1]]		= $out[2];
					else
						$list[$out[1]][]	= $out[2];
				}
			}
		}
		return $list;
	}

	/**
	 *	Returns all defined labels.
	 *	@param		int			$type		Label Type: 0=all, 1=mandatory, 2=optional
	 *	@param		boolean		$xml		Flag: with or without delimiter
	 *	@return		array					Array of Labels
	 */
	public function getLabels( int $type = 0, bool $xml = TRUE ): array
	{
 		$content = preg_replace( '/<%\??--.*--%>/sU', '', $this->template );
		switch( $type ){
			case 2:
				preg_match_all( '/<%(\?.*)%>/U', $content, $tags );
				break;
			case 1:
				preg_match_all( '/<%([^-?].*)%>/U', $content, $tags );
				break;
			default:
				preg_match_all( '/<%([^-].*)%>/U', $content, $tags );
		}
		return $xml ? $tags[0] : $tags[1];
	}

	/**
	 *	Returns a tagged comment.
	 *	@param		string		$tag		Comment Tag
	 *	@param		boolean		$xml		Flag: with or without Delimiter
	 *	@return		string|NULL				Comment or NULL
	 *	@todo		quote specialchars in tagname
	 */
	public function getTaggedComment( string $tag, bool $xml = TRUE ): ?string
	{
		if( preg_match( '/<%--'.$tag.'(.*)--%>/sU', $this->template, $tag ) )
			return $xml ? $tag[0] : $tag[1];
		return NULL;
	}

	/**
	 *	Returns loaded Template.
	 *	@return		string		template content
	 */
	public function getTemplate(): string
	{
		return $this->template;
	}

	/**
	 *	Tries to load nested templates with same context data.
	 *	Syntax: <%?load(FILENAME)%> while FILENAME is related to current templates file.
	 *	@access		protected
	 *	@param		string		$template		Template content
	 *	@return		string		Template string with loaded nested templates.
	 *	@todo		kriss: handle security issues
	 */
	protected function loadNestedTemplates( string $template ): string
	{
		$matches	= [];
		preg_match_all( '/<(\?)?%load\((.+)\)%>/U', $template, $matches );
		if( !$matches[0] )
			return $template;
		for( $i=0; $i<count( $matches[0] ); $i++ ){
			$nested		= Template::render( $matches[2][$i], $this->elements );
			$template	= str_replace( $matches[0][$i], $nested, $template );
		}
		return $template;
	}

	/**
	 *	Renders a Template with given Elements statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name of Template File
	 *	@param		array		$elements		List of Elements {@link add()}
	 *	@return		string
	 */
	public static function render( string $fileName, array $elements = [] ): string
	{
		$template	= new self( $fileName, $elements );
		return $template->create();
	}

	/**
	 *	Renders a Template String with given Elements statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			Template String
	 *	@param		array		$elements		Map of Elements for Template String
	 *	@return		string
	 */
	public static function renderString( string $string, array $elements = [] ): string
	{
		$template	= new self();
		$template->template	= $string;
		$template->add( $elements );
		return $template->create();
	}

	/**
	 *	Loads a new template file if it exists. Otherwise, it will throw an Exception.
	 *	@param		string		$fileName 	File Name of Template
	 *	@return		boolean
	 *	@throws		TemplateException		if given template file is not existing
	 */
	public function setTemplate( string $fileName ): bool
	{
		if( 0 === strlen( trim( $fileName ) ) )
			return FALSE;

		if( !file_exists( $fileName ) )
			throw new TemplateException( TemplateException::FILE_NOT_FOUND, $fileName );

		$this->fileName	= $fileName;
		$this->template = file_get_contents( $fileName );
		return TRUE;
	}
}
