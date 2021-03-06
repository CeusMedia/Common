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
 *	@uses			ADT_PHP_Category
 *	@uses			ADT_PHP_Package
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@since			0.3
 *	@deprecated		use CeusMedia/PHP-Parser (https://packagist.org/packages/ceus-media/php-parser) instead
 *	@todo			to be removed in 8.7
 */
class ADT_PHP_Container
{
	protected $files				= array();
	protected $classIdList			= array();
	protected $classNameList		= array();
	protected $interfaceIdList		= array();
	protected $interfaceNameList	= array();

	/**
	 *	Searches for a Class by its Name in same Category and Package.
	 *	Otherwise searches in different Packages and finally in different Categories.
	 *	@access		public
	 *	@param		string				$className			Name of Class to find Data Object for
	 *	@param		ADT_PHP_Interface	$relatedArtefact	A related Class or Interface (for Package and Category Information)
	 *	@return		ADT_PHP_Class
	 *	@throws		Exception			if Class is not known
	 */
	public function getClassFromClassName( string $className, ADT_PHP_Interface $relatedArtefact )
	{
		if( !isset( $this->classNameList[$className] ) )
			throw new Exception( 'Unknown class "'.$className.'"' );
		$list	= $this->classNameList[$className];
		$category	= $relatedArtefact->getCategory();
		$package	= $relatedArtefact->getPackage();

		//  found Class in same Category same Package
		if( isset( $list[$category][$package] ) )
			//  return Data Object of Class
			return $list[$category][$package];

		//  found Class in same Category but different Package
		if( isset( $list[$category] ) )
			//  this is a Guess: return Data Object of guessed Class
			return array_shift( $list[$category] );

		$firstCategory	= array_shift( $list );
		return array_shift( $firstCategory );
	}

	public function & getClassFromId( $id )
	{
		if( !isset( $this->classIdList[$id] ) )
			throw new Exception( 'Class with ID '.$id.' is unknown' );
		return $this->classIdList[$id];
	}

	public function & getFile( string $name )
	{
		if( isset( $this->files[$name] ) )
			return $this->files[$name];
		throw new RuntimeException( "File '$name' is unknown" );
	}

	public function getFileIterator()
	{
		return new ArrayIterator( $this->files );
	}

	public function & getFiles()
	{
		return $this->files;
	}

	public function & getInterfaceFromId( $id )
	{
		if( !isset( $this->interfaceIdList[$id] ) )
			throw new Exception( 'Interface with ID '.$id.' is unknown' );
		return $this->interfaceIdList[$id];
	}

	/**
	 *	Searches for an Interface by its Name in same Category and Package.
	 *	Otherwise is searches in different Packages and finally in different Categories.
	 *	@access		public
	 *	@param		string				$interfaceName		Name of Interface to find Data Object for
	 *	@param		ADT_PHP_Interface	$relatedArtefact	A related Class or Interface (for Package and Category Information)
	 *	@return		ADT_PHP_Interface
	 *	@throws		Exception			if Interface is not known
	 */
	public function getInterfaceFromInterfaceName( string $interfaceName, ADT_PHP_Interface $relatedArtefact )
	{
		if( !isset( $this->interfaceNameList[$interfaceName] ) )
			throw new Exception( 'Unknown interface "'.$interfaceName.'"' );
		$list		= $this->interfaceNameList[$interfaceName];
		$category	= $relatedArtefact->getCategory();
		$package	= $relatedArtefact->getPackage();

		//  found Interface in same Category same Package
		if( isset( $list[$category][$package] ) )
			//  return Data Object of Interface
			return $list[$category][$package];

		//  found Interface in same Category but different Package
		if( isset( $list[$category] ) )
			//  this is a Guess: return Data Object of guessed Interface
			return array_shift( $list[$category] );

		return array_shift( array_shift( $list ) );
	}

	public function hasFile( string $fileName ): bool
	{
		return isset( $this->files[$fileName] );
	}

	/**
	 *	Builds internal index of Classes for direct access bypassing the tree.
	 *	Afterwards the methods getClassFromClassName() and getClassFromId() can be used.
	 *	@access		public
	 *	@param		string		$defaultCategory		Default Category Name
	 *	@param		string		$defaultPackage			Default Package Name
	 *	@return		void
	 *	@todo		move to Environment
	 */
	public function indexClasses( string $defaultCategory = 'default', string $defaultPackage = 'default' )
	{
		foreach( $this->files as $fileName => $file ){
			foreach( $file->getClasses() as $class ){
				$category	= $class->getCategory() ? $class->getCategory() : $defaultCategory;
				$package	= $class->getPackage() ? $class->getPackage() : $defaultPackage;
				$name		= $class->getName();
				$this->classNameList[$name][$category][$package]	= $class;
				$this->classIdList[$class->getId()]	= $class;
			}
		}
	}

	/**
	 *	Builds internal index of Interfaces for direct access bypassing the tree.
	 *	Afterwards the methods getInterfaceFromInterfaceName() and getInterfaceFromId() can be used.
	 *	@access		public
	 *	@param		string		$defaultCategory		Default Category Name
	 *	@param		string		$defaultPackage			Default Package Name
	 *	@return		void
	 *	@todo		move to Environment
	 */
	public function indexInterfaces( string $defaultCategory = 'default', string $defaultPackage = 'default' )
	{
		foreach( $this->files as $fileName => $file ){
			foreach( $file->getInterfaces() as $interface ){
				$category	= $interface->getCategory() ? $interface->getCategory() : $defaultCategory;
				$package	= $interface->getPackage() ? $interface->getPackage() : $defaultPackage;
				$name		= $interface->getName();
				$this->interfaceNameList[$name][$category][$package]	= $interface;
				$this->interfaceIdList[$interface->getId()]	= $interface;
			}
		}
	}

	public function load( array $config )
	{
		if( isset( $config['creator.file.data.archive'] ) ){
			if( 0 !== strlen( trim( $config['creator.file.data.archive'] ) ) ){
				$uri	= $config['doc.path'].$config['creator.file.data.archive'];
				if( file_exists( $uri ) ){
					$serial	= "";
					if( $fp = gzopen( $uri, "r" ) ){
						while( !gzeof( $fp ) )
							$serial	.= gzgets( $fp, 4096 );
						$data	= unserialize( $serial );
						gzclose( $fp );
						return $data;
					}
				}
			}
		}
		if( isset( $config['creator.file.data.serial'] ) ){
			if( 0 !== strlen( trim( $config['creator.file.data.serial'] ) ) ){
				$uri	= $config['doc.path'].$config['creator.file.data.serial'];
				if( file_exists( $uri ) ){
					$serial	= file_get_contents( $uri );
					$data	= unserialize( $serial );
					return $data;
				}
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
	public function save( array $config )
	{
		$serial	= serialize( $this );
		if( !file_exists( $config['doc.path'] ) )
			mkdir( $config['doc.path'], 0777, TRUE );

		if( isset( $config['creator.file.data.archive'] ) ){
			if( 0 !== strlen( trim( $config['creator.file.data.archive'] ) ) ){
				$uri	= $config['doc.path'].$config['creator.file.data.archive'];
				$gz		= gzopen( $uri, 'w9' );
				gzwrite( $gz, $serial );
				gzclose( $gz );
				return;
			}
		}
		if( isset( $config['creator.file.data.serial'] ) ){
			if( 0 !== strlen( trim( $config['creator.file.data.serial'] ) ) ){
				$uri	= $config['doc.path'].$config['creator.file.data.serial'];
				file_put_contents( $uri, $serial );
			}
		}
	}

	public function setFile( string $name, ADT_PHP_File $file ): self
	{
		$this->files[$name]	= $file;
		return $this;
	}
}
