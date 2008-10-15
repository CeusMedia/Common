<?php
import( 'de.ceus-media.adt.list.Dictionary' );
import( 'de.ceus-media.console.RequestReceiver' );
/**
 *	Argument Parser for Console Applications.
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
 *	@extends		ADT_OptionObject
 *	@uses			Console_RequestReceiver
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.01.2006
 *	@version		0.6
 */
/**
 *	Argument Parser for Console Applications.
 *	@package		console
 *	@extends		ADT_List_Dictionary
 *	@uses			Console_RequestReceiver
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.01.2006
 *	@version		0.6
 */
class Console_ArgumentParser extends ADT_List_Dictionary
{
	/**	@var	array		shortcuts		Associative Array of Shortcuts */
	private $shortcuts	= array();
	
	//  --  PUBLIC METHODS  --  //

	/**
	 *	Parses Arguments of called Script.
	 *	@access		public
	 *	@return		void
	 */
	public function parseArguments( $fallBackOnEmptyPair = FALSE )
	{
		$request	= new Console_RequestReceiver( $fallBackOnEmptyPair );
		$arguments	= $request->getAll();
		$script		= key( array_slice( $arguments, 0, 1 ) );
#		$this->set( "__file", __FILE__ );
		$this->set( "__class", get_class( $this ) );
		$this->set( "__script", $script );
		array_shift( $arguments );
		foreach( $arguments as $key => $value )
		{
			$reverse	= array_flip( $this->shortcuts );
			if( in_array( $key, array_keys( $this->shortcuts ) ) )
			{
				$key	= $this->shortcuts[$key];
				$this->set( $key, $value );
			}
			else
			{
				$this->set( $key, $value );
			}
#			else if( in_array( $key, array_keys( $reverse ) ) )
#			{
#				$key	= $reverse[$key];
#				$this->set( $key, $value );
#			}
		}
	}
	
	/**
	 *	Adds Shortcut.
	 *	@access		public
	 *	@param		string		$short		Key of Shortcut
	 *	@param		string		$long		Long form of Shortcut
	 *	@return		void
	 */
	public function addShortCut( $short, $long )
	{
		if( !isset( $this->shortcuts[$short] ) )
			$this->shortcuts[$short]	= $long;
		else
			trigger_error( "Shortcut '".$short."' is already set", E_USER_ERROR );
	}
	
	/**
	 *	Removes Shortcut.
	 *	@access		public
	 *	@param		string		$key		Key of Shortcut
	 *	@return		void
	 */
	public function removeShortCut( $key )
	{
		if( isset( $this->shortcuts[$key] ) )
			unset( $this->shortcuts[$key] );
	}
}
?>