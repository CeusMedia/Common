<?php
/**
 *	Strange Search Algorithm.
 *	@package		alg.search
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Strange Search Algorithm.
 *	@package		alg.search
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Alg_Search_StrangeSearch
{
	/**	@var		int			$counter		internal counter of steps */
	protected $counter;
	
	/**
	 *	Searches in List and returns position if found.
	 *	@access		public
	 *	@param		array		$ist			List to search in
	 *	@param		mixed		$search			Element to search
	 *	@param		int			$left			Left bound
	 *	@param		int			$right			Right bound
	 *	@return 	int
	 */
	public function search( $array, $key, $left = FALSE, $right = FALSE )
	{
		if( !$right )
		{
			$left	= 0;
			$right	= sizeof( $array ) - 1;
			$this->counter = 0;
		}
		$this->counter++;
		$index1	= round( $left + ( $right - $left ) / 3, 0 );
		$index2	= round( $left + ( ( $right-$left ) / 3 ) * 2, 0 );
		//echo "searching from $left to $right [$index1 - $index2]<br>";
		if( $key == $array[$index1] )
			return ":".$index1;
		if( $key == $array[$index2] )
			return ":".$index2;
		if( $left == $right )
			return false;
		if( $key < $array[$index1] )
			return $this->search( $array, $key, $left, $index1 );
		else if( $key >= $array[$index2] )
			return $this->search( $array, $key, $index2, $right );
		else
			return $this->search( $array, $key, $index1 + 1, $index2 - 1 );
	}
}
?>