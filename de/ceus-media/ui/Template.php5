<?php
/**
 *	Template Class.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		framework.krypton.core
 *	@uses			Exception_Template
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.03.2007
 *	@version		0.6
 */
import( 'de.ceus-media.framework.krypton.exception.IO' );
import( 'de.ceus-media.exception.Template' );
/**
 *	Template Class.
 *	@package		framework.krypton.core
 *	@uses			Exception_Template
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.03.2007
 *	@version		0.6
 * 
 *	<b>Syntax of a Templatefile</b>
 *	- comment <%--comment--%>
 *	- optional tag <%?tagname%>
 *	- non optional tag <%tagname%>
 * 
 *	<b>Exmaple</b>
 *	<code>
 *	<html>
 *	<head>
 *	<title><%?pagetitle%>
 *	</title>
 *	<body> <%-- this is a comment --%>
 *	<h1><%title%></h1>
 *	<p><%text%></p><%-- just an
 *	other comment --%>
 *	</body>
 *	</html>
 *	</code>
 */
class UI_Template
{
	/**	@var		array		the first dimension holds all added labels, the second dimension holds elements for each label */
	protected $elements;
	/**	@var		string		content of a specified templatefile */
	protected $fileName;
	/**	@var		string		content of a specified templatefile */
	protected $template;
	
	public static $removeComments	= TRUE;
	
	/**
	 *	Constructor
	 *	@access		public
	 *	@param		string		$fileName		File Name of Template File
	 *	@param		array		$elements		List of Elements {@link add()}
	 *	@return		void
	 */
	public function __construct( $fileName, $elements = NULL )
	{
		$this->elements = array();
		$this->setTemplate( $fileName );
		$this->add( $elements ); 
	}
	
	/**
	 *	Adds an associative array with labels and elements to the template 
	 *	@param		array 		Array where the <b>key</b> can be a string, integer or 
	 *							float and is the <b>label</b>. The <b>value</b> can be a 
	 *							string, integer, float or a template object and represents
	 *							the element to add.
	 *	@param		bool		if true an a tag is already used, it will overwrite it 
	 *	@return		void
	 */
	public function add( $elements, $overwrite = false )
	{
		$self		= get_class( $this );
		if( is_array( $elements ) )
		{
			foreach( $elements as $key => $value )
			{
				if( is_array( $value ) || $value instanceof ArrayObject || $value instanceof ADT_List_Dictionary )
				{
					$this->addArrayRecursive( $key, $value, array(), $overwrite );
				}
				else
				{
					$validKey	= is_string( $key ) || is_int( $key ) || is_float( $key );
					$validValue	= is_string( $value ) || is_int( $value ) || is_float( $value ) || $value instanceof $self;
					if( $validKey && $validValue )
					{
						if( $overwrite == true )
							$this->elements[$key] = null;
						$this->elements[$key][] = $value;
					}
				}
			}
		}
	}

	/**
	 *	Adds an Array recursive.
	 *	@access		public
	 *	@param		string		$name			Key of Array
	 *	@param		mixed		$data			Values of Array
	 *	@param		array		$steps			Steps within Recursion
	 *	@param		bool		$overwrite		Flag: overwrite existing Tag
	 */
	public function addArrayRecursive( $name, $data, $steps = array(), $overwrite = false )
	{
		$steps[]	= $name;
		foreach( $data as $key => $value )
		{
			if( is_array( $value ) || $value instanceof ArrayObject || $value instanceof ADT_List_Dictionary  )
			{
				$this->addArrayRecursive( $key, $value, $steps );
			}
			else if( is_int( $key ) )
				$this->elements[$name][] = $value;
			else if( is_object( $value ) )
				$this->elements[$name][] = $value;
			else
			{
				$key	= implode( ".", $steps ).".".$key;
				if( $overwrite == true )
				{
					$this->elements[$key] = null;
				}
				$this->elements[$key][] = $value;
			}
		}
	}
	
	/**
	 *	Adds one Element
	 *	@param		string		tagname
	 *	@param		string|int|float|Template
	 *	@param		bool		if set to true, it will overwrite an existing element with the same label
	 *	@return		void
	 */
	public function addElement( $tag, $element, $overwrite = false )
	{
		$this->add( array( $tag => $element ), $overwrite );
	}
	
	/**
	 *	Adds another Template.
	 *	@param		string		tagname
	 *	@param		string		template file
	 *	@param		array		array containing elements {@link add()}
	 *	@param		bool		if set to true, it will overwrite an existing element with the same label
	 *	@return		void
	 */
	public function addTemplate( $tag, $fileName, $element = null, $overwrite = false )
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
		$self	= get_class( $this );
		$out	= $this->template;
#		if( preg_match( "@statistics@", $this->template ) )
#		{
#			print_m( $this->elements );
#			die;
#		}
 		$out	= preg_replace( '/<%--.*--%>/sU', '', $out );	
 		if( self::$removeComments )
			$out	= preg_replace( '/<!--.+-->/sU', '', $out );	
		foreach( $this->elements as $label => $labelElements )
		{
			$tmp = '';
			foreach( $labelElements as $element )
			{
	 			if( is_object( $element ) )
	 			{
	 				if( !( $element instanceof $self ) )
	 					continue;
					$element = $element->create();
	 			}
				$tmp	.= $element;
			}
			$out	= preg_replace( '/<%(\?)?' . $label . '%>/', $tmp, $out );
 		}
		$out = preg_replace( '/<%\?.*%>/U', '', $out );    
        $out = preg_replace( '/\n\s+\n/', "\n", $out );
		$tags = array();
		if( preg_match_all( '/<%.*%>/U', $out, $tags ) === 0 )
		    return $out; 				

		$tags	= array_shift( $tags );
		throw new Exception_Template( TEMPLATE_EXCEPTION_LABELS_NOT_USED, $this->fileName, $tags );
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
	 *	@param		bool		$unique			Flag: unique Keys only
	 *	@return		array						containing Elements or empty
	 */
	public function getElementsFromComment( $comment, $unique = TRUE )
	{
		$content = $this->getTaggedComment( $comment );
		if( !isset( $content ) )
			return null;

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
	 *	@param		bool		$xml		Flag: with or without delimiter
	 *	@return		array					Array of Labels
	 */
	public function getLabels( $type = 0, $xml = TRUE )
	{
 		$content = preg_replace( '/<%--.*--%>/sU', '', $this->template );	
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
	 *	@param		bool		$xml		Flag: with or without Delimiter
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
	 *	Loads a new template file if it exists. Otherwise it will throw an IOException.
	 *	@param		string		$fileName 	File Name of Template
	 *	@return		void
	 */
	public function setTemplate( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new InvalidArgumentException( 'Template File "'.$fileName.'" not found.' );

		$this->fileName	= $fileName;
		$this->template = file_get_contents( $fileName );
	}
	
	/**
	 *	Renders a Template with given Elements statically.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Template File
	 *	@param		array		$elements		List of Elements {@link add()}
	 *	@return		void
	 */
	public static function render( $fileName, $elements )
	{
		$template	= new self( $fileName, $elements );
		return $template->create();
	}
}
?>