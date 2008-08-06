<?php
/**
 *	Abstrace Base View Class for Tool Applications.
 *	@package		framework.tool
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.05.2008
 *	@version		0.1
 */
/**
 *	Abstrace Base View Class for Tool Applications.
 *	@package		framework.tool
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.05.2008
 *	@version		0.1
 */
abstract class Framework_Tool_View
{
	/**	@var		File_Configuration_Reader	$config			Configuration Object */
	protected $config;
	/**	@var		Logic						$logic			Logic Object */
	protected $logic;
	/**	@var		Net_HTTP_Request_Receiver	$request		HTTP Request Object */
	protected $request;
	/**	@var		Net_HTTP_Request_Response	$response		HTTP Response Object */
	protected $response;
	/**	@var		Net_HTTP_Session			$session		Session Object */
	protected $session;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Framework_Tool_Environment	$environment	Environment Object
	 *	@return		void
	 */
	public function __construct( Framework_Tool_Environment $environment )
	{
		$this->config		= $environment->getConfiguration();							//  get Configuration Object
		$this->logic		= $environment->getLogic();									//  get Logic Object
		$this->request		= $environment->getRequest();								//  get HTTP Request Object
		$this->response		= $environment->getResponse();								//  get HTTP Response Object
		$this->session		= $environment->getSession();								//  get Session Object

		$this->buildView();
	}
	
	/**
	 *	Calls View Components and responds complete View. To be overwritten.
	 *	@access		protected
	 *	@return		void
	 */
	abstract protected function buildView();
}
?>