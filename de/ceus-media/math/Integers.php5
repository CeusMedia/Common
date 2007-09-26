<?php
/**
 *	@package	math
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	@package	math
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 *	@todo		check sense, see NaturalNumbers
 *	@todo		Code Documentation
 */
class Integers
{
	function pre( $number )
	{
		return --$number;
	}

	function succ( $number )
	{
		return ++$number;
	}


	/**
	 *	maximum
	 */
	function max( $array )
	{
		return max( $array );
	}

	/**
	 *	minimum
	 */
	function min( $array )
	{
		return min( $array );
	}

	function geometricAverage( $args )
	{
		if( $size = sizeof( $args ) )
		{
			foreach( $args as $arg )
				$sum += $arg;
			$average = $sum / $size;
			return $average;
		}
		return 0;
	}

	function pow( $base, $exp )
	{
		$result = pow( abs( $base ), $exp );
		if( $exp % 2 !== 0 )
			$result = - ( $result );
		return $result;
	}
	
	function arithmeticAverage()
	{
		$sum = 1;
		$args	= func_get_args();
		if( is_array( $args[0] ) || count( $args ) > 1 )
		{
			if( is_array( $args[0] ) )
			{
				if( !count( $args[0] ) )
					return 0;
				$args = $args[0];
			}
//			print_r ($args);
			$size	= count( $args );
			foreach( $args as $arg )
				$sum *= $arg;
//			echo "<br>sum: ".($sum);
//			echo "<br>pow: ".(1/$size);
			$average = $this->pow( $sum, ( 1 / $size ) );
//			echo "<br>avg: ".$average;
			return $average;
		}
		return $sum;
	}
}
?>