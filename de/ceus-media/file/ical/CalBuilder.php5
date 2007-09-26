<?php
/**
 *	Builder for iCalendar File from XML Tree.
 *	@package	file
 *	@subpackage	ical
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		09.03.2006
 *	@version		0.1
 *	@see		RFC2445
 *	@link		http://www.w3.org/2002/12/cal/rfc2445
 */
/**
 *	Builder for iCalendar File from XML Tree.
 *	@package	file
 *	@subpackage	ical
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		09.03.2006
 *	@version		0.1
 *	@see		RFC2445
 *	@link		http://www.w3.org/2002/12/cal/rfc2445
 */
class CalBuilder
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
	 *	Builds Array of iCal Lines from XML Tree.
	 *	@access		public
	 *	@param		XML_DOM_Node	tree		XML Tree
	 *	@return 		array
	 */
	function build( &$tree )
	{
		$lines	= array();
		$children	= $tree->getChildren();
		foreach( $children as $child )
			foreach( $this->_buildRec( $child ) as $line )
				$lines[]	= $line;
		return $lines;
	}
	
	//  --  PRIVATE METHODS  --  //
	/**
	 *	Builds Array of iCal Lines from XML Tree recursive.
	 *	@access		private
	 *	@param		XML_DOM_Node	node	XML Node
	 *	@return 		array
	 */
	function _buildRec( &$node  )
	{
		$lines	= array();
		$name	= $node->getNodeName();
		$value	= $node->getContent();
		$param	= $node->getAttributes();
		if( false === $value )
		{
			$lines[]	= "BEGIN:".strtoupper( $name );
			$children	= $node->getChildren();
			foreach( $children as $child )
				foreach( $this->_buildRec( $child ) as $line )
					$lines[]	= $line;
			$lines[]	= "END:".strtoupper( $name );
		}
		else
			$lines[]	= $this->_buildLine( $name, $param, $value );
		return $lines;
	}
	
	/**
	 *	Builds iCal Line.
	 *	@access		private
	 *	@param		string		name		Line Name
	 *	@param		array		param		Line Parameters
	 *	@param		string		content		Line Value
	 *	@return 		string
	 */
	function _buildLine( $name, $param, $content )
	{
		$params	= array();
		foreach( $param as $key => $value )
			$params[]	= strtoupper( trim( $key ) )."=".$value;
		$param	= implode( ",", $params );
		if( $param )
		{
			$param	= " ;".$param;
			if( strlen( $param ) > 75 )
			{
				$rest	= $param;
				$param	= "";
				while( strlen( $rest ) > 75 )
				{
					$param	.= substr( $rest, 0, 74 ).$this->_linebreak;
					$rest	= " ".substr( $rest, 74 );
				}
			}
			$param	= $this->_linebreak.$param;
		}

		$content	= " :".$content;
		if( strlen( $content ) > 75 )
		{
			$rest	= $content;
			$content	= "";
			while( strlen( $rest ) > 75 )
			{
				$content	.= substr( $rest, 0, 74 ).$this->_linebreak;
				$rest	= " ".substr( $rest, 74 );
			}
		}

		$line	= strtoupper( $name ).$param.$this->_linebreak.$content;
		$line	= utf8_encode( $line );
		return $line;
	}
}
?>