<?php
/**
 *	@category		cmClasses
 *	@package		xml.opml
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
 *	@uses			XML_OPML_Parser
 *	@uses			File_Reader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.xml.opml.Parser' );
/**
 *	@category		cmClasses
 *	@package		xml.opml
 *	@uses			XML_OPML_Parser
 *	@uses			File_Reader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class XML_OPML_FileReader
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
	 *	Loads a OPML File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		URI of OPML File
	 *	@return		bool
	 */
	public static function load( $fileName )
	{
		$file	= new File_Reader( $fileName );
		if( !$file->exists() )
			throw new Exception( "File '".$fileName."' is not existing." );
		$xml	= $file->readString();
		$parser	= new XML_OPML_Parser();
		return $parser->parse( $xml );
	}
	
	/**
	 *	Reads OPML File and returns Outline Array.
	 *	@access		public
	 *	@return		XML_DOM_Node
	 */
	public function read()
	{
		return self::load( $this->fileName );
	}
}
?>