<?php
/**
 *	Basic Program to implement Console Application using Automaton Argument Parser.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		Console.Command
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Basic Program to implement Console Application using Automaton Argument Parser.
 *	@category		cmClasses
 *	@package		Console.Command
 *	@abstract
 *	@uses			Console_Command_ArgumentParser
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
abstract class Console_Command_Program
{
	/**	@var	array		$arguments		Map of given Arguments */
	protected $arguments	= NULL;
	/**	@var	array		$arguments		Map of given Options */
	protected $options		= NULL;
	/**	@var	array		$exitCode		Exit Code of Main Application */
	protected $exitCode		= NULL;
	
	/**
	 *	Constructor, parses Console Call String against given Options and calls Main Method.
	 *	If this class is going to be extended, the constructor must be extend too and the parents constructor must be called
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
	public function __construct( $options, $shortcuts, $numberArguments = 0 )
	{
		$this->parser	= new Console_Command_ArgumentParser();					//  load Argument Parser
		$this->parser->setNumberOfMandatoryArguments( $numberArguments );		//  set minimum Number of Arguments										//  
		$this->parser->setPossibleOptions( $options );							//  set Map of Options and Patterns
		$this->parser->setShortcuts( $shortcuts );								//  set Map of Shortcuts for Options
	}

	public function run( $argumentString = NULL ){
		if( is_null( $argumentString ) )
			$argumentString	= $this->getArgumentString();						//  get Argument String
		try
		{
			$this->parser->parse( $argumentString );							//  parses Argument String
			$this->arguments	= $this->parser->getArguments();				//  get parsed Arguments
			$this->options		= $this->parser->getOptions();					//  get parsed Options
			$this->exitCode		= $this->main();								//  run Program and store exit code
			return $this->exitCode;
		}
		catch( Exception $e )													//  handle uncatched Exceptions
		{
			$this->handleParserException( $e );
		}
		
	}

	public function getLastExitCode(){
		return $this->exitCode;
	}
	/**
	 *	Returns Program Call Argument String, in this case from PHP's Variables, but can be overwritten.
	 *	@access		protected
	 *	@return		string
	 */
	protected function getArgumentString()
	{
		$arguments	= $_SERVER['argv'];											//  get Console Arguments from PHP
		array_shift( $arguments );												//  remove Programm Call itself
		$string		= implode( " ", $arguments );								//  build Argument String
		return $string;
	}
	
	protected function handleParserException( Exception $e )
	{
		$this->showError( $e->getMessage() );									//  just show Exception Message
	}

	/**
	 *	Program, to be implemented by you.
	 *	@abstract
	 *	@access		protected
	 *	@return		mixed			can return a String or an Integer Exit Code.
	 */
	abstract protected function main();
	
	/**
	 *	Prints Error Message to Console, can be overwritten.
	 *	@access		protected
	 *	@param		string		$message		Error Message to print to Console
	 *	@param		bool		$abort			Quit Program afterwards
	 *	@return		void
	 */
	protected function showError( $message, $abort = TRUE )
	{
		$message	= "\n".$message."\n";
		if( $abort )
			die( $message );
		echo $message;
	}
}
?>