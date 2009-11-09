<?php
/**
 *	File Data Class.
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
 *	@version		$Id: File.php5 718 2009-10-19 01:34:14Z christian.wuerker $
 *	@since			0.3
 */
/**
 *	File Data Class.
 *	@category		cmClasses
 *	@category		cmClasses
 *	@package		adt.php
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id: File.php5 718 2009-10-19 01:34:14Z christian.wuerker $
 *	@since			0.3
 */
class ADT_PHP_File
{
	protected $basename		= NULL;
	protected $pathname		= NULL;
	protected $uri			= NULL;

	protected $description	= NULL;
	protected $category		= NULL;
	protected $package		= NULL;
	protected $subpackage	= NULL;
	protected $since		= NULL;
	protected $version		= NULL;
	protected $licenses		= array();
	protected $copyright	= NULL;
	
	protected $authors		= array();
	protected $links		= array();
	protected $sees			= array();
	protected $todos			= array();
	protected $deprecations		= array();
/*	protected $usedClasses	= array();*/

	protected $functions	= array();
	protected $classes		= array();
	protected $interfaces	= array();
	
	protected $sourceCode	= "";
	public $unicode;

	public function getAuthors()
	{
		return $this->authors;
	}

	public function getBasename()
	{
		return $this->basename;
	}

	public function getCategory()
	{
		return $this->category;
	}
	
	public function & getClass( $name )
	{
		if( isset( $this->classes[$name] ) )
			return $this->classes[$name];
		throw new RuntimeException( 'Class "'.$name.'" is unknown' );
	}

	public function getClasses()
	{
		return $this->classes;
	}

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
	
	public function & getFunction( $name )
	{
		if( isset( $this->functions[$name] ) )
			return $this->functions[$name];
		throw new RuntimeException( 'Function "'.$name.'" is unknown' );
	}
	
	public function getFunctions()
	{
		return $this->functions;
	}
	
	public function getId()
	{
		$parts	= array();
		if( $this->category )
			$parts[]	= $this->category;
		if( $this->package )
			$parts[]	= $this->package;
		$parts[]	= $this->basename;
		return implode( "-", $parts );
	}
	
	public function & getInterface( $name )
	{
		if( isset( $this->interfaces[$name] ) )
			return $this->interfaces[$name];
		throw new RuntimeException( 'Interface "'.$name.'" is unknown' );
	}
	
	public function getInterfaces()
	{
		return $this->interfaces;
	}
	
	public function getLicenses()
	{
		return $this->licenses;
	}
	
	public function getLinks()
	{
		return $this->links;
	}

	public function getPackage()
	{
		return $this->package;
	}

	public function getPathname()
	{
		return $this->pathname;
	}

	public function getSees()
	{
		return $this->sees;
	}

	public function getSince()
	{
		return $this->since;
	}

	public function getSourceCode()
	{
		return $this->sourceCode;
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

	public function getUri()
	{
		return $this->uri;
	}

	public function getVersion()
	{
		return $this->version;
	}

	public function hasClasses()
	{
		return count( $this->classes ) > 0;
	}
	
	public function hasFunctions()
	{
		return count( $this->functions ) > 0;
	}
	
	public function hasInterfaces()
	{
		return count( $this->interfaces ) > 0;
	}
	
	public function hasLinks()
	{
		return count( $this->links ) > 0;
	}

	public function setAuthor( ADT_PHP_Author $author )
	{
		$this->authors[]	= $author;
	}

	public function setBasename( $string )
	{
		$this->basename	= $string;
	}
	
	public function setCategory( $string )
	{
		$this->category	= $string;
	}
	
	public function setClass( $name, ADT_PHP_Class $class )
	{
		$this->classes[$name]	= $class;
	}
	
	public function setCopyright( $string )
	{
		$this->copyright	= $string;
	}

	public function setDeprecation( $string )
	{
		$this->deprecations[]	= $string;
	}

	public function setDescription( $description )
	{
		$this->description	= $description;
	}
	
	public function setFunction( ADT_PHP_Function $function )
	{
		$this->functions[$function->getName()]	= $function;
	}
	
	public function setInterace( ADT_PHP_Interface $interface )
	{
		$this->interfaces[$interface->getName()]	= $interface;
	}
	
	public function setInterfaceName( $interfaceName )
	{
		$this->interfaces[$interfaceName]	= $interfaceName;
	}
	
	public function setLicense( ADT_PHP_License $license )
	{
		$this->licenses[]	= $license;
	}
	
	public function setLink( $string )
	{
		$this->links[]	= $string;
	}
	
	public function setPackage( $string )
	{
		$this->package	= $string;
	}

	public function setPathname( $string )
	{
		$this->pathname		= $string;
	}

	public function setSee( $string )
	{
		$this->sees[]	= $string;
	}

	public function setSince( $string )
	{
		$this->since	= $string;
	}

	public function setSourceCode( $string )
	{
		$this->sourceCode	= $string;
	}

	public function setTodo( $string )
	{
		$this->todos[]	= $string;
	}

	public function setUri( $uri )
	{
		$this->uri	= $uri;
	}

	public function setVersion( $string )
	{
		$this->version	= $string;
	}
}
?>