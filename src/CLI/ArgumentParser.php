<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Argument Parser for Console Applications.
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
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI;

use CeusMedia\Common\ADT\Collection\Dictionary;
use DomainException;
use RuntimeException;

/**
 *	Argument Parser for Console Applications.
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class ArgumentParser extends Dictionary
{
	public static string $delimiterAssign	= '=';

	/**	@var	array		shortcuts		Associative Array of Shortcuts */
	private array $shortcuts	= [];

	//  --  PUBLIC METHODS  --  //

	/**
	 * Parses string or array into arguments and parameters, resolving shortcuts.
	 *	@param		array|string		$input
	 *	@param		array				$shortcuts
	 *	@param		bool				$fallBackOnEmptyPair
	 *	@return		array[]
	 */
	public static function parse( array|string $input, array $shortcuts = [], bool $fallBackOnEmptyPair = FALSE ): array
	{
		$commands	= [];
		$parameters	= [];
		$count		= 0;
		$input		= is_string( $input ) ? preg_split( '/ +/', $input ) : $input;
		if( !$fallBackOnEmptyPair && in_array( 'fallBackOnEmptyPair', $input, TRUE ) )
			$fallBackOnEmptyPair	= TRUE;
		foreach( $input as $argument ){
			if( substr_count( $argument, self::$delimiterAssign ) || $fallBackOnEmptyPair ){
				$parts	= explode( self::$delimiterAssign, $argument, 2 );
				$key	= array_shift( $parts );
				$value	= $parts ? $parts[0] : NULL;
				$parameters[$key]	= $value;
			}
			else
				$commands[$count++]	= $argument;
		}

		$list		= [];
		foreach( $parameters as $key => $value ){
			if( array_key_exists( $key, $shortcuts ) )
				$key	= $shortcuts[$key];
			$list[$key]	= $value;
		}

		return [$commands, $list];
	}

	/**
	 *	Returns parse arguments and parameters from current CLI call.
	 *	Also returns call invokable and current path.
	 *	Returns [$arguments, $parameters, $invokable, $path].
	 *	For example, ```php cli.php command1 command2 option1=value1``` will enlist 2 commands, 1 parameter and ```cli.php``` as invokable.
	 *	@param		array		$shortcuts
	 *	@param		bool		$fallBackOnEmptyPair
	 *	@return		array
	 */
	public static function parseFromCurrentCall( array $shortcuts = [], bool $fallBackOnEmptyPair = FALSE ): array
	{
		global $argv;
		if( !is_array( $argv ) )
			throw new RuntimeException( 'Missing arguments' );
		list( $arguments, $parameters )	= self::parse( $argv, $shortcuts, $fallBackOnEmptyPair );
		$call	= array_shift( $arguments );
		return [$arguments, $parameters, $call, getcwd()];
	}

	/**
	 *	Adds Shortcut.
	 *	@access		public
	 *	@param		string		$short		Key of Shortcut
	 *	@param		string		$long		Long form of Shortcut
	 *	@return		self
	 */
	public function addShortCut( string $short, string $long ): self
	{
		if( !isset( $this->shortcuts[$short] ) )
			$this->shortcuts[$short]	= $long;
		else
			throw new DomainException( "Shortcut '".$short."' is already set" );
		return $this;
	}

	/**
	 *	Parses Arguments of called Script.
	 *	@access		public
	 *	@param		bool		$fallBackOnEmptyPair
	 *	@return		void
	 *	@deprecated	use ::parse or ::parseFromCurrentCall instead
	 */
	public function parseArguments( bool $fallBackOnEmptyPair = FALSE ): void
	{
		$request	= new RequestReceiver( $fallBackOnEmptyPair );
		$commands	= [];
		$parameters	= [];
		foreach( $request->getAll() as $key => $value ){
			if( is_numeric( $key ) )
				$commands[]	= $value;
			else
				$parameters[$key]	= $value;
		}
		$script		= array_shift( $commands );
		$list		= [];
		foreach( $parameters as $key => $value ){
			if( array_key_exists( $key, $this->shortcuts ) )
				$key	= $this->shortcuts[$key];
			$list[$key]	= $value;
		}
#		$this->set( "__file", __FILE__ );
#		$this->set( "__class", get_class( $this ) );
		$this->set( "path", getcwd() );
		$this->set( "script", $script );
		$this->set( "commands", $commands );
		$this->set( "parameters", $list );
	}

	/**
	 *	Removes Shortcut.
	 *	@access		public
	 *	@param		string		$key		Key of Shortcut
	 *	@return		self
	 */
	public function removeShortCut( string $key ): self
	{
		if( isset( $this->shortcuts[$key] ) )
			unset( $this->shortcuts[$key] );
		return $this;
	}
}
