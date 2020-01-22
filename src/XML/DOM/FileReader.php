<?php
/**
 *	Loads an parses a XML File to a Tree of XML_DOM_Nodes.
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
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@version		$Id$
 */
/**
 *	Loads an parses a XML File to a Tree of XML_DOM_Nodes.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@uses			XML_DOM_Parser
 *	@uses			FS_File_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@version		$Id$
 */
class XML_DOM_FileReader
{
	/**	@var		string			$fileName		URI of XML File */
	protected $fileName;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$fileName		URI of XML File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;
	}
	
	/**
	 *	Loads a XML File statically and returns parsed Tree.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		URI of XML File
	 *	@return		XML_DOM_Node
	 */
	public static function load( $fileName )
	{
		$parser	= new XML_DOM_Parser();
		$xml	= FS_File_Reader::load( $fileName );
		$tree	= $parser->parse( $xml );
		return $tree;
	}
	
	/**
	 *	Reads XML File and returns parsed Tree.
	 *	@access		public
	 *	@return		XML_DOM_Node
	 */
	public function read()
	{
		return self::load( $this->fileName );
	}
}
