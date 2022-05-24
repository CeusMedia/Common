<?php
/**
 *	Randomizer supporting different sign types.
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
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			18.01.2006
 */

namespace CeusMedia\Common\Alg;

use CeusMedia\Common\Alg\Crypt\PasswordStrength;
use InvalidArgumentException;
use RuntimeException;
use UnderflowException;

/**
 *	Randomizer supporting different sign types.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			18.01.2006
 */
class Randomizer
{
	/**	@var		string		$digits			String with Digits */
	public $digits				= "0123456789";

	/**	@var		string		$larges		String with large Letters */
	public $larges				= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

	/**	@var		string		$smalls			String with small Letters */
	public $smalls				= "abcdefghijklmnopqrstuvwxyz";

	/**	@var		string		$signs			String with Signs */
	public $signs				= '.:_-+*=/\!§$%&(){}[]#@?~';

	/**	@var		integer		$strength		Strength randomized String should have at least (-100 <= x <= 100) */
	public $strength			= 0;

	/**	@var		integer		$maxTurns		Number of Turns to try to create a strong String */
	public $maxTurns			= 10;

	/**	@var		boolean		$unique			Flag: every Sign may only appear once in randomized String */
	public $unique				= TRUE;

	/**	@var		boolean		$useDigits		Flag: use Digits */
	public $useDigits			= TRUE;

	/**	@var		boolean		$useSmalls		Flag: use small Letters */
	public $useSmalls			= TRUE;

	/**	@var		boolean		$useLarges		Flag: use large Letters */
	public $useLarges			= TRUE;

	/**	@var		boolean		$useSigns		Flag: use Signs */
	public $useSigns			= TRUE;

	/**
	 *	Defines characters to be used for string generation.
	 *	@access		public
	 *	@param		boolean		$useDigits		Flag: use Digits
	 *	@param		boolean		$useSmalls		Flag: use small Letters
	 *	@param		boolean		$useLarges		Flag: use large Letters
	 *	@param		boolean		$useSigns		Flag: use Signs
	 *	@param		integer		$strength		Strength randomized String should have at least (-100 <= x <= 100)
	 *	@return		void
	 */
	public function configure( $useDigits, $useSmalls, $useLarges, $useSigns, $strength )
	{
		if( !( $useDigits || $useSmalls || $useLarges || $useSigns ) )
			throw InvalidArgumentException( 'Atleast one type of characters must be enabled' );
		$this->useDigits	= $useDigits;
		$this->useSmalls	= $useSmalls;
		$this->useLarges	= $useLarges;
		$this->useSigns		= $useSigns;
		$this->strength		= $strength;
	}

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
		//  Length is not Integer
		if( !is_int( $length ) )
			throw new InvalidArgumentException( 'Length must be an Integer.' );
		//  Length is 0
		if( !$length )
			throw new InvalidArgumentException( 'Length must greater than 0.' );
		//  Stength is not Integer
		if( !is_int( $strength ) )
			throw new InvalidArgumentException( 'Strength must be an Integer.' );
		//  Strength is to high
		if( $strength && $strength > 100 )
			throw new InvalidArgumentException( 'Strength must be at most 100.' );
		//  Strength is to low
		if( $strength && $strength < -100 )
			throw new InvalidArgumentException( 'Strength must be at leastt -100.' );

		//  absolute Length
		$length	= abs( $length );
		//  create Sign Pool
		$pool	= $this->createPool();
		//  Pool is empty
		if( !strlen( $pool ) )
			throw new RuntimeException( 'No usable signs defined.' );
		//  Pool is smaller than Length
		if( $this->unique && $length >= strlen( $pool ) )
			throw new UnderflowException( 'Length must be lower than Number of usable Signs in "unique" Mode.' );

		//  randomize String
		$random	= $this->createString( $length, $pool );
		//  no Strength needed
		if( !$strength )
			return $random;

		$turn	= 0;
		do
		{
			//  calculate Strength of random String
			$currentStrength	= PasswordStrength::getStrength( $random );
			//  random String is strong enough
			if( $currentStrength >= $strength )
				return $random;
			//  randomize again
			$random	= $this->createString( $length, $pool );
			//  count turn
			$turn++;
		}
		//  break if to much turns
		while( $turn < $this->maxTurns );
		throw new RuntimeException( 'Strength Score '.$strength.' not reached after '.$turn.' Turns.' );
	}
}
