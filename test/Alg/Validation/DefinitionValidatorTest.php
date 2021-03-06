<?php
/**
 *	TestUnit of Definition Validator.
 *	@package		Tests.alg.validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Definition Validator.
 *	@package		Tests.alg.validation
 *	@extends		Test_Case
 *	@uses			Alg_Validation_DefinitionValidator
 *	@uses			Alg_Validation_PredicateValidator
 *	@uses			Alg_Validation_Predicates
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
class Test_Alg_Validation_DefinitionValidatorTest extends Test_Case
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

	public function setUp(): void
	{
		$this->validator	= new Alg_Validation_DefinitionValidator();
#		$this->validator->setLabels( $this->labels );
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
/*
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
	}*/
/*
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
	}*/

	public function testValidatePass1()
	{
		$assertion	= array();
		$creation	= $this->validator->validate( $this->definition['test1'], "abc123" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testValidateFail1()
	{
		$assertion	= array(
			array( 'isClass', $this->definition['test1']['syntax']['class'] ),
			array( 'hasMaxLength', $this->definition['test1']['syntax']['maxlength'] ),
			array( 'isId', NULL ),
			array( 'isPreg', $this->definition['test1']['semantic'][1]['edge'] )
		);
		$creation	= $this->validator->validate( $this->definition['test1'], "123abc#" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testValidateFail2()
	{
		$definition	= $this->definition;
		$semanticRule	= array(
			'predicate'	=> "hasPasswordStrength",
			'edge'		=> "30",
		);
		$definition['test1']['semantic'][]	= $semanticRule;
		$assertion	= array( array_values( $semanticRule ) );
		$creation	= $this->validator->validate( $definition['test1'], "test" );
		$this->assertEquals( $assertion, $creation );
	}
}
