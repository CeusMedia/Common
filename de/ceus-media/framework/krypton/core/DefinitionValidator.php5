<?php
import( 'de.ceus-media.framework.krypton.logic.ValidationError' );
import( 'de.ceus-media.validation.CountValidator' );
import( 'de.ceus-media.validation.SemanticValidator' );
/**
 *	Validator for defined Fields.
 *	@package		framework.krypton.core
 *	@uses			CountValidator
 *	@uses			SemanticValidator
 *	@uses			Framework_Krypton_Logic_ValidationError
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			28.08.2006
 *	@version		0.6
 */
/**
 *	Validator for defined Fields.
 *	@package		framework.krypton.core
 *	@uses			CountValidator
 *	@uses			SemanticValidator
 *	@uses			Framework_Krypton_Logic_ValidationError
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			28.08.2006
 *	@version		0.6
 */
class Framework_Krypton_Core_DefinitionValidator
{
	/**	@var		array		$cv					Count Validator */
	private	$cv;
	/**	@var		array		$sv					Semantic Validator */
	private $sv;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->cv	= new CountValidator;
		$this->sv	= new SemanticValidator;
	}
	
	/**
	 *	Validates Syntax against Field Definition and generates Messages.
	 *	@access		public
	 *	@param		string		$field		Field
	 *	@param		string		$data		Field Definition
	 *	@param		string		$value		Value to validate
	 *	@param		string		$prefix		Field Prefix
	 *	@return		array
	 */
	public function validateSyntax( $field, $data, $value, $prefix = "" )
	{
		$errors	= array();
		if( strlen( $value ) )
		{
			if( isset( $data['syntax']['class'] ) && !$this->cv->validate2( $value, 'class', $data['syntax']['class'] ) )
				$errors[]	= new Framework_Krypton_Logic_ValidationError( 'syntax', $field, 'class', $value, '', $prefix );
			if( isset( $data['syntax']['minlength'] ) && $data['syntax']['minlength'] && !$this->cv->validate2( $value, 'minlength', $data['syntax']['minlength'] ) )
				$errors[]	= new Framework_Krypton_Logic_ValidationError( 'syntax', $field, 'minlength', $value, $data['syntax']['minlength'], $prefix );
			if( isset( $data['syntax']['maxlength'] ) && $data['syntax']['maxlength'] && !$this->cv->validate2( $value, 'maxlength', $data['syntax']['maxlength'] ) )
				$errors[]	= new Framework_Krypton_Logic_ValidationError( 'syntax', $field, 'maxlength', $value, $data['syntax']['maxlength'], $prefix );
		}
		else if( isset( $data['syntax']['mandatory'] ) && $data['syntax']['mandatory'] )
			$errors[]	= new Framework_Krypton_Logic_ValidationError( 'syntax', $field, 'mandatory', $value, '', $prefix );
		return $errors;
	}
	
	/**
	 *	Validates Semantics against Field Definition and generates Messages.
	 *	@access		public
	 *	@param		string		$field		Field
	 *	@param		string		$data		Field Definition
	 *	@param		string		$value		Value to validate
	 *	@return		array
	 */
	public function validateSemantics( $field, $data, $value )
	{
		$errors	= array();
		if( isset( $data['semantic'] ) )
		{
			foreach( $data['semantic'] as $semantic )
			{
				$param	= array( "'".$value."'" );
				if( strlen( $semantic['edge'] ) )
					$param[]	= "'".$semantic['edge']."'";
				$param	= implode( ", ", $param );
				if( method_exists( $this->sv, $semantic['predicate'] ) )
				{
					$method = "return \$this->sv->".$semantic['predicate']."( ".$param." );";
					if( !eval( $method ) )
						$errors[]	= new Framework_Krypton_Logic_ValidationError( 'semantic', $field, $semantic['predicate'], $value, $semantic['edge'] );
				}
				else
					throw new Exception( "Validator Predicate '".$semantic['predicate']."' does not exist." );
			}
		}
		return $errors;
	}
}
?>
