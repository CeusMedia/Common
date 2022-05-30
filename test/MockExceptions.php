<?php

namespace CeusMedia\Common\Test;

use Exception;

class MockException extends Exception{}
class MockBadMethodCallException extends MockException{}
class MockBadVarCallException extends MockException{}
class MockBadStaticMethodCallException extends MockException{}
class MockBadStaticVarCallException extends MockException{}
