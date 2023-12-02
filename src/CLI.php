<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Command Line Interface.
 *
 *	Copyright (c) 2015-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common;

use CeusMedia\Common\Alg\Text\CamelCase;
use CeusMedia\Common\Alg\UnitFormater;
use CeusMedia\Common\CLI\Dimensions as CliDimensions;
use CeusMedia\Common\Exception\Deprecation as DeprecationException;
use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\FS\File\Permissions as FilePermissions;
use CeusMedia\Common\FS\Folder;
use CeusMedia\Common\UI\DevOutput;
use CeusMedia\Common\UI\Text;
use RuntimeException;

/**
 *	Command Line Interface.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class CLI
{
	protected string $base;

	protected ?object $logger		= NULL;

	protected object $size;

	protected string $logFile		= 'cli.log';

	static protected array $mimeTypeLabels	= [
		'application/xml'		=> 'XML',
		'text/plain'			=> 'Text',
		'text/x-php'			=> 'PHP',
		'text/x-makefile'		=> 'MAKE',
		'text/x-shellscript'	=> 'Shell',
	];

	public function __construct()
	{
		$this->base	= getCwd();
		$this->size	= CliDimensions::getSize();
		Text::$defaultLineLength	= $this->size->width - 1;
	}

	/**
	 *	Ensures that runtime environment is headless, like crontab execution.
	 *	Being headless means, not having one of those environment variables: TERM, DISPLAY.
	 *	@access		public
	 *	@static
	 *	@return		boolean
	 *	@throws		DeprecationException
	 */
	public static function checkIsHeadless(): bool
	{
		Deprecation::getInstance()
			->setExceptionVersion( '1.1' )
			->setErrorVersion( '1.0' )
			->message( 'Use Env::checkIsHeadless or Env::isHeadless instead' );
		return Env::checkIsHeadless();
	}

	public static function checkIsCli(): bool
	{
		Deprecation::getInstance()
			->setExceptionVersion( '1.1' )
			->setErrorVersion( '1.0' )
			->message( 'Use Env::checkIsHeadless or Env::isCli instead' );
		return Env::checkIsCli();
	}

	public static function charTable( int $from = 2500, int $to = 2600 ): void
	{
		print PHP_EOL;
		for($i=$from/10; $i<$to/10; $i++){
			print 'x'.$i.'0  ';
			for($j=0; $j<16; $j++){
				$number	= $i.dechex( $j);
				print ' '.Text::char( 'x'.$number );
			}
			print PHP_EOL;
		}
	}

	public static function error( array|string|null $messages = NULL ): void
	{
		$isCli	= Env::isCLi();
		if( !is_array( $messages ) )
			$messages	= [$messages];
		foreach( $messages as $message ){
			if( is_null( $message ) )
				continue;
			$type		= gettype( $message );
			if( in_array( $type, ['string', 'integer'] ) ){
				if( strlen( trim( $message ) ) ){
					$message	= trim( $message );
					$isCli ? fwrite( STDERR, $message ) : print( $message );
				}
			}
			else{
				$message	= (new DevOutput)->printMixed( $message, NULL, NULL, NULL, NULL, TRUE );
				$isCli ? fwrite( STDERR, $message ) : print( $message );
			}
		}
		$isCli ? fwrite( STDERR, PHP_EOL ) : print( PHP_EOL );
	}

	/**
	 * @param string[]|string|NULL $messages
	 * @param bool $newLine
	 * @return void
	 */
	public static function out( array|string|null $messages = NULL, bool $newLine = TRUE ): void
	{
		$isCli	= Env::isCLi();
		if( !is_array( $messages ) )
			$messages	= [$messages];
		foreach( $messages as $message ){
			if( is_null( $message ) )
				continue;
			$type		= gettype( $message );
			if( in_array( $type, ['string', 'integer'] ) ){
				if( strlen( trim( $message ) ) ){
					$isCli ? fwrite( STDOUT, $message ) : print( $message );
				}
			}
			else{
				$message	= (new DevOutput)->printMixed( $message, NULL, NULL, NULL, NULL, TRUE );
				$isCli ? fwrite( STDOUT, $message ) : print( $message );
			}
		}
		if( $newLine )
			$isCli ? fwrite( STDOUT, PHP_EOL ) : print( PHP_EOL );
	}

	public function getColors(): int
	{
		return $this->size->colors;
	}

	public function getHeight(): int
	{
		return $this->size->getHeight();
	}

	public function getWidth(): int
	{
		return $this->size->getWidth();
	}

	public function log( string $message ): bool
	{
		if( is_object( $this->logger ) ){
			$this->logger->log( $message );
			return TRUE;
		}
		return error_log( date( 'Y-m-d H:i:s' ).': '.$message.PHP_EOL, 3, $this->logFile );
	}

	 /**
	 *	...
	 *	@param		string|NULL		$path
	 *	@param		bool			$mimeType
	 *	@return		void
	 *	@throws		IoException
	 */
	public function ls( string $path = NULL, bool $mimeType = TRUE ): void
	{
		$path	??= '.';
		$path	= $this->realizePath( $path );
		$f		= new Folder( $path );
		UnitFormater::$unitBytes[0]	= ' B';
		$folders	= $f->index( FS::TYPE_FOLDER, SORT_NATURAL | SORT_FLAG_CASE );
		$files		= $f->index( FS::TYPE_FILE | FS::TYPE_LINK, SORT_NATURAL | SORT_FLAG_CASE );
		$freeSize	= Text::$defaultLineLength - 40;
		$mimeType	= $mimeType && Text::$defaultLineLength > 80;
		if( $mimeType )
			$freeSize	-= 20;
		if( $folders->count() || $files->count() ){
			$headType	= $mimeType ? Text::padLeft( 'Type', 20 ) : '';
			$headSize	= Text::padLeft( 'Size', 12 );
			$headPerm	= Text::padLeft( 'Rights   ', 12 );
			$headDate	= Text::padRight( '  Date', 16 );
			if( $folders->count() && $files->count() )
				$heading	= '%1$d Folders and %2$d Files or Links:';
			else if( $folders->count() )
				$heading	= '%1$d Folders:';
			else
				$heading	= '%2$d Files or Links:';
			$heading	= sprintf( $heading, $folders->count(), $files->count() );
			$heading	= Text::padRight( $heading, $freeSize );
			CLI::out( $heading.$headType.$headSize.$headPerm.$headDate );
			CLI::out( Text::line( Text::char( 'x2550' ) ) );
		}

		if( $folders->count() !== 0 ){
			foreach( $folders as $item ){
				$name	= $item->getName();
				$sfo	= $item->count( FS::TYPE_FOLDER, TRUE );
				$sfi	= $item->count( FS::TYPE_FILE | FS::TYPE_LINK, TRUE );
				$perm	= 'd'.FilePermissions::getStringFromFile( $item->getPathName() );
				$date	= date( 'y-m-d H:i', $item->getTime() );
				CLI::out( join( [
					Text::padRight( $name, $freeSize ),
					$mimeType ? Text::padLeft( '[folder]', 20 ) : '',
					Text::padLeft( $sfo.'/'.$sfi, 12 ),
					Text::padLeft( $perm, 12 ),
					Text::padLeft( $date, 16 )
				] ) );
			}
		}
		if( $files->count() !== 0 ){
			if( $folders->count() )
				CLI::out( Text::line( Text::char( 'x2500' ) ) );
			foreach( $files as $item ){
				$name	= $item->getName();
				$mime	= self::shortenMimeType( $item->getMimeType() );
				$size	= Text::formatBytes( $item->getSize() );
				$perm	= FilePermissions::getStringFromFile( $item->getPathName() );
				$date	= date( 'y-m-d H:i', $item->getTime() );
				CLI::out( join( [
					Text::padRight( $name, $freeSize ),
					$mimeType ? Text::padLeft( $mime, 20 ) : '',
					Text::padLeft( $size, 12 ),
					Text::padLeft( $perm, 12 ),
					Text::padLeft( $date, 16 )
				] ) );
			}
		}
	}

	static protected function shortenMimeType( string $mimeType ): string
	{
		$mimeType	= trim( $mimeType );
		if( !strlen( $mimeType ) )
			return '';
		if( !preg_match( '/.+\/.+/', $mimeType ) )
			return $mimeType;
		if( array_key_exists( $mimeType, self::$mimeTypeLabels ) )
			return self::$mimeTypeLabels[$mimeType];
		[$topic, $type]	= explode( '/', $mimeType, 2 );
		$short	= NULL;
		$type	= preg_replace( '/^x-/', '', $type );
		if( $topic === 'text' )
			$short = 'T';
		else if( $topic === 'application' )
			$short = 'A';
		$type	= CamelCase::encode( str_replace( '-', ' ', $type ) );
		return $short ? $short.':'.$type : $topic.'/'.$type;
	}

	/**
	 *	...
	 *	@param		string			$path
	 *	@return		string
	 *	@throws		IoException
	 */
	protected function realizePath( string $path ): string
	{
//		if( !strlen( trim( $path ) )
//			$path	= '.';
		$first	= substr( $path, 0, 1 );
		switch( $first ){
			case '/':
				break;
			case '~':
				//	@todo		implement!
				break;
			default:
				$path	= $this->base.'/'.$path;
		}
		if( file_exists( $path ) )
			return realpath( $path );
		throw new IoException( 'Path is not existing', 0, $path );
	}
}
