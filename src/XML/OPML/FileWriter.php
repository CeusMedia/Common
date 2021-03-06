<?php
/**
 *	Writes XML Files from Trees build with XML_Node.
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
 *	@package		CeusMedia_Common_XML_OPML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Writes XML Files from Trees build with XML_Node.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_OPML
 *	@uses			XML_DOM_Builder
 *	@uses			FS_File_Writer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class XML_OPML_FileWriter
{
	/**	@var		string		$fileName		URI of OPML File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of OPML File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;
	}

	/**
	 *	Saves OPML Tree to OPML File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		URI of OPML File
	 *	@param		XML_DOM_Node	tree		OPML Tree
	 *	@param		string			encoding	Encoding Type
	 *	@return		bool
	 */
	public static function save( $fileName, $tree, $encoding = "utf-8" )
	{
		$builder	= new XML_DOM_Builder();
		$xml		= $builder->build( $tree, $encoding );
		$file		= new FS_File_Writer( $fileName, 0777 );
		return $file->writeString( $xml );
	}

	/**
	 *	Writes OPML Tree to OPML File.
	 *	@access		public
	 *	@param		XML_DOM_Node	tree		OPML Tree
	 *	@param		string			encoding	Encoding Type
	 *	@return		bool
	 */
	public function write( $tree, $encoding = "utf-8" )
	{
		return self::save( $this->fileName, $tree, $encoding );
	}
}
