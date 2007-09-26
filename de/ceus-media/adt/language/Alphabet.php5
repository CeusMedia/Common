<?php
import ("de.ceus-media.adt.set.Set");
/**
 *	@package		adt
 *	@subpackage		language
 *	@extends		Set
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	@package		adt
 *	@subpackage		language
 *	@extends		Set
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 *	@todo			Finish Implementation
 *	@todo			Code Documentation
 */
class Alphabet extends Set
{
	/**
	 *	Contructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct ()
	{
		parent::__construct(func_get_args());
	}

	/**
	 *	Adds a Sign to the Alphabet.
	 *	@access		public
	 *	@param		Sign		$sign		Sign to be added
	 *	@return		void
	 */
	function addSign ($sign)
	{
		if ($sign && is_object ($sign) && $sign->getClass() == "sign")
		{
			if (!$this->hasSign ($sign))
			{
				$this->add ($sign);
			}
			else
				trigger_error( "sign '".$sign->getSign ()."' is already in sign set", E_USER_WARNING );
		}
		else
			trigger_error( "from data type of '\$sign', must be abstract data type 'Sign'", E_USER_ERROR );
	}
	
	/**
	 *	Indicates wheter a Sign is in the Alphabet.
	 *	@access		public
	 *	@param		Sign		$sign		Sign to be proved
	 *	@return		bool
	 */
	function hasSign ($sign)
	{
		return $this->has ($sign);
	}
}
?>