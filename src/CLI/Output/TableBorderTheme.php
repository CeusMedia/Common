<?php

/**
 *	Progress bar for console output.
 *
 *	Copyright (c) 2020-2025 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_CLI_Output
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2020-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Output;

/**
 *	Progress bar for console output.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Output
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2020-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class TableBorderTheme
{
	public string $otl		= '';
	public string $ot		= '';
	public string $otj		= '';
	public string $otr		= '';
	public string $ol		= '';
	public string $olj		= '';
	public string $or		= '';
	public string $orj		= '';
	public string $obl		= '';
	public string $ob		= '';
	public string $obr		= '';
	public string $obj		= '';
	public string $ij		= '';
	public string $ih		= '';
	public string $iv		= ' ';

	public function set( string $name, string $value ): void
	{
		if( property_exists( $this, $name ) )
			$this->$name = $value;
	}

	public static function createFromArray( array $data ): self
	{
		$o  = new self();
		foreach( $data as $key => $value )
			$o->set( $key, $value );
		return $o;
	}
}
