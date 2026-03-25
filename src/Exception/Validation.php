<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Exception for Input Validation Errors, which can be serialized e.G. for NetServices.
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
 *	Exception for Input Validation Errors, which can be serialized e.G. for NetServices.
 *	Is serializable (to PHP, JSON), renderable and describable.
 *	@category		Library
 *	@package		CeusMedia_Common_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Validation extends Runtime
{
	use CreatableTrait;
	use DescriptiveTrait;
	use JsonableTrait;
	use RenderableTrait;
	use SerializableTrait;

	/**	@var		array		$errors			List of Validation Errors */
	protected array $errors		= [];

	/**	@var		string		$form			Name Form in Validation File */
	protected string $form		= '';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$message		Error Message
	 *	@param		array			$errors			List of Validation Errors
	 *	@param		Throwable|NULL	$previous		Previous exception
	 *	@return		void
	 */
	public function __construct( string $message, int $code = 0, ?Throwable $previous = null, array $errors = [], string $form = '' )
	{
		parent::__construct( $message, $code, $previous );
		$this->errors	= $errors;
		$this->form		= $form;
	}

	/**
	 *	Returns List of Validation Errors.
	 *	@access		public
	 *	@return		array
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}

	/**
	 *	Returns Name of Form in Validation File.
	 *	@access		public
	 *	@return		string
	 */
	public function getForm(): string
	{
		return $this->form;
	}

	/**
	 *	Sets List of Validation Errors.
	 *	@access		public
	 *	@param		array		$errors
	 *	@return		static
	 */
	public function setErrors( array $errors ): static
	{
		$this->errors	= $errors;
		return $this;
	}

	/**
	 *	Sets Name of Form in Validation File.
	 *	@access		public
	 *	@param		string		$form
	 *	@return		static
	 */
	public function setForm( string $form ): static
	{
		$this->form	= $form;
		return $this;
	}
}
