<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Allows static creation of exceptions.
 *
 *	Copyright (c) 2011-2023 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Exception_Traits
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://fabien.potencier.org/article/9/php-serialization-stack-traces-and-exceptions
 */

namespace CeusMedia\Common\Exception\Traits;

use Throwable;

/**
 *	Allows static creation of exceptions.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Exception_Traits
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://fabien.potencier.org/article/9/php-serialization-stack-traces-and-exceptions
 */
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
