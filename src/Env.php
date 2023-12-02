<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Handler for system environment variables.
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
 *	@package		CeusMedia_Common
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common;

use RuntimeException;

/**
 *	Handler for system environment variables.
 *
 *	@category		Library
 *	@package		CeusMedia_Common
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Env
{
	protected string $workingPath;

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

	public function __construct()
	{
		$this->workingPath	= getCwd();
	}
}
