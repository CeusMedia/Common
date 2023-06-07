<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\OPML;

use CeusMedia\Common\FS\File\Reader as RawFileReader;
use Exception;

/**
 *	@category		Library
 *	@package		CeusMedia_Common_XML_OPML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class FileReader
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
	 *	Loads an OPML File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		URI of OPML File
	 *	@return		Parser
	 *	@throws		Exception
	 */
	public static function load( string $fileName ): Parser
	{
		$file	= new RawFileReader( $fileName );
		if( !$file->exists() )
			throw new Exception( "File '".$fileName."' is not existing." );
		$xml	= $file->readString();
		$parser	= new Parser();
		$parser->parse( $xml );
		return $parser;
	}

	/**
	 *	Reads OPML File and returns Outline Array.
	 *	@access		public
	 *	@return		Parser
	 *	@throws		Exception
	 */
	public function read(): Parser
	{
		return self::load( $this->fileName );
	}
}
