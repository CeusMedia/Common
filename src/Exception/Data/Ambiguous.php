<?php
/**
 *	...
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

namespace CeusMedia\Common\Exception\Data;

use Exception;
use Throwable;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_Exception_Data
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Ambiguous extends Exception
{
	/**	@var		array		$variants		List of possible variants or... candidates */
	protected $variants	= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$message		Exception message
	 *	@param		integer			$code			Exception code
	 *	@param		Throwable|NULL	$previous		Subject on which this logic exception happened
	 *	@param		array			$variants		List of possible variants or... candidates
	 *	@return		void
	 */
	public function __construct( string $message, int $code = 0, ?Throwable $previous = NULL, array $variants = [] )
	{
		parent::__construct( $message, $code, $previous );
		$this->variants	= $variants;
	}

	/**
	 *	Returns list of possible variants or... candidates.
	 *	@access		public
	 *	@return		array
	 */
	public function getVariants(): array
	{
		return $this->variants;
	}
}
