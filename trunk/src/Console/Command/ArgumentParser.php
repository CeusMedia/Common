<?php
/**
 *	Argument Parser for Console Applications using an Automaton.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Argument Parser for Console Applications using an Automaton.
 *	@category		cmClasses
 *	@package		Console.Command
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class Console_Command_ArgumentParser
{
	const STATUS_START				= 0; 
	const STATUS_READ_OPTION_KEY	= 1; 
	const STATUS_READ_OPTION_VALUE	= 2;
	const STATUS_READ_ARGUMENT		= 3; 
	
	protected $parsed				= FALSE;

	protected $foundArguments		= array();
	protected $foundOptions			= array();

	protected $numberArguments		= 0;
	protected $possibleOptions		= array();
	protected $shortcuts			= array();

	/**
	 *	Extends internal Option List with afore set Shortcut List.
	 *	@access		protected
	 *	@return		void
	 */
	protected function extendPossibleOptionsWithShortcuts()
	{
		foreach( $this->shortcuts as $short	=> $long )
		{
			if( !isset( $this->possibleOptions[$long] ) )
				throw new InvalidArgumentException( 'Invalid shortcut to not existing option "'.$long.'" .' );
			$this->possibleOptions[$short]	= $this->possibleOptions[$long];
		}
	}

	/**
	 *	Resolves parsed Option Shortcuts.
	 *	@access		protected
	 *	@return		void
	 */
	protected function finishOptions()
	{
		foreach( $this->shortcuts as $short	=> $long )
		{
			if( !array_key_exists( $short, $this->foundOptions ) )
				continue;
			$this->foundOptions[$long]	= $this->foundOptions[$short];
			unset( $this->foundOptions[$short] );
		}
	}

	/**
	 *	Returns List of parsed Arguments.
	 *	@access		public
	 *	@return		array
	 */
	public function getArguments()
	{
		if( !$this->parsed )
			throw new RuntimeException( 'Nothing parsed yet.' );
		return $this->foundArguments;
	}

	/**
	 *	Returns List of parsed Options.
	 *	@access		public
	 *	@return		array
	 */
	public function getOptions()
	{
		if( !$this->parsed )
			throw new RuntimeException( 'Nothing parsed yet.' );
		return $this->foundOptions;
	}

	/**
	 *	Handles open Argument or Option at the End of the Argument String.
	 *	@access		protected
	 *	@param		int			$status		Status
	 *	@param		string		$buffer		Argument Buffer
	 *	@param		string		$option		Option Buffer
	 *	@return		void
	 */
	protected function onEndOfLine( $status, $buffer, $option )
	{
		if( $status == self::STATUS_READ_ARGUMENT )
			$this->foundArguments[]	= $buffer;
		else if( $status == self::STATUS_READ_OPTION_VALUE )
			$this->foundOptions[$option]	= $buffer;
		else if( $status == self::STATUS_READ_OPTION_KEY )
		{
			if( !array_key_exists( $option, $this->possibleOptions ) )
				throw new InvalidArgumentException( 'Invalid option: '.$option.'.' );
			if( $this->possibleOptions[$option] )
				throw new RuntimeException( 'Missing value of option "'.$option.'".' );
			$this->foundOptions[$option]	= TRUE;
		}
		if( count( $this->foundArguments ) < $this->numberArguments )
			throw new RuntimeException( 'Missing argument.' );
		$this->finishOptions();
		$this->parsed	= TRUE;
	}

	/**
	 *	Handles current Sign in STATUS_READ_ARGUMENT.
	 *	@access		protected
	 *	@param		string		$sign		Sign to handle
	 *	@param		int			$status		Status Reference
	 *	@param		string		$buffer		Argument Buffer Reference
	 *	@param		string		$option		Option Buffer Reference
	 *	@return		void
	 */
	protected function onReadArgument( $sign, &$status, &$buffer )
	{
		if( $sign == " " )
		{
			$this->foundArguments[]	= $buffer;
			$buffer		= "";
			$status		= self::STATUS_START;
			return;
		}
		$buffer	.= $sign;
	}

	/**
	 *	Handles current Sign in STATUS_READ_OPTION_KEY.
	 *	@access		protected
	 *	@param		string		$sign		Sign to handle
	 *	@param		int			$status		Status Reference
	 *	@param		string		$buffer		Argument Buffer Reference
	 *	@param		string		$option		Option Buffer Reference
	 *	@return		void
	 */
	protected function onReadOptionKey( $sign, &$status, &$buffer, &$option )
	{
		if( in_array( $sign, array( " ", ":", "=" ) ) )
		{
			if( !array_key_exists( $option, $this->possibleOptions ) )
				throw new InvalidArgumentException( 'Invalid option "'.$option.'"' );
			if( !$this->possibleOptions[$option] )
			{
				if( $sign !== " " )
					throw new InvalidArgumentException( 'Option "'.$option.'" cannot receive a value' );
				$this->foundOptions[$option]	= TRUE;
				$status	= self::STATUS_START;
			}
			else
			{
				$buffer	= "";
				$status	= self::STATUS_READ_OPTION_VALUE;
			}
		}
		else if( $sign !== "-" )
			$option	.= $sign;
	}

	/**
	 *	Handles current Sign in STATUS_READ_OPTION_VALUE.
	 *	@access		protected
	 *	@param		string		$sign		Sign to handle
	 *	@param		int			$status		Status Reference
	 *	@param		string		$buffer		Argument Buffer Reference
	 *	@param		string		$option		Option Buffer Reference
	 *	@return		void
	 */
	protected function onReadOptionValue( $sign, &$status, &$buffer, &$option )
	{
		if( $sign == "-" )																//  illegal Option following
			throw new RuntimeException( 'Missing value of option "'.$option.'"' );
		if( $sign == " " )																//  closing value...
		{
			if( !$buffer ){																//  no value
				if( !$this->possibleOptions[$option] )									//  no value required/defined
					$this->foundOptions[$option]	= TRUE;								//  assign true for existance
				return;																	//  
			}
			if( $this->possibleOptions[$option] !== TRUE )								//  must match regexp
				if( !preg_match( $this->possibleOptions[$option], $buffer ) )			//  not matching
					throw new InvalidArgumentException( 'Argument "'.$option.'" has invalid value' );
			$this->foundOptions[$option]	= $buffer;
			$buffer	= "";
			$status	= self::STATUS_START;
			return;
		}
		$buffer	.= $sign;
	}

	/**
	 *	Handles current Sign in STATUS_READY.
	 *	@access		protected
	 *	@param		string		$sign		Sign to handle
	 *	@param		int			$status		Status Reference
	 *	@param		string		$buffer		Argument Buffer Reference
	 *	@param		string		$option		Option Buffer Reference
	 *	@return		void
	 */
	protected function onReady( $sign, &$status, &$buffer, &$option )
	{
		if( $sign == "-" )
		{
			$option	= "";
			$status	= self::STATUS_READ_OPTION_KEY;
		}
		else if( preg_match( "@[a-z0-9]@i", $sign ) )
		{
			$buffer	.= $sign;
			$status	= self::STATUS_READ_ARGUMENT;
		}
	}

	/**
	 *	Parses given Argument String and extracts Arguments and Options.
	 *	@access		public
	 *	@param		string		$string		String of Arguments and Options
	 *	@return		void
	 */
	public function parse( $string )
	{
		if( !is_string( $string ) )														//  no String given
			throw new InvalidArgumentException( 'Given argument is not a string' );		//  throw Exception

		$this->extendPossibleOptionsWithShortcuts();									//  realize Shortcuts

		$position	= 0;																//  initiate Sign Pointer
		$status		= self::STATUS_START;												//  initiate Status
		$buffer		= "";																//  initiate Argument Buffer
		$option		= "";																//  initiate Option Buffer
		
		while( isset( $string[$position] ) )											//  loop until End of String
		{
			$sign	= $string[$position];												//  get current Sign
			$position ++;																//  increase Sign Pointer

			switch( $status )															//  handle Sign depending on Status
			{
				case self::STATUS_START:												//  open for all Signs
					$this->onReady( $sign, $status, $buffer, $option );					//  handle Sign
					break;
				case self::STATUS_READ_OPTION_KEY:										//  open for Option Key Signs
					$this->onReadOptionKey( $sign, $status, $buffer, $option );			//  handle Sign
					break;
				case self::STATUS_READ_OPTION_VALUE:									//  open for Option Value Signs
					$this->onReadOptionValue( $sign, $status, $buffer, $option );		//  handle Sign
					break;
				case self::STATUS_READ_ARGUMENT:										//  open for Argument Signs
					$this->onReadArgument( $sign, $status, $buffer );					//  handle Sign
					break;
			}
		}
		$this->onEndOfLine( $status, $buffer, $option );								//  close open States
	}
	
	/**
	 *	Sets mininum Number of Arguments.
	 *	@access		public
	 *	@param		int			$number			Minimum Number of Arguments
	 *	@return		bool
	 */
	public function setNumberOfMandatoryArguments( $number = 0 )
	{
		if( !is_int( $number ) )														//  no Integer given
			throw new InvalidArgument( 'No integer given' );							//  throw Exception
		if( $number === $this->numberArguments )										//  this Number is already set
			return FALSE;																//  do nothing
		$this->numberArguments	= $number;												//  set new Argument Number
		return TRUE;																	//  indicate Success
	}

	/**
	 *	Sets Map of Options with optional Regex Patterns.
	 *	@access		public
	 *	@param		array		$options		Map of Options and their Regex Patterns (or empty for a Non-Value-Option)
	 *	@return		bool
	 */
	public function setPossibleOptions( $options )
	{
		if( !is_array( $options ) )														//  no Array given
			throw InvalidArgumentException( 'No array given.' );						//  throw Exception
		if( $options === $this->possibleOptions )										//  threse Options are already set
			return FALSE;																//  do nothing
		$this->possibleOptions	= $options;												//  set new Options
		return TRUE;																	//  indicate Success
	}

	/**
	 *	Sets Map between Shortcuts and afore set Options.
	 *	@access		public
	 *	@param		array		$shortcuts		Array of Shortcuts for Options
	 *	@return		bool
	 */
	public function setShortcuts( $shortcuts )
	{
		if( !is_array( $shortcuts ) )													//  no Array given
			throw InvalidArgumentException( 'No array given.' );						//  throw Exception
		foreach( $shortcuts as $short => $long )										//  iterate Shortcuts
			if( !array_key_exists( $long, $this->possibleOptions ) )					//  related Option is not set
				throw new OutOfBoundsException( 'Option "'.$long.'" not set' );			//  throw Exception
		if( $shortcuts === $this->shortcuts )											//  these Shortcuts are already set
			return FALSE;																//  do nothing
		$this->shortcuts	= $shortcuts;												//  set new Shortcuts
		return TRUE;																	//  indicate Success
	}
}
?>