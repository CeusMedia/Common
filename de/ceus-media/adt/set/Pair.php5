<?php
/**
 *	Pair.
 *	@package		adt.set
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Pair.
 *	@package		adt.set
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Pair
{
	/**	@var		mixed		$key 		Key of Pair */
	protected $key;
	/**	@var		mixed		$value		Value of Pair */
	protected $value;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed		$key		Key of Pair
	 *	@param		mixed		$value		Value of Pair
	 *	@return		void
	 */
	public function Pair( $key, $value )
	{
		$this->key		= $key;
		$this->value	= $value;
	}

	/**
	 *	Returns Pair Key.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 *	Returns Pair Value.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getValue()
	{
		return $this->value;
	}
	
	/**
	 *	Returns Pair as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		$array = array( $this->key, $this->value );
		return $array;
	}

	/**
	 *	Returns Pair as String.
	 *	@access		public
	 *	@return		string
	 */
	public function toString( $startsWith = "(", $endsWith = ")", $delimiter = ", " )
	{
		$string = $startsWith.$this->key.$delimiter.$this->value.$endsWith;
		return $string;
	}
}
?>