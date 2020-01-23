<?php
/**
 *	TestUnit of Alg_Text_CamelCase.
 *	@package		Tests.alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			22.10.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Alg_Text_CamelCase.
 *	@package		Tests.alg
 *	@extends		Test_Case
 *	@uses			Alg_Text_CamelCase
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			22.10.2008
 *	@version		0.1
 */
class Test_Alg_Text_CamelCaseTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Method 'encode' using String 1.
	 *	@access		public
	 *	@return		void
	 */
	public function testEncodeWithString1()
	{
		$string1	= "test_alpha__test___RDF string";

		$assertion	= "testAlphaTestRdfString";
		$creation	= Alg_Text_CamelCase::encode( $string1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testAlphaTestRdfString";
		$creation	= Alg_Text_CamelCase::encode( $string1, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::encode( $string1, FALSE  );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'encode' using String 2.
	 *	@access		public
	 *	@return		void
	 */
	public function testEncodeWithString2()
	{
		$string2	= "Test_alpha__test___RDF string";

		$assertion	= "testAlphaTestRdfString";
		$creation	= Alg_Text_CamelCase::encode( $string2 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testAlphaTestRdfString";
		$creation	= Alg_Text_CamelCase::encode( $string2, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "testAlphaTestRDFString";
		$creation	= Alg_Text_CamelCase::encode( $string2, FALSE  );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'decode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecode()
	{
		$string1	= "testAlphaTestRdfString";

		$assertion	= "test alpha test rdf string";
		$creation	= Alg_Text_CamelCase::decode( $string1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test_alpha_test_rdf_string";
		$creation	= Alg_Text_CamelCase::decode( $string1, '_' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toPascalCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToPascalCase()
	{
		$string1	= "testAlphaTestRdfString";

		$assertion	= "TestAlphaTestRdfString";
		$creation	= Alg_Text_CamelCase::toPascalCase( $string1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toSnakeCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToSnakeCase()
	{
		$string1	= "testAlphaTestRdfString";

		$assertion	= "test_alpha_test_rdf_string";
		$creation	= Alg_Text_CamelCase::toSnakeCase( $string1, '_' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'validate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testValidate()
	{
		$string1	= "testAlphaTestRdfString";
		$string2	= "testAlphaTestRDFString";
		$string3	= "TestAlphaTestRdfString";
		$string4	= "TestAlphaTestRdf String";

		$creation	= Alg_Text_CamelCase::validate( $string1 );
		$this->assertEquals( TRUE, $creation );

		$creation	= Alg_Text_CamelCase::validate( $string2 );
		$this->assertEquals( TRUE, $creation );

		$creation	= Alg_Text_CamelCase::validate( $string3 );
		$this->assertEquals( FALSE, $creation );

		$creation	= Alg_Text_CamelCase::validate( $string4 );
		$this->assertEquals( FALSE, $creation );
	}
}
