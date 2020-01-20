<?php
/**
 *	TestUnit of Alg_Text_PascalCase.
 *	@package		Tests.alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			22.10.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Alg_Text_PascalCase.
 *	@package		Tests.alg
 *	@extends		Test_Case
 *	@uses			Alg_Text_PascalCase
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			22.10.2008
 *	@version		0.1
 */
class Test_Alg_Text_PascalCaseTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
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

		$assertion	= "TestAlphaTestRdfString";
		$creation	= Alg_Text_PascalCase::encode( $string1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestAlphaTestRdfString";
		$creation	= Alg_Text_PascalCase::encode( $string1, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestAlphaTestRDFString";
		$creation	= Alg_Text_PascalCase::encode( $string1, FALSE );
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

		$assertion	= "TestAlphaTestRdfString";
		$creation	= Alg_Text_PascalCase::encode( $string2 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestAlphaTestRdfString";
		$creation	= Alg_Text_PascalCase::encode( $string2, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "TestAlphaTestRDFString";
		$creation	= Alg_Text_PascalCase::encode( $string2, FALSE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'decode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecode()
	{
		$string1	= "TestAlphaTestRdfString";

		$assertion	= "test alpha test rdf string";
		$creation	= Alg_Text_PascalCase::decode( $string1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test_alpha_test_rdf_string";
		$creation	= Alg_Text_PascalCase::decode( $string1, '_' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toCamelCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToCamelCase()
	{
		$string1	= "TestAlphaTestRdfString";

		$assertion	= "testAlphaTestRdfString";
		$creation	= Alg_Text_PascalCase::toCamelCase( $string1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toSnakeCase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToSnakeCase()
	{
		$string1	= "TestAlphaTestRdfString";

		$assertion	= "test_alpha_test_rdf_string";
		$creation	= Alg_Text_PascalCase::decode( $string1, '_' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'validate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testValidate()
	{
		$string1	= "TestAlphaTestRdfString";
		$string2	= "TestAlphaTestRDFString";
		$string3	= "testAlphaTestRdfString";
		$string4	= "testAlphaTestRdf String";

		$creation	= Alg_Text_PascalCase::validate( $string1 );
		$this->assertEquals( TRUE, $creation );

		$creation	= Alg_Text_PascalCase::validate( $string2 );
		$this->assertEquals( TRUE, $creation );

		$creation	= Alg_Text_PascalCase::validate( $string3 );
		$this->assertEquals( FALSE, $creation );

		$creation	= Alg_Text_PascalCase::validate( $string4 );
		$this->assertEquals( FALSE, $creation );
	}
}
?>
