<?php
import( 'de.ceus-media.framework.krypton.exception.IO' );
import( 'de.ceus-media.framework.krypton.exception.Template' );
/**
 *	Template Class.
 *	@package		mv2.core
 *	@uses			Framework_Krypton_Exception_IO
 *	@uses			Framework_Krypton_Exception_Template
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.03.2007
 *	@version		0.3
 */
/**
 *	Template Class.
 *	@package		mv2.core
 *	@uses			Framework_Krypton_Exception_IO
 *	@uses			Framework_Krypton_Exception_Template
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.03.2007
 *	@version		0.3
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
class Framework_Krypton_Core_Template
{
	/**	@var		array		the first dimension holds all added labels, the second dimension holds elements for each label */
	protected $elements;
	/**	@var		string		content of a specified templatefile */
	protected $filename;
	/**	@var		string		content of a specified templatefile */
	protected $template;
	
	/**
	 *	Constructor
	 *	@access		public
	 *	@param		string		template file
	 *	@param		array		array containing elements {@link add()}
	 */
	public function __construct( $filename, $elements = null )
	{
		$this->elements = array();
		$this->setTemplate( $filename );
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
		if( is_array( $elements ) )
		{
			foreach( $elements as $key => $value )
			{
				if( is_array( $value ) )
				{
					$this->addArrayRecursive( $key, $value, array(), $overwrite );
				}
				else
				{
					$key_valid		= is_string( $key ) || is_int( $key ) || is_float( $key );
					$value_valid	= is_string( $value ) || is_int( $value ) || is_float( $value ) || is_a( $value, 'Core_Template' );
					if( $key_valid && $value_valid )
					{
						if( $overwrite == true )
						{
							$this->elements[$key] = null;
						}
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
	public function addArrayRecursive( $name, $data, $steps = array(), $overwrite = fale )
	{
		$steps[]	= $name;
		foreach( $data as $key => $value )
		{
			if( is_array( $value ) )
			{
				$this->addArrayRecursive( $key, $value, $steps );
			}
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
	public function addTemplate( $tag, $filename, $element = null, $overwrite = false )
	{
		$this->addElement( $tag, new self( $filename, $element ), $overwrite );
	}
	
	/**
	 *	Creates an output string from the templatefile where all labels will be replaced with apropriate elements.
	 *	If a non optional label wasn't specified, the method will throw a TemplateException
	 *	@return		string
	 */
	public function create()
	{
		$out	= $this->template;
		$out	= preg_replace('/<%--.+?--%>/s', '', $out );
		foreach( $this->elements as $label => $labelElements )
		{
			$tmp = '';
			foreach( $labelElements as $element )
			{
	 			if( is_object( $element ) )
	 			{
	 				if( !is_a( $element, 'Framework_Krypton_Core_Template' ) )
	 				{
	 					continue;
	 				}
					$element = $element->create( $verbose );
	 			}
				$tmp	.= $element;
			}
			$out	= preg_replace( '/<%(\?)?' . $label . '%>/', $tmp, $out );
		}
		$out	= preg_replace('/<%\?(.+)?%>/', '', $out );
//		$out	= preg_replace('/<%\?[^%>]+%>/u', '', $out );
//		$out	= preg_replace('/\n\s+\n/', "\n", $out);
		if( preg_match_all( '/<%.+?%>/', $out, $tags ) === 0 )
		{
			return $out;
		}
		$tags		= array_shift( $tags );
//		$filename	= basename( $this->filename );
		throw new Framework_Krypton_Exception_Template( TEMPLATE_EXCEPTION_LABELS_NOT_USED, $this->filename, $tags );
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
	 *	Returns loaded Template.
	 *	@return		string		template content
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 *	Loads a new template file if it exists. Otherwise it will throw an IOException.
	 *	@param		string		filename
	 *	@return		void
	 */
	public function setTemplate( $filename )
	{
		if( !file_exists( $filename ) )
		{
			throw new Framework_Krypton_Exception_IO( "Template File '".$filename."' not found." );
		}
		$this->filename	= $filename;
		$this->template = file_get_contents( $filename );
	}
}
?>