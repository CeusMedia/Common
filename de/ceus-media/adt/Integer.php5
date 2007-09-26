<?php
/**
 *	Integer type that supports large numbers, conversion to number formats up to base 36, and arithmitic in up to 36 base numbers.
 *	@package	adt
 *	@access		public
 *	@author		Brian Takita <brian.takita@runbox.com>
 */
/**
 *	Integer type that supports large numbers, conversion to number formats up to base 36, and arithmitic in up to 36 base numbers.
 *	@package	adt
 *	@access		public
 *	@author		Brian Takita <brian.takita@runbox.com>
 */
class Integer 
{
	/**	@var	string	_val			Value of the Integer */
	var $_val;
	/**	@var	int		_base		Numeric Base of the Integer */	
	var $_base;
	/**	@var	bool		_negative	Is this a negative Number? */
	var $_negative;

	/**
	 *	The constructor. Sets the integer value.
	 *	@access		protected
	 *	@param		int		val		The Integer value.
	 *	@param		int		base	The base of this number.
	 */
	public function __construct( $val = 0, $base = 10 )
	{
		$this->set( $val, $base );
	}

	/**
	 *	Sets the integer value.
	 *	@access		public
	 *	@param		int		val		The Integer value.
	 *	@param		int		base	The base of this number.
	 */
	function set( $val, $base = 10 )
	{
		if( $val[0] == '-' )
			$this->_negative = true;
		$this->_base = $base;
		if( is_int( $val ) )
			$val = ''.$val;
		$this->_val = $val;
	}

	/**
	 *	Gets the value in decimal format.
	 *	@access		public
	 *	@return		String The decimal value.
	 */
	function get()
	{
		return $this->_val;
	}

	/**
	 *	@access		public
	 *	Gets the base the integer is in.
	 *	@return		int The base the integer is in.
	 */
	function getBase()
	{
		return $this->_base;
	}

	/**
	 *	Convert the integer into binary format.
	 *	@access		public
	 *	@param		int		len		The minimum length of the integer. If it is longer than the number's actual length, zeros are prepended to the number.
	 *	@return		string			The Integer in binary format.
	 */
	function toBin( $len = 0 )
	{
		return str_pad( Integer::convert( $this->_val, $this->_base, 2 ), $len, '0', STR_PAD_LEFT );
	}

	/**
	 *	Convert the integer into octal format.
	 *	@access		public
	 *	@param		int		len		The minimum length of the integer. If it is longer than the number's actual length, zeros are prepended to the number.
	 *	@return		The Integer in octal format.
	 */
	function toOct( $len = 0 )
	{
		return str_pad( Integer::convert( $this->_val, $this->_base, 8 ), $len, '0', STR_PAD_LEFT );
	}

	/**
	 *	Convert the integer into decimal format.
	 *	@access		public
	 *	@param		int		len		The minimum length of the integer. If it is longer than the number's actual length, zeros are prepended to the number.
	 *	@return		The Integer in decimal format.
	 */
	function toDec( $len = 0 )
	{
		return  str_pad( Integer::convert( $this->_val, $this->_base, 10 ), $len, '0', STR_PAD_LEFT );
	}

	/**
	 *	Convert the integer into hex format.
	 *	@access		public
	 *	@param		int		len The minimum length of the integer. If it is longer than the number's actual length, zeros are prepended to the number.
	 *	@return				The Integer in hex format.
	 */
	function toHex( $len = 0 )
	{
		//return str_pad(strtoupper(dechex($this->_val)), $len, '0', STR_PAD_LEFT);
		return str_pad( Integer::convert( $this->_val, $this->_base, 16 ), $len, '0', STR_PAD_LEFT );
	}

	/**
	 *	Convert the number from one number base to another up to 36 base. This is a Class method. An object does not need to be instantiated for this to be used. Usage is Integer::convert($val, $inSys, $outSys)
	 *	@access		public
	 *	@param		int		input		The number to be converted.
	 *	@param		int		inputBase	The Input base system.
	 *	@param		int		outputBase	The Output base system.
	 *	@return		string				The converted number.
	 */
	function convert( $input, $inputBase = 10, $outputBase = 10 )
	{
		if( $inputBase == $outputBase )
			return $input;
		if( $input == '0' )											// If '0', return val.
			return $input;
		$output = '';
		$divmod = array();
		$outBaseInInBase = ltrim( Integer::_convertSingle( $outputBase, $inputBase ), '0' );
		while( 1 )
		{
			if( Integer::compare( $input, $outBaseInInBase ) < 0 )
			{
				$output = Integer::_convertSingle( $input, $outputBase ) . $output;
				break;
			}
			$divmod = Integer::divmod( $input, $outBaseInInBase, $inputBase );
			$r = $divmod['mod'];
			$input = $divmod['div'];
			$output = Integer::_convertSingle( $r, $outputBase ) . $output;
		}
		return $output;
	}

	/**
	 *	Converts a single integer digit into the appropriate number base system..
	 *	@access		private
	 *	@param		int		in		The Integer Digit in any base system.
	 *	@param		int		base	The system that the Integer digit will be converted to.
	 *	@return		string      			The converted digit.
	 */
	function _convertSingle( $in, $base )
	{
		if( $in < $base )
			return Integer::_baseVal( $in );
		$outVal = '';
		while( $in > 0 )
		{
			$r = $in % $base;
			$outVal = Integer::_baseVal( $r ) . $outVal;
			$in = (int) $in/$base;
		}
		return $outVal;
	}

	/**
	 *	Compares two Integers to see which is greater or if they are equal.
	 *	@access		public
	 *	@param		 String $a The first number to be compared.
	 *	@param		 String $b The second number to be compared.
	 *	@return		int       If $a > $b return 1.<br>If $a < $b return -1.<br>If $a == $b return 0.
	 */
	function compare( $a, $b )
	{
		$lenA	= strlen( $a );
		$lenB	= strlen( $b );
		$len		= ( $lenA > $lenB ) ? $lenA : $lenB;
		$a = str_pad( $a, $len, '0', STR_PAD_LEFT );
		$b = str_pad( $b, $len, '0', STR_PAD_LEFT );
		if( $a < $b )
			return -1;
		else if( $a > $b )
			return 1;
		else if( $a == $b )
			return 0;
	}

	/**
	 *	Adds two numbers. The numbers must be in the same base system.
	 *	@access		public
	 *	@param		int		a		The number on the left side of the add.
	 *	@param		int		b		The number on the right side of the add.
	 *	@param		int		base	The base system where the addition will take place.
	 *	@return		int            		The sum.
	 */
	function add( $a, $b, $base = 10 )
	{
		$negA = ( $a[0] == '-' ) ? true : false;
		$negB = ( $b[0] == '-' ) ? true : false;
		$a = strtoupper( $a );
		$b = strtoupper( $b );
		$a = Integer::_trimString( $a, $base );								// Get rid of nonvalid characters.
		$b = Integer::_trimString( $b, $base );
		if( $negA === true && $negB === false )							// Handle negative numbers.
			return Integer::sub( $b, $a, $base );
		else if( $negA === false && $negB === true )
			return Integer::sub( $a, $b, $base );
		else if( $negA === true && $negB === true )
			return '-'.Integer::add( $a, $b, $base );
		$lenA = strlen( $a );
		$lenB = strlen( $b );
		$len = ( $lenA > $lenB ) ? $lenA : $lenB;
		$a = str_pad( $a, $len, '0', STR_PAD_LEFT );
		$b = str_pad( $b, $len, '0', STR_PAD_LEFT );
		$i = $len - 1;
		$c = 0;
		$sum = '';
		while( $i >= 0 || $c > 0 )
		{
			if( $i >= 0 )
			{
				$valA = Integer::_decVal( $a[$i] );
				$valB = Integer::_decVal( $b[$i] );
			}
			else
			{
				$valA = 0;
				$valB = 0;
			}
			$r = $valA + $valB + $c;
			if( $r < $base )
				$c = 0;
			else
			{
				$c = 1;
				$r = ( $r - $base );
			}
			$r = Integer::_baseVal( $r );
			$sum = $r . $sum;
			$i--;
		}
		return $sum;
	}

	/**
	 *	Subtract two numbers. The numbers must be in the same base system.
	 *	@access		public
	 *	@param		int		a		The number to be subtracted from.
	 *	@param		int		b		The subtractor.
	 *	@param		int    	base	The base system where the subtraction will take place.
	 *	@return		int				The difference.
	 */
	function sub( $a, $b, $base = 10 )
	{
		$negA	= ( $a[0] == '-' ) ? true : false;
		$negB	= ( $b[0] == '-' ) ? true : false;
		$a = strtoupper( $a );
		$b = strtoupper( $b );
		$a = Integer::_trimString( $a, $base );							// Get rid of nonvalid characters.
		$b = Integer::_trimString( $b, $base );
		if( $negA === true && $negB === false )						// Handle negative numbers.
			return '-'.Integer::add( $a, $b, $base );
		else if( $negA === false && $negB === true )
			return Integer::add( $a, $b, $base );
		else if( $negA === true && $negB === true )
			return Integer::sub ($b, $a, $base);
		$lenA = strlen( $a );
		$lenB = strlen( $b );
		$len = ( $lenA > $lenB ) ? $lenA : $lenB;
		$a = str_pad( $a, $len, '0', STR_PAD_LEFT );
		$b = str_pad( $b, $len, '0', STR_PAD_LEFT );
		if( $b > $a )
			return '-'.Integer::sub( $b, $a, $base );
		$c = false;
		$difference = '';
		for( $i=$len-1; $i >= 0; $i-- )
		{
			if( $c === false )
				$valA = Integer::_decVal( $a[$i] );
			$valB = Integer::_decVal( $b[$i] );
			$r = $valA - $valB;
			if( $r >= 0 )											// Is $r not negative?
				$c = false;										// $r is not negative. Set carry to false.
			else
			{
				$valA = Integer::_decVal( $a[$i-1] ) - 1;				// $r is negative. Carry down the number
				$r += $base;										// Carry increases $r by the system value.
				$c = true;										// Set carry to true
			}
			$r = Integer::_baseVal( $r );
			$difference = $r . $difference;
		}
		$difference = ltrim( $difference, '0' );
		return ( $difference != '' ) ? $difference : '0';
	}

	/**
	 *	Multiplies two numbers. The numbers must be in the same base system.
	 *	@access		public
	 *	@param		int		a		The number on the left side of the multiplication.
	 *	@param		int		b		The number on the right side of the multiplication.
	 *	@param		int		base	The base system where the multiplication will take place.
	 *	@return		int				The product.
	 */
	function mul( $a, $b, $base = 10 )
	{
		$negA = ( $a[0] == '-' ) ? true : false;
		$negB = ( $b[0] == '-' ) ? true : false;
		$a = strtoupper( $a );
		$b = strtoupper( $b );
		$a = Integer::_trimString( $a, $base );								// Get rid of nonvalid characters.
		$b = Integer::_trimString( $b, $base );
		if( $negA == '-' xor $negB == '-' )
			return '-'.Integer::mul( $a, $b, $base );
		$lenA = strlen( $a );
		$lenB = strlen( $b );
		if( $lenB > $lenA )												// $b is supposed to be shorter
			return Integer::mul( $b, $a, $base );
		$prod = '0';													// The total product
		for( $i = 0; $i < $lenB; $i++ )										// Cycle through all $b numbers
		{
			$valB = Integer::_decVal( $b[( $lenB - 1 ) - $i] );
			$val = '';
			$c = 0;
			$j = $lenA -1;
			while( $j >= 0 || $c > 0 )									// Multiply $b cycled through all $a numbers
			{
				$valA = ( $j < 0 ) ? 0 : Integer::_decVal( $a[$j] );			// If $a still has characters, get one.
				$res = $valA * $valB + $c;
				if( $res < $base ) 
				{
					$c = 0;
					$val = Integer::_baseVal( $res ) . $val;
				}
				else
				{
					$c = (int) ( $res / $base );
					$val = Integer::_baseVal( $res % $base ) . $val;
				}
				$j--;
			}
			$prod = Integer::add( $val.str_pad( '', $i, 0 ), $prod, $base );		// Pad $val with 0's behind it. Then Add $val to $prod.
		}
		return $prod;
	}

	/**
	 *	Divides two numbers. Returns the quotient. The numbers must be in the same base system.
	 *	@access		public
	 *	@param		int		a		The numerator.
	 *	@param		int		b		The denominator.
	 *	@param		int		base	The base system where the addition will take place.
	 *	@return		int				The Quotient.
	 */
	function div( $a, $b, $base = 10 )
	{
		$d = Integer::divmod( $a, $b, $base );
		return $d['div'];
	}

	/**
	 *	Divides two numbers. Returns the remainder. The numbers must be in the same base system.
	 *	@access		public
	 *	@param		int		a		The numerator.
	 *	@param		int		b		The denominator.
	 *	@param		int		base	The base system where the addition will take place.
	 *	@return		int				The Remainder.
	 */
	function mod( $a, $b, $base = 10 )
	{
		$d = Integer::divmod( $a, $b, $base );
		return $d['mod'];
	}

	/**
	 *	Divides two numbers. Returns an array of the quotient and the remainder.
	 *	@access		public
	 *	@param		int		a		The numerator.
	 *	@param		int		b		The denominator.
	 *	@param		int		base	The base system where the addition will take place.
	 *	@return		int[]				The array containing the quotient and remainder. Array is array('div' => quotient, 'mod' => remainder).
	 */
	function divmod( $a, $b, $base )
	{
		$negA = ( $a[0] == '-' ) ? true : false;
		$negB = ( $b[0] == '-' ) ? true : false;
		$a = strtoupper( $a );
		$b = strtoupper( $b );
		$a = Integer::_trimString( $a, $base );								// Get rid of nonvalid characters.
		$b = Integer::_trimString( $b, $base );
		if( Integer::compare( $a, $b ) == -1 )
			return '0';
		$len = strlen($a);
		$quot = '';
		$r = '';
		for( $i=0; $i < $len; $i++ )
		{
			$r .= $a[$i];
			$cVal = 0;
			if( Integer::compare( $r, $b ) >= 0 )
			{
				do													// Subtract until $r < $b
				{
					$cVal++;
					$r = Integer::sub( $r, $b, $base );					// See if the next subtr
				}
				while( Integer::compare( $r, $b ) >= 0 );
			}
			$quot .= Integer::_baseVal( $cVal );
		}
		$quot = ltrim( $quot, '0' );
		$r = ltrim( $r, '0' );
		if( $quot == '' )
			$quot = '0';
		if( $r == '' )
			$r = '0';
		if( $negA xor $negB )											// Is this a negative product?
			$quot = '-'.$quot;
		return array(
			'div' => $quot,
			'mod' => $r
			);
	}

	/**
	 *	Removes characters that are not part of the base number system from the string.
	 *	@access		private
	 *	@param		string	string	The number.
	 *	@param		int		base	The base number system.
	 *	@return		string			The converted number.
	 */
	function _trimString( $string, $base )
	{
		if( $base <= 10 )
			$rep = '/[^0-'.( $base-1 ).']/';
		else if ($base > 10 && $base <= 36)
			$rep = '/[^0-9A-'.chr( ord( 'A' ) + ( $base-11 ) ).']/';
		else
		{
			echo 'Error: Cant go above a 36 number system.<br>';
			return false;
		}
		return preg_replace( $rep, '', $string );
	}

	/**
	 *	Gets the decimal value of the character in a certain number format.
	 *	@access		private
	 *	@param		char		chr		The number in its original base format.
	 *	@return		int				The base10 equivalent.
	 */
	function _decVal( $chr )
	{
		if ($chr >= '0' && $chr <= '9')
			return ord( $chr ) - ord( '0' );
		else if( $chr >= 'A' && $chr <= 'Z' )
			return 10 + ( ord( $chr ) - ord( 'A' ) );
		else
			return false;
	}

	/**
	 *	Gets the equivalent character from a base10 number.
	 *	@access		private
	 *	@param		int		val		The number to be converted.
	 *	@return		char				The converted number in up to base36.
	 */
	function _baseVal( $val )
	{
		if( $val >= 0 && $val <= 9 )
			return ''.$val;
		else if( $val >= 10 && $val <= 36 )
			return chr( ord( 'A' ) + ( $val-10 ) );
		else
			return false;
	}
}
?>