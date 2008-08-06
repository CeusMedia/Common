<?php
/**
 *	Algorithmus von Euklid.
 *
 *	Bestimmen des groessten gemeinsamen Teilers ggT
 *	und des kleinsten gemeinsamen Vielfachen kgV
 *	zweier natuerlicher Zahlen m und n
 *	mittels euklidischen Algorithmus.
 *
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 *	@todo			Code Documentation
 */
class Math_Euklid
{
	/**
	 *	ggT( m, n)
	 *	@param	int	$m	natuerliche Zahlen > 0
	 *	@param	int	$n	natuerliche Zahlen > 0
	 */
	public static function ggT( $m, $n )
	{
		if( $n != 0 )
			return self::ggT( $n, $m % $n );
		else
			return $m;
	}

	/**
	 *	kgV( m, n)
	 *	@param	int	$m	natuerliche Zahlen > 0
	 *	@param	int	$n	natuerliche Zahlen > 0
	 */
	public static function kgV( $m, $n )
	{
		return $m * $n / self::ggT( $m, $n );
	}
	
	public static function ggTe( $a, $b )
	{
		$array	= self::ggTe_rec( $a, $b );
		return $array[0];
	}
	
	public static function ggTe_rec( $a, $b )
	{
		if( $b == 0 )
			$array	= array( $a, 1, 0 );
		else
		{
			$tmp	= self::ggTe_rec( $b, $a % $b );
			$array	= array( $tmp[0], $tmp[2], $tmp[1] - round( $a / $b ) * $tmp[2] );
		}
		return $array;
	}
}
?>