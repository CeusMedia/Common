<?php
/**
 *	Deserializer for a XML File into a Data Object.
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
 *	@extends		XML_DOM_ObjectDeserializer
 *	@uses			File_Reader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			26.12.2005
 *	@version		0.6
 */
import( 'de.ceus-media.xml.dom.ObjectDeserializer' );
import( 'de.ceus-media.file.Reader' );
/**
 *	Deserializer for a XML File into a Data Object.
 *	@category		cmClasses
 *	@package		xml.dom
 *	@extends		XML_DOM_ObjectDeserializer
 *	@uses			File_Reader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			26.12.2005
 *	@version		0.6
 */
class XML_DOM_ObjectFileDeserializer extends XML_DOM_ObjectDeserializer
{
	/**
	 *	Builds Object from XML File of a serialized Object.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		XML File of a serialized Object
	 *	@return		Object
	 */
	public static function deserialize( $fileName )
	{
		$reader	= new File_Reader( $fileName );
		$xml	= $reader->readString( $fileName );
		return parent::deserialize( $xml ); 
	}
}
?>