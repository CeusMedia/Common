<?php
/**
 *	Registry Pattern Implementation to store Objects.
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@package		ADT
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	@abtract
 *	@category		cmClasses
 *	@package		ADT
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
abstract class ADT_Singleton
{
	/**	@var		ADT_Singleton		$instance		Instance of Singleton */
	protected static $instance;

	/**
	 *	Constructor is disabled from public context.
	 *	Use static call 'getInstance()' instead of 'new'.
	 *	@access		protected
	 *	@return		void
	 */
	protected function __construct(){}

	/**
	 *	Cloning this object is not allowed.
	 *	@access		private
	 *	@return		void
	 */
	private function __clone(){}

	/**
	 *	Returns a single instance of this Singleton class.
	 *	This method is abtract and must be defined in inheriting clases.
	 *	@abstract
	 *	@static
	 *	@access		public
	 *	@return		ADT_Singleton	Single instance of this Singleton class
	 */
	abstract public static function getInstance();

	/**
	 *	Builds a single instance of this or inheriting classes.
	 *	The instance will be stored inside and returned on each request.
	 *	Use this method in the 'getInstance()' implementation.
	 *	@access		public
	 *	@param		string			$className		Name of Singleton class
	 * @return <type>
	 */
	protected static function buildInstance( $className )
	{
		if( !self::$instance )													//  no instance built, yet
			self::$instance	= new $className;									//  build a single instance and store it
		return self::$instance;													//  return stored instance
	}
}
?>