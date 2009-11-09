<?php
/**
 *	Formats a XML String or recodes it to another Character Set.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		xml.dom
 *	@uses			XML_DOM_SyntaxValidator
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.05.2008
 *	@version		0.6
 */
import( 'de.ceus-media.xml.dom.SyntaxValidator' );
/**
 *	Formats untidy XML or recodes to another Character Set.
 *	@category		cmClasses
 *	@package		xml.dom
 *	@uses			XML_DOM_SyntaxValidator
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.05.2008
 *	@version		0.6
 *	@todo			Unit Test
 */
class XML_DOM_Formater
{
	/**
	 *	Formats a XML String with Line Breaks and Indention and returns it.
	 *	@access		public
	 *	@static
	 *	@param		string		$xml		XML String to format
	 *	@return		string
	 */
	public static function format( $xml  )
	{
		$validator	= new XML_DOM_SyntaxValidator();
		if( !$validator->validate( $xml ) )
			throw new InvalidArgumentException( 'XML String is not valid XML.' ); 

		$document	= DOMDocument::loadXml( $xml );
		$document->formatOutput = TRUE;
		return $document->saveXml();
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
			throw new InvalidArgumentException( 'XML String is not valid XML.' ); 

		$encodeTo	= strtoupper( $encodeTo );
		$document	= DOMDocument::loadXml( $xml );
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