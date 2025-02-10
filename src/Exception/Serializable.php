<?php /** @noinspection SpellCheckingInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Base Exception which can be serialized e.G. for NetServices.
 *
 *	Copyright (c) 2011-2025 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://fabien.potencier.org/article/9/php-serialization-stack-traces-and-exceptions
 */

namespace CeusMedia\Common\Exception;

use Exception;
use Serializable as SerializableInterface;

/**
 *	Base Exception which can be serialized e.G. for NetServices.
 *	@category		Library
 *	@package		CeusMedia_Common_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@deprecated		use trait or exception using the trait, instead
 */
class Serializable extends Exception
{
	/**
	 *	Returns serial of exception.
	 *	@access		public
	 *	@return		array
	 */
	public function __serialize(): array
	{
		return [$this->message, $this->code, $this->file, $this->line];
	}

	/**
	 *	Recreates an exception from its serial.
	 *	@access		public
	 *	@param		array		$data			Serial string of a serialized exception
	 *	@return		void
	 */
	public function unserialize( array $data ): void
	{
		[$this->message, $this->code, $this->file, $this->line]	= $data;
	}
}
