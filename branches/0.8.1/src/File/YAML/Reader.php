<?php
/**
 *	YAML Reader based on Spyc.
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
 *	@package		File.YAML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2007
 *	@version		$Id$
 */
/**
 *	YAML Reader based on Spyc.
 *	@category		cmClasses
 *	@package		File.YAML
 *	@uses			File_Reader
 *	@uses			File_YAML_Spyc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2007
 *	@version		$Id$
 */
class File_YAML_Reader
{
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
	 *	Loads YAML File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name of YAML File.
	 *	@return		array
	 */
	public static function load( $fileName )
	{
		$yaml	= File_Reader::load( $fileName );
		$array	= File_YAML_Spyc::YAMLLoad( $yaml );
		return $array;
	}

	/**
	 *	Reads YAML File.
	 *	@access		public
	 *	@return		array
	 */
	public function read()
	{
		return self::load( $this->fileName );
	}
}
?>