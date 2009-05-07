<?php
/**
 *	TestUnit of Service Parameter Validator.
 *	@package		Tests.net.service
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_Service_ParameterValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.net.service.ParameterValidator' );
/**
 *	TestUnit of Service Parameter Validator.
 *	@package		Tests.net.service
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_Service_ParameterValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Tests_Net_Service_ParameterValidatorTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->validator	= new Net_Service_ParameterValidator();
		$this->rules		= array(
			'mandatory'	=> TRUE,
			'minLength'	=> 3,
			'maxLength'	=> 8,
			'preg'		=> "@[a-z][0-9]+@",
		);
	}
	
	/**
	 *	Tests Method 'validateFieldValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testValidateFieldValue()
	{
		$assertion	= NULL;
		$creation	= $this->validator->validateParameterValue( $this->rules, 'a12345' );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Exception Method 'validateFieldValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testValidateFieldValueException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->validator->validateParameterValue( $this->rules, '' );
	}
	
	/**
	 *	Tests Exception Method 'validateFieldValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testValidateFieldValueException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->validator->validateParameterValue( $this->rules, 'a1' );
	}
	
	/**
	 *	Tests Exception Method 'validateFieldValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testValidateFieldValueException3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->validator->validateParameterValue( $this->rules, 'a12345678' );
	}
	
	/**
	 *	Tests Exception Method 'validateFieldValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testValidateFieldValueException4()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->validator->validateParameterValue( $this->rules, '12345' );
	}
}
?>