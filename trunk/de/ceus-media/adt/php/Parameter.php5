<?php
/**
 *	Function/Method Parameter Data Class.
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
 *	@version		$Id: Parameter.php5 725 2009-10-20 05:41:39Z christian.wuerker $
 *	@since			0.3
 */
/**
 *	Function/Method Parameter Data Class.
 *	@category		cmClasses
 *	@category		cmClasses
 *	@package		adt.php
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id: Parameter.php5 725 2009-10-20 05:41:39Z christian.wuerker $
 *	@since			0.3
 */
class ADT_PHP_Parameter
{
	protected $parent		= NULL;
	protected $name			= NULL;
	protected $cast			= NULL;
	protected $type			= NULL;
	protected $reference	= NULL;
	protected $description	= NULL;
	protected $default		= NULL;
	protected $line			= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$name			Parameter name
	 *	@param		string			$type			Parameter type
	 *	@param		string			$description	Parameter description
	 *	@return		void
	 */
	public function __construct( $name, $type = NULL, $description = NULL )
	{
		$this->setName( $name );
		if( !is_null( $type ) )
			$this->setType( $type );
		if( !is_null( $description ) )
			$this->setDescription( $description );
	}

	/**
	 *	Returns casted type of parameter.
	 *	@access		public
	 *	@return		mixed			Type string or data object
	 */
	public function getCast()
	{
		return $this->cast;
	}

	/**
	 *	Returns parameter default.
	 *	@access		public
	 *	@return		string			Parameter default
	 */
	public function getDefault()
	{
		return $this->default;
	}

	/**
	 *	Returns parameter description.
	 *	@access		public
	 *	@return		string			Parameter description
	 */
	public function getDescription()
	{
		return $this->description;
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

	/**
	 *	Returns function name.
	 *	@access		public
	 *	@return		string			Function name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 *	Return parent container, an instance of ADT_PHP_Function or ADT_PHP_Class.
	 *	@access		public
	 *	@return		ADT_PHP_Function	Parent container object, instance of ADT_PHP_Function or ADT_PHP_Class, 
	 */
	public function getParent()
	{
		if( !is_object( $this->parent ) )
			throw new RuntimeException( 'Parameter has no related function. Parser Error' );
		return $this->parent;
	}

	/**
	 *	Returns type of parameter.
	 *	@access		public
	 *	@return		mixed			Type string or data object
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 *	Indicates whether parameter is set by reference.
	 *	@access		public
	 *	@return		bool			Flag: Parameter is set by reference
	 */
	public function isReference()
	{
		return (bool) $this->reference;
	}


	public function merge( ADT_PHP_Parameter $parameter )
	{
#		remark( "merging parameter: ".$parameter->getName() );
		if( $this->name != $parameter->getName() )
			throw new Exception( 'Not mergable' );
		if( $parameter->getCast() )
			$this->setCast( $parameter->getCast() );
		if( $parameter->getDefault() )
			$this->setDefault( $parameter->getDefault() );
		if( $parameter->getDescription() )
			$this->setDescription( $parameter->getDescription() );
		if( $parameter->getType() )
			$this->setType( $parameter->getType() );
#		if( $parameter->getParent() )
#			$this->setParent( $parameter->getParent() );
		if( $parameter->isReference() )
			$this->setParent( $parameter->getParent() );

		// @todo		$reference	is missing
	}

	/**
	 *	Sets parameter casted type.
	 *	@access		public
	 *	@param		mixed			$type			Casted type string or data object
	 *	@return		void
	 */
	public function setCast( $type )
	{
		$this->cast	= $type;
	}

	/**
	 *	Sets parameter default.
	 *	@access		public
	 *	@param		string			$string			Parameter default
	 *	@return		void
	 */
	public function setDefault( $string )
	{
		$this->default	= $string;
	}

	/**
	 *	Sets variable description.
	 *	@access		public
	 *	@param		string			$string			Parameter description
	 *	@return		void
	 */
	public function setDescription( $string )
	{
		$this->description	= $string;
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

	/**
	 *	Sets function name.
	 *	@access		public
	 *	@param		string			$string			Parameter name
	 *	@return		void
	 */
	public function setName( $string )
	{
		$this->name	= $string;
	}

	/**
	 *	Sets parent container, an instance of ADT_PHP_Function or ADT_PHP_Class.
	 *	@access		public
	 *	@param		ADT_PHP_Function	$function		Parent container object, instance of ADT_PHP_Function or ADT_PHP_Class, 
	 *	@return		void
	 */
	public function setParent( ADT_PHP_Function $function )
	{
		$this->parent	= $function;
	}

	public function setReference( $bool )
	{
		$this->reference	= (bool) $bool;
	}

	/**
	 *	Sets parameter type.
	 *	@access		public
	 *	@param		mixed			$type			Type string or data object
	 *	@return		void
	 */
	public function setType( $type )
	{
		$this->type	= $type;
	}
}
?>