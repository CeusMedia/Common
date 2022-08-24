<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Generic Console Application.
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
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
namespace CeusMedia\Common\CLI;

use CeusMedia\Common\CLI;

/**
 *	Generic Console Application.
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Application
{
	/** @var	ArgumentParser		$arguments  */
	protected $arguments;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$shortcuts		Array of Shortcuts to be set
	 *	@return		void
	 */
	public function __construct( array $shortcuts = [], bool $fallBackOnEmptyPair = FALSE, bool $handleNoneCLI = TRUE )
	{
		CLI::checkIsCLi( $handleNoneCLI );
		$this->arguments	= new ArgumentParser();
		foreach( $shortcuts as $key => $value )
			$this->arguments->addShortCut( $key, $value );
		$this->arguments->parseArguments( $fallBackOnEmptyPair );
		$this->main();
	}

	/**
	 *	Main Method called by Console Application Constructor, to be overridden.
	 *	@access		protected
	 *	@return		void
	 */
	protected function main()
	{
		if( join( $this->arguments->get( 'commands' ) ) === 'help' )
			$this->showUsage();
	}

	//  --  PROTECTED METHODS  --  //

	/**
	 *	Prints Error Message to Console, to be overridden.
	 *	@access		protected
	 *	@param		string		$message		Error Message to print to Console
	 *	@return		void
	 */
	protected function showError( string $message, bool $abort = TRUE )
	{
		CLI::error( $message );
		if( $abort )
			die( $message );
	}

	/**
	 *	Prints Usage Message to Console and exits Script, to be overridden.
	 *	@access		protected
	 *	@param		string|NULL		$message		Message to show below usage lines
	 *	@return		void
	 */
	protected function showUsage( ?string $message = NULL )
	{
		CLI::out();
		CLI::out( 'Console Application' );
		CLI::out();
		CLI::out( 'Usage: ./cli_app.php a [b]' );
		CLI::out( 'Options:' );
		CLI::out( '  a			Mandatory Option' );
		CLI::out( '    help		show help' );
		CLI::out( '  b			Optional Option' );
		if( $message )
			$this->showError( $message );
	}

	/**
	 *	Prints Usage Message Link to Console, to be overridden.
	 *	@access		protected
	 *	@return		void
	 */
	protected function showUsageLink()
	{
		CLI::out( 'Use command "help" for usage information.' );
	}
}
