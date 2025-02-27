<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Argument Parser for Console Applications using an Automaton.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_CLI_Command
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Command;

use InvalidArgumentException;
use OutOfBoundsException;
use RuntimeException;

/**
 *	Argument Parser for Console Applications using an Automaton.
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Command
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class ArgumentParser
{
	protected const STATUS_START				= 0;
	protected const STATUS_READ_OPTION_KEY		= 1;
	protected const STATUS_READ_OPTION_VALUE	= 2;
	protected const STATUS_READ_ARGUMENT		= 3;

	protected const STATUSES					= [
		self::STATUS_START,
		self::STATUS_READ_OPTION_KEY,
		self::STATUS_READ_OPTION_VALUE,
		self::STATUS_READ_ARGUMENT,
	];

	protected bool $parsed				= FALSE;

	protected array $foundArguments		= [];
	protected array $foundOptions		= [];

	protected int $numberArguments		= 0;
	protected array $possibleOptions	= [];
	protected array $shortcuts			= [];

	protected int $status;
	protected string $buffer			= '';

	/**
	 *	Returns List of parsed Arguments.
	 *	@access		public
	 *	@return		array
	 */
	public function getArguments(): array
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
	public function getOptions(): array
	{
		if( !$this->parsed )
			throw new RuntimeException( 'Nothing parsed yet.' );
		return $this->foundOptions;
	}

	/**
	 *	Parses given Argument String and extracts Arguments and Options.
	 *	@access		public
	 *	@param		string		$string		String of Arguments and Options
	 *	@return		void
	 */
	public function parse( string $string ): void
	{
		$this->foundArguments	= [];
		$this->foundOptions		= [];

		//  realize Shortcuts
		$this->extendPossibleOptionsWithShortcuts();

		//  initiate Sign Pointer
		$position	= 0;
		//  initiate Status
		$this->status		= self::STATUS_START;
		//  initiate Argument Buffer
		$this->buffer		= "";
		//  initiate Option Buffer
		$option		= "";

		//  loop until End of String
		while( isset( $string[$position] ) ){
			//  get current Sign
			$sign	= $string[$position];
			//  increase Sign Pointer
			$position ++;

			//  handle Sign depending on Status
			switch( $this->status ){
				//  open for all Signs
				case self::STATUS_START:
					//  handle Sign
					$this->onReady( $sign, $option );
					break;
				//  open for Option Key Signs
				case self::STATUS_READ_OPTION_KEY:
					//  handle Sign
					$this->onReadOptionKey( $sign, $option );
					break;
				//  open for Option Value Signs
				case self::STATUS_READ_OPTION_VALUE:
					//  handle Sign
					$this->onReadOptionValue( $sign, $option );
					break;
				//  open for Argument Signs
				case self::STATUS_READ_ARGUMENT:
					//  handle Sign
					$this->onReadArgument( $sign );
					break;
			}
		}
		//  close open States
		$this->onEndOfLine( $option );
	}

	/**
	 *	Sets minimum Number of Arguments.
	 *	@access		public
	 *	@param		int			$number			Minimum Number of Arguments
	 *	@return		bool
	 */
	public function setNumberOfMandatoryArguments( int $number = 0 ): bool
	{
		//  no Integer given
		if( !is_int( $number ) )
			//  throw Exception
			throw new InvalidArgumentException( 'No integer given' );
		//  this Number is already set
		if( $number === $this->numberArguments )
			//  do nothing
			return FALSE;
		//  set new Argument Number
		$this->numberArguments	= $number;
		//  indicate Success
		return TRUE;
	}

	/**
	 *	Sets Map of Options with optional Regex Patterns.
	 *	@access		public
	 *	@param		array		$options		Map of Options and their Regex Patterns (or empty for a Non-Value-Option)
	 *	@return		bool
	 */
	public function setPossibleOptions( array $options ): bool
	{
		//  these Options are already set
		if( $options === $this->possibleOptions )
			//  do nothing
			return FALSE;
		//  set new Options
		$this->possibleOptions	= $options;
		//  indicate Success
		return TRUE;
	}

	/**
	 *	Sets Map between Shortcuts and before set Options.
	 *	@access		public
	 *	@param		array		$shortcuts		Array of Shortcuts for Options
	 *	@return		bool
	 */
	public function setShortcuts( array $shortcuts ): bool
	{
		//  iterate Shortcuts
		foreach( $shortcuts as $long )
			//  related Option is not set
			if( !array_key_exists( $long, $this->possibleOptions ) )
				//  throw Exception
				throw new OutOfBoundsException( 'Option "'.$long.'" not set' );
		//  these Shortcuts are already set
		if( $shortcuts === $this->shortcuts )
			//  do nothing
			return FALSE;
		//  set new Shortcuts
		$this->shortcuts	= $shortcuts;
		//  indicate Success
		return TRUE;
	}

	//  --  PROTECTED  --  //

	/**
	 *	Extends internal Option List with before set Shortcut List.
	 *	@access		protected
	 *	@return		void
	 */
	protected function extendPossibleOptionsWithShortcuts()
	{
		foreach( $this->shortcuts as $short	=> $long ){
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
		foreach( $this->shortcuts as $short	=> $long ){
			if( !array_key_exists( $short, $this->foundOptions ) )
				continue;
			$this->foundOptions[$long]	= $this->foundOptions[$short];
			unset( $this->foundOptions[$short] );
		}
	}

	/**
	 *	Handles open Argument or Option at the End of the Argument String.
	 *	@access		protected
	 *	@param		string		$option		Option Buffer Reference
	 *	@return		void
	 */
	protected function onEndOfLine( string $option ): void
	{
		if( $this->status == self::STATUS_READ_ARGUMENT )
			$this->foundArguments[]	= $this->buffer;
		//  still reading an option value
		else if( $this->status == self::STATUS_READ_OPTION_VALUE )
			//  close reading and save last option
			$this->onReadOptionValue( ' ', $option );
		else if( $this->status == self::STATUS_READ_OPTION_KEY ){
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
	 *	@return		void
	 */
	protected function onReadArgument( string $sign ): void
	{
		if( $sign == " " ){
			$this->foundArguments[]	= $this->buffer;
			$this->buffer		= "";
			$this->status		= self::STATUS_START;
			return;
		}
		$this->buffer	.= $sign;
	}

	/**
	 *	Handles current Sign in STATUS_READ_OPTION_KEY.
	 *	@access		protected
	 *	@param		string		$sign		Sign to handle
	 *	@param		string		$option		Option Buffer Reference
	 *	@return		void
	 */
	protected function onReadOptionKey( string $sign, string &$option ): void
	{
		if( in_array( $sign, [" ", ":", "="], TRUE ) ){
			if( !array_key_exists( $option, $this->possibleOptions ) )
				throw new InvalidArgumentException( 'Invalid option "'.$option.'"' );
			if( !$this->possibleOptions[$option] ){
				if( $sign !== " " )
					throw new InvalidArgumentException( 'Option "'.$option.'" cannot receive a value' );
				$this->foundOptions[$option]	= TRUE;
				$this->status	= self::STATUS_START;
			}
			else{
				$this->buffer	= "";
				$this->status	= self::STATUS_READ_OPTION_VALUE;
			}
		}
		else if( $sign !== "-" )
			$option	.= $sign;
	}

	/**
	 *	Handles current Sign in STATUS_READ_OPTION_VALUE.
	 *	@access		protected
	 *	@param		string		$sign		Sign to handle
	 *	@param		string		$option		Option Buffer Reference
	 *	@return		void
	 */
	protected function onReadOptionValue( string $sign, string $option ): void
	{
		//  illegal Option following
//		if( $sign === "-" )
//			throw new RuntimeException( 'Missing value of option "'.$option.'"' );
		//  closing value...
		if( $sign === " " ){
			//  no value
			if( !$this->buffer ){
				//  no value required/defined
				if( !$this->possibleOptions[$option] )
					//  assign true for existence
					$this->foundOptions[$option]	= TRUE;
				return;																	//
			}
			//  must match regexp
			if( $this->possibleOptions[$option] !== TRUE ){
				//  not matching
				if( !preg_match( $this->possibleOptions[$option], $this->buffer ) )
					throw new InvalidArgumentException( 'Argument "'.$option.'" has invalid value (not matching regexp: '.$this->possibleOptions[$option].')' );
			}
			$this->foundOptions[$option]	= $this->buffer;
			$this->buffer	= "";
			$this->status	= self::STATUS_START;
			return;
		}
		$this->buffer	.= $sign;
	}

	/**
	 *	Handles current Sign in STATUS_READY.
	 *	@access		protected
	 *	@param		string		$sign		Sign to handle
	 *	@param		string		$option		Option Buffer Reference
	 *	@return		void
	 */
	protected function onReady( string $sign, string &$option ): void
	{
		if( $sign == "-" ){
			$option	= "";
			$this->status	= self::STATUS_READ_OPTION_KEY;
		}
		else if( preg_match( "@[a-z\d]@i", $sign ) ){
			$this->buffer	.= $sign;
			$this->status	= self::STATUS_READ_ARGUMENT;
		}
	}
}
