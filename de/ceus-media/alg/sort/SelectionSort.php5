<?php
/**
 *	SelectionSort
 *
 *	@package	sort
 *	@author	Christian Würker
 *
 */
/**
 *	@todo		Code Documentation
 */
class SelectionSort
{
	function sort($list)
	{
		$n = sizeof($list);
		for ($i=0; $i<= $n -1; $i++)
		{
			echo "Liste: ".implode (", ", $list)."<br>";
			$lowest = $this->_getLowest($list, $i, $n);
			echo "<br>$i $lowest<br>";
			$this->_swap($list, $i, $lowest);
			print_m ($list);
		}
		return $list;
	}

	function _getLowest($list, $pos1, $pos2)
	{
		$lowest = $pos1;
		for ($i=$pos1; $i<$pos2; $i++)
			if ($list[$lowest] == $list[$i])
				$lowest = $i;
		return $lowest;
	}

	function _swap(&$list, $pos1, $pos2)
	{
		$mem = $list[$pos1];
		$list[$pos1] = $list[$pos2];
		$list[$pos2] = $mem;
	}
}
?>