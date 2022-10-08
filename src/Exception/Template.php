<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Exception for Templates.
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
 *	@package		CeusMedia_Common_Exception
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Exception;

use RuntimeException;
use Throwable;

/**
 *	Exception for Templates.
 *	@category		Library
 *	@package		CeusMedia_Common_Exception
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Template extends RuntimeException
{
	public const FILE_NOT_FOUND			= 0;
	public const FILE_LABELS_MISSING	= 1;
	public const LABELS_MISSING			= 2;

	/**	@var		array			$messages		Map of Exception Messages, can be overwritten statically */
	public static array $messages	= [
		self::FILE_NOT_FOUND		=> 'Template File "%1$s" is missing',
		self::FILE_LABELS_MISSING	=> 'Template "%1$s" is missing %2$s',
		self::LABELS_MISSING		=> 'Template is missing %1$s',
	];

	/**	@var		array			$labels			Holds all not used and non-optional labels */
	protected array $labels			= [];

	/**	@var		string|NULL		$filePath		File Path of Template, set only if not found */
	protected ?string $filePath		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int				$code			Exception Code
	 *	@param		string			$fileName		File Name of Template
	 *	@param		array			$data			Some additional data
	 *	@param		Throwable|NULL	$previous
	 *	@return		void
	 */
	public function __construct( int $code, string $fileName, array $data = [], ?Throwable $previous = null )
	{
		$tagList		= '"'.implode( '", "', $data ).'"';
		$this->filePath	= $fileName;
		switch( $code ){
			case self::FILE_NOT_FOUND:
				$message		= self::$messages[self::FILE_NOT_FOUND];
				$message		= sprintf( $message, $fileName );
				parent::__construct( $message, self::FILE_NOT_FOUND, $previous );
				break;
			case self::FILE_LABELS_MISSING:
				$this->labels	= $data;
				$message		= self::$messages[self::FILE_LABELS_MISSING];
				$message		= sprintf( $message, $fileName, $tagList );
				parent::__construct( $message, self::FILE_LABELS_MISSING, $previous );
				break;
			case self::LABELS_MISSING:
				$this->labels	= $data;
				$message		= self::$messages[self::LABELS_MISSING];
				$message		= sprintf( $message, $tagList );
				parent::__construct( $message, self::LABELS_MISSING, $previous );
				break;
		}
	}

	/**
	 *	Returns File Path of Template if not found.
	 *	@access	  public
	 *	@return	  string		{@link $filePath}
	 */
	public function getFilePath(): string
	{
		return $this->filePath;
	}

	/**
	 *	Returns not used Labels.
	 *	@access	  public
	 *	@return	  array		{@link $labels}
	 */
	public function getNotUsedLabels(): array
	{
		return $this->labels;
	}
}
