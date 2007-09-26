<?php
/**
 *	Strange Search Algorithm.
 *	@package		alg
 *	@subpackage		search
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Strange Search Algorithm.
 *	@package		alg
 *	@subpackage		search
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class StrangeSearch
{
	/**	@var	int		_counter		internal counter of steps */
	var $_counter;
	
	/**
	 *	Searches in List and returns position if found.
	 *	@access		public
	 *	@param		array	List			List to search in
	 *	@param		mixed	search		Element to search
	 *	@param		int		left			Left bound
	 *	@param		int		right			Right bound
	 *	@return 		int
	 */
	function search ($array, $key, $left = false, $right = false)
	{
		if (!$right)
		{
			$left = 0;
			$right = sizeof($array)-1;
			$this->counter = 0;
		}
		$this->_counter++;
		$index1 = round($left + ($right-$left)/3, 0);
		$index2 = round($left + (($right-$left)/3)*2, 0);
		//echo "searching from $left to $right [$index1 - $index2]<br>";
		if ($key == $array[$index1]) return ":".$index1;
		if ($key == $array[$index2]) return ":".$index2;
		if ($left == $right) return false;
		if ($key < $array[$index1])
			return $this->search ($array, $key, $left, $index1);
		else if ($key >= $array[$index2])
			return $this->search ($array, $key, $index2, $right);
		else
			return $this->search ($array, $key, $index1+1, $index2-1);
	}
}
?>