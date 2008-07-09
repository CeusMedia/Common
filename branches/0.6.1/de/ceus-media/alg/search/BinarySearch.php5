<?php
/**
 *	Binary Search Algorithm.
 *	@package		alg.search
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Binary Search Algorithm.
 *	@package		alg.search
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Alg_Search_BinarySearch
{
	/**	@var		int		$counter	internal counter of steps */
	protected $counter;
	
	/**
	 *	Searches in List and returns position if found, else 0.
	 *	@access		public
	 *	@param		array		$list		List to search in
	 *	@param		mixed		$search		Element to search
	 *	@param		int			$pos		Position (initial = 0)
	 *	@return 	int
	 */
	public function search( $list, $search, $pos = 0 )
	{
		$size = sizeof( $list );
		if( $size == 1 )
		{
			if( $list[0] == $search )
				return $list[0];
			else
				return -1;
		}
		else
		{
			$this->counter++;
			$mid = floor( $size / 2 );
			if( $search < $list[$mid] )
			{
				$list = array_slice( $list, 0, $mid );
				return $this->search( $list, $search, $pos );
			}
			else
			{
				$list = array_slice( $list, $mid );
				return $this->search( $list, $search, $pos );
			}
		}
	}
}
?>