<?php

/**
 *	...
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
 *	@package		CeusMedia_Common_ADT_URL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://www.w3.org/Addressing/URL/url-spec.html
 */

namespace CeusMedia\Common\ADT\URL;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_URL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://www.w3.org/Addressing/URL/url-spec.html
 *	@todo			code doc
 *	@phpstan-consistent-constructor
 */
class Parts
{
	public ?string $scheme		= NULL;
	public ?string $host		= NULL;
	public ?int $port			= NULL;
	public ?string $user		= NULL;
	public ?string $pass		= NULL;
	public ?string $path		= NULL;
	public ?string $query		= NULL;
	public ?string $fragment	= NULL;

	/**
	 *	@param		array		$array
	 *	@return		self
	 */
	public static function fromArray( array $array ): self
	{
		$parts	= new self();
		foreach( $array as $key => $value ){
			if( property_exists( $parts, $key ) )
				$parts->$key	= $value;
		}
		return $parts;
	}

	/**
	 *	@return		array
	 */
	public function toArray(): array
	{
		return get_object_vars( $this );
	}
}