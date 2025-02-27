<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Alg_Parcel_Packet.
 *	@package		Tests.alg.parcel
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Alg\Parcel;

use CeusMedia\Common\Alg\Parcel\Packet as ParcelPacket;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Alg_Parcel_Packet.
 *	@package		Tests.alg.parcel
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class PacketTest extends BaseCase
{
	protected $packet;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->packet	= new ParcelPacket( 'testPacket' );
		$this->packet->addArticle( 'testArticle1', 0.2 );
		$this->packet->addArticle( 'testArticle2', 0.3 );
		$this->packet->addArticle( 'testArticle3', 0.4 );
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
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$packetName	= "testPacketName";
		$packet		= new ParcelPacket( $packetName );

		$assertion	= $packetName;
		$creation	= $packet->getName();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToString()
	{
		$assertion	= "[testPacket] {testArticle1:1, testArticle2:1, testArticle3:1} (90%)";
		$creation	= (string) $this->packet;
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'addArticle'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddArticle()
	{
		$packet		= new ParcelPacket( 'testPacket' );

		$assertion	= 0;
		$creation	= count( $packet->getArticles() );
		self::assertEquals( $assertion, $creation );

		$packet->addArticle( 'testArticle1', 0.1 );

		$creation	= count( $packet->getArticles() );
		self::assertEquals( 1, $creation );

		$packet->addArticle( 'testArticle1', 0.1 );

		$creation	= count( $packet->getArticles() );
		self::assertEquals( 1, $creation );

		$packet->addArticle( 'testArticle2', 0.2 );

		$assertion	= 2;
		$creation	= count( $packet->getArticles() );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getArticles'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetArticles()
	{
		$articles	= $this->packet->getArticles();

		$assertion	= 3;
		$creation	= count( $articles );
		self::assertEquals( $assertion, $creation );

		$assertion	= array(
			'testArticle1'	=> 1,
			'testArticle2'	=> 1,
			'testArticle3'	=> 1,
		);
		$creation	= $articles;
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetName()
	{
		$assertion	= "testPacket";
		$creation	= $this->packet->getName();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getVolume'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetVolume()
	{
		$assertion	= 0.9;
		$creation	= $this->packet->getVolume();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasVolumeLeft'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasVolumeLeft()
	{
		$creation	= $this->packet->hasVolumeLeft( 0.05 );
		self::assertTrue( $creation );

		$creation	= $this->packet->hasVolumeLeft( 0.1 );
		self::assertTrue( $creation );

		$creation	= $this->packet->hasVolumeLeft( 0.2 );
		self::assertFalse( $creation );
	}
}
