<?php
import( 'de.ceus-media.framework.neon.Component' );
import( 'de.ceus-media.adt.Reference' );
import( 'de.ceus-media.file.ini.Reader' );
import( 'de.ceus-media.alg.TimeConverter' );
/**
 *	Generic Action Handler.
 *	@package		framework.neon
 *	@extends		Framework_Neon_Component
 *	@uses			ADT_Reference
 *	@uses			File_INI_Reader
 *	@uses			Alg_TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.1
 */
/**
 *	Generic Action Handler.
 *	@package		framework.neon
 *	@extends		Framework_Neon_Component
 *	@uses			ADT_Reference
 *	@uses			File_INI_Reader
 *	@uses			Alg_TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.1
 */
class Framework_Neon_Action extends Framework_Neon_Component
{
	/**	@var	array			$actions	Array of Action events and methods */
	protected $actions	= array();

	/**
	 *	Calls Actions by checking calls in Request.
	 *	@access		public
	 *	@return		void
	 */
	public function act()
	{
		$request	= $this->ref->get( 'request' );
		foreach( $this->actions as $event => $action )
			if( $request->has( $event ) )
				$this->$action();
	}

	/**
	 *	Adds an Action by an event name and a method name.
	 *	@access		public
	 *	@param		string		$event			Event name of Action
	 *	@param		string		$action			Method name of Action
	 *	@return		void
	 */
	public function add( $event, $action = false )
	{
		if( !$action )
			$action = $event;
		$this->actions[$event]	= $action;
	}

	/**
	 *	Indicates whether an Action is registered.
	 *	@access		public
	 *	@param		string		$event			Event name of Action
	 *	@return		bool
	 */
	public function has( $event )
	{
		return isset( $this->actions[$event]);
	}

	/**
	 *	Removes a registered Action.
	 *	@access		public
	 *	@param		string		$event			Event name of Action
	 *	@return		void
	 */
	public function remove( $event )
	{
		if( $this->has( $event ) )
			unset( $this->actions[$event] );
	}

	/**
	 *	Restart application after realizing an Action.
	 *	@access		public
	 *	@param		string		$request			Request URL with Query String
	 *	@return		void
	 */
	public function restart( $request )
	{
		header( "Location: ".$request );
		die;
	}
}
?>