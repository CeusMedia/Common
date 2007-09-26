<?php
/**
 *	Transition of a Petri Net.
 *	@package		adt
 *	@subpackage		net
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.06.05
 *	@version		0.4
 */
/**
 *	Transition of a Petri Net.
 *	@package		adt
 *	@subpackage		net
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.06.05
 *	@version		0.4
 */
class Transition
{
	/**	@var	string		_name		Name of the Transition */
	var $_name;
	/**	@var	bool			_enabled		Indicates wheter this Transition is ready to fire */
	var $_enabled = false;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	name		Name of the Transition
	 *	@return		void
	 */
	public function __construct ($name)
	{
		$this->_name = $name;
	}

	/**
	 *	Indicates wheter this Transition is ready to fire.
	 *	@access		public
	 *	@return		bool
	 *	@deprecated	not use in Petri Net Implementation
	 */
	function isEnabled ()
	{
		return $this->_enabled;
	}

	/**
	 *	Sets status to enabled.
	 *	@access		public
	 *	@return		void
	 *	@deprecated	not use in Petri Net Implementation
	 */
	function enable ()
	{
		$this->_enabled = true;
	}

	/**
	 *	Sets status to disabled.
	 *	@access		public
	 *	@return		void
	 *	@deprecated	not use in Petri Net Implementation
	 */
	function disable ()
	{
		$this->_enabled = false;
	}

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	function toString ()
	{
		return "[".$this->_name."]";
	}
}
?>
