<?php
/**
 *	Formats Numbers intelligently and adds Units to Bytes and Seconds.
 *	@package		alg
 *	@version		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.04.2008
 *	@version		0.1
 */
/**
 *	Formats Numbers intelligently and adds Units to Bytes and Seconds.
 *	@package		alg
 *	@version		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.10.2007
 *	@version		0.1
 */
define( 'SIZE_BYTE', pow( 1024, 0 ) );
define( 'SIZE_KILOBYTE', pow( 1024, 1 ) );
define( 'SIZE_MEGABYTE', pow( 1024, 2 ) );
define( 'SIZE_GIGABYTE', pow( 1024, 3 ) );
class Alg_UnitFormater
{
	/**	@var		array		$unitBytes		List of Byte Units */
	public static $unitBytes	= array(
		"B",
		"KB",
		"MB",
		"GB",
	);
	
	/**	@var		array		$unitBytes		List of Second Units */
	public static $unitSeconds	= array(
		"µs",
		"ms",
		"s",
		"m",
		"h",
		"d",
		"a"
	);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$string			String to unicode
	 *	@param		bool		$force			Flag: encode into UTF-8 even if UTF-8 Encoding has been detected
	 *	@return		void
	 */
	public static function formatNumber( $float, $unit = 1, $precision = 0 )
	{
		if( (int) $unit )
		{
			$float	= $float / $unit;
			if( is_int( $precision ) )
				$float	= round( $float, $precision );
		}
		return $float;
	}
	
	/**
	 *	Formats Number of Bytes Seconds by switching to next higher Unit if an set Edge is reached.
	 *	Edge is a Factor when to switch to ne next higher Unit, eG. 0.5 means 50% of 1024.
	 *	If you enter 512 (B) it will return 0.5 KB.
	 *	Caution! With Precision at 0 you may have Errors from rounding.
	 *	To avoid the Units to be appended, enter FALSE or NULL for indent.
	 *	@access		public
	 *	@param		float		$float			Number of Bytes
	 *	@param		int			$precision		Number of Floating Point Digits
	 *	@param		string		$indent			Space between Number and Unit
	 *	@param		float		$edge			Factor of next higher Unit when to break
	 *	@return		string
	 */
	public static function formatBytes( $float, $precision = 1, $indent = " ", $edge = 0.5 )
	{
		$unitKey	= 0;															//  step to first Unit
		$divider	= 1024;															//  1024 Bytes are 1 Kilo Byte
		$edge		= abs( $edge );													//  avoid negative Edges
		$edge		= $edge > 1 ? 1 : $edge;										//  avoid senseless Edges
		$edgeValue	= $divider * $edge;												//  calculate Edge Value
		while( $float >= $edgeValue )												//  Value is larger than Edge
		{
			$unitKey ++;															//  step to next Unit
			$float	/= $divider;													//  calculate Value in new Unit
		}
		if( is_int( $precision ) )													//  Precision is set
			$float	= round( $float, $precision );									//  round Value
		if( is_string( $indent ) )													//  Indention is set
			$float	= $float.$indent.self::$unitBytes[$unitKey];					//  append Unit
		return $float;																//  return resultung Value
	}

	/**
	 *	Formats Kilo Bytes like formatBytes.
	 *	You can also enter 0.25 (KB) and it will return 256 B.
	 *	@access		public
	 *	@param		float		$float			Number of Kilo Bytes
	 *	@param		int			$precision		Number of Floating Point Digits
	 *	@param		string		$indent			Space between Number and Unit
	 *	@param		float		$edge			Factor of next higher Unit when to break
	 *	@return		string
	 */
	public static function formatKiloBytes( $float, $precision = 1, $indent = " ", $edge = 0.5 )
	{
		return self::formatBytes( $float * 1024, $precision, $indent, $edge );
	}

	/**
	 *	Formats Mega Bytes like formatBytes.
	 *	You can also enter 0.25 (MB) and it will return 256 KB.
	 *	@access		public
	 *	@param		float		$float			Number of Mega Bytes
	 *	@param		int			$precision		Number of Floating Point Digits
	 *	@param		string		$indent			Space between Number and Unit
	 *	@param		float		$edge			Factor of next higher Unit when to break
	 *	@return		string
	 */
	public static function formatMegaBytes( $float, $precision = 1, $indent = " ", $edge = 0.5 )
	{
		return self::formatBytes( $float * 1024 * 1024, $precision, $indent, $edge );
	}
	
	/**
	 *	Formats Micro Seconds by switching to next higher Unit if an set Edge is reached.
	 *	Edge is a Factor when to switch to ne next higher Unit, eG. 0.5 means 50% of 1000.
	 *	If you enter 500 (µs) it will return 0.5 ms.
	 *	Caution! With Precision at 0 you may have Errors from rounding.
	 *	To avoid the Units to be appended, enter FALSE or NULL for indent.
	 *	@access		public
	 *	@param		float		$float			Number of Micro Seconds
	 *	@param		int			$precision		Number of Floating Point Digits
	 *	@param		string		$indent			Space between Number and Unit
	 *	@param		float		$edge			Factor of next higher Unit when to break
	 *	@return		string
	 */
	public static function formatMicroSeconds( $float, $precision = 1, $indent = " ", $edge = 0.5 )
	{
		$unitKey	= 0;															//  step to first Unit
		$divider	= 1000;															//  1000 Micro Seconds are 1 Milli Second
		$edge		= abs( $edge );													//  avoid negative Edges											
		$edge		= $edge > 1 ? 1 : $edge;										//  avoid senseless Edges
		$edgeValue	= $divider * $edge;												//  calculate Edge Value

		while( $float >= $edgeValue )												//  Value is larger than Edge
		{
			$unitKey ++;															//  step to next Unit
			$float	/= $divider;													//  calculate Value in new Unit
			if( $unitKey == 2 )														//  Seconds are reached
			{
				$divider	= 60;													//  60 Seconds per Minute
				$edgeValue	= $edge * $divider;										//  calculate new Edge
			}
			if( $unitKey == 4 )														//  Hours are reached
			{
				$divider	= 24;													//  24 Hours per Day
				$edgeValue	= $edge * $divider;										//  calculate new Edge
			}
			if( $unitKey == 5 )														//  Days are reached
			{
				$divider	= 365;													//  365 Days per Year
				$edgeValue	= $edge * $divider;										//  calculate new Edge
			}
		}
		if( is_int( $precision ) )													//  Precision is set
			$float	= round( $float, $precision );									//  round Value
		if( is_string( $indent ) )													//  Indention is set
			$float	= $float.$indent.self::$unitSeconds[$unitKey];					//  append Unit
		return $float;																//  return resulting Value
	}

	/**
	 *	Formats Milli Seconds like formatMicroSeconds.
	 *	You can also enter 0.1 (ms) and it will return 100 µs.
	 *	@access		public
	 *	@param		float		$float			Number of Milli Seconds
	 *	@param		int			$precision		Number of Floating Point Digits
	 *	@param		string		$indent			Space between Number and Unit
	 *	@param		float		$edge			Factor of next higher Unit when to break
	 *	@return		string
	 */
	public static function formatMilliSeconds( $float, $precision = 1, $indent = " ", $edge = 0.5 )
	{
		return self::formatMicroSeconds( $float * 1000, $precision, $indent, $edge );
	}
	
	/**
	 *	Formats Minutes like formatMicroSeconds.
	 *	You can also enter 0.1 (m) and it will return 6 s.
	 *	@access		public
	 *	@param		float		$float			Number of Minutes
	 *	@param		int			$precision		Number of Floating Point Digits
	 *	@param		string		$indent			Space between Number and Unit
	 *	@param		float		$edge			Factor of next higher Unit when to break
	 *	@return		string
	 */
	public static function formatMinutes( $float, $precision = 1, $indent = " ", $edge = 0.5 )
	{
		return self::formatMicroSeconds( $float * 60000000, $precision, $indent, $edge );
	}
	
	/**
	 *	Formats Seconds like formatMicroSeconds.
	 *	You can also enter 0.1 (s) and it will return 100 ms.
	 *	@access		public
	 *	@param		float		$float			Number of Seconds
	 *	@param		int			$precision		Number of Floating Point Digits
	 *	@param		string		$indent			Space between Number and Unit
	 *	@param		float		$edge			Factor of next higher Unit when to break
	 *	@return		string
	 */
	public static function formatSeconds( $float, $precision = 1, $indent = " ", $edge = 0.5 )
	{
		return self::formatMicroSeconds( $float * 1000000, $precision, $indent, $edge );
	}
}
?>