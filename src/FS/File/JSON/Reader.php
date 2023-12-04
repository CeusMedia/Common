<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	JSON Reader.
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
 *	@package		CeusMedia_Common_FS_File_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\JSON;

use CeusMedia\Common\ADT\JSON\Parser as JsonParser;
use CeusMedia\Common\Exception\FileNotExisting as FileNotExistingException;
use CeusMedia\Common\FS\File\Reader as FileReader;
use RuntimeException;

/**
 *	JSON Reader.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader
{
	protected static array $defaultFilters	= ['comments'];
	protected string $filePath;
	protected array $filters				= [];
	protected object|array|NULL $data		= NULL;
	protected JsonParser $parser;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$filePath		Path to JSON file
	 *	@return		void
	 */
	public function __construct( string $filePath )
	{
		if( !file_exists( $filePath ) )
			throw new RuntimeException( 'File "'.$filePath.'" is not existing' );
		$this->filePath	= $filePath;
		$this->filters	= self::$defaultFilters;
		$this->parser	= new JsonParser();
	}

	/**
	 *	Returns constant value or key of last parse error.
	 *	@access		public
	 *	@param		boolean		$asConstantKey	Flag: return constant name as string instead of its integer value
	 *	@return		integer|string
	 */
	public function getError( bool $asConstantKey = FALSE ): int|string
	{
		return $this->parser->getError( $asConstantKey );
	}

	/**
	 *	Returns all collected information as object including current parse status.
	 *	The nested status object holds the latest parse information, like error code, message and code constant key.
	 *	If file has been read with flag "storeData" the parsed data will be included, too.
	 *	@access		public
	 *	@return		object
	 */
	public function getInfo(): object
	{
		return (object) [
			'filePath'		=> $this->filePath,
			'filters'		=> $this->filters,
			'status'		=> $this->parser->getInfo(),
			'data'			=> $this->data,
		];
	}

	/**
	 *	Returns message of last parse error.
	 *	@access		public
	 *	@return		string
	 */
	public function getMessage(): string
	{
		return $this->parser->getMessage();
	}

	/**
	 *	Get new instance of JSON reader by static call.
	 *	This method is useful for chaining method calls.
	 *	@access		public
	 *	@static
	 *	@param		string		$filePath
	 *	@return		self
	 *	@noinspection	PhpUnused
	 */
	public static function getNew( string $filePath ): self
	{
		return new self( $filePath );
	}

	/**
	 *	Reads a JSON file to an object or array statically.
	 *	@access		public
	 *	@param		string		$filePath		Path to JSON file
	 *	@param		bool		$asArray		Flag: read into an array, default: no
	 *	@return		object|array
	 */
	public static function load( string $filePath, bool $asArray = FALSE ): object|array
	{
		$reader	= new Reader( $filePath );
		return $reader->read( $asArray );
	}

	/**
	 *	Reads the JSON file to an object or array.
	 *	@access		public
	 *	@param		bool		$asArray		Flag: read into an array, default: no
	 *	@param		bool		$storeData		Flag: copy read data in object for info (needs more memory), default: yes
	 *	@return		object|array|string|int|float|NULL
	 *	@throws		RuntimeException			if parsing failed
	 *	@throws		FileNotExistingException	if strict and file is not existing or given path is not a file
	 */
	public function read( bool $asArray = FALSE, bool $storeData = TRUE ): object|array|string|int|float|NULL
	{
		$json	= FileReader::load( $this->filePath );
		$json	= $this->applyFilters( $json );
		$data	= $this->parser->parse( $json, $asArray );

		$this->data	= $storeData ? $data : NULL;
		return $data;
	}

	/**
	 *	Set default filters to set for each instance.
	 *	@access		public
	 *	@static
	 *	@param		array		$defaultFilters		List of filters to set for each new instance
	 *	@noinspection	PhpUnused
	 */
	public static function setDefaultFilters( array $defaultFilters ): void
	{
		self::$defaultFilters	= $defaultFilters;
	}

	/**
	 *	Applies set filters to JSON file content, to be done before parsing.
	 *	Only one filter at the moment: comments - strip comments
	 *	@access		protected
	 *	@param		string		$json				JSON file content to be filtered
	 *	@return		string
	 */
	protected function applyFilters( string $json ): string
	{
		foreach( $this->filters as $filter ){
			if( $filter === 'comments' ){
				$json	= preg_replace( '@(/\*)(.*)(\*/)@su', '', $json );
//				$json	= preg_replace( '@^(//)(.*)$@u', '', $json );
			}
		}
		return $json;
	}
}
