<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Writes XML Files from Trees build with XML_Node.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\OPML;

use CeusMedia\Common\FS\File\Writer as RawFileWriter;
use CeusMedia\Common\XML\DOM\Builder;
use CeusMedia\Common\XML\DOM\Node;
use DOMException;

/**
 *	Writes XML Files from Trees build with XML_Node.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_OPML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class FileWriter
{
	/**	@var		string		$fileName		URI of OPML File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of OPML File
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		$this->fileName	= $fileName;
	}

	/**
	 *	Saves OPML Tree to OPML File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		URI of OPML File
	 *	@param		Node		$tree			OPML Tree
	 *	@param		string		$encoding		Encoding Type
	 *	@return		int
	 *	@throws		DOMException;
	 */
	public static function save( string $fileName, Node $tree, string $encoding = "utf-8" ): int
	{
		$builder	= new Builder();
		$xml		= $builder->build( $tree, $encoding );
		$file		= new RawFileWriter( $fileName, 0777 );
		return $file->writeString( $xml );
	}

	/**
	 *	Writes OPML Tree to OPML File.
	 *	@access		public
	 *	@param		Node		$tree		OPML Tree
	 *	@param		string		$encoding	Encoding Type
	 *	@return		int
	 *	@throws		DOMException;
	 */
	public function write( Node $tree, string $encoding = "utf-8" ): int
	{
		return self::save( $this->fileName, $tree, $encoding );
	}
}
