<?php
import ("de.ceus-media.ui.DevOutput");
/**
 *	Convertion between roman and arabic number system.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.06.2005
 *	@version		0.1
 */
/**
 *	Convertion between roman and arabic number system.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.06.2005
 *	@version		0.1
 */
class RomanNumbers
{
	/**	@var	array	$_roman		Map of roman numbers and shortcut placeholders*/
	var $_roman	= array ();
	/**	@var	array	$_shorts		Map of shortcuts in roman number system */
	var $_shorts	= array ();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->_roman	= array(
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
			"U"		=> 1000000);
		$this->_shorts	= array(
			"A"	=> "IV",				"B"	=> "IX",
			"E"	=> "XL",				"F"	=> "XC",
			"G"	=> "CD",				"H"	=> "CM",
			"J"	=> "MP",				"K"	=> "MQ",
			"N"	=> "QR",				"W"	=> "QS",
			"Y"	=> "ST",				"Z"	=> "SU"
			);
		arsort( $this->_roman );
	}
	
	/**
	 *	Converts and returns an arabian number as roman number.
	 *	@access		public
	 *	@param		int		$integer		Arabian number
	 *	@return		string
	 */
	function convertToRoman( $integer )
	{
		$roman = "";																		//  initiating roman number
		if( is_numeric( $integer ) && $integer == round( $integer, 0 ) )								//  prove integer by cutting floats
		{
			while( $integer > 0 )
			{
				foreach( $this->_roman as $key => $value )									//  all roman number starting with biggest
				{
					if( $integer >= $value )													//  current roman number is in integer
					{
						$roman	.= $key;													//  append roman number
						$integer	-= $value;												//  decrease integer by current value
						break;															
					}
				}
			}
			$keys	= array_keys( $this->_shorts );
			$values	= array_values( $this->_shorts );
			$roman = str_replace( $keys, $values, $roman );										//  realize shortcuts
			return $roman;
		}
		else
			trigger_error( "Integer '".$integer."' is invalid.", E_USER_WARNING );
	}
	
	/**
	 *	Converts and returns a roman number as arabian number.
	 *	@access		public
	 *	@param		string	$roman		Roman number
	 *	@return		integer
	 */
	function convertFromRoman( $roman )
	{
		$_r = str_replace( array_keys( $this->_roman ), "", $roman );								//  prove roman number by clearing all valid numbers
		if( strlen( $_r ) )																	//  some numbers are invalid
			trigger_error( "Roman '".$roman."' is invalid.", E_USER_WARNING );
		$integer = 0;																		//  initiating integer
		$roman = str_replace( array_values( $this->_shorts ), array_keys( $this->_shorts ), $roman );	//  resolve shortcuts
		foreach( $this->_roman as $key => $value )											//  all roman number starting with biggest
		{
			$count = substr_count( $roman, $key );											//  amount of roman numbers of current value
			$integer += $count * $value;													//  increase integer by amount * current value
			$roman = str_replace( $key, "", $roman );											//  remove current roman numbers
		}
		return $integer;
	}
}
?>