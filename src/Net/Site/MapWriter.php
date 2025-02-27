<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Google Sitemap XML Writer.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_Site
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\Site;

use CeusMedia\Common\FS\File\Writer as FileWriter;

/**
 *	Google Sitemap XML Writer.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_Site
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class MapWriter
{
	/**	@var		string		$fileName			File Name of Sitemap XML File */
	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName			File Name of Sitemap XML File
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		$this->fileName	= $fileName;
	}

	/**
	 *	Writes Sitemap for List of URLs.
	 *	@access		public
	 *	@param		array		$urls				List of URLs for Sitemap
	 *	@param		int			$mode				Right Mode
	 *	@return		int
	 */
	public function write( array $urls, int $mode = 0755 ): int
	{
		return static::save($this->fileName, $urls, $mode);
	}

	/**
	 *	Saves Sitemap for List of URLs statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName			File Name of Sitemap XML File
	 *	@param		array		$urls				List of URLs for Sitemap
	 *	@param		int			$mode				Right Mode
	 *	@return		int
	 */
	public static function save( string $fileName, array $urls, int $mode = 0777 ): int
	{
		$builder	= new MapBuilder();
		$file		= new FileWriter( $fileName, $mode );
		$xml		= $builder->build( $urls );
		return $file->writeString( $xml );
	}
}
