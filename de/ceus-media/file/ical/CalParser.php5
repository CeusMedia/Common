<?php
import( 'de.ceus-media.xml.dom.XML_DOM_Node' );
/**
 *	Parser for iCalendar Files.
 *	@package	file
 *	@subpackage	ical
 *	@uses		XML_DOM_Node
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		09.03.2006
 *	@version		0.1
 *	@see		RFC2445
 *	@link		http://www.w3.org/2002/12/cal/rfc2445
 */
/**
 *	Parser for iCalendar Files.
 *	@package	file
 *	@subpackage	ical
 *	@uses		XML_DOM_Node
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		09.03.2006
 *	@version		0.1
 *	@see		RFC2445
 *	@link		http://www.w3.org/2002/12/cal/rfc2445
 */
class CalParser
{
	/**	@var	string	_linebreak		Line Break String */
	var $_linebreak;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	linebreak		Line Break String
	 *	@return 		void
	 */
	public function __construct( $linebreak = "\r\n" )
	{
		$this->_linebreak	= $linebreak;
	}
	
	/**
	 *	Parses iCal Lines and returns a XML Tree.
	 *	@access		private
	 *	@param		string		name		Line Name
	 *	@param		array		string		String of iCal Lines
	 *	@return 		XML_DOM_Node
	 */
	function parse( $name, $string )
	{
		$root	= new XML_DOM_Node( $name );
	
		$string	= $this->_unfoldString( $string );
		$lines = explode( $this->_linebreak, $string );

		while( count( $lines ) )
		{
			$line	= array_shift( $lines );
			$parsed	= $this->_parseLine( $line );
			if( $parsed['name'] == "BEGIN" )
				$this->_parseRec( $parsed['value'], $root, $lines );
		}
		return $root;
	}
	
	//  --  PRIVATE METHODS  --  //
	/**
	 *	Parses iCal Lines and returns a XML Tree recursive.
	 *	@access		private
	 *	@param		string			type			String to unfold
	 *	@param		XML_DOM_Node	root			Parent XML Node
	 *	@param		string			lines			Array of iCal Lines
	 *	@return 		void
	 */
	function _parseRec( $type, &$root, $lines )
	{
		$node =& new XML_DOM_Node( strtolower( $type ) );
		$root->addChild( $node );
		while( count( $lines ) )
		{
			$line	= array_shift( $lines );
			$parsed	= $this->_parseLine( $line );
			if( $parsed['name'] == "END" && $parsed['value'] == $type )
				return $lines;
			else if( $parsed['name'] == "BEGIN" )
				$lines	= $this->_parseRec( $parsed['value'], $node, $lines );
			else
			{
				$child	=& new XML_DOM_Node( strtolower( $parsed['name'] ), $parsed['value'] );
				foreach( $parsed['param'] as $param )
				{
					$parts	= explode( "=", $param );
					$child->setAttribute( strtolower( $parts[0] ), $parts[1] );
				}
				$node->addChild( $child );
			}
		}
	}

	/**
	 *	Unfolds folded Contents of iCal Lines.
	 *	@access		private
	 *	@param		string	string		String to unfold
	 *	@return 		string
	 */
	function _unfoldString( $string )
	{
		$string	= str_replace( $this->_linebreak." ;", ";", $string );
		$string	= str_replace( $this->_linebreak." :", ":", $string );
		$string	= str_replace( $this->_linebreak." ", "", $string );
		return $string;	
	}
	
	/**
	 *	Parses a single iCal Lines.
	 *	@access		private
	 *	@param		string	line		Line to parse
	 *	@return 		array
	 */
	function _parseLine( $line )
	{
		$pos	= strpos( $line, ":" );
		$name	= substr( $line, 0, $pos );
		$value	= substr( $line, $pos+1 );
		
		$params	= array();
		if( substr_count( $name, ";" ) )
		{
			$pos	= strpos( $name, ";" );
			$params	= substr( $name, $pos+1 );
			$name	= substr( $name, 0, $pos );
			$params	= explode( ",", utf8_decode( $params ) );
		}
		
		$parsed	= array(
			"name"	=> trim( $name ),
			"param"	=> $params,
			"value"	=> utf8_decode( $value ),
		);
		return $parsed;
	}
}
?>