<?php
import( 'de.ceus-media.framework.krypton.core.Component' );
/**
 *	Abstract Basic Action Handler.
 *	@package		mv2.core
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2007
 *	@version		0.2
 */
/**
 *	Abstract Basic Action Handler.
 *	@package		mv2.core
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2007
 *	@version		0.2
 */
abstract class Framework_Krypton_Core_Action extends Framework_Krypton_Core_Component
{
	/**	@var	array			$actions		Array of Action events and methods */
	protected $actions	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	function __construct( $useWikiParser = false )
	{
		parent::__construct( $useWikiParser );
	}

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
	 *	Calls Actions by checking calls in Request.
	 *	@access		public
	 *	@return		void
	 */
	public function performActions()
	{
		$request	= Framework_Krypton_Core_Registry::getStatic( 'request' );
		foreach( $this->actions as $event => $action )
		{
			if( $request->has( $event ) )
			{
				if( method_exists( $this, $action ) )
					$this->$action( $request->get( $event ) );
				else
					$this->messenger->noteFailure( "Action '".get_class( $this )."::".$action."()' is not existing." );
			}
		}
	}

	/**
	 *	Removes a registered Action.
	 *	@access		public
	 *	@param		string		$event			Event name of Action
	 *	@return		void
	 */
	public function removeAction( $event )
	{
		if( $this->has( $event ) )
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
		$this->messenger->noteNotice( "Redirecting to: ".$request );
		$session	= Framework_Krypton_Core_Registry::getStatic( 'session' );
		$session->__destruct();
		header( "Location: ".$request );
		exit;
	}

	/**
	 *	Restart application with a Request URL.
	 *	@access		protected
	 *	@param		bool		$result				Result of Logic Action
	 *	@param		Core_Logic	$logic				Business Logic Object
	 *	@param		string		$language_file		Language File Key
	 *	@param		string		$label_section		Field Label Section for Validation Messages
	 *	@param		string		$success			Key of Success Message
	 *	@param		string		$error				Key of Error Message
	 *	@return		void
	 */
	protected function showResultMessages( $result, $logic, $language_file, $label_section, $success, $error = "" )
	{
		$words		=& $this->words[$language_file]['msg'];
		if( $logic->hasErrors() )
		{
			$this->interpretValidationErrors( $logic->getErrors(), $language_file, $label_section );		
			$this->interpretErrors( $logic->getErrors(), $language_file, 'msg' );
		}
		else if( $result )
		{
			$this->messenger->noteSuccess( $words[$success] );
		}
		else
		{
			$this->messenger->noteError( $words[$error] );
		}
	}
}
?>