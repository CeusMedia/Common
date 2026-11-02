<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Net;

use CeusMedia\Common\Net\Connectivity;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net\Connectivity.
 *	@package		Tests.Net
 */
class ConnectivityTest extends BaseCase
{
	public function testUsesTargetAndCallsCallback(): void
	{
		$states		= [];
		$connectivity	= new Connectivity();
		$connectivity->setTarget( 'host.invalid' );
		$connectivity->setCallbackOnChange( static function( int $status ) use ( &$states ){
			$states[]	= $status;
		} );
		self::assertTrue( $connectivity->isOffline() );
		self::assertSame( [Connectivity::STATUS_OFFLINE], $states );

		$connectivity->setMethod( Connectivity::METHOD_PING );
		self::assertFalse( $connectivity->isOnline() );
	}
}
