<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Writes XML Files from Trees build with XML_DOM_Nodes.
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
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\DOM;

use CeusMedia\Common\FS\File\Writer as RawFileWriter;
use DOMException;

/**
 *	Writes XML Files from Trees build with XML_DOM_Nodes.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class FileWriter
{
	/**	@var		string			$fileName		URI of XML File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$fileName		URI of XML File
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		$this->fileName	= $fileName;
	}

	/**
	 *	Writes XML Tree into XML File.
	 *	@access		public
	 *	@param		Node			$tree			XML Tree
	 *	@param		string			$encoding		Encoding Type
	 *	@return		int
	 *	@throws		DOMException
	 */
	public function write( Node $tree, string $encoding = "utf-8" ): int
	{
		return self::save( $this->fileName, $tree, $encoding );
	}

	/**
	 *	Writes XML Tree into XML File.
	 *	@access		public
	 *	@static
	 *	@param		string			$fileName		URI of XML File
	 *	@param		Node			$tree			XML Tree
	 *	@param		string			$encoding		Encoding Type
	 *	@return		int
	 *	@throws		DOMException
	 */
	public static function save( string $fileName, Node $tree, string $encoding = "utf-8" ): int
	{
		$builder	= new Builder();
		$xml		= $builder->build( $tree, $encoding );
		return RawFileWriter::save( $fileName, $xml );
	}
}
