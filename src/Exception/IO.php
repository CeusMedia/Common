<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Exception for Input/Output Errors.
 *	Stores an additional resource and is serializable.
 *	Is serializable (to PHP, JSON), renderable and describable.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Exception;

use CeusMedia\Common\Exception\Traits\Creatable as CreatableTrait;
use CeusMedia\Common\Exception\Traits\Descriptive as DescriptiveTrait;
use CeusMedia\Common\Exception\Traits\Jsonable as JsonableTrait;
use CeusMedia\Common\Exception\Traits\Renderable as RenderableTrait;
use CeusMedia\Common\Exception\Traits\Serializable as SerializableTrait;
use Throwable;

/**
 *	Exception for Input/Output Errors.
 *	Stores an additional resource and is serializable.
 *	Is serializable (to PHP, JSON), renderable and describable.
 *	@category		Library
 *	@package		CeusMedia_Common_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class IO extends Runtime
{
	use CreatableTrait;
	use DescriptiveTrait;
	use JsonableTrait;
	use RenderableTrait;
	use SerializableTrait;

	/**	@var		string			$resource		Name or Value of resource which was not fully accessible */
	protected string $resource		= '';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$message		Error Message
	 *	@param		integer			$code			Error Code
	 *	@param		Throwable|NULL	$previous		Previous exception
	 *	@param		string			$resource		Name or Value of unavailable Resource
	 *	@return		void
	 */
	public function __construct( string $message, int $code = 0, ?Throwable $previous = NULL, string $resource = '' )
	{
		parent::__construct( $message, $code, $previous );
		$this->setResource( $resource );
	}

	/**
	 *	Returns Name of Source which was not fully accessible.
	 *	@access		public
	 *	@return		string
	 */
	public function getResource(): string
	{
		return $this->resource;
	}

	/**
	 *	Sets Name of Source which was not fully accessible.
	 *	@param		string		$resource
	 *	@return		static
	 */
	public function setResource( string $resource ): static
	{
		$this->resource	= $resource;
		return $this;
	}
}
