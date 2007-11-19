<?php
/**
 *	Logic Error.
 *	@package		mv2.logic
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.02.2007
 *	@version		0.1
 */
/**
 *	Logic Error.
 *	@package		mv2.logic
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.02.2007
 *	@version		0.1
 */
class Framework_Krypton_Exception_Logic extends Exception
{
	/**	@var	string		$key		Message Key */
	public $key;
	/**	@var	string		$subject	Subject to be filled in */
	public $subject;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$key			Message Key
	 *	@param		string		$subject		Subject to be filled in
	 *	@param		string		$situation		Situation to be filled in
	 *	@return		void
	 */
	public function __construct( $key, $subject = "" )
	{
		parent::__construct( $key );
		$this->key			= $key;
		$this->subject		= $subject;
	}
}
?>