<?php
/**
 *	Insertion Sort.
 *	@package	alg
 *	@subpackage	sort
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Insertion Sort.
 *	@package	alg
 *	@subpackage	sort
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class InsertionSort
{
	var $_compares	= 0;
	var $_moves		= 0;

	public function __construct ()
	{
//		this->
	}	
	
	
	/**
	 *
	 */
	function sort ($list)
	{
//		echo "liste: ".implode (" | ", $list)."<br>";
		$n = sizeof ($list);
		for ($i=0; $i<$n; $i++)
		{
			$temp = $list[$i];
			$j = $n-1;
			while ($j>=0 && $this->_moves < 100)
			{
				if ($list[$j]>$temp)
				{
					//$counter ++;
					$this->_moves ++;
					$list = $this->swap($list,$j+1,$j);
//					echo "liste[$i|$j]: ".implode (" | ", $list)."<br>";
					$j--;
				}
				$this->_compares ++;
			}
		}
		return array ($list);
	}

	/**
	 *
	 */
	function swap ($list, $pos1, $pos2)
	{
		$a = $list[$pos1];
		$list[$pos1] = $list[$pos2];
		$list[$pos2] = $a;
		return $list;
	}
}
?>