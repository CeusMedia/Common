<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common\ADT\JSON;

use CeusMedia\Common\Exception\Conversion as ConversionException;
use Throwable;

class Encoder
{
	public static function create(): self
	{
		return new self();
	}

	public static function do( mixed $data, int $flags = 0 ): string
	{
		return self::create()->encode( $data, $flags );
	}

	public function encode( mixed $data, int $flags = 0 ): string
	{
		try{
			/**	@var string $json */
			$json	= json_encode( $data, $flags | JSON_THROW_ON_ERROR );
			return $json;
		}
		catch( Throwable $t ){
			throw ConversionException::create( 'Conversion to JSON failed: '.$t->getMessage(), 0, $t );
		}
	}
}