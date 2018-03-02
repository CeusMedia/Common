<?php
/**
 *	Writes vCard String from vCard Data Object to a File.
 *
 *	Copyright (c) 2007-2018 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			03.09.2008
 *	@version		$Id$
 */
/**
 *	Writes vCard String from vCard Data Object to a File.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_VCard
 *	@uses			FS_File_Writer
 *	@uses			FS_File_VCard_Builder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			03.09.2008
 *	@version		$Id$
 */
class FS_File_VCard_Writer
{
	/**	@var		string		$fileName		File Name of VCard File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of VCard File.
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;
	}
	
	/**
	 *	Saves a vCard Object to a File statically and returns Number of written Bytes.
	 *	@access		public
	 *	@static
	 *	@param		ADT_VCard	$card			vCard Object
	 *	@param		string		$charsetIn		Charset to convert from
	 *	@param		string		$charsetOut		Charset to convert to
	 *	@return		int
	 */
	public static function save( $fileName, $card, $charsetIn = NULL, $charsetOut = NULL )
	{
		$string	= FS_File_VCard_Builder::build( $card, $charsetIn, $charsetOut );
		return FS_File_Writer::save( $fileName, $string );
	}

	/**
	 *	Writes a vCard Object to the set up File and returns Number of written Bytes.
	 *	@access		public
	 *	@param		ADT_VCard	$card			vCard Object
	 *	@param		string		$charsetIn		Charset to convert from
	 *	@param		string		$charsetOut		Charset to convert to
	 *	@return		int
	 */
	public function write( $card, $charsetIn = NULL, $charsetOut = NULL )
	{
		return $this->save( $this->fileName, $card, $charsetIn, $charsetOut );
	}
}
?>