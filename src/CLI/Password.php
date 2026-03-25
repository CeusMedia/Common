<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

/**
 *	Prompt for hidden input, like passwords.
 *
 *	Copyright (c) 2024-2025 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2024-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI;

use CeusMedia\Common\Exception\NotSupported as NotSupportedException;
use CeusMedia\Common\Exception\Runtime as RuntimeException;

/**
 *	Prompt for hidden input, like passwords.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2024-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Password
{
	protected string $prompt	= 'Enter Password: ';

	/**
	 *	@param		string|NULL			$prompt
	 *	@return		self
	 *	@throws		NotSupportedException	on Windows
	 *	@throws		RuntimeException		if invoking bash failed
	 */
	public static function getInstance( string $prompt = NULL ): self
	{
		return new self( $prompt );
	}

	/**
	 *	@param		string|NULL				$prompt
	 *	@throws		NotSupportedException	on Windows
	 *	@throws		RuntimeException		if invoking bash failed
	 */
	public function __construct( string $prompt = NULL )
	{
		if( str_starts_with( PHP_OS, 'WIN' ) )
			throw new NotSupportedException( 'Not supported on Windows' );

		$command	= "/usr/bin/env bash -c 'echo OK'";
		if( 'OK' !== rtrim( shell_exec( $command ) ) )
			throw new RuntimeException( 'Cant invoke bash' );

		if( NULL !== $prompt )
			$this->prompt	= $prompt;
	}

	/**
	 *	Asks for hidden input.
	 *	@return		string
	 */
	public function ask(): string
	{
		$command = "/usr/bin/env bash -c 'read -s -p \""
			. addslashes( $this->prompt )
			. "\" mypassword && echo \$mypassword'";
		$password	= rtrim( shell_exec( $command ) );
		echo PHP_EOL;
		return $password;
	}
}