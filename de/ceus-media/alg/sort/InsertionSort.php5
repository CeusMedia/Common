<?php
/**
 *	Insertion Sort.
 *	@package		alg.sort
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Insertion Sort.
 *	@package		alg.sort
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Alg_Sort_InsertionSort
{
	protected $compares	= 0;
	protected $moves	= 0;
	
	/**
	 *	Sorts List with Insertion Sort.
	 *	@access		public
	 *	@param		array		$list		List to sort
	 *	@return		array
	 */
	public function sort( $list )
	{
//		echo "list: ".implode (" | ", $list)."<br>";
		$n	= sizeof( $list );
		for( $i=0; $i<$n; $i++ )
		{
			$temp	= $list[$i];
			$j		= $n - 1;
			while( $j>=0 && $this->moves < 100 )
			{
				if( $list[$j] > $temp )
				{
					$this->moves ++;
					$list = self::swap( $list, $j + 1, $j );
//					echo "list[$i|$j]: ".implode (" | ", $list)."<br>";
					$j--;
				}
				$this->compares ++;
			}
		}
		return $list;
	}

	/**
	 *	Swaps two Elements in List.
	 *	@access		protected
	 *	@param		array		$list		List
	 *	@param		int			$pos1		Position of first Element
	 *	@param		int			$pos1		Position of second Element
	 *	@return		array
	 */
	protected static function swap( $list, $pos1, $pos2 )
	{
		$memory	= $list[$pos1];
		$list[$pos1] = $list[$pos2];
		$list[$pos2] = $memory;
		return $list;
	}
}
?>