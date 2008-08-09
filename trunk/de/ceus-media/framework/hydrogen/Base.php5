<?php
import( 'de.ceus-media.database.DatabaseConnection' );
import( 'de.ceus-media.file.ini.Reader' );
import( 'de.ceus-media.net.http.PartitionSession' );
import( 'de.ceus-media.net.http.request.Receiver' );
import( 'de.ceus-media.StopWatch' );
import( 'de.ceus-media.adt.FieldDefinition' );
import( 'de.ceus-media.framework.hydrogen.Messenger' );
import( 'de.ceus-media.framework.hydrogen.Model' );
import( 'de.ceus-media.framework.hydrogen.View' );
import( 'de.ceus-media.framework.hydrogen.Controller' );
import( 'de.ceus-media.framework.hydrogen.Language' );
/**
 *	Abstract Main Class of Framework Hydrogen
 *	@package		framework.hydrogen
 *	@uses			DatabaseConnection
 *	@uses			File_INI_Reader
 *	@uses			Net_HTTP_PartitionSession
 *	@uses			Net_HTTP_Request_Receiver
 *	@uses			StopWatch
 *	@uses			FieldDefinition
 *	@uses			Framework_Hydrogen_Messenger
 *	@uses			Framework_Hydrogen_Model
 *	@uses			Framework_Hydrogen_View
 *	@uses			Framework_Hydrogen_Controller
 *	@uses			Framework_Hydrogen_Language
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			01.09.2006
 *	@version		0.1
 */
/**
 *	Abstract Main Class of Framework Hydrogen
 *	@package		framework.hydrogen
 *	@uses			DatabaseConnection
 *	@uses			File_INI_Reader
 *	@uses			Net_HTTP_PartitionSession
 *	@uses			Net_HTTP_Request_Receiver
 *	@uses			StopWatch
 *	@uses			FieldDefinition
 *	@uses			Framework_Hydrogen_Messenger
 *	@uses			Framework_Hydrogen_Model
 *	@uses			Framework_Hydrogen_View
 *	@uses			Framework_Hydrogen_Controller
 *	@uses			Language
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			01.09.2006
 *	@version		0.1
 *	@todo			Code Documentation
 */
class Framework_Hydrogen_Base
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
		$this->init();
		$this->openBuffer();
	}
	
	/**
	 *	Main Method of Framework calling Controller (and View) and Master View.
	 *	@access		public
	 *	@return		void
	 */
	public function main()
	{
		$this->control();
		$this->closeBuffer();
		$this->setViewComponents( array(
				'config'		=> $this->config,
				'content'		=> $this->content,
				'messages'		=> $this->messenger->buildMessages( $this->config['layout']['format_timestamp'] ),
				'language'		=> $this->config['languages']['default'],
				'words'			=> $this->language->getWords( 'main' ),
				'stopwatch'		=> $this->_sw->stop(),
				'dev'			=> $this->_dev,
			)
		);
		$this->view();
	}

	/**
	 *	Executes called Controller and stores generated View.
	 *	@access		public
	 *	@return		void
	 */
	public function control()
	{
		$this->controller	= $this->request->get( 'controller' );
		$this->action		= $this->request->get( 'action' );
//		remark( "controller: ".$this->controller );
//		remark( "action: ".$this->action );
		
		$filename		= $this->getFilenameOfController( ucfirst( $this->controller ) );
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
	public function setViewComponents( $components = array() )
	{
		$this->_components	= $components;
	}

	/**
	 *	Collates View Components and puts out Master View.
	 *	@access		public
	 *	@return		void
	 */
	public function view()
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
	 *	@access		protected
	 *	@return		void
	 */
	protected function closeBuffer()
	{
		$this->_dev	= ob_get_contents();
		ob_end_clean();
	}

	/**
	 *	Returns File Name of selected Controller.
	 *	@access		protected
	 *	@param		string		$controller		Name of called Controller
	 *	@return		string
	 */
	protected function getFilenameOfController( $controller )
	{
		$filename	= $this->config['paths']['controllers'].ucfirst( $controller)."Controller.php";
		return $filename;
	}
	
	/**
	 *	Initialisation of Framework.
	 *	@access		protected
	 *	@return		void
	 */
	protected function init()
	{
		$this->_sw	= new StopWatch();
		
		//  --  CONFIGURATION  --  //
		$ir_conf		= new File_INI_Reader( "config/config.ini", true );
		$this->config	= $ir_conf->toArray();
		error_reporting( $this->config['config']['error_reporting'] );

		//  --  SESSION HANDLING  --  //
		$this->session	= new Net_HTTP_PartitionSession( $this->config['application']['name'], $this->config['config']['session_name'] );

		//  --  UI MESSENGER  --  //
		$this->messenger	=& new Framework_Hydrogen_Messenger( $this->session );

		//  --  DATABASE CONNECTION  --  //
		$data		= parse_ini_file( "config/db_access.ini" );
		$this->dbc	= new Database_MySQL_Connection ( $data['type'], $data['logfile'] );
		$this->dbc->connect( $data['hostname'], $data['username'], $data['password'], $data['database'] );
		$this->config['config']['table_prefix']	= $data['prefix'];
		
		//  --  LANGUAGE SUPPORT  --  //
		$this->language	= new Framework_Hydrogen_Language( $this, $this->config['languages']['default'] );
		$this->language->load( 'main' );

		//  --  REQUEST HANDLER  --  //
		$this->request	= new Net_HTTP_Request_Receiver();
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
	 *	@access		protected
	 *	@return		void
	 */
	protected function openBuffer()
	{
		ob_start();
	}
}
?>