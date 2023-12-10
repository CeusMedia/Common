<?php

namespace CeusMedia\Common\Exception\Traits;

trait Creatable
{
	public static function create( string $message = '', int $code = 0 ): self
	{
		$e		= new static( $message, $code );
		$trace	= $e->getTrace();
		$top	= array_pop( $trace );
		if( '' !== ( $top['file'] ?? '' ) ){
			$e->file	= $top['file'];
			$e->line	= $top['line'];
			$e->trace	= $trace;
		}
		return $e;
	}

	public function setCode( int $code = 0 ): self
	{
		$this->code	= $code;
		return $this;
	}

	public function setMessage( string $message ): self
	{
		$this->message	= $message;
		return $this;
	}

	public function setPrevious( Throwable $previous ): self
	{
		$this->previous	= $previous;
		return $this;
	}
}
