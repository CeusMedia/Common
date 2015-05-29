<?php
/**
 *	Formats Numbers intelligently and adds Units to Bytes and Seconds.
 *
 *	Copyright (c) 2015 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			11.04.2014
 *	@version		$Id$
 */
/**
 *	Formats Numbers intelligently and adds Units to Bytes and Seconds.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			11.04.2014
 *	@version		$Id$
 *	@todo			code doc
 */
class Alg_UnitParser{

	static public $rules	= array(
		'/^([0-9.,]+)$/'		=> 1,
		'/^([0-9.,]+)k$/'		=> 1000,
		'/^([0-9.,]+)kB$/'		=> 1000,
		'/^([0-9.,]+)kiB$/'		=> 1000,
		'/^([0-9.,]+)K$/'		=> 1024,
		'/^([0-9.,]+)KB$/i'		=> 1024,
		'/^([0-9.,]+)m$/'		=> 1000000,
		'/^([0-9.,]+)M$/'		=> 1048576,
		'/^([0-9.,]+)MB$/i'		=> 1048576,
		'/^([0-9.,]+)MiB$/i'	=> 1000000,
		'/^([0-9.,]+)g$/'		=> 1000000000,
		'/^([0-9.,]+)G$/'		=> 1073741824,
		'/^([0-9.,]+)GB$/i'		=> 1073741824,
		'/^([0-9.,]+)GiB$/i'	=> 1000000000,
	);

	static public function parse( $string, $exceptedUnit = NULL ){
		$int	= (int) $string;
		if( $exceptedUnit && strlen( $int ) == strlen( $string ) && $int == $string )
			$string	.= $exceptedUnit;
		$string	= trim( $string );
		$factor	= NULL;
		foreach( self::$rules as $key => $value ){
			if( preg_match( $key, $string ) ){
				$string		= (float) preg_replace( $key, '\\1', $string );
				$factor		= $value;
				break;
			}
		}
		if( $factor !== NULL )																		//  
			return $factor * $string;
		throw new DomainException( 'Given string is not matching any parser rules' );
	}
}
?>