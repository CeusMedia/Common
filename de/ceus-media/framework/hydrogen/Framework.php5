<?php
import( 'de.ceus-media.database.DatabaseConnection' );
import( 'de.ceus-media.file.ini.IniReader' );
import( 'de.ceus-media.protocol.http.PartitionSession' );
import( 'de.ceus-media.protocol.http.HTTP_RequestReceiver' );
import( 'de.ceus-media.StopWatch' );
import( 'de.ceus-media.adt.FieldDefinition' );
import( 'de.ceus-media.framework.hydrogen.Messenger' );
import( 'de.ceus-media.framework.hydrogen.Model' );
import( 'de.ceus-media.framework.hydrogen.View' );
import( 'de.ceus-media.framework.hydrogen.Controller' );
import( 'de.ceus-media.framework.hydrogen.Language' );
/**
 *	Abstract Main Class of Framework Hydrogen
 *	@package		framework
 *	@subpackage		hydrogen
 *	@uses			DatabaseConnection
 *	@uses			IniReader
 *	@uses			PartitionSession
 *	@uses			HTTP_RequestReceiver
 *	@uses			StopWatch
 *	@uses			FieldDefinition
 *	@uses			Messenger
 *	@uses			Model
 *	@uses			View
 *	@uses			Controller
 *	@uses			Language
 *	@author			Christian W�rker <Christian.Wuerker@Ceus-Media.de>
 *	@since			01.09.2006
 *	@version		0.1
 */
/**
 *	Abstract Main Class of Framework Hydrogen
 *	@package		framework
 *	@subpackage		hydrogen
 *	@uses			DatabaseConnection
 *	@uses			IniReader
 *	@uses			PartitionSession
 *	@uses			HTTP_RequestReceiver
 *	@uses			StopWatch
 *	@uses			FieldDefinition
 *	@uses			Messenger
 *	@uses			Model
 *	@uses			View
 *	@uses			Controller
 *	@uses			Language
 *	@author			Christian W�rker <Christian.Wuerker@Ceus-Media.de>
 *	@since			01.09.2006
 *	@version		0.1
 *	@todo			Code Documentation
 */
class Framework
{
	var $config;
	var $dbc;
	var $session;
	var $request;
	var $language;
	var $messenger;
	var $definition;

	var $controller	= "";
	var $action	= "";
	var $content	= "";
	
	var $_components;
	var $_dev;
	var $_sw;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->_init();
		$this->_openBuffer();
	}
	
	/**
	 *	Main Method of Framework calling Controller (and View) and Master View.
	 *	@access		public
	 *	@return		void
	 */
	function main()
	{
		$this->control();
		$this->_closeBuffer();
		$this->setViewComponents( array(
				'config'			=> $this->config,
				'content'			=> $this->content,
				'messages'		=> $this->messenger->buildMessages( $this->config['layout']['format_timestamp'] ),
				'language'		=> $this->config['languages']['default'],
				'words'			=> $this->language->getWords( 'main' ),
				'stopwatch'		=> $this->_sw->stop(),
				'dev'				=> $this->_dev,
			)
		);
		$this->view();
	}

	/**
	 *	Executes called Controller and stores generated View.
	 *	@access		public
	 *	@return		void
	 */
	function control()
	{
		$this->controller	= $this->request->get( 'controller' );
		$this->action		= $this->request->get( 'action' );
//		remark( "controller: ".$this->controller );
//		remark( "action: ".$this->action );
		
		$filename		= $this->_getFilenameOfController( ucfirst( $this->controller ) );
		if( file_exists( $filename ) )
		{
			require_once( $filename );
			$class		= ucfirst( $this->controller )."Controller";
			$controller	= new $class( $this );
			if( method_exists( $controller, $this->action ) )
			{
				$controller->{$this->action}();
				if( $controller->redirect )
					$this->control();
				else
					$this->content = $controller->getView();
			}
			else
				$this->messenger->noteFailure( "Action '".ucfirst( $this->controller )."::".$this->action."' not defined yet." );
		}
		else
			$this->messenger->noteFailure( "Controller '".ucfirst( $this->controller )."' not defined yet." );

	}

	/**
	 *	Sets collated View Components for Master View.
	 *	@access		public
	 *	@return		void
	 */
	function setViewComponents( $components = array() )
	{
		$this->_components	= $components;
	}

	/**
	 *	Collates View Components and puts out Master View.
	 *	@access		public
	 *	@return		void
	 */
	function view()
	{
		extract( $this->_components );
		require_once( $this->config['paths']['templates']."master.php" );
		$this->session->close();
		$this->dbc->close();
		die( $content );
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Closes Output Buffer.
	 *	@access		private
	 *	@return		void
	 */
	function _closeBuffer()
	{
		$this->_dev	= ob_get_contents();
		ob_end_clean();
	}

	/**
	 *	Returns File Name of selected Controller.
	 *	@access		private
	 *	@param		string		$controller		Name of called Controller
	 *	@return		string
	 */
	function _getFilenameOfController( $controller )
	{
		$filename	= $this->config['paths']['controllers'].ucfirst( $controller)."Controller.php";
		return $filename;
	}
	
	/**
	 *	Initialisation of Framework.
	 *	@access		private
	 *	@return		void
	 */
	function _init()
	{
		$this->_sw	= new StopWatch();
		
		//  --  CONFIGURATION  --  //
		$ir_conf		= new IniReader( "config/config.ini", true );
		$this->config	= $ir_conf->toArray();
		error_reporting( $this->config['config']['error_reporting'] );

		//  --  SESSION HANDLING  --  //
		$this->session	= new PartitionSession( );
		$this->session->openSession( $this->config['application']['name'], $this->config['config']['session_name'] );

		//  --  UI MESSENGER  --  //
		$this->messenger	=& new Messenger( $this->session );

		//  --  DATABASE CONNECTION  --  //
		$data		= parse_ini_file( "config/db_access.ini" );
		$this->dbc	= new DatabaseConnection ( $data['type'], $data['logfile'] );
		$this->dbc->connect( $data['hostname'], $data['username'], $data['password'], $data['database'] );
		$this->config['config']['table_prefix']	= $data['prefix'];
		
		//  --  LANGUAGE SUPPORT  --  //
		$this->language	= new Language( $this, $this->config['languages']['default'] );
		$this->language->load( 'main' );

		//  --  REQUEST HANDLER  --  //
		$this->request	= new HTTP_RequestReceiver();
		if( $this->request->get( 'param' ) && !$this->request->get( 'controller' ) )
		{
			$parts	= explode( ".", $this->request->get( 'param' ) );
			$this->request->set( 'controller', $parts[0] );
			$this->request->set( 'action', isset( $parts[1] ) ? $parts[1] : "index" );
			$this->request->set( 'id', isset( $parts[2] ) ? $parts[2] : "0" );
		}

		//  --  FIELD DEFINITION SUPPORT  --  //
		$this->definition	= new FieldDefinition( "config/", $this->config['config']['use_cache'], $this->config['config']['cache_path'] );
		$this->definition->setChannel( "html" );
	}
	
	/**
	 *	Opens Output Buffer.
	 *	@access		private
	 *	@return		void
	 */
	function _openBuffer()
	{
		ob_start();
	}
}
?>