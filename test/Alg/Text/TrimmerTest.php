<?php
/**
 *	TestUnit of Alg_Text_Trimmer.
 *	@package		Tests.alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			27.10.2008
 *	@version		0.1
 */
require_once dirname( dirname( __DIR__ ) ).'/initLoaders.php';
/**
 *	TestUnit of Alg_Text_Trimmer.
 *	@package		Tests.alg
 *	@extends		Test_Case
 *	@uses			Alg_Text_Trimmer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			27.10.2008
 *	@version		0.1
 */
class Test_Alg_Text_TrimmerTest extends Test_Case
{
	/**	@var		string		Default test string */
	protected $string;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->string	= "abcdefghijklmnop";
	}

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
	 *	Tests Method 'trim'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrim()
	{
		$this->assertEquals( $this->string, Alg_Text_Trimmer::trim( $this->string ) );

		$assertion	= "abc...";
		$creation	= Alg_Text_Trimmer::trim( $this->string, 6 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "abc---";
		$creation	= Alg_Text_Trimmer::trim( $this->string, 6, '---' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "ÄÄ-";
		$creation	= Alg_Text_Trimmer::trim( "ÄÄÖÖÜÜ", 3, '-' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'trimCentric'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrimCentricException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Alg_Text_Trimmer::trimCentric( "not_relevant", 2 );
	}

	/**
	 *	Tests Exception of Method 'trimCentric'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrimCentricException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Alg_Text_Trimmer::trimCentric( "not_relevant", 3 );
	}

	/**
	 *	Tests Exception of Method 'trimCentric'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrimCentricException3()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Alg_Text_Trimmer::trimCentric( "not_relevant", 4, "1234" );
	}

	/**
	 *	Tests Method 'trimCentric'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrimCentric()
	{
		$this->assertEquals( $this->string, Alg_Text_Trimmer::trimCentric( $this->string ) );

		$assertion	= "ab...p";
		$creation	= Alg_Text_Trimmer::trimCentric( $this->string, 6 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "a...p";
		$creation	= Alg_Text_Trimmer::trimCentric( $this->string, 5 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "ab...p";
		$creation	= Alg_Text_Trimmer::trimCentric( $this->string, 6 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "ab---p";
		$creation	= Alg_Text_Trimmer::trimCentric( $this->string, 6, '---' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "ab...op";
		$creation	= Alg_Text_Trimmer::trimCentric( $this->string, 7 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "Ä-Ü";
		$creation	= Alg_Text_Trimmer::trimCentric( "ÄÄÖÖÜÜ", 3, '-' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testTrimLeft()
	{
		$assertion	= "abcdefghijklmnop";
		$creation	= Alg_Text_Trimmer::trimLeft( $this->string, 60 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "...efghijklmnop";
		$creation	= Alg_Text_Trimmer::trimLeft( $this->string, 15 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "...nop";
		$creation	= Alg_Text_Trimmer::trimLeft( $this->string, 6 );
		$this->assertEquals( $assertion, $creation );
	}
}
?>