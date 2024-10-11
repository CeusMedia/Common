<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Creates instances of Classes using Reflection.
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
 *	@package		CeusMedia_Common_Alg_Object
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Obj;

use ReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 *	Creates instances of Classes using Reflection.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Object
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Factory
{
	protected array $arguments		= [];

	public function __construct( array $arguments = [] )
	{
		$this->setArguments( $arguments );
	}

	/**
	 *	@param		mixed		$argument
	 *	@return		$this
	 */
	public function addArgument( $argument ): self
	{
		$this->arguments[]	= $argument;
		return $this;
	}

	/**
	 *	@param		string		$className		Name of Class
	 *	@param		array		$arguments		List of Arguments for construction
	 *	@return		object
	 *	@throws		ReflectionException
	 * @todo		what? why clone? why keep current instance and append arguments?
	 */
	public function create( string $className, array $arguments = [] ): object
	{
		$factory	= clone $this;
		foreach( $arguments as $argument )
			$factory->addArgument( $argument );
		$arguments	= $factory->getArguments();
		return Factory::createObject( $className, $arguments );
	}

	/**
	 *	Creates an instance of a class using Reflection.
	 *	@access		public
	 *	@static
	 *	@param		string		$className		Name of Class
	 *	@param		array		$arguments		List of Arguments for construction
	 *	@return		object
	 *	@throws		ReflectionException
	 *	@throws		RuntimeException
	 */
	public static function createObject( string $className, array $arguments = [] ): object
	{
		if( !class_exists( $className ) )
			throw new RuntimeException( 'Class "'.$className.'" has not been loaded' );
		$reflectedClass	= new ReflectionClass( $className );
		return $reflectedClass->newInstanceArgs( $arguments );
	}

	public function getArguments(): array
	{
		return $this->arguments;
	}

	public function setArguments( array $arguments = [] ): self
	{
		$this->arguments	= array_values( $arguments );
		return $this;
	}
}
