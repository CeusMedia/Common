<?php
import ("de.ceus-media.adt.set.Set");
/**
 *	@package		adt.language
 *	@extends		Set
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	@package		adt.language
 *	@extends		Set
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 *	@todo			Finish Implementation
 *	@todo			Code Documentation
 */
class ADT_Language_Alphabet extends Set
{
	/**
	 *	Contructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct ()
	{
		parent::__construct( func_get_args() );
	}

	/**
	 *	Adds a Sign to the Alphabet.
	 *	@access		public
	 *	@param		ADT_Language_Sign		$sign		Sign to be added
	 *	@return		void
	 */
	public function addSign ($sign)
	{
		if( !( $sign && is_object( $sign ) && is_a( $sign, "ADT_Language_Sign" ) )
			throw new InvalidArgumentException( 'Data Type of  Sign must be "ADT_Language_Sign".' );
		if( $this->hasSign( $sign ) )
			throw new InvalidArgumentException( 'Sign "'.$sign->getSign ().'" is already in Sign Set.' );
		$this->add( $sign );
	}
	
	/**
	 *	Indicates wheter a Sign is in the Alphabet.
	 *	@access		public
	 *	@param		ADT_Language_Sign		$sign		Sign to be proved
	 *	@return		bool
	 */
	public function hasSign( $sign )
	{
		return $this->has( $sign );
	}
}
?>