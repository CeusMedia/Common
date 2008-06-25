<?php
import( 'de.ceus-media.alg.validation.DefinitionValidator' );
/**
 *	Validator for defined Fields.
 *	@package		framework.krypton.core
 *	@extends		Alg_Validation_DefinitionValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			28.08.2006
 *	@version		0.6
 */
/**
 *	Validator for defined Fields.
 *	@package		framework.krypton.core
 *	@extends		Alg_Validation_DefinitionValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			28.08.2006
 *	@version		0.6
 */
class Framework_Krypton_Core_DefinitionValidator extends Alg_Validation_DefinitionValidator
{
	/**
	 *	Generated Message for Validation Error.
	 *	@access		protected
	 *	@param		string		$field		Field
	 *	@param		string		$key		Validator Key
	 *	@param		string		$value		Value to validate
	 *	@param		string		$edge		At least accepted Value
	 *	@param		string		$prefix		Prefix of Input Field
	 *	@return		string
	 */
	protected function handleError( $field, $key, $value, $edge = false, $prefix )
	{
		import( 'de.ceus-media.framework.krypton.logic.ValidationError' );
		return new Framework_Krypton_Logic_ValidationError( $field, $key, $value, $edge, $prefix );
	}

	/**
	 *	Validates Syntax against Field Definition and generates Messages, special Treatment for Select Options with Key '0'.
	 *	@access		public
	 *	@param		string		$field		Field
	 *	@param		string		$data		Field Definition
	 *	@param		string		$value		Value to validate
	 *	@param		string		$prefix	 	Prefix of Input Field
	 *	@return		array
	 */
	public function validate( $field, $definition, $value, $prefix = "" )
	{
		$errors	= parent::validate( $field, $definition, $value, $prefix );
		foreach( $errors as $error )
			if( $error->key == "isMandatory" )
				return $errors;

		if( isset( $definition['syntax']['mandatory'] ) && $definition['syntax']['mandatory'] )
			if( $definition['input']['type'] == "select" )
				if( !$this->validator->validate( $value, 'isNotZero' ) )
					$errors[]	= $this->handleError( $field, 'isMandatory', $value, NULL, $prefix );
		return $errors;
	}
}
?>