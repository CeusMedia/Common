<?php
/**
 *	YAML Writer based on Spyc.
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
 *	@package		File.YAML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2007
 *	@version		$Id$
 */
/**
 *	YAML Writer based on Spyc.
 *	@category		cmClasses
 *	@package		File.YAML
 *	@uses			File_Writer
 *	@uses			File_YAML_Spyc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2007
 *	@version		$Id$
 */
class File_YAML_Writer
{
	/**	@var		string		$fileName		File Name of YAML File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of YAML File.
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;
	}

	/**
	 *	Writes Data into YAML File.
	 *	@access		public
	 *	@return		bool
	 */
	public function write( $data )
	{
		return self::save( $this->fileName, $data );
	}

	/**
	 *	Writes Data into YAML File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name of YAML File.
	 *	@param		array		$data			Array to write into YAML File
	 *	@return		bool
	 */
	public static function save( $fileName, $data )
	{
		$yaml	= File_YAML_Spyc::YAMLDump( $data );
		return File_Writer::save( $fileName, $yaml );
	}
}
?>