<?php
/**
 *	Interface Data Class.
 *
 *	Copyright (c) 2008-2012 Christian Würker (ceusmedia.com)
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
 *	@package		ADT.PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id$
 *	@since			0.3
 */
/**
 *	Interface Data Class.
 *	@category		cmClasses
 *	@package		ADT.PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id$
 *	@since			0.3
 */
class ADT_PHP_Interface
{
	protected $parent			= NULL;

	protected $category			= NULL;
	protected $package			= NULL;
	protected $subpackage		= NULL;
	protected $name				= NULL;

	protected $final			= FALSE;

	protected $extends			= array();
	protected $implementedBy	= array();
	protected $extendedBy		= array();	
	protected $usedBy			= array();
	protected $composedBy		= array();
	protected $receivedBy		= array();
	protected $returnedBy		= array();
	
	protected $description		= NULL;
	protected $since			= NULL;
	protected $version			= NULL;
	protected $licenses			= array();
	protected $copyright		= array();
	
	protected $authors			= array();
	protected $links			= array();
	protected $sees				= array();
	protected $todos			= array();
	protected $deprecations		= array();

	protected $methods			= array();
	
	protected $line				= 0;
	
	/**
	 *	Constructor, binding a ADT_PHP_File.
	 *	@access		public
	 *	@param		ADT_PHP_File	$file		File with contains this interface
	 *	@return		void
	 */
	public function __construct( $name = NULL )
	{
		if( !is_null( $name ) )
			$this->setName( $name );
	}

	public function addReceivingClass( ADT_PHP_Class $class )
	{
		return $this->receivedBy[$class->getName()]	= $class;
	}

	public function addReceivingInterface( ADT_PHP_Interface $interface )
	{
		return $this->receivedBy[$interface->getName()]	= $interface;
	}

	public function addReturningClass( ADT_PHP_Class $class )
	{
		return $this->returnedBy[$class->getName()]	= $class;
	}

	public function addReturningInterface( ADT_PHP_Interface $interface )
	{
		return $this->returnedBy[$interface->getName()]	= $interface;
	}

	/**
	 *	Returns list of author data objects.
	 *	@access		public
	 *	@return		array		List of author data objects
	 */
	public function getAuthors()
	{
		return $this->authors;
	}

	/**
	 *	Returns category.
	 *	@return		string		Category name
	 */
	public function getCategory()
	{
		return $this->category;
	}

	public function getComposingClasses()
	{
		return $this->composedBy;
	}

	/**
	 *	Returns copyright notes.
	 *	@return		array 
	 */
	public function getCopyright()
	{
		return $this->copyright;
	}

	public function getDeprecations()
	{
		return $this->deprecations;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getExtendedInterface()
	{
		return $this->extends;
	}

	public function getExtendingInterfaces()
	{
		return $this->extendedBy;
	}
	
	public function getImplementingClasses()
	{
		return $this->implementedBy;	
	}

	/**
	 *	Returns the full ID of this interface (category_package_file_interface).
	 *	@access		public
	 *	@return		string
	 */
	public function getId()
	{
		$parts	= array();
		if( $this->category )
			$parts[]	= $this->category;
		if( $this->package )
			$parts[]	= $this->package;
#		$parts[]	= $this->parent->getBasename();
		$parts[]	= $this->name;
		return implode( "-", $parts );
	}

	public function getLicenses()
	{
		return $this->licenses;
	}

	/**
	 *	Returns line in code.
	 *	@access		public
	 *	@return		int				Line number in code
	 */
	public function getLine()
	{
		return $this->line;
	}

	public function getLinks()
	{
		return $this->links;
	}

	/**
	 *	Returns a interface method by its name.
	 *	@access		public
	 *	@param		string			$name		Method name
	 *	@return		ADT_PHP_Method		Method data object
	 *	@throws		RuntimeException if method is not existing
	 */
	public function & getMethod( $name )
	{
		if( isset( $this->methods[$name] ) )
			return $this->methods[$name];
		throw new RuntimeException( "Method '$name' is unknown" );
	}

	/**
	 *	Returns a list of method data objects.
	 *	@access		public
	 *	@return		array			List of method data objects
	 */
	public function getMethods( $withMagics = TRUE )
	{
		if( $withMagics )
			return $this->methods;
		else
		{
			$methods	= array();
			foreach( $this->methods as $method )
				if( substr( $method->getName(), 0, 2 ) !== "__" )
					$methods[$method->getName()]	= $method;
			return $methods;
		}
	}

	/**
	 *	Returns name of interface.
	 *	@access		public
	 *	@return		string			Name of interface
	 */
	public function getName()
	{
		if( !$this->name )
			throw new RuntimeException( 'No interface name has been set' );
		return $this->name;
	}

	/**
	 *	Returns full package name.
	 *	@access		public
	 *	@return		string			Package name
	 */
	public function getPackage()
	{
		return $this->package;
	}

	/**
	 *	Returns parent File Data Object.
	 *	@access		public
	 *	@return		ADT_PHP_File		Parent File Data Object
	 *	@throws		Exception		if not parent is set
	 */
	public function getParent()
	{
		if( !is_object( $this->parent ) )
			throw new Exception( 'Parser Error: Interface has no related file' );
		return $this->parent;
	}

	public function getReceivingClasses()
	{
		return $this->receivedBy;
	}

	public function getReturningClasses()
	{
		return $this->returnedBy;
	}

	public function getSees()
	{
		return $this->sees;
	}

	public function getSince()
	{
		return $this->since;
	}

	public function getSubpackage()
	{
		return $this->subpackage;
	}

	/**
	 *	Returns list of todos.
	 *	@access		public
	 *	@return		array			List of todos
	 */
	public function getTodos()
	{
		return $this->todos;
	}
	
	public function getUsingClasses()
	{
		return $this->usedBy;	
	}

	public function getVersion()
	{
		return $this->version;
	}

	/**
	 *	Indicates whether this interface defines methods.
	 *	@access		public
	 *	@return		bool			Flag: interface defines methods
	 */
	public function hasMethods()
	{
		return count( $this->methods ) > 0;
	}
	
	public function isFinal()
	{
		return (bool) $this->final;
	}

	public function merge( ADT_PHP_Interface $artefact )
	{
		if( $this->name != $artefact->getName() )
			throw new Exception( 'Not mergable' );
		if( $artefact->getDescription() )
			$this->setDescription( $artefact->getDescription() );
		if( $artefact->getSince() )
			$this->setSince( $artefact->getSince() );
		if( $artefact->getVersion() )
			$this->setVersion( $artefact->getVersion() );
		if( $artefact->getCopyright() )
			$this->setCopyright( $artefact->getCopyright() );
		if( $artefact->getReturn() )
			$this->setReturn( $artefact->getReturn() );

		foreach( $function->getAuthors() as $author )
			$this->setAuthor( $author );
		foreach( $function->getLinks() as $link )
			$this->setLink( $link );
		foreach( $function->getSees() as $see )
			$this->setSee( $see );
		foreach( $function->getTodos() as $todo )
			$this->setTodo( $todo );
		foreach( $function->getDeprecations() as $deprecation )
			$this->setDeprecation( $deprecation );
		foreach( $function->getThrows() as $throws )
			$this->setThrows( $throws );
		foreach( $function->getLicenses() as $license )
			$this->setLicense( $license );

		//	@todo		many are missing
	}

	/**
	 *	Sets author.
	 *	@access		public
	 *	@param		ADT_PHP_Author	$author		Author data object
	 *	@return		void
	 */
	public function setAuthor( ADT_PHP_Author $author )
	{
		$this->authors[]	= $author;
	}

	/**
	 *	Sets category.
	 *	@param		string			$string		Category name
	 *	@return		void
	 */
	public function setCategory( $string )
	{
		$this->category	= trim( $string );
	}

	public function setComposingClass( ADT_PHP_Class $class )
	{
		$this->composedBy[$class->getName()]	= $class;
	}

	public function setComposingClassName( $className )
	{
		$this->composedBy[$className]	= $className;
	}
	
	public function setCopyright( $string )
	{
		$this->copyright[]	= $string;
	}

	public function setDeprecation( $string )
	{
		$this->deprecations[]	= $string;
	}

	public function setDescription( $string )
	{
		$this->description		= $string;
	}

	public function setExtendedInterface( ADT_PHP_Interface $interface )
	{
		$this->extends	= $interface;
	}

	public function setExtendedInterfaceName( $interface ){
		$this->extends	= $interface;
	}

	public function setExtendingInterfaceName( $interface )
	{
		$this->extendedBy[$interface]	= $interface;
	}

	public function setExtendingInterface( ADT_PHP_Interface $interface )
	{
		$this->extendedBy[$interface->getName()]	= $interface;
	}

	public function setFinal( $isFinal = TRUE )
	{
		$this->final	= (bool) $isFinal;
	}

	public function setImplementingClassByName( $class )
	{
		$this->implementedBy[$class]	= $class;
	}

	public function setImplementingClass( ADT_PHP_Class $class )
	{
		$this->implementedBy[$class->getName()]	= $class;
	}

	public function setLicense( ADT_PHP_License $license )
	{
		$this->licenses[]	= $license;
	}

	/**
	 *	Sets line in code.
	 *	@access		public
	 *	@param		int				Line number in code
	 *	@return		void
	 */
	public function setLine( $number )
	{
		$this->line	= $number;
	}

	public function setLink( $string )
	{
		$this->links[]	= $string;
	}

	/**
	 *	Sets a method.
	 *	@access		public
	 *	@param		ADT_PHP_Method	$method		Method to add to interface
	 *	@return		void
	 */
	public function setMethod( ADT_PHP_Method $method )
	{
		$this->methods[$method->getName()]	= $method;
	}

	/**
	 *	Sets name of interface.
	 *	@access		public
	 *	@return		string			$string		Name of interface
	 */
	public function setName( $string )
	{
		if( empty( $string ) )
			throw new InvalidArgumentException( 'Interface name cannot be empty' );
		$this->name	= $string;
	}

	/**
	 *	Sets package.
	 *	@param		string			$string		Package name
	 *	@return		void
	 */
	public function setPackage( $string )
	{
		$this->package	= $string;
	}
	
	/**
	 *	Sets parent File Data Object.
	 *	@access		public
	 *	@param		ADT_PHP_File		$parent		Parent File Data Object
	 *	@return		void
	 */
	public function setParent( ADT_PHP_File $parent )
	{
		$this->parent	= $parent;
	}

	public function setSee( $string )
	{
		$this->sees[]	= $string;
	}

	public function setSince( $string )
	{
		$this->since	= $string;
	}

	/**
	 *	Sets subpackage.
	 *	@param		string			$string		Subpackage name
	 *	@return		void
	 */
	public function setSubpackage( $string )
	{
		$this->subpackage	= $string;
	}

	/**
	 *	Sets todo notes.
	 *	@access		public
	 *	@param		string			$string		Todo notes
	 *	@return		void
	 */
	public function setTodo( $string )
	{
		$this->todos[]	= $string;
	}

	public function setUsingClass( ADT_PHP_Class $class )
	{
		$this->usedBy[$class->getName()]	= $class;
	}

	public function setUsingClassName( $className )
	{
		$this->usedBy[$className]	= $className;
	}

	public function setVersion( $string )
	{
		$this->version	= $string;
	}
}
?>
