<?php
/**
 *	Builder for iCalendar File from XML Tree.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		File.ICal
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.03.2006
 *	@version		$Id$
 *	@see			RFC2445
 *	@link			http://www.w3.org/2002/12/cal/rfc2445
 */
/**
 *	Builder for iCalendar File from XML Tree.
 *	@category		cmClasses
 *	@package		File.ICal
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.03.2006
 *	@version		$Id$
 *	@see			RFC2445
 *	@link			http://www.w3.org/2002/12/cal/rfc2445
 */
class File_Ical_Builder
{
	/**	@var	string		$lineBreak		Line Break String */
	protected static $lineBreak;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$lineBreak		Line Break String
	 *	@return 	void
	 */
	public function __construct( $lineBreak = "\r\n" )
	{
		self::$lineBreak	= $lineBreak;	
	}

	/**
	 *	Builds Array of iCal Lines from XML Tree.
	 *	@access		public
	 *	@param		XML_DOM_Node	$tree		XML Tree
	 *	@return 	array
	 */
	public function build( $tree )
	{
		$lines	= array();
		$children	= $tree->getChildren();
		foreach( $children as $child )
			foreach( self::buildRecursive( $child ) as $line )
				$lines[]	= $line;
		return implode( self::$lineBreak, $lines );
	}
	
	//  --  PRIVATE METHODS  --  //
	/**
	 *	Builds Array of iCal Lines from XML Tree recursive.
	 *	@access		protected
	 *	@static
	 *	@param		XML_DOM_Node	node	XML Node
	 *	@return 	array
	 */
	protected static function buildRecursive( $node  )
	{
		$lines	= array();
		$name	= $node->getNodeName();
		$value	= $node->getContent();
		$param	= $node->getAttributes();
		if( NULL === $value )
		{
			$lines[]	= "BEGIN:".strtoupper( $name );
			$children	= $node->getChildren();
			foreach( $children as $child )
				foreach( self::buildRecursive( $child ) as $line )
					$lines[]	= $line;
			$lines[]	= "END:".strtoupper( $name );
		}
		else
			$lines[]	= self::buildLine( $name, $param, $value );
		return $lines;
	}
	
	/**
	 *	Builds iCal Line.
	 *	@access		protected
	 *	@static
	 *	@param		string		name		Line Name
	 *	@param		array		param		Line Parameters
	 *	@param		string		content		Line Value
	 *	@return 	string
	 */
	protected static function buildLine( $name, $param, $content )
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
					$param	.= substr( $rest, 0, 74 ).self::$lineBreak;
					$rest	= " ".substr( $rest, 74 );
				}
			}
			$param	= self::$lineBreak.$param;
		}

		$content	= ":".$content;
		if( strlen( $content ) > 75 )
		{
			$rest	= $content;
			$content	= "";
			while( strlen( $rest ) > 75 )
			{
				$content	.= substr( $rest, 0, 74 ).self::$lineBreak;
				$rest	= " ".substr( $rest, 74 );
			}
		}

		$line	= strtoupper( $name ).$param.$content;
		$line	= $line;
		return $line;
	}
}
?>