<?php
/**
 *	Reads and parses vCard Strings from File or URL to vCard Data Object.
 *
 *	Copyright (c) 2010-2018 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_VCard
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	Reads and parses vCard Strings from File or URL to vCard Data Object.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_VCard
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 *	@version		$Id$
 *	@todo			Code Doc
 */
class FS_File_VCard_Reader
{
	/**
	 *	Reads and parses vCard File to vCard Object and converts between Charsets.
	 *	@access		public
	 *	@static
	 *	@param		string		$vcard			VCard String
	 *	@param		string		$charsetIn		Charset to convert from
	 *	@param		string		$charsetOut		Charset to convert to
	 *	@return		string
	 */
	public function readFile( $fileName, $charsetIn = NULL, $charsetOut = NULL )
	{
		$text	= FS_File_Reader::load( $fileName );
		$parser	= new FS_File_VCard_Parser;
		return $parser->parse( $text, $charsetIn, $charsetOut );
	}
}
