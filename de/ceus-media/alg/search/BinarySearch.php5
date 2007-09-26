<?php
/**
 *	Binary Search Algorithm.
 *	@package		alg
 *	@subpackage		search
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Binary Search Algorithm.
 *	@package		alg
 *	@subpackage		search
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class BinarySearch
{
	/**	@var	int		_counter		internal counter of steps */
	var $_counter;
	
	/**
	 *	Searches in List and returns position if found, else 0.
	 *	@access		public
	 *	@param		array	List			List to search in
	 *	@param		mixed	search		Element to search
	 *	@param		int		pos			Position (initial = 0)
	 *	@return 		int
	 */
	function search ($list, $search, $pos = 0)
	{
		$size = sizeof($list);
		if ($size == 1)
		{
			if ($list[0] == $search) return $list[0];
			else return -1;
		}
		else
		{
			$this->_counter ++;
			$mid = floor($size/2);
			if ($search < $list[$mid])
			{
				$list = array_slice($list, 0, $mid);
				return $this->search($list, $search, $pos);
			}
			else
			{
				$list = array_slice($list, $mid);
				return $this->search($list, $search, $pos);
			}
		}
	}
}
?>