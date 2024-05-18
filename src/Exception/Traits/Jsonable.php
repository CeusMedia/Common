<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Allows exception to be converted to JSON.
 *
 *	Copyright (c) 2011-2024 Christian Würker (ceusmedia.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Exception_Traits
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://fabien.potencier.org/article/9/php-serialization-stack-traces-and-exceptions
 */

namespace CeusMedia\Common\Exception\Traits;

use CeusMedia\Common\ADT\JSON\Encoder as JsonEncoder;
use CeusMedia\Common\Exception\Traits\Serializable as SerializableTrait;
use JsonException;
use Throwable;

/**
 *	Allows exception to be converted to JSON.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Exception_Traits
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://fabien.potencier.org/article/9/php-serialization-stack-traces-and-exceptions
 */
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
			return JsonEncoder::create()->encode( $data, JSON_PRETTY_PRINT );
		}
		catch( JsonException $e ){
			return NULL;
		}
	}
}