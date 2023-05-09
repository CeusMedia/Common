<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest;

use PHPUnit\Framework\TestCase as FrameworkTestCase;

class BaseCase extends FrameworkTestCase
{
	/**	@var		array		$_config */
	protected static array $_config;

	/**	@var		string		$_pathLib */
	protected static string $_pathLib;

	/**
	 *	Constructor, sets internal library path and loads library config file.
	 *	@return		void
	 */
	public function __construct( $name = '' )
	{
		parent::__construct($name);
		self::$_pathLib	= realpath( dirname( __DIR__ ) ).'/';
		self::$_config	= parse_ini_file( self::$_pathLib.'Common.ini', TRUE );
	}
}
