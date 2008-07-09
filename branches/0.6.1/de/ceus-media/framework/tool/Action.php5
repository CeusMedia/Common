<?php
/**
 *	Action.
 *	@package		tool
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.05.2008
 *	@version		0.1
 */
/**
 *	Action.
 *	@package		tool
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.05.2008
 *	@version		0.1
 */
abstract class Action
{
	/**	@var	File_Configuration_Reader	$config		Configuration Object */
	private $config;
	/**	@var	Logic						$logic		Logic Object */
	private $logic;
	/**	@var	Net_HTTP_Request_Receiver	$request	HTTP Request Object */
	private $request;
	/**	@var	Net_HTTP_Session			$session	Session Object */
	private $session;

	/**
	 *	Constructor, calls Actions and stores set Filters.
	 *	@access		public
	 *	@param		Environment		$environment		Environment Object
	 *	@return		void
	 */
	public function __construct( Environment $environment )
	{
		$this->config		= $environment->getConfiguration();							//  get Configuration Object
		$this->logic		= $environment->getLogic();									//  get Logic Object
		$this->request		= $environment->getRequest();								//  get HTTP Request Object
		$this->session		= $environment->getSession();								//  get Session Object
		$this->handleActions();
	}
	
	abstract private function handleActions();
}
?>