<?php
/**
 *	Global reference for objects.
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Global reference for objects.
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class Reference
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
		if( !( !$overwrite && $this->has( $name ) ) )
		{
			$GLOBALS[$this->workspace][$name] =& $object;
		}
		else
			trigger_error( "Refence to '".$name."' has already been added (Overwriting not used by function call)", E_USER_WARNING );
	}
	
	/**
	 *	Alias for remove.
	 *	@access		public
	 *	@param		string		$name			Name of object to deleted
	 *	@return		void
	 */
	public function delete( $name )
	{
		$this->remove( $name );
	}
	
	/**
	 *	Returns a Reference to an Object from workspace.
	 *	@access		public
	 *	@param		string		$name			Name of object to get
	 *	@return		Object
	 */
	public function & get( $name )
	{
		if( $this->has( $name ) )
			return $GLOBALS[$this->workspace][$name];
		else
		{
//			print_m( debug_backtrace() );
			$trace	= debug_backtrace();
			$file	= $trace[0]['file'];
			$line	= $trace[0]['line'];
			if( isset( $trace[1] ) )
			{
				if( isset( $trace[1]['object'] ) )
					$message	 = "No Reference available for Object '".$name."' called in Object '".get_class( $trace[1]['object'] )."' while calling '".$trace[1]['class']."::".$trace[1]['function']."' in File '".$trace[1]['file']."' on Line ".$trace[1]['line'];
				else if( isset( $trace[1]['class'] ) )
					$message	 = "No Reference available for Object '".$name."' called in Class '".$trace[1]['class']."->".$trace[1]['function']."' on Line ".$trace[1]['line'];
				else
					$message	 = "No Reference available for Object '".$name."' called in File ".$trace[1]['file']." on Line ".$trace[1]['line'];
			}
			else
				$message	 = "No Reference available for Object '".$name."' called in File ".$trace[0]['file']." on Line ".$trace[0]['line'];
			die( $message );
		}
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