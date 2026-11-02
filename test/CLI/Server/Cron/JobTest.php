<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of CLI\Server\Cron\Job.
 *	@package		Tests.CLI.Server.Cron
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\CLI\Server\Cron;

use CeusMedia\Common\CLI\Server\Cron\Job;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of CLI\Server\Cron\Job.
 *	@package		Tests.CLI.Server.Cron
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
final class JobTest extends BaseCase
{
	public function testCheckMaturity(): void
	{
		$job	= new Job( 'echo 1' );
		foreach( ['minute', 'hour', 'day', 'month', 'weekday'] as $key )
			$job->setOption( $key, ['*'] );
		self::assertTrue( $job->checkMaturity() );

		$job->setOption( 'month', [date( 'm', strtotime( '+1 month' ) )] );
		self::assertFalse( $job->checkMaturity() );
	}
}
