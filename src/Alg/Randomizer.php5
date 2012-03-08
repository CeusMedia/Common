<?php
/**
 *	Randomizer supporting different sign types.
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
 *	@package		Alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.01.2006
 *	@version		$Id$
 */
/**
 *	Randomizer supporting different sign types.
 *	@category		cmClasses
 *	@package		Alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.01.2006
 *	@version		$Id$
 */
class Alg_Randomizer
{
	/**	@var		string		$digits			String with Digits */
	public $digits				= "0123456789";
	/**	@var		string		$larges		String with large Letters */
	public $larges				= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	/**	@var		string		$smalls			String with small Letters */
	public $smalls				= "abcdefghijklmnopqrstuvwxyz";
	/**	@var		string		$signs			String with Signs */
	public $signs				= '.:_-+*=/\!§$%&(){}[]#@?~';
	/**	@var		int			$strength		Strength randomized String should have at least (-100 <= x <= 100) */
	public $strength			= 0;
	/**	@var		int			$maxTurns		Number of Turns to try to create a strong String */
	public $maxTurns			= 10;
	/**	@var		bool		$unique			Flag: every Sign may only appear once in randomized String */
	public $unique				= TRUE;
	/**	@var		bool		$useDigits		Flag: use Digits */
	public $useDigits			= TRUE;
	/**	@var		bool		$useSmalls		Flag: use small Letters */
	public $useSmalls			= TRUE;
	/**	@var		bool		$useLarges		Flag: use large Letters */
	public $useLarges			= TRUE;
	/**	@var		bool		$useSigns		Flag: use Signs */
	public $useSigns			= TRUE;

	/**
	 *	Creates and returns Sign Pool as String.
	 *	@access		protected
	 *	@return		string
	 */
	protected function createPool()
	{
		$pool	= "";
		$sets	= array(
			"useDigits"	=> "digits",
			"useSmalls"	=> "smalls",
			"useLarges"	=> "larges",
			"useSigns"	=> "signs",
			);
		
		foreach( $sets as $key => $value )
			if( $this->$key )
				$pool	.= $this->$value;
		return $pool;
	}

	public function configure( $useDigits, $useSmalls, $useLarges, $useSigns ){
		if( !( $useDigits || $useSmalls || $useLarges || $useSigns ) )
			throw InvalidArgumentException( 'Atleast one type of characters must be enabled' );
		$this->useDigits	= $useDigits;
		$this->useSmalls	= $useSmalls;
		$this->useLarges	= $useLarges;
		$this->useSigns		= $useSigns;
	}
	
	/**
	 *	Creates and returns randomized String.
	 *	@access		protected
	 *	@param		int			$length			Length of String to create
	 *	@param		string		$pool			Sign Pool String
	 *	@return		string
	 */
	protected function createString( $length, $pool )
	{
		$random	= array();
		$input	= array();
		for( $i=0; $i<strlen( $pool ); $i++ )
			$input[] = $pool[$i];

		if( $this->unique )
		{
			for( $i=0; $i<$length; $i++ )
			{
				$key = array_rand( $input, 1 );
				if( in_array( $input[$key], $random ) )
					$i--;
				else
					$random[] = $input[$key];
			}
		}
		else
		{
			if( $length <= strlen( $pool ) )
			{
				shuffle( $input );
				$random	= array_slice( $input, 0, $length );
			}
			else
			{
				for( $i=0; $i<$length; $i++ )
				{
					$key = array_rand( $input, 1 );
					$random[] = $input[$key];
				}
			}
		}
		$random	= join( $random );
		return $random;
	}

	/**
	 *	Builds and returns randomized string.
	 *	@access		public
	 *	@param		int			$length			Length of String to build
	 *	@param		int			$strength		Strength to have at least (-100 <= x <= 100)
	 *	@return		string
	 */
	public function get( $length, $strength = 0 )
	{
		if( !is_int( $length ) )															//  Length is not Integer
			throw new InvalidArgumentException( 'Length must be an Integer.' );
		if( !$length )																		//  Length is 0
			throw new InvalidArgumentException( 'Length must greater than 0.' );
		if( !is_int( $strength ) )															//  Stength is not Integer
			throw new InvalidArgumentException( 'Strength must be an Integer.' );
		if( $strength && $strength > 100 )													//  Strength is to high
			throw new InvalidArgumentException( 'Strength must be at most 100.' );
		if( $strength && $strength < -100 )													//  Strength is to low
			throw new InvalidArgumentException( 'Strength must be at leastt -100.' );

		$length	= abs( $length );															//  absolute Length
		$pool	= $this->createPool();														//  create Sign Pool
		if( !strlen( $pool ) )																//  Pool is empty
			throw new RuntimeException( 'No usable signs defined.' );
		if( $this->unique && $length >= strlen( $pool ) )									//  Pool is smaller than Length
			throw new UnderflowException( 'Length must be lower than Number of usable Signs in "unique" Mode.' );

		$random	= $this->createString( $length, $pool );									//  randomize String
		if( !$strength )																	//  no Strength needed
			return $random;

		$turn	= 0;
		do
		{
			$currentStrength	= Alg_Crypt_PasswordStrength::getStrength( $random );		//  calculate Strength of random String
			if( $currentStrength >= $strength )												//  random String is strong enough
				return $random;
			$random	= $this->createString( $length, $pool );								//  randomize again
			$turn++;																		//  count turn
		}
		while( $turn < $this->maxTurns );													//  break if to much turns
		throw new RuntimeException( 'Strength Score '.$strength.' not reached after '.$turn.' Turns.' );
	}
}
?>