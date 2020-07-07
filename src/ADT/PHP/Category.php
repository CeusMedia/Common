<?php
/**
 *	...
 *
 *	Copyright (c) 2008-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@since			0.3
 *	@deprecated		use CeusMedia/PHP-Parser (https://packagist.org/packages/ceus-media/php-parser) instead
 *	@todo			to be removed in 8.7
 */
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@since			0.3
 *	@deprecated		use CeusMedia/PHP-Parser (https://packagist.org/packages/ceus-media/php-parser) instead
 *	@todo			to be removed in 8.7
 */
class ADT_PHP_Category
{
	protected $categories	= array();
	protected $classes		= array();
	protected $interfaces	= array();
	protected $packages		= array();
	protected $label		= "";
	protected $parent;

	/**
	 *	Constructure, sets Label of Category if given.
	 *	@access		public
	 *	@param		string		$label		Label of Category
	 *	@return		void
	 */
	public function __construct( $label = NULL )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.8.6' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/PHP-Parser"',
			 	'https://packagist.org/packages/ceus-media/php-parser'
			) );
		if( $label )
			$this->setLabel( $label );
	}

	/**
	 *	Relates a Class Object to this Category.
	 *	@access		public
	 *	@param		ADT_PHP_Class		$class			Class Object to relate to this Category
	 *	@return		void
	 */
	public function addClass( ADT_PHP_Class $class )
	{
		$this->classes[$class->getName()]	= $class;
	}

	/**
	 *	Relates a Interface Object to this Category.
	 *	@access		public
	 *	@param		ADT_PHP_Interface	$interface		Interface Object to relate to this Category
	 *	@return		void
	 */
	public function addInterface( ADT_PHP_Interface $interface )
	{
		$this->interfaces[$interface->getName()]	= $interface;
	}

	/**
	 *	@deprecated		not used yet
	 */
	public function getCategories()
	{
		return $this->categories;
	}

	/**
	 *	@deprecated	seems to be unused
	 */
	public function & getClassByName( $name )
	{
		if( isset( $this->classes[$name] ) )
			return $this->classes[$name];
		throw new RuntimeException( "Class '$name' is unknown" );
	}

	public function getClasses()
	{
		return $this->classes;
	}

	public function getId()
	{
#		remark( get_class( $this ).": ".$this->getLabel() );
		$parts	= array();
		$separator	= "_";
		if( $this->parent )
		{
			if( $parent = $this->parent->getId() )
			{
#				remark( $this->parent->getId() );
				if( get_class( $this->parent ) == 'ADT_PHP_Category' )
					$separator	= '-';
				$parts[]	= $parent;
			}
		}
		else
			return NULL;
		$parts[]	= $this->label;
		return implode( $separator, $parts );
	}

	/**
	 *	@deprecated	seems to be unused
	 */
	public function & getInterfaceByName( $name )
	{
		if( isset( $this->interface[$name] ) )
			return $this->interface[$name];
		throw new RuntimeException( "Interface '$name' is unknown" );
	}

	public function getInterfaces()
	{
		return $this->interfaces;
	}

	public function getLabel()
	{
		return $this->label;
	}

	public function & getPackage( $name )
	{
		//  set underscore as separator
		$parts		= explode( "_", str_replace( ".", "_", $name ) );
		//  no Package parts found
		if( !$parts )
			//  break: invalid Package name
			throw new InvalidArgumentException( 'Package name cannot be empty' );
		//  Mainpackage name
		$main	= $parts[0];
		//  Mainpackage is not existing
		if( !array_key_exists( $main, $this->packages ) )
			//  break: unknown Mainpackage
			throw new RuntimeException( 'Package "'.$name.'" is unknown' );
		//  has no Subpackage, must be existing Mainpackage
		if( count( $parts ) == 1 )
			//  return Mainpackage
			return $this->packages[$main];
		//  Subpackage key
		$sub	= implode( "_", array_slice( $parts, 1 ) );
		//  ask for Subpackage in Mainpackage
		return $this->packages[$main]->getPackage( $sub );
	}

	/**
	 *	Returns Map of nested Packages.
	 *	@access		public
	 *	@return		array
	 */
	public function getPackages()
	{
		return $this->packages;
	}

	/**
	 *	Indicates whether Classes are registered in this Category.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasClasses()
	{
		return (bool) count( $this->classes );
	}

	/**
	 *	Indicates whether Interfaces are registered in this Category.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasInterfaces()
	{
		return (bool) count( $this->interfaces );
	}

	public function hasPackage( $name )
	{
		//  set underscore as separator
		$parts		= explode( "_", str_replace( ".", "_", $name ) );
		//  no Package parts found
		if( !$parts )
			//  break: invalid Package name
			throw new InvalidArgumentException( 'Package name cannot be empty' );
		//  Mainpackage name
		$main	= $parts[0];
		//  Mainpackage is not existing
		if( !array_key_exists( $main, $this->packages ) )
			//  break: unknown Mainpackage
			return FALSE;
		//  has no Subpackage
		if( count( $parts ) == 1 )
			//  must be existing Mainpackage
			return TRUE;
		//  Subpackage key
		$sub	= implode( "_", array_slice( $parts, 1 ) );
		//  ask for Subpackage in Mainpackage
		return $this->packages[$main]->hasPackage( $sub );
	}

	/**
	 *	Indicates whether Packages are registered in this Category.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasPackages()
	{
		return count( $this->packages ) > 0;
	}

	public function setLabel( $string )
	{
		$this->label	= $string;
	}

	public function setPackage( $name, ADT_PHP_Category $package )
	{
		//  set underscore as separator
		$parts		= explode( "_", str_replace( ".", "_", $name ) );
		//  no Package parts found
		if( !$parts )
			//  break: invalid Package name
			throw new InvalidArgumentException( 'Package name cannot be empty' );
		//  Mainpackage name
		$main	= $parts[0];
		//  has Subpackage
		if( count( $parts ) > 1 )
		{
			//  Subpackage key
			$sub	= implode( "_", array_slice( $parts, 1 ) );
			//  Mainpackage is not existing
			if( !array_key_exists( $main, $this->packages ) )
			{
				//  create empty Mainpackage for now
				$this->packages[$main]	= new ADT_PHP_Package( $main );
				$this->packages[$main]->setParent( $this );
			}
			//  give Subpackage to Mainpackage
			$this->packages[$main]->setPackage( $sub, $package );
		}
		else
		{
			//  Package is not existing
			if( !array_key_exists( $name, $this->packages ) )
			{
				//  add Package
				$this->packages[$name]	= $package;
				$this->packages[$name]->setParent( $this );
			}
			else
			{
				//  iterate Classes in Package
				foreach( $package->getClasses() as $class )
					//  add Class to existing Package
					$this->packages[$name]->addClass( $class );
				//  iterate Interfaces in Package
				foreach( $package->getInterfaces() as $interface )
					//  add Interface to existing Package
					$this->packages[$name]->addInterface( $interface );
			}
//  iterate Files
#			foreach( $package->getFiles() as $file )
//  add File to existing Package
#				$this->packages[$name]->setFile( $file->basename, $file );
		}
	}

	public function setParent( ADT_PHP_Category $parent )
	{
		$this->parent	= $parent;
	}
}
