<?php
/**
 *	Abstract Base Action for Tool Applications.
 *	@package		framework.tool
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.05.2008
 *	@version		0.1
 */
/**
 *	Abstract Base Action for Tool Applications.
 *	@package		framework.tool
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.05.2008
 *	@version		0.1
 */
abstract class Framework_Tool_Action
{
	/**	@var		File_Configuration_Reader	$config			Configuration Object */
	protected $config;
	/**	@var		Logic						$logic			Logic Object */
	protected $logic;
	/**	@var		Net_HTTP_Request_Receiver	$request		HTTP Request Object */
	protected $request;
	/**	@var		Net_HTTP_Session			$session		Session Object */
	protected $session;

	/**
	 *	Constructor, calls Actions and stores set Filters.
	 *	@access		public
	 *	@param		Framework_Tool_Environment	$environment	Environment Object
	 *	@return		void
	 */
	public function __construct( Framework_Tool_Environment $environment )
	{
		$this->config		= $environment->getConfiguration();							//  get Configuration Object
		$this->logic		= $environment->getLogic();									//  get Logic Object
		$this->request		= $environment->getRequest();								//  get HTTP Request Object
		$this->session		= $environment->getSession();								//  get Session Object
		$this->handleActions();
	}

	/**
	 *	Handle called Actions. To be overwritten.
	 *	@access		protected
	 *	@return		void
	 */
	abstract protected function handleActions();
}
?>