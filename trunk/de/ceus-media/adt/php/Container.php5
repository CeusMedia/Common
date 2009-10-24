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
 *	@package		ADT_PHP
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id: Container.php5 718 2009-10-19 01:34:14Z christian.wuerker $
 *	@since			0.3
 */
import( 'de.ceus-media.adt.php.Category' );
import( 'de.ceus-media.adt.php.Package' );
/**
 *	...
 *	@category		cmClasses
 *	@category		cmClasses
 *	@package		ADT_PHP
 *	@uses			ADT_PHP_Category
 *	@uses			ADT_PHP_Package
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id: Container.php5 718 2009-10-19 01:34:14Z christian.wuerker $
 *	@since			0.3
 */
class ADT_PHP_Container
{
	protected $files			= array();
	protected $classIdList		= array();
	protected $classNameList	= array();

	/**
	 *	Searches for a Class by its Name in same Category and Package.
	 *	Otherwise is searches in different Packages and finally in different Categories.
	 *	@access		public
	 *	@param		string				$className		Name of Class to find Data Object for
	 *	@param		ADT_PHP_Interface	$relatedClass	A related Class (for Package and Category Information)
	 *	@return		ADT_PHP_Interface
	 *	@throws		Exception			if Class is not known
	 */
	public function getClassFromClassName( $className, ADT_PHP_Interface $relatedClass )
	{
#		file_put_contents( "c.list", implode( "\n", get_declared_classes() ) );
#		remark( "getClassFromClassName: ".$className );
		
		if( !isset( $this->classNameList[$className] ) )
			throw new Exception( 'Unknown class "'.$className.'"' );
		$list	= $this->classNameList[$className];
		$category	= $relatedClass->getCategory();
		$package	= $relatedClass->getPackage();
		if( isset( $list[$category][$package] ) )													//  found Class in same Category same Package
			return $list[$category][$package];														//  return Data Object of Class
		if( isset( $list[$category] ) )																//  found Class in same Category but different Package
		{
			$firstPackage	= array_slice( $list[$category], 0, 1 );								//  this is a Guess: get first Package found for Class Name
			return array_pop( $firstPackage );														//  return Data Object of guessed Class
		}
		$firstCategory	= array_slice( $list, 0, 1 );											//  this is a Guess: get first Category found for Class Name
		$firstPackage	= array_slice( $firstCategory, 0, 1 );									//  this is a Guess: get first Package found for Class Name
		return array_pop( $firstPackage );														//  return Data Object of guessed Class
	}

	public function & getClassFromId( $id )
	{
		if( !isset( $this->classIdList[$id] ) )
			throw new Exception( 'Class with ID '.$id.' is unknown' );
		return $this->classIdList[$id];
	}

	public function & getFile( $name )
	{
		if( isset( $this->files[$name] ) )
			return $this->files[$name];
		throw new RuntimeException( "File '$name' is unknown" );
	}

	public function & getFiles()
	{
		return $this->files;	
	}

	public function getFileIterator()
	{
		return new ArrayIterator( $this->files );
	}

	public function hasFile( $fileName )
	{
		return isset( $this->files[$fileName] );
	}

	/**
	 *	Builds internal index of classes for direct access bypassing the tree.
	 *	Afterwards the methods getClassFromClassName() and getClassFromId() can be used.
	 *	@access		public
	 *	@param		string		$defaultCategory		Default Category Name
	 *	@param		string		$defaultPackage			Default Package Name
	 *	@return		void
	 *	@todo		move to Environment
	 */
	public function indexClasses( $defaultCategory = 'default', $defaultPackage = 'default' )
	{
		foreach( $this->files as $fileName => $file )
		{
			foreach( $file->getClasses() as $class )
			{
				$category	= $class->getCategory() ? $class->getCategory() : $defaultCategory;
				$package	= $class->getPackage() ? $class->getPackage() : $defaultPackage;
				$name		= $class->getName();
				$this->classNameList[$name][$category][$package]	= $class;
				$this->classIdList[$class->getId()]	= $class;
			}
		}
	}

	public function load( $config )
	{
		if( !empty( $config['creator.file.data.archive'] ) )
		{
			$uri	= $config['doc.path'].$config['creator.file.data.archive'];
			if( file_exists( $uri ) )
			{
				$serial	= "";
				if( $fp = gzopen( $uri, "r" ) )
				{
					while( !gzeof( $fp ) )
						$serial	.= gzgets( $fp, 4096 );
					$data	= unserialize( $serial );
					gzclose( $fp );
				}				
				return $data;
			}
		}
		if( !empty( $config['creator.file.data.serial'] ) )
		{
			$uri	= $config['doc.path'].$config['creator.file.data.serial'];
			if( file_exists( $uri ) )
			{
				$serial	= file_get_contents( $uri );
				$data	= unserialize( $serial );
				return $data;
			}
		}
		throw new RuntimeException( 'No data file existing' );
	}
	
	/**
	 *	Stores collected File/Class Data as Serial File or Archive File.
	 *	@access		protected
	 *	@param		array		$data		Collected File / Class Data
	 *	@return		void
	 */
	public function save( $config )
	{
		$serial	= serialize( $this );
		if( !file_exists( $config['doc.path'] ) )
			mkDir( $config['doc.path'], 0777, TRUE );
		if( !empty( $config['creator.file.data.archive'] ) )
		{
			$uri	= $config['doc.path'].$config['creator.file.data.archive'];
			$gz		= gzopen( $uri, 'w9' );
			gzwrite( $gz, $serial );
			gzclose( $gz );
		}
		else if( !empty( $config['creator.file.data.serial'] ) )
		{
			$uri	= $config['doc.path'].$config['creator.file.data.serial'];
			file_put_contents( $uri, $serial );
		}
	}
	
	public function setFile( $name, ADT_PHP_File $file )
	{
		$this->files[$name]	= $file;
	}
}
?>