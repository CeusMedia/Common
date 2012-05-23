<?php
/**
 *	Formats a XML String or recodes it to another Character Set.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@package		XML.DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.05.2008
 *	@version		$Id$
 */
/**
 *	Formats untidy XML or recodes to another Character Set.
 *	@category		cmClasses
 *	@package		XML.DOM
 *	@uses			XML_DOM_SyntaxValidator
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.05.2008
 *	@version		$Id$
 *	@todo			Unit Test
 */
class XML_DOM_Formater
{
	/**
	 *	Formats a XML String with Line Breaks and Indention and returns it.
	 *	@access		public
	 *	@static
	 *	@param		string		$xml			XML String to format
	 *	@param		boolean		$leadingTabs	Flag: replace leading spaces by tabs
	 *	@return		string
	 */
	public static function format( $xml, $leadingTabs = FALSE )
	{
		$validator	= new XML_DOM_SyntaxValidator();
		if( !$validator->validate( $xml ) )
			throw new InvalidArgumentException( 'String is no valid XML' ); 

		$document	= new DOMDocument();
		$document->preserveWhiteSpace	= FALSE;
		$document->loadXml( $xml );
		$document->formatOutput = TRUE;
		$xml	= $document->saveXml();

		if( $leadingTabs ){
			$lines	= explode( "\n", $xml );
			foreach( $lines as $nr => $line )
				while( preg_match( "/^\t*  /", $lines[$nr] ) )
					$lines[$nr]	= preg_replace( "/^(\t*)  /", "\\1\t", $lines[$nr] );
			$xml	= implode( "\n", $lines );
		}
		return $xml;
	}

	/**
	 *	Recodes a XML String to another Character Set.
	 *	@access		public
	 *	@static
	 *	@param		string		$xml		XML String to format
	 *	@param		string		$encodeTo	Character Set to encode to
	 *	@see		http://www.iana.org/assignments/character-sets
	 *	@return		string
	 */
	public static function recode( $xml, $encodeTo = "UTF-8" )
	{
		$validator	= new XML_DOM_SyntaxValidator();
		if( !$validator->validate( $xml ) )
			throw new InvalidArgumentException( 'String is no valid XML' ); 

		$encodeTo	= strtoupper( $encodeTo );
		
		$document	= new DOMDocument();
		$document->loadXml( $xml );
		$encoding	= strtoupper( $document->actualEncoding );
#		remark( "Encoding: ".$encoding );
		if( $encoding == $encodeTo )
			return $xml;
		
		$pattern		= '@<\?(.*) encoding=(\'|")'.$encoding.'(\'|")(.*)\?>@i';
		$replacement	= '<?\\1 encoding="'.$encodeTo.'"\\4?>';
		$xml	= iconv( $encoding, $encodeTo, $xml );
		$xml	= preg_replace( $pattern, $replacement, $xml );
		return $xml;
	}
}
?>