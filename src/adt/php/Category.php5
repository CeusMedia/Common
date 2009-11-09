<?php
/**
 *	...
 *
 *	Copyright (c) 2008-2009 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		adt.php
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id: Category.php5 718 2009-10-19 01:34:14Z christian.wuerker $
 *	@since			0.3
 */
/**
 *	...
 *	@category		cmClasses
 *	@category		cmClasses
 *	@package		adt.php
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id: Category.php5 718 2009-10-19 01:34:14Z christian.wuerker $
 *	@since			0.3
 */
class ADT_PHP_Category
{
	protected $classes	= array();
	protected $packages	= array();
	protected $label	= "";
	protected $parent;
	
	public function __construct( $label = NULL )
	{
		if( $label )
			$this->setLabel( $label );
	}
		
	public function & getClass( $name )
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
	
	public function getPackages()
	{
		return $this->packages;
	}
	
	public function hasClasses()
	{
		return count( $this->classes ) > 1;
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
	
	public function hasPackages()
	{
		return count( $this->packages ) > 0;
	}
	
	public function setClass( $name, ADT_PHP_Class $class )
	{
		$this->classes[$name]	= $class;
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
				foreach( $package->getClasses() as $class )											//  iterate classes in Package
					$this->packages[$name]->setClass( $class->getName(), $class );					//  add Class to existing Package
#			foreach( $package->getFiles() as $file )												//  iterate Files
#				$this->packages[$name]->setFile( $file->basename, $file );							//  add File to existing Package
		}
	}
	
	public function setParent( ADT_PHP_Category $parent )
	{
		$this->parent	= $parent;
	}
	
	public function setLabel( $string )
	{
		$this->label	= $string;
	}
}
?>