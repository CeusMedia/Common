<?php
/**
 *	@package		adt.language
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	@package		adt.language
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 *	@deprecated		not used by Alphabet
 */
class ADT_Language_Sign
{
	protected $value;	

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$value		Value of the Sign
	 *	@return		void
	 */
 	public function __construct( $value )
	{
		$this->setSign( $value );
	}
	
	/**
	 *	Returns the value of the Sign.
	 *	@access		public
	 *	@return		string
	 */
	public function getSign()
	{
		return $this->value;
	}
	
	/**
	 *	Sets the value of the Sign.
	 *	@access		public
	 *	@param		string		$value		Value of the Sign
	 *	@return		void
	 */
	public function setSign( $value )
	{
		$this->value = $value;
	}
}
?>