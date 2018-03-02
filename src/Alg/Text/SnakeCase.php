<?php
/**
 *	Support for strings in snake case.
 *	Converts strings into and from snake case.
 *	Snake case is a string format where all spaces are replaced by underscores.
 *	Example for encoding: Hello World! ---> Hello_World!
 *	Example for decoding: snake_cased_string ---> snake cased string
 *
 *	Copyright (c) 2017-2018 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2017-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.8.3.4
 *	@see			https://en.wikipedia.org/wiki/Snake_case
 */
/**
 *	Support for strings in snake case.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2017-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.8.3.4
 */
class Alg_Text_SnakeCase{

	static public function apply( $string ){
		return self::encode( $string );
	}

	static public function decode( $string ){
		return str_replace( "_", " ", $string );
	}

	static public function encode( $string ){
		return str_replace( " ", "_", $string );
	}

	static public function validate( $string ){
		return self::apply( $string ) === $string;
	}
}
?>
