<?php
import( 'de.ceus-media.adt.OptionObject' );
/**
 *	Argument Parser for Console Applications.
 *	@package		console
 *	@extends		ADT_OptionObject
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.01.2006
 *	@version		0.6
 */
/**
 *	Argument Parser for Console Applications.
 *	@package		console
 *	@extends		ADT_OptionObject
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.01.2006
 *	@version		0.6
 */
class Console_ArgumentParser extends ADT_OptionObject
{
	/**	@var	array		shortcuts		Associative Array of Shortcuts */
	private $shortcuts	= array();
	
	//  --  PUBLIC METHODS  --  //

	/**
	 *	Parses Arguments of called Script.
	 *	@access		public
	 *	@return		void
	 */
	public function parseArguments()
	{
		$args	= $_SERVER["argv"];
		$this->setOption( "script", array_shift( $args ) );
		foreach( $args as $arg )
		{
			$value	= TRUE;
			if( substr_count( $arg, ":" ) )
			{
				$parts	= explode( ":", $arg );
				$arg	= array_shift( $parts );
				$value	= implode( ":", $parts );
			}
			if( substr( $arg, 0, 1 ) == "-" )
			{
				if( !$this->hasOption( 'options' ) )
					$options	= array();
				else
					$options	= (array) $this->getOption( 'options' );
				$arg	= substr( $arg, 1 );
				$options[$arg]	= $value;
				$arg	= "options";
				$value	= $options;
			}
			$this->setArgument( $arg, $value );
		}
	}
	
	/**
	 *	Adds Shortcut.
	 *	@access		public
	 *	@param		string		$short		Key of Shortcut
	 *	@param		string		$long		Long form of Shortcut
	 *	@return		void
	 */
	public function addShortCut( $short, $long )
	{
		if( !isset( $this->shortcuts[$short] ) )
			$this->shortcuts[$short]	= $long;
		else
			trigger_error( "Shortcut '".$short."' is already set", E_USER_ERROR );
	}
	
	/**
	 *	Removes Shortcut.
	 *	@access		public
	 *	@param		string		$key		Key of Shortcut
	 *	@return		void
	 */
	public function removeShortCut( $key )
	{
		if( isset( $this->shortcuts[$key] ) )
			unset( $this->shortcuts[$key] );
	}
	
	//  --  PRIVATE METHODS  --  //

	/**
	 *	Sets Argument to Object's Options.
	 *	@access		protected
	 *	@param		string	key		Key of Argument
	 *	@param		string	value	Value of Argument
	 *	@return		void
	 */
	protected function setArgument( $key, $value )
	{
		if( in_array( $key, array_keys( $this->shortcuts ) ) )
		{
			$key	= $this->shortcuts[$key];
			$this->setArgument( $key, $value );
		}
		else
			$this->setOption( $key, $value );
	}
}
?>