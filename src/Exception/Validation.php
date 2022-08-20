<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Exception for Input Validation Errors, which can be serialized e.G. for NetServices.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Exception;

use Throwable;

/**
 *	Exception for Input Validation Errors, which can be serialized e.G. for NetServices.
 *	@category		Library
 *	@package		CeusMedia_Common_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Validation extends Runtime
{
	/**	@var		array		$errors			List of Validation Errors */
	protected $errors	= array();

	/**	@var		string		$form			Name Form in Validation File */
	protected $form		= "";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$message		Error Message
	 *	@param		array			$errors			List of Validation Errors
	 *	@param		Throwable|NULL	$previous		Previous exception
	 *	@return		void
	 */
	public function __construct( string $message, array $errors = [], $form = '', ?Throwable $previous = null )
	{
		parent::__construct( $message, 0, $previous );
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
	 *	@return		string: string
	 */
	public function getForm(): string
	{
		return $this->form;
	}

	/**
	 *	Returns serial of exception.
	 *	@access		public
	 *	@return		string
	 */
	public function serialize(): string
	{
		return serialize( array( $this->message, $this->code, $this->file, $this->line, $this->errors, $this->form ) );
	}

	/**
	 *	Recreates an exception from its serial.
	 *	@access		public
	 *	@param		string		$data			Serial string of a validation exception
	 *	@return		void
	 */
	public function unserialize( $data )
	{
		list( $this->message, $this->code, $this->file, $this->line, $this->errors, $this->form ) = unserialize( $data );
	}
}
