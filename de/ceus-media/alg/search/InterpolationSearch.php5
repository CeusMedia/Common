<?php
/**
 *	Implementation of interpolation search algorithm for sorted lists of numbers.
 *	@package	alg
 *	@subpackage	search
 *	@extends	Object
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Implementation of interpolation search algorithm for sorted lists of numbers.
 *	@package	alg
 *	@subpackage	search
 *	@extends	Object
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class InterpolationSearch
{
	/**
	 *	Searches in List and returns position if found, else -1.
	 *	@access		public
	 *	@param		array	List			List to search in
	 *	@param		mixed	search		Element to search
	 *	@return 		int
	 */
	function search ($list, $search)
	{
		$lowbound	= 0;								// lowbound - untergrenze
		$highbound	= sizeof($list) - 1;					// highbound - obergrenze
		do
		{
			$index = InterpolationSearch::_calculateIndex ($list, $search, $lowbound, $highbound);
//			echo "[".$lowbound."|".$highbound."]  search_index: ".$index.": ".$list[$index]."<br>";
			if($index < $lowbound || $index > $highbound)
				return -1;
			if($list[$index] == $search)
				return $index;
			if($list[$index] < $search)
				$lowbound	= $index+1;
			else
				$highbound	= $index-1;
		}
		while($lowbound < $highbound);
		return -1;
	}
	
	/**
	 *	Calculates next bound index.
	 *	@access		private
	 *	@param		array	List			List to search in
	 *	@param		mixed	search		Element to search
	 *	@param		int		lowbound		Last lower bound
	 *	@param		int		highbound	Last higher bound
	 *	@return 		int
	 */
	function _calculateIndex ($list, $search, $lowbound, $highbound)
	{
		$span_index	= $list[$highbound]-$list[$lowbound];
		$span_values	= $highbound - $lowbound;
		$span_diff	= $search - $list[$lowbound];
		$index		= $lowbound + round($span_values * ($span_diff / $span_index));
		return $index;	
	}
}
?>