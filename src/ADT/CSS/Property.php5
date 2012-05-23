<?php
/**
 *	...
 *
 *	Copyright (c) 2011-2012 Christian Würker (ceusmedia.com)
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
 *	@category		cmClasses
 *	@package		ADT.CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.5
 *	@version		$Id$
 */
/**
 *	...
 *
 *	@category		cmClasses
 *	@package		ADT.CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.5
 *	@version		$Id$
 */
class ADT_CSS_Property{

	protected $key;

	protected $value;

	public function __construct( $key, $value ){
		$this->key		= $key;
		$this->value	= $value;
	}

	public function getKey(){
		return $this->key;
	}

	public function getValue(){
		return $this->value;
	}

	public function setKey( $key ){
		$this->key		= $key;
	}

	public function setValue( $value ){
		$this->value	= $value;
	}
}
?>
