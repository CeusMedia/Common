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
class Console_Application extends Console_ArgumentParser
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$shortcuts		Array of Shortcuts to be set
	 *	@return		void
	 */
	public function __construct( $shortcuts = array())
	{
		parent::__construct();
		$this->setShortCuts( $shortcuts );
		$this->parseArguments();
		$this->main();
	}
	
	/**
	 *	Main Method called by Console Parser, to be overwritten.
	 *	@access		public
	 *	@return		void
	 */
	public function main()
	{
		if( $this->getOption( "/?" ) )
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
	 *	Sets ShortCuts of Applications's Arguments, to be overwritten.
	 *	@access		protected
	 *	@return		void
	 */
	protected function setShortCuts( $shortcuts )
	{
		foreach( $shortcuts as $key => $value )
		{
			$this->addShortCut( $key, $value );
		}
	}
		
	/**
	 *	Prints Error Message to Console, to be overwritten.
	 *	@access		protected
	 *	@param		string		$message		Error Message to print to Console
	 *	@return		void
	 */
	protected function showError( $message )
	{
		echo $message."\n";
	}
	
	/**
	 *	Prints Usage Message to Console and exits Script, to be overwritten.
	 *	@access		protected
	 *	@return		void
	 */
	protected function showUsage()
	{
		echo "\n";
		echo "ConsoleApplication v0.5\n";
		echo "Usage: php -f ConsoleApplication_test.php a [b] /?\n";
		echo "Options:\n";
		echo "  a\tMandatory Option\n";
		echo "  b\tOptional Option\n";
		echo "  /?\tUsage Information\n";
		die();
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