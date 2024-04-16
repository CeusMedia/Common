<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Registry Pattern Implementation to store Objects.
 *
 *	Copyright (c) 2010-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT;

/**
 *	@abtract
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
abstract class Singleton
{
	/**	@var		Singleton|NULL		$instance		Instance of Singleton */
	protected static ?Singleton $instance;

	/**
	 *	Constructor is disabled from public context.
	 *	Use static call 'getInstance()' instead of 'new'.
	 *	@access		protected
	 *	@return		void
	 */
	protected function __construct()
	{
	}

	/**
	 *	Cloning this object is not allowed.
	 *	@access		private
	 *	@return		void
	 */
	private function __clone()
	{
	}

	/**
	 *	Returns a single instance of this Singleton class.
	 *	This method is abstract and must be defined in inheriting classes.
	 *	@abstract
	 *	@static
	 *	@access		public
	 *	@return		Singleton		Single instance of this Singleton class
	 */
	abstract public static function getInstance(): self;

	/**
	 *	Builds a single instance of this or inheriting classes.
	 *	The instance will be stored inside and returned on each request.
	 *	Use this method in the 'getInstance()' implementation.
	 *	@access		public
	 *	@param		string			$className		Name of Singleton class
	 *	@return		Singleton
	 */
	protected static function buildInstance( string $className ): Singleton
	{
		//  no instance built, yet
		if( NULL === self::$instance ){
			//  build a single instance and store it
			/** @var Singleton $singleton */
			$singleton	= new $className;
			self::$instance	= $singleton;

		}
		//  return stored instance
		return self::$instance;
	}
}
