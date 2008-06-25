<?php
/**
 *	Base Class for other Classes to inherit.
 *	@package		adt
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Base Class for other Classes to inherit.
 *	@package		adt
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class ADT_Object
{
	/**
	 *	Returns Class Name of current Object.
	 *	@access		public
	 *	@return		string
	 */
	public function getClass()
	{
		return get_class( $this );
	}

	/**
	 *	Returns all Methods of current Object.
	 *	@access		public
	 *	@return		array
	 */
	public function getMethods()
	{
		return get_class_methods( $this );
	}

	/**
	 *	Returns an Array with Information about current Object.
	 *	@access		public
	 *	@return		array
	 */
	public function getObjectInfo()
	{
		$info	= array(
			'name'		=> $this->getClass(),
			'parent'	=> $this->getParent(),
			'methods'	=> $this->getMethods(),
			'vars'		=> $this->getVars(),
		);
		return $info;
	}

	/**
	 *	Returns Class Name of Parent Class of current Object.
	 *	@access		public
	 *	@return		string
	 */
	public function getParent()
	{
		return get_parent_class( $this );
	}

	/**
	 *	Returns all Members of current Object.
	 *	@access		public
	 *	@return		array
	 */
	public function getVars()
	{
		return get_object_vars( $this );
	}

	/**
	 *	Indicates whether an Method is existing within current Object.
	 *	@access		public
	 *	@param		string		$methodName		Name of Method to check
	 *	@param		bool		$callableOnly	Flag: also check if Method is callable
	 *	@return		bool
	 */
	public function hasMethod( $methodName, $callableOnly = TRUE )
	{
		if( $callableOnly )
			return method_exists( $this, $methodName ) && is_callable( array( $this, $methodName ) );
		else
			return method_exists( $this, $methodName );
	}

	/**
	 *	Indicates whether current Object is an Instance or Inheritance of a Class.
	 *	@access		public
	 *	@param		string		$className		Name of Class
	 *	@return		string
	 */
	public function isInstanceOf( $className )
	{
		return is_a( $this, $className );
	}

	/**
	 *	Indicates whether current Object has a Class as one of its parent Classes.
	 *	@access		public
	 *	@param		string		$className		Name of parent Class
	 *	@return		bool
	 */
	public function isSubclassOf( $className )
	{
		return is_subclass_of( $this, $className );
	}

	/**
	 *	Returns a String Representation of current Object.
	 *	@access		public
	 *	@return		string
	 */
	public function serialize()
	{
		return serialize( $this );
	}
}
?>