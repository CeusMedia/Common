<?php
import( 'de.ceus-media.file.Editor' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Base bzip File implementation.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		file.arc
 *	@uses			File_Editor
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	Base bzip File implementation.
 *	@package		file.arc
 *	@uses			File_Editor
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class File_Arc_Bzip extends File_Editor
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		if( !function_exists( "bzcompress" ) )
			throw new Exception( 'Bzip2 Extension is not available.' );
		parent::__construct( $fileName );
	}

	/**
	 *	Reads gzip File and returns it as String.
	 *	@access		public
	 *	@return		string
	 */
 	public function readString()
	{
		$content	= parent::readString();
		return bzdecompress( $content );
	}

	/**
	 *	Writes a String to the File.
	 *	@access		public
	 *	@param		string		$string			String to write to File
	 *	@return		int
	 */
	public function writeString( $string )
	{
		$content	= bzcompress( $string );
		return parent::writeString( $content );
	}
}
?>