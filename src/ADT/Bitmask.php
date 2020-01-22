<?php
/**
 *	Handler for bitmask.
 *
 *	Copyright (c) 2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.8.4.7
 */
/**
 *	Handler for bitmask.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.8.4.7
 */
class ADT_Bitmask{

	protected $bits	= 0;

	public function __construct( $bits = 0 ){
		$this->set( $bits );
	}

	public function add( $bit ){
		if( !is_int( $bit ) )
			throw new InvalidArgumentException( 'Bitmask bit must be of integer' );
		$this->bits |= $bit;
		return $this;
	}

	public function get(){
		return $this->bits;
	}

	public function has( $bit ){
		if( !is_int( $bit ) )
			throw new InvalidArgumentException( 'Bitmask bit must be of integer' );
		return (bool)( $this->bits & $bit );
	}

	public function remove( $bit ){
		if( !is_int( $bit ) )
			throw new InvalidArgumentException( 'Bitmask bit must be of integer' );
		$this->bits	^= $bit;
		return $this;
	}

	public function set( $bits ){
		if( !is_int( $bits ) )
			throw new InvalidArgumentException( 'Bitmask bits must be of integer' );
		$this->bits	= $bits;
		return $this;
	}
}
