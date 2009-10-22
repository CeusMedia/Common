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

	public function getClassFromClassName( $className, ADT_PHP_Interface $relatedClass )
	{
#		file_put_contents( "c.list", implode( "\n", get_declared_classes() ) );
#		remark( "getClassFromClassName: ".$className );
		
		if( !isset( $this->classNameList[$className] ) )
			return NULL;
		$list	= $this->classNameList[$className];
		$category	= $relatedClass->getCategory();
		$package	= $relatedClass->getPackage();
		if( isset( $list[$category][$package] ) )													//  found class in same category same package
			return $list[$category][$package];														//  return class
		if( isset( $list[$category] ) )																//  found class in same category but different package
			return array_shift( $list[$category] );													//  return class
		if( count( $list ) )																		//  found class in different category not looking for package
			return array_shift( array_shift( $list ) );															//  return class


		remark( "!NOT FOUND!" );
/*		if( isset( $list['default'][$relatedClass->package] ) )
			return $list['default'][$relatedClass->package];
		if( isset( $list[$relatedClass->category]['default'] ) )
			return $list[$relatedClass->category]['default'];
*/		
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