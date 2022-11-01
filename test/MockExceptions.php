<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest;

use Exception;

class MockException extends Exception{}
class MockBadMethodCallException extends MockException{}
class MockBadVarCallException extends MockException{}
class MockBadStaticMethodCallException extends MockException{}
class MockBadStaticVarCallException extends MockException{}
