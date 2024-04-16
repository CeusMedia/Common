<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Handler for system environment variables.
 *
 *	Copyright (c) 2015-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common;

use CeusMedia\Common\Exception\FileNotExisting as FileNotExistingException;
use CeusMedia\Common\FS\File;
use CeusMedia\Common\FS\File\INI\Reader as IniReader;
use RuntimeException;

/**
 *	Handler for system environment variables.
 *
 *	@category		Library
 *	@package		CeusMedia_Common
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Env
{
	protected string $workingPath;

	/**
	 *	Ensures that runtime environment is headless, like crontab execution.
	 *	Being headless means, not having one of those environment variables: TERM, DISPLAY.
	 *	@access		public
	 *	@static
	 *	@return		boolean
	 *	@throws		RuntimeException	if environment is headless
	 */
	public static function checkIsHeadless(): bool
	{
		if( FALSE === self::isHeadless() )
			throw new RuntimeException( 'Available in headless environment, only' );
		return TRUE;
	}

	/**
	 *	Ensures that runtime environment is command line interface.
	 *	@access		public
	 *	@static
	 *	@return		boolean
	 *	@throws		RuntimeException	if environment is not command line interface
	 */
	public static function checkIsCli(): bool
	{
		if( FALSE === self::isCli() )
			throw new RuntimeException( 'Available in CLI environment, only' );
		return TRUE;
	}

	/**
	 *	Get environment variable.
	 *	@param		string						$key
	 *	@param		string|int|float|NULL		$default
	 *	@return		string|int|float|NULL
	 */
	public static function get( string $key, string|int|float|null $default = NULL ): string|int|float|NULL
	{
		$pair	= getenv( $key );
		if( FALSE !== $pair )
			return $pair;
		return $default;
	}

	public static function getAllAsJson(): string
	{
		$env	= getenv();
		ksort( $env );
		return json_encode( $env, JSON_PRETTY_PRINT );
	}

	/**
	 *	Indicates whether environment is headless, like crontab execution.
	 *	Being headless means, not having one of those environment variables: TERM, DISPLAY.
	 *	@access		public
	 *	@static
	 *	@return		boolean
	 */
	public static function isHeadless(): bool
	{
		return FALSE === getEnv( 'TERM' ) && FALSE === getEnv( 'DISPLAY' );
	}

	/**
	 *	Indicates whether environment is command line interface.
	 *	@access		public
	 *	@static
	 *	@return		boolean
	 */
	public static function isCli(): bool
	{
		return 'cli' === php_sapi_name();
	}

	/**
	 *	Load config file and set environment variables.
	 *	This allows to load an .env file.
	 *	@param		File|string		$file
	 *	@return		void
	 *	@throws		FileNotExistingException	if file is not existing, not readable or given path is not a file
	 *	@noinspection	PhpDocMissingThrowsInspection
	 */
	public static function load( File|string $file ): void
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		$file	= is_string( $file ) ? new File( $file ) : $file;
		$reader	= new IniReader( $file, TRUE );
		if( $reader->usesSections() )
			foreach( $reader->getSections() as $section )
				foreach( $reader->getProperties( TRUE, $section ) as $key => $value )
					Env::set( str_replace( '.', '_', $section.'_'.$key ), $value );

		else
			foreach( $reader->getProperties() as $key => $value )
				Env::set( str_replace( '.', '_', $key ), $value );
	}

	/**
	 *	Set environment variable.
	 *	@param		string				$key
	 *	@param		string|int|float	$value
	 *	@return		bool
	 */
	public static function set( string $key, string|int|float $value ): bool
	{
		$assign	= $key.'='.$value;
		if( is_string( $value ) )
			$assign	= $key.'="'.$value.'"';
		return putenv( $assign );
	}

	public function __construct()
	{
		$this->workingPath	= getCwd();
	}
}
