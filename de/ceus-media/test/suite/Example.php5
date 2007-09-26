<?php
/**
 *	Example Class for a TestSuite.
 *	@package	test
 *	@subpackage	suite
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Example Class for a TestSuite.
 *	@package	test
 *	@subpackage	suite
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class Example
{
	/**
	 *	Function does something unexpectable.
	 *	@access		public
	 *	@return		string
	 */
	function doSomething ()
	{
		$activities = array (
			"humbling around",
			"sniffing the nose",
			"moving a bone",
			"making a funny face"
			);
		srand ((float) microtime() * 10000000);
		$something = array_rand ($activities, 1);
		return $activities[$something];
	}
	
	/**
	 *	Function does absolutely nothing.
	 *	@access		public
	 *	@return		void
	 */
	function doNothing ()
	{
	}
}
function example ()
{
	return new Example;
}
?>