<?php
import( 'de.ceus-media.console.ArgumentParser' );
/**
 *	Generic Console Application.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		console
 *	@extends		Console_ArgumentParser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.01.2006
 *	@version		0.6
 */
/**
 *	Generic Console Application.
 *	@package		console
 *	@extends		Console_ArgumentParser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.01.2006
 *	@version		0.6
 */
class Console_Application 
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$shortcuts		Array of Shortcuts to be set
	 *	@return		void
	 */
	public function __construct( $shortcuts = array(), $fallBackOnEmptyPair = FALSE )
	{
		$this->arguments	= new Console_ArgumentParser();
		foreach( $shortcuts as $key => $value )
			$this->arguments->addShortCut( $key, $value );
		$this->arguments->parseArguments( $fallBackOnEmptyPair );
		$this->main();
	}
	
	/**
	 *	Main Method called by Console Application Constructor, to be overwritten.
	 *	@access		protected
	 *	@return		void
	 */
	protected function main()
	{
		if( $this->arguments->has( "/?" ) )
			$this->showUsage();
/*		if( !$this->getOption( "a" ) )						//  asking for parameters and options
		{
			$this->showError( "Option 'a' is missing." );
			$this->showUsageLink();
		}
		echo "running application";						//  start application/service and report
*/	}

	//  --  PROTECTED METHODS  --  //
		
	/**
	 *	Prints Error Message to Console, to be overwritten.
	 *	@access		protected
	 *	@param		string		$message		Error Message to print to Console
	 *	@return		void
	 */
	protected function showError( $message, $abort = TRUE )
	{
		$message	= "\nERROR: ".$message."\n";
		if( $abort )
			die( $message );
		echo $message;
	}
	
	/**
	 *	Prints Usage Message to Console and exits Script, to be overwritten.
	 *	@access		protected
	 *	@return		void
	 */
	protected function showUsage( $message = NULL )
	{
		echo "\n";
		echo "ConsoleApplication v0.5\n";
		echo "Usage: php -f ConsoleApplication_test.php a [b] /?\n";
		echo "Options:\n";
		echo "  a\tMandatory Option\n";
		echo "  b\tOptional Option\n";
		echo "  /?\tUsage Information\n";
		if( $message )
			$this->showError( $message );
	}

	/**
	 *	Prints Usage Message Link to Console.
	 *	@access		protected
	 *	@return		void
	 */
	protected function showUsageLink()
	{
		echo "Use option /? for usage information.\n";
	}
}
?>