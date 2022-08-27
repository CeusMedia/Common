<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Base ZIP File implementation.
 *
 *	Copyright (c) 2015-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Arc;

use RuntimeException;
use ZipArchive;

/**
 *	Base ZIP File implementation.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			ATTENTION!!! This is a hydrid of existing gzip class and ZIP injection.
 *	@todo			TEST!!!
 *	@todo			code doc
 */
class Zip
{
	static public $errors	= [
		0	=> 'No error',
		1	=> 'Multi-disk zip archives not supported',
		2	=> 'Renaming temporary file failed',
		3	=> 'Closing zip archive failed',
		4	=> 'Seek error',
		5	=> 'Read error',
		6	=> 'Write error',
		7	=> 'CRC error',
		8	=> 'Containing zip archive was closed',
		9	=> 'No such file',
		10	=> 'File already exists',
		11	=> 'Can\'t open file',
		12	=> 'Failure to create temporary file',
		13	=> 'Zlib error',
		14	=> 'Malloc failure',
		15	=> 'Entry has been changed',
		16	=> 'Compression method not supported',
		17	=> 'Premature EOF',
		18	=> 'Invalid argument',
		19	=> 'Not a zip archive',
		20	=> 'Internal error',
		21	=> 'Zip archive inconsistent',
		22	=> 'Can\'t remove file',
		23	=> 'Entry has been deleted',
	];

	protected $fileName;

	protected $archive;

	public function __construct( string $fileName )
	{
		$this->checkSupport();
		$this->archive	= new ZipArchive();
		$this->setFileName( $fileName );
	}

	public function addFile( string $fileName, ?string $localFileName = NULL ): bool
	{
		$this->checkFileOpened();
		return $this->archive->addFile( $fileName, $localFileName );
	}

	protected function checkFileOpened()
	{
		if( !$this->fileName )
			throw new RuntimeException( 'No archive file opened' );
	}

	protected function checkSupport( bool $throwException = TRUE ): bool
	{
		$hasZipSupport	= self::hasSupport();
		if( $throwException && !$hasZipSupport )
			throw new RuntimeException( 'PHP extension for ZIP support is not loaded' );
		return $hasZipSupport;
	}

	public function getFileName(): string
	{
		return $this->fileName;
	}

	static public function hasSupport(): bool
	{
		return class_exists( 'ZipArchive' );
	}

	public function save( ?string $fileName = NULL ): bool
	{
		$instance	= $this;
		if( !is_null( $fileName ) ){
			$instance	= clone $this;
			$instance->setFileName( $fileName );
		}
		return $instance->archive->close();
	}

	public function setFileName( string $fileName )
	{
		$this->fileName	= $fileName;
		$this->archive->open( $fileName, ZipArchive::CREATE );
	}

	public function index(): array
	{
		$this->checkFileOpened();
		$list	= [];
		for( $i = 0; $i < $this->archive->numFiles; $i++ ){
			$stat = $this->archive->statIndex( $i );
			$list[]	= $stat;
		}
		return $list;
	}
}
