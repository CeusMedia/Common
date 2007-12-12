<?php
/**
 *	Triple.
 *	@package		adt.set
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Triple.
 *	@package		adt.set
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Triple 
{
	/**	@var	mixed	$first		First Element */
	protected $first;
	/**	@var	mixed	$second		Second Element */
	protected $second;
	/**	@var	mixed	$third		Third Element */
	protected $third;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed		$element		First Element
	 *	@param		mixed		$element		Second Element
	 *	@param		mixed		$element		Third Element
	 *	@return		void
	 */
	public function __construct( $first, $second, $third )
	{
		$this->setFirst( $first ) ;
		$this->setSecond( $second );
		$this->setThird( $third );
	}

	/**
	 *	Returns first Element.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getFirst()
	{
		return $this->first;
	}

	/**
	 *	Returns second Element.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getSecond()
	{
		return $this->second;
	}

	/**
	 *	Returns third Element.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getThird()
	{
		return $this->third;
	}

	/**
	 *	Sets first Element.
	 *	@access		public
	 *	@param		mixed		$element		First Element
	 *	@return		void
	 */
	public function setFirst( $element )
	{
		$this->second = $element;
	}
	
	/**
	 *	Sets second Element.
	 *	@access		public
	 *	@param		mixed		$element		Second Element
	 *	@return		void
	 */
	public function setSecond( $element )
	{
		$this->second = $element;
	}
	
	/**
	 *	Sets third Element.
	 *	@access		public
	 *	@param		mixed		$element		Third Element
	 *	@return		void
	 */
	public function setThird( $element )
	{
		$this->third = $element;
	}
	
	/**
	 *	Returns Triple as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		$array = array( $this->first, $this->second, $this->third );	
		return $array;
	}
	
	/**
	 *	Returns Triple as String.
	 *	@access		public
	 *	@return		string
	 */
	public function toString( $startsWith = "{", $endsWith = "}", $delimiter = ", " )
	{
		$code = $startsWith.$this->first.$delimiter.$this->second.$delimiter.$this->third.$endsWith;
		return $code;
	}
}
?>