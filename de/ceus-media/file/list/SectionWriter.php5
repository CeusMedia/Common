<?php
/**
 *	Writer for Section List.
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
 *	@package		file.list
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
import ("de.ceus-media.file.Writer");
/**
 *	Writer for Section List.
 *	@package		file.list
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class File_List_SectionWriter
{
	/**	@var		string		$fileName		File Name of Section List */
	protected $fileName;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Section List
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName = $fileName;
	}

	/**
	 *	Writes Section List.
	 *	@access		public
	 *	@param		array		$list			Section List to write
	 *	@return		void
	 */
	public function write( $list )
	{
		return self::save( $this->fileName, $list );
	}
	
	/**
	 *	Saves a Section List to a File.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Section List
	 *	@param		array		$list			Section List to write
	 *	@return		void
	 */
	public static function save( $fileName, $list )
	{
		$lines = array();
		foreach( $list as $section => $data )
		{
			if( count( $lines ) )
				$lines[] = "";
			$lines[] = "[".$section."]";
			foreach( $data as $entry )
				$lines[] = $entry;
		}
		$writer	= new File_Writer( $fileName, 0755 );
		return $writer->writeArray( $lines );
	}
}
?>