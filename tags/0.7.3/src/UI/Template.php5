<?php
/**
 *	Template Class.
 *
 *	Copyright (c) 2007-2011 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		UI
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2011 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.03.2007
 *	@version		$Id$
 */
/**
 *	Template Class.
 *	@category		cmClasses
 *	@package		UI
 *	@uses			Exception_Template
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2011 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.03.2007
 *	@version		$Id$
 * 
 *	<b>Syntax of a template file</b>
 *	- comment <%--comment--%>				 | will be removed on render
 *	- optional tag <%?tagname%>              | will be replaced, even with empty string
 *	- non optional tag <%tagname%>           | will be replaced but must be defined and have content
 *	- optional content <%?--optional--%>     | content will be shown or removed depending on ::$removeOptional
 *  - load(file.html)                        | load another template relatively to this one an insert here
 *
 *	<b>Example</b>
 *	<code>
 *	<html>
 *		<head>
 *			<title><%?pagetitle%></title>
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
class UI_Template
{
	/**	@var		string		$className		Name of template class */
	protected $className;
	/**	@var		array		the first dimension holds all added labels, the second dimension holds elements for each label */
	protected $elements;
	/**	@var		string		content of a specified templatefile */
	protected $fileName;
	/**	@var		string		content of a specified templatefile */
	protected $template;
	
	public static $removeComments	= FALSE;
	public static $removeOptional	= FALSE;
	
	/**
	 *	Constructor
	 *	@access		public
	 *	@param		string		$fileName		File Name of Template File
	 *	@param		array		$elements		List of Elements {@link add()}
	 *	@return		void
	 */
	public function __construct( $fileName = NULL, $elements = NULL )
	{
		$this->elements		= array();
		$this->className	= get_class( $this );
		$this->fileName		= $fileName;
		$this->setTemplate( $fileName );
		$this->add( $elements ); 
	}
	
	/**
	 *	Adds an associative array with labels and elements to the template and returns number of added elements. 
	 *	@param		array 		Array where the <b>key</b> can be a string, integer or 
	 *							float and is the <b>label</b>. The <b>value</b> can be a 
	 *							string, integer, float or a template object and represents
	 *							the element to add.
	 *	@param		boolean		if TRUE an a tag is already used, it will overwrite it
	 *	@return		integer
	 */
	public function add( $elements, $overwrite = FALSE )
	{
		if( !is_array( $elements ) )
			return 0;
		$number	= 0;
		foreach( $elements as $key => $value )
		{
			if( !( is_string( $key ) || is_int( $key ) || is_float( $key ) ) )
				throw new InvalidArgumentException( 'Invalid key type "'.gettype( $key ).'"' );
			if( !strlen( trim( $key ) ) )
				throw new InvalidArgumentException( 'Key cannot be empty' );

			$isListObject	= $value instanceof ArrayObject || $value instanceof ADT_List_Dictionary;
			$isPrimitive	= is_string( $value ) || is_int( $value ) || is_float( $value ) || is_bool( $value );
			$isTemplate		= $value instanceof $this->className;
			if( is_null( $value ) )
				continue;
			else if( is_array( $value ) || $isListObject )
				$number	+= $this->addArrayRecursive( $key, $value, array(), $overwrite );
			else if( $isPrimitive || $isTemplate )
			{
				if( $overwrite == TRUE )
					$this->elements[$key] = NULL;
				$this->elements[$key][] = $value;
				$number	++;
			}
			else if( is_object( $value ) )
				$this->addObject( $key, $value, array(), $overwrite );
			else
				throw new InvalidArgumentException( 'Unsupported type '.gettype( $value ).' for "'.$key.'"' );
		}
		return $number;
	}

	public function addObject( $name, $object, $steps = array(), $overwrite = FALSE )
	{
		$number		= 0;
		$steps[]	= $name;
		$reflection	= new ReflectionObject( $object );
		foreach( $reflection->getProperties() as $property )
		{
			$key		= $property->getName();
			$methodName	= 'get'.ucFirst( $key );
			if( $property->isPublic() )
				$value	= $property->getValue( $object );
			else if( $reflection->hasMethod( $methodName ) )
				$value	= Alg_Object_MethodFactory::callObjectMethod( $object, $methodName );
			else
				continue;
			$label	= implode( ".", $steps ).".".$key;
			$this->addElement( $label, $value, $overwrite );
			$number ++;
		}
		return $number;
	}
	/**
	 *	Adds an array recursive and returns number of added elements.
	 *	@access		public
	 *	@param		string		$name			Key of array
	 *	@param		mixed		$data			Values of array
	 *	@param		array		$steps			Steps within recursion
	 *	@param		bool		$overwrite		Flag: overwrite existing tag
	 *	@return		int
	 */
	public function addArrayRecursive( $name, $data, $steps = array(), $overwrite = FALSE )
	{
		$number		= 0;
		$steps[]	= $name;
		foreach( $data as $key => $value )
		{
			$isListObject	= $value instanceof ArrayObject || $value instanceof ADT_List_Dictionary;
			if( is_array( $value ) || $isListObject  )
			{
				$number	+= $this->addArrayRecursive( $key, $value, $steps );
			}
			else
			{
				$key	= implode( ".", $steps ).".".$key;
				$this->addElement( $key, $value );
				$number ++;
			}
		}
		return $number;
	}
	
	/**
	 *	Adds one Element.
	 *	@param		string		$tag		Tag name
	 *	@param		string|integer|float|UI_Template
	 *	@param		boolean		if set to TRUE, it will overwrite an existing element with the same label
	 *	@return		void
	 */
	public function addElement( $tag, $element, $overwrite = FALSE )
	{
		$this->add( array( $tag => $element ), $overwrite );
	}
	
	/**
	 *	Adds another Template.
	 *	@param		string		tagname
	 *	@param		string		template file
	 *	@param		array		array containing elements {@link add()}
	 *	@param		boolean		if set to TRUE, it will overwrite an existing element with the same label
	 *	@return		void
	 */
	public function addTemplate( $tag, $fileName, $element = NULL, $overwrite = FALSE )
	{
		$this->addElement( $tag, new self( $fileName, $element ), $overwrite );
	}

	/**
	 *	Creates an output string from the templatefile where all labels will be replaced with apropriate elements.
	 *	If a non optional label wasn't specified, the method will throw a Template Exception
	 *	@access		public
	 *	@return		string
	 */
	public function create()
	{
		$out	= $this->template;																	//  local copy of set template
		$out	= $this->loadNestedTemplates($out);													//  search for nested templates and load them
 		$out	= preg_replace( '/<%--.*--%>/sU', '', $out );										//  remove template engine style comments
 		if( self::$removeComments )																	//  HTML comments should be removed
			$out	= preg_replace( '/<!--.+-->/sU', '', $out );									//  find and remove all HTML comments 
 		if( self::$removeOptional )																	//  optional parts should be removed
			$out	= preg_replace( '/<%\?--.+--%>/sU', '', $out );									//  find and remove optional parts
		else																						//  otherwise
			$out	= preg_replace( '/<%\?--(.+)--%>/sU', '\\1', $out );							//  find, remove markup but keep content

		foreach( $this->elements as $label => $labelElements )										//  iterate over all registered element containers
		{
			$tmp = '';																				//  
			foreach( $labelElements as $element )													//  iterate over all elements with current element container
			{
	 			if( is_object( $element ) )															//  element is an object
	 			{
	 				if( !( $element instanceof $this->className ) )									//  object is not an template of this template engine
						continue;																	//  skip this one
					$element = $element->create();													//  render template before concat
	 			}
				$tmp	.= $element;
			}
			$out	= preg_replace( '/<%(\?)?' . $label . '%>/', $tmp, $out );						//  find placeholder and set in content
 		}
		$out = preg_replace( '/<%\?.*%>/U', '', $out );    											//  remove left over optional placeholders
        $out = preg_replace( '/\n\s+\n/', "\n", $out );												//  remove double line breaks

		$tags = array();																			//  create container for left over placeholders
		if( preg_match_all( '/<%.*%>/U', $out, $tags ) === 0 )										//  no more placeholders left over
		    return $out; 																			//  return final result

		$tags	= array_shift( $tags );																//  
		foreach( $tags as $key => $value )
			$tags[$key]	= preg_replace( '@(<%\??)|%>@', "", $value );
		if( $this->fileName )
			throw new Exception_Template( Exception_Template::FILE_LABELS_MISSING, $this->fileName, $tags );
		throw new Exception_Template( Exception_Template::LABELS_MISSING, NULL, $tags );
	}

	/**
	 *	Returns all registered Elements.
	 *	@access		public
	 *	@return		array		all set elements
	 */
	public function getElements()
	{
		return $this->elements;
	}

	/**
	 *	Returns all marked elements from a comment.
	 *	@param		string		$comment		Comment Tag
	 *	@param		boolean		$unique			Flag: unique Keys only
	 *	@return		array						containing Elements or empty
	 */
	public function getElementsFromComment( $comment, $unique = TRUE )
	{
		$content = $this->getTaggedComment( $comment );
		if( !isset( $content ) )
			return NULL;

		$list	= array();
		$content = explode( "\n", $content );
		foreach( $content as $row )
		{
			if( preg_match( '/\s*@(\S+)?\s+(.*)/', $row, $out ) )
			{
				if( $unique )
					$list[$out[1]] = $out[2];
				else
					$list[$out[1]][] = $out[2];
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
	public function getLabels( $type = 0, $xml = TRUE )
	{
 		$content = preg_replace( '/<%\??--.*--%>/sU', '', $this->template );	
		switch( $type )
		{
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
	 *	@return		string					Comment or NULL
	 *	@todo		quote specialchars in tagname
	 */
	public function getTaggedComment( $tag, $xml = TRUE )
	{
		if( preg_match( '/<%--'.$tag.'(.*)--%>/sU', $this->template, $tag ) )
			return $xml ? $tag[0] : $tag[1];
		return NULL;
	}

	/**
	 *	Returns loaded Template.
	 *	@return		string		template content
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 *	Tries to load nested templates with same context data.
	 *	Syntax: <%?load(FILENAME)%> while FILENAME is related to current templates file.
	 *	@access		protected
	 *	@param		string		$template		Template content
	 *	@return		string		Template string with loaded nested templates.
	 */
	protected function loadNestedTemplates( $template)
	{
		$matches	= array();
		preg_match_all( '/<(\?)?%load\((.*)\)%>/U', $template, $matches );
		if( !$matches[0] )
			return $template;
		for( $i=0; $i<count( $matches ); $i++ )
		{
			$filePath	= dirname( $this->fileName ).'/'.$matches[2][$i];
			$nested		= UI_Template::render( $filePath, $this->elements );
			$template	= str_replace( $matches[0][$i], $nested, $template );
		}
		return $template;
	}

	/**
	 *	Loads a new template file if it exists. Otherwise it will throw an Exception.
	 *	@param		string		$fileName 	File Name of Template
	 *	@return		boolean
	 */
	public function setTemplate( $fileName )
	{
		if( empty( $fileName ) )
			return FALSE;
			
		if( !file_exists( $fileName ) )
			throw new Exception_Template( Exception_Template::FILE_NOT_FOUND, $fileName );

		$this->fileName	= $fileName;
		$this->template = file_get_contents( $fileName );
		return TRUE;
	}
	
	/**
	 *	Renders a Template with given Elements statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name of Template File
	 *	@param		array		$elements		List of Elements {@link add()}
	 *	@return		void
	 */
	public static function render( $fileName, $elements = array() )
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
	public static function renderString( $string, $elements = array() )
	{
		$template	= new self();
		$template->template	= $string;
		$template->add( $elements );
		return $template->create();
	}
}
?>