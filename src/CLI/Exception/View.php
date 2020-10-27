<?php
class CLI_Exception_View
{
	public function __construct( ?Exception $exception = NULL ){
		if( !is_null( $exception ) )
			$this->setException( $exception );
	}

	public function __toString()
	{
		return $this->render();
	}

	public function setException( Exception $exception ){
		$this->exception	= $exception;
	}

	public function render()
	{
		if( !$this->exception || !$this->exception instanceof Exception )
			throw new InvalidArgumentException( 'No exception set' );
		$e	= $this->exception;
		$lines	= [
			'Exception caught:',
			'- Message:     '.$e->getMessage(),
			'- File:Lines:  '.$e->getFile().':'.$e->getLine(),
			'- Exception:   '.get_class( $e ),
		];
		return join( PHP_EOL, $lines ).PHP_EOL;
	}

	public static function getInstance( ?Exception $exception = NULL )
	{
		return new self( $exception );
	}
}
