<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
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
 *	@package		CeusMedia_Common_FS_Folder_Treeview
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\Folder\Treeview;

use CeusMedia\Common\ADT\JSON\Encoder as JsonEncoder;
use CeusMedia\Common\Alg\Time\Clock;
use CeusMedia\Common\Exception\Conversion as ConversionException;
use CeusMedia\Common\UI\HTML\Tag;
use DirectoryIterator;
use SplFileInfo;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_FS_Folder_Treeview
 *	@todo			Code Doc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Json
{
	protected string $basePath;
	protected ?string $logFile;

	public string $classLeaf		= 'file';
	public string $classNode		= 'folder';

	public string $fileUrl			= './?file=';
	public ?string $fileTarget		= NULL;

	public function __construct( string $basePath, ?string $logFile = NULL )
	{
		$this->basePath		= $basePath;
		$this->logFile		= $logFile;
	}

	/**
	 *	...
	 *	@param			string		$path
	 *	@return			string
	 *	@noinspection	PhpUnused
	 *	@throws			ConversionException
	 */
	public function buildJson( string $path = '' ): string
	{
		$clock		= new Clock;
		$index		= new DirectoryIterator( $this->basePath.$path );
		$folders	= [];
		$files		= [];
		/** @var SplFileInfo $entry */
		foreach( $index as $entry ){
			if( str_starts_with( $entry->getFilename(), '.' ) )
				continue;
			if( $entry->isDir() )
				$folders[]	= $this->buildFolderItem( $entry );
			else if( $entry->isFile() )
				$files[]		= $this->buildFileItem( $entry );
		}
		$list	= [...$folders, ...$files];
		$json	= JsonEncoder::create()->encode( $list );
		if( $this->logFile )
			$this->log( $path, count( $list ), strlen( $json ), (int) $clock->stop( 6, 0 ) );
		return $json;
	}


	protected function buildFileItem( SplFileInfo $entry ): array
	{
		$label		= $entry->getFilename();
		$url		= $this->getFileUrl( $entry );
		$attributes	= [
			'href' 		=> $url,
			'target'	=> $this->fileTarget
		];
		$link		= Tag::create( "a", $label, $attributes );
		return [
			'text'		=> $link,
			'classes'	=> $this->classLeaf,
		];
	}

	protected function buildFolderItem( SplFileInfo $entry ): array
	{
		return [
			'text'			=> $entry->getFilename(),
			'id'			=> rawurlencode( $this->getPathName( $entry ) ),
			'hasChildren'	=> $this->hasChildren( $entry ),
			'classes'		=> $this->classNode,
		];
	}

	protected function getFileExtension( SplFileInfo $entry ): string
	{
		return pathinfo( $entry->getPathname(), PATHINFO_EXTENSION );
	}

	protected function getFileUrl( SplFileInfo $entry ): string
	{
		return $this->fileUrl.rawurlencode( $this->getPathName( $entry ) );
	}

	protected function getPathname( SplFileInfo $entry ): string
	{
		$path	= str_replace( "\\", "/", $entry->getPathname() );
		$base	= str_replace( "\\", "/", $this->basePath );
		return substr( $path, strlen( $base ) );
	}

	protected function hasChildren( SplFileInfo $entry ): bool
	{
		$childIndex	= new DirectoryIterator( $entry->getPathname() );
		foreach( $childIndex as $child ){
			if( str_starts_with( $child->getFilename(), '.' ) )
				continue;
			if( $child->isLink() )
				continue;
			return TRUE;
		}
		return FALSE;
	}

	protected function log( string $path, int $numberItems, int $jsonLength, int $time ): void
	{
		$message	= '%1$d {%3$d} [%4$d] (%5$d) %2$s';
		$message	= sprintf(
			$message,
			time(),
			$this->basePath.$path,
			$numberItems,
			$jsonLength,
			$time
		);
		error_log( $message."\n", 3, $this->logFile );
	}
}
