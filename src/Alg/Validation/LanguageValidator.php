<?php
/**
 *	Validator for Languages (ISO).
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Validation;

use CeusMedia\Common\Net\HTTP\Sniffer\Language as HttpLanguage;
use OutOfRangeException;
use RangeException;

/**
 *	Validator for Languages (ISO).
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class LanguageValidator
{
	/**	@var		array		$allowed		Array of allowed Languages */
	protected array $allowed;

	/**	@var		string		$default		Default Language */
	protected string $default;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array			$allowed		List of allowed Languages
	 *	@param		string|NULL		$default		Default Language
	 *	@return		void
	 */
	public function __construct( array $allowed, string $default = NULL )
	{
		if( !count( $allowed ) )
			throw new RangeException( 'At least one Language must be allowed.' );
		$this->allowed	= $allowed;
		if( NULL !== $default ){
			if( !in_array( $default, $allowed ) )
				throw new OutOfRangeException( 'Default Language must be an allowed Language.' );
			$this->default	= $default;
		}
		else
			$this->default = $this->allowed[0];
	}

	/**
	 *	Returns preferred allowed and accepted Language.
	 *	@access		public
	 *	@param		string	$language		Language to prove
	 *	@return		string|NULL
	 */
	public function getLanguage( string $language ): ?string
	{
		return HttpLanguage::getLanguageFromString( $language, $this->allowed, $this->default );
	}

	/**
	 *	Validates Language statically and returns valid Language.
	 *	@access		public
	 *	@static
	 *	@param		string			$language		Language to validate
	 *	@param		array			$allowed		List of allowed Languages
	 *	@param		string|NULL		$default		Default Language
	 *	@return		string
	 */
	public static function validate( string $language, array $allowed, ?string $default = NULL ): string
	{
		$validator	= new LanguageValidator( $allowed, $default );
		return $validator->getLanguage( $language );
	}
}
