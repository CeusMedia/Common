<?php
/**
 *	@package	math
 *	@subpackage	syntax
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	@package	math
 *	@subpackage	syntax
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 *	@todo		check sense, finish
 *	@todo		Code Documentation
 */
class FormulaExpression
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$formula		Formula Expression as String
	 *	@return		void
	 */
	public function __construct ($formula)
	{
		$this->_formula = $formula;
	}
	
	function _parseFormula ()
	{
		$fe = $this->getFormula ();
		//	TermTree bauen
	}
	
	function getFormula ()
	{
		return $this->_formula;
	}
	

}
//$e = new FormulaExpression ("y=2*x");
?>