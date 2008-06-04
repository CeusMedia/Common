<?php
import ("de.ceus-media.ui.DevOutput");
/**
 *	Convertion between roman and arabic number system.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.06.2005
 *	@version		0.6
 */
/**
 *	Convertion between roman and arabic number system.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.06.2005
 *	@version		0.6
 */
class Math_RomanNumbers
{
	/**	@var	array	$roman		Map of roman numbers and shortcut placeholders*/
	protected static $roman	= array(
		"I"		=> 1,			"A"		=> 4,
		"V"		=> 5,			"B"		=> 9,
		"X"		=> 10,			"E"		=> 40,
		"L"		=> 50,			"F"		=> 90,
		"C"		=> 100,			"G"		=> 400,
		"D"		=> 500,			"H"		=> 900,
		"M"		=> 1000,		"J"		=> 4000,
		"P"		=> 5000,		"K"		=> 9000,
		"Q"		=> 10000,		"N"		=> 40000,
		"R"		=> 50000,		"W"		=> 90000,
		"S"		=> 100000,		"Y"		=> 400000,
		"T"		=> 500000,		"Z"		=> 900000,
		"U"		=> 1000000
	);
	/**	@var	array	$shorts		Map of shortcuts in roman number system */
	protected static $shorts	= array(
		"A"	=> "IV",				"B"	=> "IX",
		"E"	=> "XL",				"F"	=> "XC",
		"G"	=> "CD",				"H"	=> "CM",
		"J"	=> "MP",				"K"	=> "MQ",
		"N"	=> "QR",				"W"	=> "QS",
		"Y"	=> "ST",				"Z"	=> "SU"
	);
	
	/**
	 *	Converts and returns an arabian number as roman number.
	 *	@access		public
	 *	@param		int			$integer		Arabian number
	 *	@return		string
	 */
	public static function convertToRoman( $integer )
	{
		arsort( self::$roman );
		$roman = "";																		//  initiating roman number
		if( is_numeric( $integer ) && $integer == round( $integer, 0 ) )					//  prove integer by cutting floats
		{
			while( $integer > 0 )
			{
				
				foreach( self::$roman as $key => $value )									//  all roman number starting with biggest
				{
					if( $integer >= $value )												//  current roman number is in integer
					{
						$roman	.= $key;													//  append roman number
						$integer	-= $value;												//  decrease integer by current value
						break;															
					}
				}
			}
			$keys	= array_keys( self::$shorts );
			$values	= array_values( self::$shorts );
			$roman	= str_replace( $keys, $values, $roman );								//  realize shortcuts
			return $roman;
		}
		else
			throw new InvalidArgumentException( "Integer '".$integer."' is invalid." );
	}
	
	/**
	 *	Converts and returns a roman number as arabian number.
	 *	@access		public
	 *	@param		string		$roman		Roman number
	 *	@return		integer
	 */
	public function convertFromRoman( $roman )
	{
		$_r = str_replace( array_keys( $this->roman ), "", $roman );						//  prove roman number by clearing all valid numbers
		if( strlen( $_r ) )																	//  some numbers are invalid
			throw new InvalidArgumentException( "Roman '".$roman."' is invalid." );
		$integer = 0;																		//  initiating integer
		$keys	= array_keys( $this->shorts );
		$values	= array_values( $this->shorts );
		$roman = str_replace( $values, $keys, $roman );										//  resolve shortcuts
		foreach( $this->roman as $key => $value )											//  all roman number starting with biggest
		{
			$count = substr_count( $roman, $key );											//  amount of roman numbers of current value
			$integer += $count * $value;													//  increase integer by amount * current value
			$roman = str_replace( $key, "", $roman );											//  remove current roman numbers
		}
		return $integer;
	}
}
?>