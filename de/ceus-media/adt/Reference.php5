<?php
/**
 *	Object Registry using a global Reference.
 *	@package		adt
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Object Registry using a global Reference.
 *	@package		adt
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class ADT_Reference
{
	/**	@var		string		$workspace		Name of the global workspace */
	protected $workspace;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$workspace		Name of the global workspace
	 *	@return		void
	 */
	public function __construct( $workspace = 'REFERENCES' )
	{
		$this->workspace = $workspace;
		if( !( isset( $GLOBALS[$this->workspace] ) && is_array( $GLOBALS[$this->workspace] ) ) )
			$GLOBALS[$this->workspace]	= array();
	}
	
	/**
	 *	Adds a Reference to an Object to workspace.
	 *	@access		public
	 *	@param		string		$name			Name of object to store
	 *	@param		Object		$object			Reference to object to be stored
	 *	@return		void
	 */
	public function add( $name, &$object, $overwrite = false )
	{
		if( $this->has( $name ) && !$overwrite )
			throw new InvalidArgumentException ( 'Refence to "'.$name.'" has already been added (Overwriting not used by function call)' );
		$GLOBALS[$this->workspace][$name] =& $object;
	}
	
	/**
	 *	Returns a Reference to an Object from workspace.
	 *	@access		public
	 *	@param		string		$name			Name of object to get
	 *	@return		Object
	 */
	public function & get( $name )
	{
		if( !$this->has( $name ) )
			throw new InvalidArgumentException( 'No Reference available for Object "'.$name.'"' );
		return $GLOBALS[$this->workspace][$name];
	}
	
	/**
	 *	Returns a List of Object names in workspace.
	 *	@access		public
	 *	@return		array
	 */
	public function getList()
	{
		return array_keys( $GLOBALS[$this->workspace]);
	}

	/**
	 *	Indicates whether a Object Reference is in workspace.
	 *	@access		public
	 *	@param		string		$name			Name of object to be looked for
	 *	@return		bool
	 */
	public function has( $name )
	{
		return in_array( $name, array_keys( $GLOBALS[$this->workspace]) );	
	}
	
	/**
	 *	Removes a Reference to an Object in workspace.
	 *	@access		public
	 *	@param		string		$name			Name of object to removed.
	 *	@return		void
	 */
	public function remove( $name )
	{
		if( $this->has( $name ) )
			unset( $GLOBALS[$this->workspace][$name] );
	}
}
?>