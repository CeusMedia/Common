<?php
import( 'de.ceus-media.framework.krypton.core.Component' );
/**
 *	Abstract Basic Action Handler.
 *	@package		framework.krypton.core
 *	@uses			Framework_Krypton_Core_Registry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2007
 *	@version		0.6
 */
/**
 *	Abstract Basic Action Handler.
 *	@package		framework.krypton.core
 *	@uses			Framework_Krypton_Core_Registry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2007
 *	@version		0.6
 */
abstract class Framework_Krypton_Core_Action extends Framework_Krypton_Core_Component
{
	/**	@var	array			$actions		Array of Action events and methods */
	protected $actions	= array();

	/**
	 *	Method for manually called Actions in inheriting Action Classes.
	 *	@access		public
	 *	@return		void
	 */
	public function act()
	{
	}
	
	/**
	 *	Adds an Action by an event name and a method name.
	 *	@access		protected
	 *	@param		string		$event			Event name of Action
	 *	@param		string		$action			Method name of Action
	 *	@return		void
	 */
	protected function addAction( $event, $action = "" )
	{
		if( !$action )
			$action	= $event;
		$this->actions[$event]	= $action;
	}

	/**
	 *	Indicates whether an Action is registered by an Event.
	 *	@access		public
	 *	@param		string		$event			Event name of Action
	 *	@return		bool
	 */
	public function hasAction( $event )
	{
		return isset( $this->actions[$event]);
	}

	/**
	 *	Calls Actions by checking calls in Request. Also collects and returns results of all Action Calls as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function performActions()
	{
		$results	= array();
		$request	= Framework_Krypton_Core_Registry::getStatic( 'request' );
		foreach( $this->actions as $event => $action )
		{
			if( $request->has( $event ) )
			{
				if( !method_exists( $this, $action ) )
					throw new BadMethodCallException( 'Action "'.get_class( $this ).'::'.$action.'()" is not existing.' );
				$results[$action]	= $this->$action( $request->get( $event ) );
			}
		}
		return $results;
	}

	/**
	 *	Removes a registered Action.
	 *	@access		public
	 *	@param		string		$event			Event name of Action
	 *	@return		void
	 */
	public function removeAction( $event )
	{
		if( $this->hasAction( $event ) )
			unset( $this->actions[$event] );
	}

	/**
	 *	Restart application with a Request URL.
	 *	@access		protected
	 *	@param		string		$request			Request URL with Query String
	 *	@return		void
	 */
	protected function restart( $request )
	{
		$session	= Framework_Krypton_Core_Registry::getStatic( 'session' );
		$session->__destruct();
		header( "Location: ./".$request );
		exit;
	}
}
?>