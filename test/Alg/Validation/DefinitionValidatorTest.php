<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Definition Validator.
 *	@package		Tests.Alg.Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Validation;

use CeusMedia\Common\Alg\Validation\DefinitionValidator;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Definition Validator.
 *	@package		Tests.Alg.Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class DefinitionValidatorTest extends BaseCase
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

	protected $validator;

	protected $labels	= array(
		'test1'	=> 'Test Field 1'
	);

	public function setUp(): void
	{
		$this->validator	= new DefinitionValidator();
#		$this->validator->setLabels( $this->labels );
	}

	public function testConstruct()
	{
		$validator	= new DefinitionValidator();
		ob_start();
		var_dump( $validator );
		$dump	= ob_get_clean();

		$creation	= substr_count( $dump, "PredicateValidator" );
		self::assertEquals( 1, $creation );

		$creation	= substr_count( $dump, "Predicates" );
		self::assertEquals( 1, $creation );
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
		self::assertEquals( $assertion, $creation );


		$this->validator->setLabels( [] );
		$assertion	= array(
			"Field 'test1' is mandatory.",
		);
		$creation	= $this->validator->validate( "test1", $this->definition['test1'], "" );
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
	}*/

	public function testValidatePass1()
	{
		$assertion	= [];
		$creation	= $this->validator->validate( $this->definition['test1'], "abc123" );
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
	}
}
