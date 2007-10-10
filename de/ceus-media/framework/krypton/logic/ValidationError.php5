<?php
/**
 *	Validation Error.
 *	@package		mv2.logic
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.02.2007
 *	@version		0.1
 */
/**
 *	Validation Error.
 *	@package		mv2.logic
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.02.2007
 *	@version		0.1
 */
class Framework_Krypton_Logic_ValidationError
{
	/**	@var	string		$edge		Edge for semantic validation */
	public $edge;
	/**	@var	string		$field		Name of Field */
	public $field;
	/**	@var	string		$key		Message Key */
	public $key;
	/**	@var	string		$type		Validation Type were Error occured (syntax|sematic) */
	public $type;
	/**	@var	string		$value		Value of Field */
	public $value;
	/**	@var	string		$prefix		Prefix of Field Name */
	public $prefix;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$type		Validation Type were Error occured (syntax|sematic)
	 *	@param		string		$field		Name of Field
	 *	@param		string		$key		Message Key
	 *	@param		string		$value		Situation to be filled in
	 *	@param		string		$edge		Edge for semantic validation
	 *	@param		string		$edge		Field Prefix
	 *	@return		void
	 */
	public function __construct( $type, $field, $key, $value, $edge = false, $prefix = "" )
	{
		$this->type		= $type;
		$this->key		= $key;
		$this->field	= $field;
		$this->value	= $value;
		$this->edge		= $edge;
		$this->prefix	= $prefix;
	}
}
?>