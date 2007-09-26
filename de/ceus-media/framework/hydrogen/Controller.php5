<?php
/**
 *	Abstract Controller Class of Framework Hydrogen.
 *	@package		framework
 *	@subpackage		hydrogen
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			01.09.2006
 *	@version		0.1
 */
/**
 *	Abstract Controller Class of Framework Hydrogen.
 *	@package		framework
 *	@subpackage		hydrogen
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			01.09.2006
 *	@version		0.1
 */
class Controller
{
	/**	@var		Framework			$_application		Instance of Framework */
	var $_application;
	/**	@var		array				$_data			Collected Data for View */
	var $_data	= array();
	/**	@var		array				$_env_keys		Keys of Environment */
	var $_env_keys	= array(
		'dbc',
		'config',
		'session',
		'request',
		'language',
		'messenger',
		'controller',
		'action',
		);
	/**	@var		DatabaseConnection	$dbc			Database Connection */
	var $dbc;
	/**	@var		array				$config			Configuration Settings */
	var $config;
	/**	@var		PartitionSession		$session			Partition Session */
	var $session;
	/**	@var		HTTP_RequestReceiver	$request			Receiver of Request Parameters */
	var $request;
	/**	@var		Language			$language		Language Support */
	var $language;
	/**	@var		Messenger			$messenger		UI Messenger */
	var $messenger;
	/**	@var		string				$controller		Name of called Controller */
	var $controller	= "";
	/**	@var		string				$action			Name of called Action */
	var $action	= "";
	/**	@var		bool					$redirect			Flag for Redirection */
	var $redirect	= false;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Framework	application	Instance of Framework
	 *	@return		void
	 */
	public function __construct( &$application )
	{
		$this->_setEnv( $application );
		$this->_loadModel();
		$this->language->load( $this->controller );
	}
	
	//  --  SETTERS & GETTERS  --  //
	/**
	 *	Sets Data for View.
	 *	@access		public
	 *	@param		array		$data			Array of Data for View
	 *	@param		string		[$topic]			Topic Name of Data
	 *	@return		void
	 */
	function setData( $data, $topic = "" )
	{
		if( $topic )
		{
			if( !isset( $this->_data[$topic] ) )
				$this->_data[$topic]	= array();
			foreach( $data as $key => $value )
				$this->_data[$topic][$key]	= $value;
		}
		else
		{
			foreach( $data as $key => $value )
				$this->_data[$key]	= $value;
		}
	}
	
	/**
	 *	Returns Data for View.
	 *	@access		public
	 *	@return		array
	 */
	function getData()
	{
		return $this->_data;
	}
	
	//  --  PUBLIC METHODS  --  //
	/**
	 *	Returns View Content of called Action.
	 *	@access		public
	 *	@return		string
	 */
	function getView()
	{
		$this->_loadView();
		if( method_exists( $this->view, $this->action ) )
		{
			$this->view->{$this->action}();
			$this->view->setData( $this->getData() );
			return $this->view->loadTemplate();
		}
		else
			$this->messenger->noteFailure( "View Action '".$this->action."' not defined yet." );
	}
	
	/**
	 *	Redirects by calling different Controller and Action.
	 *	@access		public
	 *	@param		string		$controller		Controller to be called
	 *	@param		string		$action			Action to be called
	 *	@return		void
	 */
	function redirect( $controller, $action = "index" )
	{
		$this->request->set( 'controller', $controller );
		$this->request->set( 'action', $action );
		$this->redirect = true;
	}
	
	/**
	 *	Redirects by requesting a URI.
	 *	@access		public
	 *	@param		string		$uri				URI to request
	 *	@return		void
	 */
	function restart( $uri )
	{
		$base	= dirname( getEnv( 'SCRIPT_NAME' ) )."/";
		$this->dbc->close();
		$this->session->close();
		header( "Location: ".$base.$uri );
		die;
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Returns File Name of selected Model.
	 *	@access		private
	 *	@param		string		$controller		Name of called Controller
	 *	@return		string
	 */
	function _getFilenameOfModel( $controller )
	{
		$filename	= $this->config['paths']['models'].ucfirst( $controller)."Model.php";
		return $filename;
	}

	/**
	 *	Returns File Name of selected View.
	 *	@access		private
	 *	@param		string		$controller		Name of called Controller
	 *	@return		string
	 */
	function _getFilenameOfView( $controller )
	{
		$filename	= $this->config['paths']['views'].ucfirst( $controller)."View.php";
		return $filename;
	}
	
	/**
	 *	Loads Modul Class of called Controller.
	 *	@access		private
	 *	@return		void
	 */
	function _loadModel()
	{
		$filename	= $this->_getFilenameOfModel( ucfirst( $this->controller ) );
		if( file_exists( $filename ) )
		{
			require_once( $filename );
			$class	= ucfirst( $this->controller )."Model";
			$this->model	= new $class( $this );
		}
		else
			$this->messenger->noteFailure( "Model '".ucfirst( $this->controller )."' not defined yet." );
	}

	/**
	 *	Loads View Class of called Controller.
	 *	@access		private
	 *	@return		void
	 */
	function _loadView()
	{
		$filename	= $this->_getFilenameOfView( ucfirst( $this->controller ) );
		if( file_exists( $filename ) )
		{
			require_once( $filename );
			$class	= ucfirst( $this->controller )."View";
			$this->view	= new $class( $this );
		}
		else
			$this->messenger->noteFailure( "View '".ucfirst( $this->controller )."' not defined yet." );
	}

	/**
	 *	Sets Environment of Controller by copying Framework Member Variables.
	 *	@access		private
	 *	@param		Framework	$application		Instance of Framework
	 *	@return		void
	 */
	function _setEnv( &$application )
	{
		$this->_application	=& $application;
		foreach( $this->_env_keys as $key )
			$this->$key	=& $this->_application->$key;
	}
}
?>