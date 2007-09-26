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
 *	@version		0.4
 */
/**
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 *	@todo			Code Documentation
 */
class Euklid
{
	/**
	 *	ggT( m, n)
	 *	@param	int	$m	natuerliche Zahlen > 0
	 *	@param	int	$n	natuerliche Zahlen > 0
	 */
	function ggT( $m, $n )
	{
		if( $n != 0 )
			return Euklid::ggT( $n, $m % $n );
		else
			return $m;
	}

	/**
	 *	kgV( m, n)
	 *	@param	int	$m	natuerliche Zahlen > 0
	 *	@param	int	$n	natuerliche Zahlen > 0
	 */
	function kgV( $m, $n )
	{
		return $m * $n / Euklid::ggT( $m, $n );
	}
	
	function ggTe( $a, $b )
	{
		$array	= Euklid::ggTe_rec( $a, $b );
		return $array[0];
	}
	
	function ggTe_rec( $a, $b )
	{
		if( $b == 0 )
			$array	= array( $a, 1, 0 );
		else
		{
			$tmp	= Euklid::ggTe_rec( $b, $a % $b );
			$array	= array( $tmp[0], $tmp[2], $tmp[1] - round( $a / $b ) * $tmp[2] );
		}
		return $array;
	}
}
?>