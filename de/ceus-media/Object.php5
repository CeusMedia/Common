<?php
/**
 *	Base Class for other Classes to inherit.
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Base Class for other Classes to inherit.
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Object
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	function __construct()
	{
	}

	/**
	 *	Compares this Object to another Object.
	 *	@access		public
	 *	@return		bool
	 */
	function compareTo( $object )
	{
		$serial1 = serialize( $this );
		$serial2 = serialize( $object );
		return $serial1 === $serial2;
	}

	/**
	 *	Returns the class name of this object.
	 *	@access		public
	 *	@return		string
	 */
	function getClass()
	{
		return get_class( $this );
	}

	/**
	 *	Returns the class name of this object's parent.
	 *	@access		public
	 *	@return		string
	 */
	function getParent()
	{
		return get_parent_class( $this );
	}

	/**
	 *	Returns all members or vars of this object.
	 *	@access		public
	 *	@return		array
	 */
	function getVars()
	{
		return get_object_vars( $this );
	}

	/**
	 *	Returns all methods of this object.
	 *	@access		public
	 *	@return		array
	 */
	function getMethods()
	{
		return get_class_methods( $this );
	}

	/**
	 *	Checks the existence of a method in this object.
	 *	@access		public
	 *	@param		string	method_name		name of method
	 *	@return		bool
	 */
	function hasMethod( $method_name )
	{
		return method_exists( $this, $method_name );
	}

	/**
	 *	Indicates whether a object in an instance or inheritance of a class.
	 *	@access		public
	 *	@param		string	class_name		name of class
	 *	@return		string
	 */
	function isInstanceOf( $class_name )
	{
		return is_a( $this, $class_name );
	}

	/**
	 *	Indicates whether this object instances a subclass of a superclass.
	 *	@access		public
	 *	@param		string	class_name		name of superclass
	 *	@return		bool
	 */
	function isSubclass( $class_name )
	{
		return is_subclass_of( $this, $class_name );
	}

	/**
	 *	Returns an array filled with all information about this object.
	 *	@access		public
	 *	@return		array
	 */
	function getObjectInfo()
	{
		$info['name'] = $this->getClass();
		$info['parent'] = $this->getParent();
		$info['methods'] = $this->getMethods();
		$info['vars'] = $this->getVars();
		return $info;
	}

	/**
	 *	Returns a string representation of the object.
	 *	@access		public
	 *	@return		string
	 */
	function serialize()
	{
		return serialize( $this );
	}

	/**
	 *	Returns a HTML representation in UML of the object.
	 *	@access		public
	 *	@return		string
	 */
	function toUML()
	{
		$methods = array();
		$vars = array();
		$name = $this->getClass();

 		foreach( $this->getMethods() as $method )
		{
			if( substr( $method, 0, 1 ) == "_" )
			{
				$access = "-";
				$method = substr( $method, 1 );
			}
			else $access = "+";
			$methods[] = $access." ".$method;
		}
		sort( $methods );

   		foreach( $this->getVars() as $var => $value )
		{
			if( substr( $var, 0, 1 ) == "_" )
			{
				$access = "-";
				$var = substr( $var, 1 );
			}
			else $access = "+";
			$vars[] = $access." ".$var." [".$value."]";
		}
		sort( $vars );
		$object_table = "
<table cellpadding='2' cellspacing='0' border='1'>
  <tr><td>
    <b>$name</b>
    <hr/>
    ".implode( "<br/>\n    ", $methods )."
    <hr/>
    ".implode( "<br/>\n    ", $vars )."
  </td></tr>
</table>";
		return $object_table;
	}

	/**
	 *	Returns an object for a string representation of the object.
	 *	@access		public
	 *	@param		string		serial		String representation of the object
	 *	@return		Object
	 */
	function & unserialize( $serial )
	{
		return unserialize( $serial );
	}
}
?>