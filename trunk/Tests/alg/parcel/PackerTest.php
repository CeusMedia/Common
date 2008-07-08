<?php
/**
 *	TestUnit of Alg_Parcel_Packer.
 *	@package		Tests.alg.parcel
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Parcel_Packer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.07.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.alg.parcel.Packer' );
/**
 *	TestUnit of Alg_Parcel_Packer.
 *	@package		Tests.alg.parcel
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Parcel_Packer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.07.2008
 *	@version		0.1
 */
class Tests_Alg_Parcel_PackerTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->articles	= array(
			'a',
			'b',
			'c'
		);
		$this->packets	= array(
			'S'	=> 2,
			'M'	=> 5,
			'L'	=> 8
		);
		$this->volumes	= array(
			'S'	=> array(
				'a'	=> 0.2,
				'b'	=> 0.5,
				'c'	=> 0.8
			),
			'M'	=> array(
				'a'	=> 0.11,
				'b'	=> 0.26,
				'c'	=> 0.4
			),
			'L'	=> array(
				'a'	=> 0.06,
				'b'	=> 0.19,
				'c'	=> 0.27
			)
		);

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
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Alg_Parcel_Packer::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'calculatePackage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCalculatePackage()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Alg_Parcel_Packer::calculatePackage();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'calculatePrice'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCalculatePrice()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Alg_Parcel_Packer::calculatePrice();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPacket'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPacket()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Alg_Parcel_Packer::getPacket();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getPacket'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPacketException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->setExpectedException( 'OutOfRangeException' );
		Alg_Parcel_Packer::getPacket();
	}

	/**
	 *	Tests Exception of Method 'replacePacket'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReplacePacketException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->setExpectedException( 'OutOfRangeException' );
		Alg_Parcel_Packer::replacePacket();
	}

	/**
	 *	Tests Method 'replacePacket'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReplacePacket()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Alg_Parcel_Packer::replacePacket();
		$this->assertEquals( $assertion, $creation );
	}
}
?>