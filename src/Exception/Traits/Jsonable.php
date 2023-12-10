<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common\Exception\Traits;

use CeusMedia\Common\Exception\Traits\Serializable as SerializableTrait;
use JsonException;
use Throwable;

trait Jsonable
{
	/**
	 * @return string|NULL
	 */
	public function getJson(): ?string
	{
		try{
			$classParts	= explode( '\\', static::class );
			if( in_array( SerializableTrait::class, class_uses( $this ) ) ){
				$data	= array_merge( [
					'class'		=> static::class,
					'type'		=> end( $classParts ),
				], $this->__serialize() );
			}
			else {
				$data	= [
					'class'		=> static::class,
					'type'		=> end( $classParts ),
					'message'	=> $this->getMessage(),
					'code'		=> $this->getCode(),
					'file'		=> $this->getFile(),
					'line'		=> $this->getLine(),
					'trace'		=> $this->getTrace(),
					'previous'	=> $this->getPrevious(),
				];
			}
			return json_encode( $data, JSON_PRETTY_PRINT|JSON_THROW_ON_ERROR );
		}
		catch( JsonException $e ){
			return NULL;
		}
	}
}