<?php
/**
 *	Selection Sort.
 *	@package		alg.sort
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Selection Sort.
 *	@package		alg.sort
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Alg_Sort_SelectionSort
{
	/**
	 *	Sorts List with Selection Sort.
	 *	@access		public
	 *	@param		array		$list		List to sort
	 *	@return		array
	 */
	public static function sort( $list )
	{
		$n = sizeof( $list );
		for( $i=0; $i<= $n -1; $i++ )
		{
#			echo "List: ".implode( ", ", $list )."<br>";
			$lowest	= self::getLowest( $list, $i, $n );
#			echo "<br>$i $lowest<br>";
			self::swap( $list, $i, $lowest );
#			print_m ($list);
		}
		return $list;
	}

	/**
	 *	Finds and returns Position of lowest Element in Bounds.
	 *	@access		protected
	 *	@param		array		$list		List
	 *	@param		int			$pos1		Position of lower Bound
	 *	@param		int			$pos1		Position of higher Bound
	 *	@return		int
	 */
	protected static function getLowest( $list, $pos1, $pos2 )
	{
		$lowest = $pos1;
		for( $i=$pos1; $i<$pos2; $i++ )
			if( $list[$lowest] == $list[$i] )
				$lowest = $i;
		return $lowest;
	}

	/**
	 *	Swaps two Elements in List.
	 *	@access		protected
	 *	@param		array		$list		Reference to List
	 *	@param		int			$pos1		Position of first Element
	 *	@param		int			$pos1		Position of second Element
	 *	@return		void
	 */
	protected static function swap( &$list, $pos1, $pos2 )
	{
		$memory = $list[$pos1];
		$list[$pos1] = $list[$pos2];
		$list[$pos2] = $memory;
	}
}
?>