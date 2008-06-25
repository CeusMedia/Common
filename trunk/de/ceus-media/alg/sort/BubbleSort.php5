<?php
/**
 *	Bubble Sort.
 *	@package		alg.sort
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Bubble Sort.
 *	@package		alg.sort
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Alg_Sort_BubbleSort
{

	/**
	 *	Sorts List with Bubble Sort.
	 *	@access		public
	 *	@param		array		$list		List to sort
	 *	@return		array
	 */
	public static function sort($list)
	{
		for( $i=sizeof( $list ) - 1; $i>=1; $i-- )
			for( $j=0; $j<$i; $j++ )
				if( $list[$j] > $list[$j+1] ) 
					self::swap( $list, $j, $j + 1 );
		return $list;
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
		$memory	= $list[$pos1];
		$list[$pos1] = $list[$pos2];
		$list[$pos2] = $memory;
	}
}
?>