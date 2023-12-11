<?php

namespace CeusMedia\Common\Exception\Traits;

use Throwable;

trait Creatable
{
	public static function create( string $message = '', int $code = 0, ?Throwable $previous = NULL ): self
	{
		$class	= static::class;
		$e		= new $class( $message, $code, $previous );
		$trace	= $e->getTrace();
		$top	= array_pop( $trace );
		if( '' !== ( $top['file'] ?? '' ) ){
			$e->file	= $top['file'];
			$e->line	= $top['line'];
//			$e->trace	= $trace;
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
}
