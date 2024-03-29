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
	/**	@var	array		shortcuts		Associative Array of Shortcuts */
	private array $shortcuts	= [];

	//  --  PUBLIC METHODS  --  //

	/**
	 *	Adds Shortcut.
	 *	@access		public
	 *	@param		string		$short		Key of Shortcut
	 *	@param		string		$long		Long form of Shortcut
	 *	@return		void
	 */
	public function addShortCut( string $short, string $long )
	{
		if( !isset( $this->shortcuts[$short] ) )
			$this->shortcuts[$short]	= $long;
		else
			trigger_error( "Shortcut '".$short."' is already set", E_USER_ERROR );
	}

	/**
	 *	Parses Arguments of called Script.
	 *	@access		public
	 *	@return		void
	 */
	public function parseArguments( bool $fallBackOnEmptyPair = FALSE )
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
	 *	@return		void
	 */
	public function removeShortCut( string $key )
	{
		if( isset( $this->shortcuts[$key] ) )
			unset( $this->shortcuts[$key] );
	}
}
