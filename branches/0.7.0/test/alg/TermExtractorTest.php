<?php
/**
 *	TestUnit of Alg_TermExtractor.
 *	@package		Tests.alg
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_TermExtractor
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Alg_TermExtractor.
 *	@package		Tests.alg
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_TermExtractor
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.12.2008
 *	@version		0.1
 */
class Test_Alg_TermExtractorTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->text		= file_get_contents( $this->path."TermExtractorText.txt" );
		$this->black	= $this->path."TermExtractorBlacklist.list";
		$this->terms1	= parse_ini_file( $this->path."TermExtractorTerms1.ini" );
		$this->terms2	= parse_ini_file( $this->path."TermExtractorTerms2.ini" );
		
		foreach( $this->terms1 as $key => $value )
			$this->terms1[$key] = (int) $value;
		foreach( $this->terms2 as $key => $value )
			$this->terms2[$key] = (int) $value;
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
	 *	Tests Method 'getTerms'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTerms1()
	{
		$text	= "aa bb bb cc cc cc";
		$assertion	= array(
			"aa"	=> 1,
			"bb"	=> 2,
			"cc"	=> 3
		);
		$creation	= Alg_TermExtractor::getTerms( $text );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTerms'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTerms2()
	{
		$assertion	= $this->terms1;
		$creation	= Alg_TermExtractor::getTerms( $this->text );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTerms'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTerms3()
	{
		Alg_TermExtractor::loadBlackList( $this->black );
		$assertion	= $this->terms2;
		$creation	= Alg_TermExtractor::getTerms( $this->text );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'loadBlacklist'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadBlacklist()
	{
		$assertion	= explode( "\n", file_get_contents( $this->black ) );
		Alg_TermExtractor::loadBlacklist( $this->black );
		$creation	= Alg_TermExtractor::$blacklist;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setBlacklist'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetBlacklist()
	{
		$list		= array( "a", "b", "b" );

		$assertion	= array_unique( $list );
		Alg_TermExtractor::setBlacklist( $list );
		$creation	= Alg_TermExtractor::$blacklist;
		$this->assertEquals( $assertion, $creation );
	}
}
?>