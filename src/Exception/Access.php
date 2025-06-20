<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Exception for problems on accessing a subject (resource) by an object (accessing entity, like user, group or role) driven by a rule.
 *	Is serializable (to PHP, JSON), renderable and describable.
 *	Stores 3 additional properties: object, rule, subject.
 *
 *	Copyright (c) 2025-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2025-2025 Christian Würker
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
 *	Exception for problems on accessing a subject (resource) by an object (accessing entity, like user, group or role) driven by a rule.
 *	Is serializable (to PHP, JSON), renderable and describable.
 *	Stores 3 additional properties: object, rule, subject.
 *	@category		Library
 *	@package		CeusMedia_Common_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2025-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Access extends Runtime
{
	use CreatableTrait;
	use DescriptiveTrait;
	use JsonableTrait;
	use RenderableTrait;
	use SerializableTrait;

	/**	@var	int|string|NULL		$object			Identifier of accessor object */
	protected int|string|NULL $object	= NULL;

	/**	@var	int|string|NULL		$rule			Identifier of rule which denied access */
	protected int|string|NULL $rule		= NULL;

	/**	@var	int|string|NULL		$subject		Identifier of access subject  */
	protected int|string|NULL $subject	= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$message		Exception message
	 *	@param		integer			$code			Exception code
	 *	@param		Throwable|NULL	$previous		Previous exception
	 *	@param		int|string|NULL	$object			Identifier of accessor object
	 *	@param		int|string|NULL	$rule			Identifier of rule which denied access
	 *	@param		int|string|NULL	$subject		Identifier of access subject
	 *	@return		void
	 */
	public function __construct( string $message, int $code = 0, ?Throwable $previous = NULL, int|string $object = NULL, int|string $rule = NULL, int|string $subject = NULL )
	{
		parent::__construct( $message, $code, $previous );
		$this->object	= $object;
		$this->rule		= $rule;
		$this->subject	= $subject;
	}

	/**
	 *	Returns identifier of accessor object.
	 *	@access		public
	 *	@return		int|string|NULL
	 */
	public function getObject(): int|string|NULL
	{
		return $this->object;
	}

	/**
	 *	Returns identifier of rule which denied access.
	 *	@access		public
	 *	@return		int|string|NULL
	 */
	public function getRule(): int|string|NULL
	{
		return $this->rule;
	}

	/**
	 *	Returns identifier of access subject.
	 *	@access		public
	 *	@return		int|string|NULL
	 */
	public function getSubject(): int|string|NULL
	{
		return $this->subject;
	}

	/**
	 *	Sets identifier of accessor object.
	 *	@param		int|string		$object
	 *	@return		static
	 */
	public function setObject( int|string $object ): static
	{
		$this->object	= $object;
		return $this;
	}

	/**
	 *	Sets identifier of rule which denied access.
	 *	@param		int|string		$rule
	 *	@return		static
	 */
	public function setRule( int|string $rule ): static
	{
		$this->rule	= $rule;
		return $this;
	}

	/**
	 *	Sets identifier of access subject.
	 *	@param		int|string		$subject
	 *	@return		static
	 */
	public function setSubject( int|string $subject ): static
	{
		$this->subject	= $subject;
		return $this;
	}
}
