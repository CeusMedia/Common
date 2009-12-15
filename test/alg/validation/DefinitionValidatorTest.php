<?php
/**
 *	TestUnit of Definition Validator.
 *	@package		Tests.alg.validation
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Validation_DefinitionValidator
 *	@uses			Alg_Validation_PredicateValidator
 *	@uses			Alg_Validation_Predicates
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of Definition Validator.
 *	@package		Tests.alg.validation
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Validation_DefinitionValidator
 *	@uses			Alg_Validation_PredicateValidator
 *	@uses			Alg_Validation_Predicates
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
class Test_Alg_Validation_DefinitionValidatorTest extends PHPUnit_Framework_TestCase
{
	protected $definition	= array(
		'test1' => array(
			'syntax'	=> array(
				'mandatory'		=> 1,
				'minlength'		=> 3,
				'maxlength'		=> 6,
				'class'			=> "alpha",
			),
			'semantic'	=> array(
				array(
					'predicate'	=> "isId",
					'edge'		=> "",
				),
				array(
					'predicate'	=> 'isPreg',
					'edge'		=> '@^\w+$@',
				),
			),
		),
	);

	protected $labels	= array(
		'test1'	=> 'Test Field 1'
	);

	public function setUp()
	{
		$this->validator	= new Alg_Validation_DefinitionValidator();
		$this->validator->setLabels( $this->labels );
	}

	public function testConstruct()
	{
		$validator	= new Alg_Validation_DefinitionValidator();
		ob_start();
		var_dump( $validator );
		$dump	= ob_get_clean();
		
		$assertion	= 1;
		$creation	= substr_count( $dump, "Alg_Validation_PredicateValidator" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= substr_count( $dump, "Alg_Validation_Predicates" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testSetLabels()
	{
		$labels		= array(
			'test1'	=> "Label 1",
		);
		$this->validator->setLabels( $labels );
		$assertion	= array(
			"Field 'Label 1' is mandatory.",
		);
		$creation	= $this->validator->validate( "test1", $this->definition['test1'], "" );
		$this->assertEquals( $assertion, $creation );


		$this->validator->setLabels( array() );
		$assertion	= array(
			"Field 'test1' is mandatory.",
		);
		$creation	= $this->validator->validate( "test1", $this->definition['test1'], "" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testSetMessages()
	{
		$messages	= array(
			'isMandatory'	=> "%label% needs to be set.",
		);
		$this->validator->setMessages( $messages );
		$assertion	= array(
			"Test Field 1 needs to be set.",
		);
		$creation	= $this->validator->validate( "test1", $this->definition['test1'], "" );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testValidatePass1()
	{
		$assertion	= array();
		$creation	= $this->validator->validate( "test1", $this->definition['test1'], "abc123" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testValidateFail1()
	{
		$assertion	= array(
			"Field 'Test Field 1' must only contain letters and digits.",
			"Field 'Test Field 1' must be at most 6 characters long.",
			"Field 'Test Field 1' must be a valid ID.",
			"Field 'Test Field 1' is not valid.",
		);
		$creation	= $this->validator->validate( "test1", $this->definition['test1'], "123abc#" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testValidateFail2()
	{
		$definition	= $this->definition;
		$definition['test1']['semantic'][]	= array(
			'predicate'	=> "hasPasswordStrength",
			'edge'		=> "30",
		);
		$assertion	= array(
			"Field 'Test Field 1' must have a stronger password.",
		);
		$creation	= $this->validator->validate( "test1", $definition['test1'], "test" );
		$this->assertEquals( $assertion, $creation );
	}
}
?>