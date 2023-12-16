<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Exception for SQL Errors. Stores SQLSTATE if PDO is used.
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
use CeusMedia\Common\Exception\Traits\Renderable as RenderableTrait;
use CeusMedia\Common\Exception\Traits\Serializable as SerializableTrait;
use Throwable;

/**
 *	Exception for SQL Errors. Stores SQLSTATE if PDO is used.
 *	@category		Library
 *	@package		CeusMedia_Common_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class SQL extends Runtime
{
	use CreatableTrait;
	use DescriptiveTrait;
	use JsonableTrait;
	use RenderableTrait;
	use SerializableTrait;

	/**	@var		string			$defaultMessage		Default Message if SQL Info Message is empty */
	public static string $default	= "Unknown SQL Error.";

	/**	@var		string|NULL		$SQLSTATE			SQLSTATE Code */
	protected ?string $SQLSTATE		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string|NULL		$message		SQL Error Message
	 *	@param		int				$code			SQL Error Code
	 *	@param		string|NULL		$SQLSTATE 		SQLSTATE Code
	 *	@param		Throwable|NULL	$previous		Previous exception
	 *	@return		void
	 */
	public function __construct( ?string $message, int $code = 0, ?string $SQLSTATE = NULL, ?Throwable $previous = null )
	{
		if( NULL === $message || 0 === strlen( trim( $message ) ) )
			$message	= self::$default;
		parent::__construct( $message, $code, $previous);
		$this->SQLSTATE		= $SQLSTATE;
	}

	/**
	 *	Returns SQLSTATE Code delivered by PDO.
	 *	@access		public
	 *	@return		string|NULL
	 *	@see		http://developer.mimer.com/documentation/html_92/Mimer_SQL_Mobile_DocSet/App_Return_Codes2.html
	 *	@see		http://publib.boulder.ibm.com/infocenter/idshelp/v10/index.jsp?topic=/com.ibm.sqls.doc/sqls520.htm
	 */
	public function getSQLSTATE(): ?string
	{
		return $this->SQLSTATE;
	}
}
