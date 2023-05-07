<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Basic Program to implement Console Application using Automaton Argument Parser.
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
 *	@package		CeusMedia_Common_CLI_Command
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Command;

use Exception;

/**
 *	Basic Program to implement Console Application using Automaton Argument Parser.
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Command
 *	@abstract
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
abstract class Program
{
	public const EXIT_NO		= -1;
	public const EXIT_OK		= 0;
	public const EXIT_INIT		= 1;
	public const EXIT_PARSE		= 2;
	public const EXIT_RUN		= 4;

	/**	@var	array			$arguments		Map of given Arguments */
	protected array $arguments	= [];

	/**	@var	array			$options		Map of given Options */
	protected array $options	= [];

	/**	@var	int				$exitCode		Exit Code of Main Application */
	protected int $exitCode		= self::EXIT_NO;

	protected ArgumentParser $parser;

	/**
	 *	Constructor, parses Console Call String against given Options and calls Main Method.
	 *	If this class is going to be extended, the constructor must be extended too and the parent's constructor must be called
	 *
	 *	<code>
	 *  public function __construct()
	 *  {
	 *		$numberArguments	= 1;
	 *		$options	= array(
	 *			'anything'	=> "",
	 *			'something'	=> "@.+@",
	 *		);
	 *		$shortcuts	= array(
	 *			'a'	=> "anything",
	 *			's'	=> "something",
	 *		);
	 *		parent::__construct( $options, $shortcuts, $numberArguments );
	 *	}
	 *  </code>
	 *
	 *	@access		public
	 *	@param		array		$options			Map of Options and their Regex Patterns (optional)
	 *	@param		array		$shortcuts			Array of Shortcuts for Options
	 *	@param		int			$numberArguments	Number of mandatory Arguments
	 *	@return		void
	 */
	public function __construct( array $options, array $shortcuts, int $numberArguments = 0 )
	{
		//  load Argument Parser
		$this->parser	= new ArgumentParser();
		//  set minimum Number of Arguments
		$this->parser->setNumberOfMandatoryArguments( $numberArguments );
		//  set Map of Options and Patterns
		$this->parser->setPossibleOptions( $options );
		//  set Map of Shortcuts for Options
		$this->parser->setShortcuts( $shortcuts );
	}

	/**
	 *	Returns Program Call Argument String, in this case from PHP's Variables, but can be overwritten.
	 *	@access		protected
	 *	@return		string
	 */
	protected function getArgumentString(): string
	{
		//  get console arguments from PHP
		$arguments	= $_SERVER['argv'];
		//  remove Program call itself
		array_shift( $arguments );
		//  build Argument String
		return implode( " ", $arguments );
	}

	public function getLastExitCode(): int
	{
		return $this->exitCode;
	}

	protected function handleParserException( Exception $e, int $exitCode = self::EXIT_PARSE ): void
	{
		//  show exception message and exit if set so
		$this->showError( $e->getMessage(), $exitCode );
	}

	/**
	 *	Program, to be implemented by you.
	 *	@abstract
	 *	@access		protected
	 *	@return		int			Exit code, 0 on success
	 */
	abstract protected function main(): int;

	public function run( ?string $argumentString = NULL ): int
	{
		if( is_null( $argumentString ) )
			//  get Argument String
			$argumentString	= $this->getArgumentString();
		try{
			//  parses Argument String
			$this->parser->parse( $argumentString );
			//  get parsed Arguments
			$this->arguments	= $this->parser->getArguments();
			//  get parsed Options
			$this->options		= $this->parser->getOptions();
			//  run Program and store exit code
			$this->exitCode		= $this->main();
			return $this->exitCode;
		}
		//  handle uncaught Exceptions
		catch( Exception $e ){
			$this->handleParserException( $e );
		}
		return self::EXIT_OK;
	}

	/**
	 *	Prints Error Message to Console, can be overwritten.
	 *	@access		protected
	 *	@param		string|array	$message		Error Message to print to Console
	 *	@param		integer			$exitCode		Quit program afterwards, if >= 0 (EXIT_OK|EXIT_INIT|EXIT_PARSE|EXIT_RUN), default: -1 (EXIT_NO)
	 *	@return		void
	 */
	protected function showError( $message, int $exitCode = self::EXIT_NO )
	{
		if( is_array( $message ) )
			$message	= join( PHP_EOL, $message );
		$message	= PHP_EOL.$message.PHP_EOL;
		print( $message );
		if( $exitCode != self::EXIT_NO )
			exit( $exitCode );
	}
}
