<?php
/**
 *	Bubble Sort.
 *	@package	alg
 *	@subpackage	sort
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Bubble Sort.
 *	@package	alg
 *	@subpackage	sort
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class BubbleSort
{

	/**
	 *
	 */
	function sort($list)
	{
		for ($i=sizeof($list)-1; $i>=1; $i--)
			for ($j=0; $j<$i; $j++)
				if ($list[$j] > $list[$j+1]) 
					$this->_swap($list, $j, $j+1);
		return $list;
	}

	/**
	 *
	 */
	function _swap (&$list, $pos1, $pos2)
	{
		$mem = $list[$pos1];
		$list[$pos1] = $list[$pos2];
		$list[$pos2] = $mem;
	}
}
?>