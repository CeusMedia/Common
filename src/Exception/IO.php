<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Exception for Input/Output Errors.
 *	Stores an additional resource and is serializable.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Exception;

use CeusMedia\Common\Exception\Traits\Creatable as CreatableTrait;
use CeusMedia\Common\Exception\Traits\Descriptive as DescriptiveTrait;
use CeusMedia\Common\Exception\Traits\Jsonable as JsonableTrait;
use CeusMedia\Common\Exception\Traits\Serializable as SerializableTrait;
use Throwable;

/**
 *	Exception for Input/Output Errors.
 *	Stores an additional resource and is serializable.
 *	@category		Library
 *	@package		CeusMedia_Common_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class IO extends Runtime
{
	use CreatableTrait;
	use JsonableTrait;
	use SerializableTrait;
	use DescriptiveTrait;

	/**	@var		string			$resource		Name or Value of resource which was not fully accessible */
	protected string $resource		= '';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$message		Error Message
	 *	@param		integer			$code			Error Code
	 *	@param		string			$resource		Name or Value of unavailable Resource
	 *	@param		Throwable|NULL	$previous		Previous exception
	 *	@return		void
	 */
	public function __construct( string $message, int $code = 0, string $resource = '', ?Throwable $previous = NULL )
	{
		parent::__construct( $message, $code, $previous );
		$this->resource	= $resource;
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
}
