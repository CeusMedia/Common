<?php
/**
 *	...
 *
 *	Copyright (c) 2008-2010 Christian Würker (ceus-media.de)
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
 *	@package		adt.php
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id$
 *	@since			0.3
 */
/**
 *	...
 *	@category		cmClasses
 *	@package		adt.php
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id$
 *	@since			0.3
 */
class ADT_PHP_Category
{
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

	public function getLabel()
	{
		return $this->label;
	}
		
	public function & getPackage( $name )
	{
		$parts		= explode( "_", str_replace( ".", "_", $name ) );								//  set underscore as separator
		if( !$parts )																				//  no Package parts found
			throw new InvalidArgumentException( 'Package name cannot be empty' );					//  break: invalid Package name
		$main	= $parts[0];																		//  Mainpackage name
		if( !array_key_exists( $main, $this->packages ) )											//  Mainpackage is not existing
			throw new RuntimeException( 'Package "'.$name.'" is unknown' );							//  break: unknown Mainpackage
		if( count( $parts ) == 1 )																	//  has no Subpackage, must be existing Mainpackage
			return $this->packages[$main];															//  return Mainpackage
		$sub	= implode( "_", array_slice( $parts, 1 ) );											//  Subpackage key
		return $this->packages[$main]->getPackage( $sub );											//  ask for Subpackage in Mainpackage
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
		$parts		= explode( "_", str_replace( ".", "_", $name ) );								//  set underscore as separator
		if( !$parts )																				//  no Package parts found
			throw new InvalidArgumentException( 'Package name cannot be empty' );					//  break: invalid Package name
		$main	= $parts[0];																		//  Mainpackage name
		if( !array_key_exists( $main, $this->packages ) )											//  Mainpackage is not existing
			return FALSE;																			//  break: unknown Mainpackage
		if( count( $parts ) == 1 )																	//  has no Subpackage
			return TRUE;																			//  must be existing Mainpackage
		$sub	= implode( "_", array_slice( $parts, 1 ) );											//  Subpackage key
		return $this->packages[$main]->hasPackage( $sub );											//  ask for Subpackage in Mainpackage
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
		$parts		= explode( "_", str_replace( ".", "_", $name ) );								//  set underscore as separator
		if( !$parts )																				//  no Package parts found
			throw new InvalidArgumentException( 'Package name cannot be empty' );					//  break: invalid Package name
		$main	= $parts[0];																		//  Mainpackage name
		if( count( $parts ) > 1 )																	//  has Subpackage
		{
			$sub	= implode( "_", array_slice( $parts, 1 ) );										//  Subpackage key
			if( !array_key_exists( $main, $this->packages ) )										//  Mainpackage is not existing
			{
				$this->packages[$main]	= new ADT_PHP_Package( $main );								//  create empty Mainpackage for now
				$this->packages[$main]->setParent( $this );
			}
			$this->packages[$main]->setPackage( $sub, $package );									//  give Subpackage to Mainpackage
		}
		else
		{
			if( !array_key_exists( $name, $this->packages ) )										//  Package is not existing
			{
				$this->packages[$name]	= $package;													//  add Package
				$this->packages[$name]->setParent( $this );
			}
			else
			{
				foreach( $package->getClasses() as $class )											//  iterate Classes in Package
					$this->packages[$name]->addClass( $class );										//  add Class to existing Package
				foreach( $package->getInterfaces() as $interface )									//  iterate Interfaces in Package
					$this->packages[$name]->addInterface( $interface );							//  add Interface to existing Package
			}
#			foreach( $package->getFiles() as $file )												//  iterate Files
#				$this->packages[$name]->setFile( $file->basename, $file );							//  add File to existing Package
		}
	}
	
	public function setParent( ADT_PHP_Category $parent )
	{
		$this->parent	= $parent;
	}
}
?>