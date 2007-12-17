<?php
import( 'de.ceus-media.ui.html.Elements' );
import( 'de.ceus-media.adt.TimeConverter' );
/**
 *	Abstract View Class of Framework Hydrogen.
 *	@package		framework
 *	@subpackage		hydrogen
 *	@uses			UI_HTML_Elements
 *	@uses			TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			01.09.2006
 *	@version		0.1
 */
/**
 *	Abstract View Class of Framework Hydrogen.
 *	@package		framework
 *	@subpackage		hydrogen
 *	@uses			UI_HTML_Elements
 *	@uses			TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			01.09.2006
 *	@version		0.1
 */
class View
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
		'model',
		'controller',
		'action',
		);
	/**	@var		DatabaseConnection	$dbc			Database Connection */
	var $dbc;
	/**	@var		array				$config			Configuration Settings */
	var $config;
	/**	@var		PartitionSession		$session			Partition Session */
	var $session;
	/**	@var		Net_HTTP_Request_Receiver	$request			Receiver of Request Parameters */
	var $request;
	/**	@var		Language			$language		Language Support */
	var $language;
	/**	@var		Messenger			$messenger		UI Messenger */
	var $messenger;
	/**	@var		string				$controller		Name of called Controller */
	var $controller	= "";
	/**	@var		string				$action			Name of called Action */
	var $action	= "";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Framework	$application		Instance of Framework
	 *	@return		void
	 */
	public function __construct( $application )
	{
		$this->_setEnv( $application );
		$this->html	= new UI_HTML_Elements;
		$this->time	= new TimeConverter();
	}
	
	//  --  SETTERS & GETTERS  --  //
	/**
	 *	Sets Data of View.
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
	
	//  --  PUBLIC METHODS  --  //
	/**
	 *	Loads Template of View and returns Content.
	 *	@access		public
	 *	@return		string
	 */
	function loadTemplate()
	{
		$words	= $this->language->getWords( $this->controller );
		$content	= "";
		$filename	= $this->_getFilenameOfTemplate( $this->controller, $this->action );
		if( file_exists( $filename ) )
		{
			extract( $this->_data );
			$content	= require( $filename );
		}
		else
			$this->messenger->noteFailure( "Template '".$controller.".".$action."' is not existing." );
		return $content;
	}
	
	//  --  PRIVATE METHODS  --  //
	/**
	 *	Returns File Name of Template.
	 *	@access		private
	 *	@param		string		$controller		Name of Controller
	 *	@param		string		$action			Name of Action
	 *	@return		string
	 */
	function _getFilenameOfTemplate( $controller, $action )
	{
		$filename	= $this->config['paths']['templates'].$controller."/".$action.".php";
		return $filename;
	}
	
	/**
	 *	Sets Environment of Controller by copying Framework Member Variables.
	 *	@access		private
	 *	@param		Framework	$application		Instance of Framework
	 *	@return		void
	 */
	function _setEnv( $application )
	{
		$this->_application	=& $application;
		foreach( $this->_env_keys as $key )
			$this->$key	=& $this->_application->$key;
	}
}
?>