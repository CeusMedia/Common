<?php
/**
 *	Abstract static Cache Store, can be used to implement a static Data Cache.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_Cache
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			13.04.2009
 *	@version		0.1
 */
namespace CeusMedia\Common\ADT\Cache;

/**
 *	Abstract static Cache Store, can be used to implement a static Data Cache.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Cache
 *	@abstract
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			13.04.2009
 *	@version		0.1
 */
abstract class StaticStore
{
	/**
	 *	Returns a Value from Cache by its Key.
	 *	@abstract
	 *	@access		public
	 *	@static
	 *	@param		string		$key			Key of Cache File
	 *	@return		mixed
	 */
	abstract public static function get( $key );

	/**
	 *	Indicates wheter a Value is in Cache by its Key.
	 *	@abstract
	 *	@access		public
	 *	@static
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	abstract public static function has( $key );

	/**
	 *	Removes a Value from Cache by its Key.
	 *	@abstract
	 *	@access		public
	 *	@static
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	abstract public static function remove( $key );

	/**
	 *	Stores a Value in Cache by its Key.
	 *	@abstract
	 *	@access		public
	 *	@static
	 *	@param		string		$key			Key of Cache File
	 *	@param		mixed		$value			Value to store
	 *	@return		void
	 */
	abstract public static function set( $key, $value );
}
?>
