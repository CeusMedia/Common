<?php
/**
 *	Serializer for Data Object into a XML File.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_XML_DOM
 *	@extends		XML_DOM_ObjectSerializer
 *	@uses			FS_File_Writer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			26.12.2005
 */
/**
 *	Serializer for Data Object into a XML File.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@extends		XML_DOM_ObjectSerializer
 *	@uses			FS_File_Writer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			26.12.2005
 */
class XML_DOM_ObjectFileSerializer
{
	/**
	 *	Writes XML String from an Object to a File.
	 *	@access		public
	 *	@static
	 *	@param		mixed		$object			Object to serialize
	 *	@param		string		$fileName		XML File to write to
	 *	@return		void
	 */
	public static function serialize( $object, $fileName )
	{
		return FS_File_Writer::save( $fileName, XML_DOM_ObjectSerializer::serialize( $object ) );
	}
}
