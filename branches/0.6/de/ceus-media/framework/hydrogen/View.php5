<?php
import( 'de.ceus-media.ui.html.Elements' );
import( 'de.ceus-media.alg.TimeConverter' );
/**
 *	Abstract View Class of Framework Hydrogen.
 *	@package		framework
 *	@subpackage		hydrogen
 *	@uses			UI_HTML_Elements
 *	@uses			Alg_TimeConverter
 *	@author			Christian W�rker <Christian.Wuerker@Ceus-Media.de>
 *	@since			01.09.2006
 *	@version		0.1
 */
/**
 *	Abstract View Class of Framework Hydrogen.
 *	@package		framework
 *	@subpackage		hydrogen
 *	@uses			UI_HTML_Elements
 *	@uses			Alg_TimeConverter
 *	@author			Christian W�rker <Christian.Wuerker@Ceus-Media.de>
 *	@since			01.09.2006
 *	@version		0.1
 */
class View
{
	/**	@var		Framework			$application		Instance of Framework */
	var $application;
	/**	@var		array				$data			Collected Data for View */
	var $data	= array();
	/**	@var		array				$envKeys		Keys of Environment */
	var $envKeys	= array(
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
		$this->setEnv( $application );
		$this->html	= new UI_HTML_Elements;
		$this->time	= new Alg_TimeConverter();
	}
	
	//  --  SETTERS & GETTERS  --  //
	/**
	 *	Sets Data of View.
	 *	@access		public
	 *	@param		array		$data			Array of Data for View
	 *	@param		string		[$topic]			Topic Name of Data
	 *	@return		void
	 */
	public function setData( $data, $topic = "" )
	{
		if( $topic )
		{
			if( !isset( $this->data[$topic] ) )
				$this->data[$topic]	= array();
			foreach( $data as $key => $value )
				$this->data[$topic][$key]	= $value;
		}
		else
		{
			foreach( $data as $key => $value )
				$this->data[$key]	= $value;
		}
	}
	
	/**
	 *	Loads Template of View and returns Content.
	 *	@access		public
	 *	@return		string
	 */
	public function loadTemplate()
	{
		$content	= "";
		$filename	= $this->getFilenameOfTemplate( $this->controller, $this->action );
		if( file_exists( $filename ) )
		{
			extract( $this->data );
			$content	= require( $filename );
		}
		else
			$this->messenger->noteFailure( "Template '".$controller.".".$action."' is not existing." );
		return $content;
	}
	
	/**
	 *	Returns File Name of Template.
	 *	@access		protected
	 *	@param		string		$controller		Name of Controller
	 *	@param		string		$action			Name of Action
	 *	@return		string
	 */
	protected function getFilenameOfTemplate( $controller, $action )
	{
		$filename	= $this->config['paths']['templates'].$controller."/".$action.".php";
		return $filename;
	}
	
	/**
	 *	Sets Environment of Controller by copying Framework Member Variables.
	 *	@access		protected
	 *	@param		Framework	$application		Instance of Framework
	 *	@return		void
	 */
	protected function setEnv( $application )
	{
		$this->application	=& $application;
		foreach( $this->envKeys as $key )
			$this->$key	=& $this->application->$key;
	}
}
?>