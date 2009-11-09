<?php
/**
 *	Generic Controller Class of Framework Hydrogen.
 *
 *	Copyright (c) 2007-2009 Christian W�rker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		framework.hydrogen
 *	@author			Christian W�rker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.09.2006
 *	@version		0.5
 */
/**
 *	Generic Controller Class of Framework Hydrogen.
 *	@category		cmClasses
 *	@package		framework.hydrogen
 *	@author			Christian W�rker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.09.2006
 *	@version		0.5
 */
class Framework_Hydrogen_Controller
{
	/**	@var		Framework_Hydrogen_Base			$application	Instance of Framework */
	var $application;
	/**	@var		array							$_data			Collected Data for View */
	var $_data	= array();
	/**	@var		array							$envKeys		Keys of Environment */
	var $envKeys	= array(
		'dbc',
		'config',
		'session',
		'request',
		'language',
		'messenger',
		'controller',
		'action',
		);
	/**	@var		Database_MySQL_Connection		$dbc			Database Connection */
	var $dbc;
	/**	@var		array							$config			Configuration Settings */
	var $config;
	/**	@var		Net_HTTP_PartitionSession		$session		Partition Session */
	var $session;
	/**	@var		Net_HTTP_Request_Receiver		$request		Receiver of Request Parameters */
	var $request;
	/**	@var		Framework_Hydrogen_Language		$language		Language Support */
	var $language;
	/**	@var		Framework_Hydrogen_Messenger	$messenger		UI Messenger */
	var $messenger;
	/**	@var		string							$controller		Name of called Controller */
	var $controller	= "";
	/**	@var		string							$action			Name of called Action */
	var $action	= "";
	/**	@var		bool							$redirect		Flag for Redirection */
	var $redirect	= FALSE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Framework_Hydrogen_Framework	$application	Instance of Framework
	 *	@return		void
	 */
	public function __construct( &$application )
	{
		$this->setEnv( $application );
		$this->loadModel();
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
	public function setData( $data, $topic = "" )
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
	public function getData()
	{
		return $this->_data;
	}
	
	//  --  PUBLIC METHODS  --  //
	/**
	 *	Returns View Content of called Action.
	 *	@access		public
	 *	@return		string
	 */
	public function getView()
	{
		$this->loadView();
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
	public function redirect( $controller, $action = "index" )
	{
		$this->request->set( 'controller', $controller );
		$this->request->set( 'action', $action );
		$this->redirect = TRUE;
	}
	
	/**
	 *	Redirects by requesting a URI.
	 *	@access		public
	 *	@param		string		$uri				URI to request
	 *	@return		void
	 */
	public function restart( $uri )
	{
		$base	= dirname( getEnv( 'SCRIPT_NAME' ) )."/";
		$this->dbc->close();
		$this->session->close();
		header( "Location: ".$base.$uri );
		die;
	}

	/**
	 *	Returns File Name of selected Model.
	 *	@access		protected
	 *	@param		string		$controller		Name of called Controller
	 *	@return		string
	 */
	protected function getFilenameOfModel( $controller )
	{
		$filename	= $this->config['paths']['models'].ucfirst( $controller)."Model.php5";
		return $filename;
	}

	/**
	 *	Returns File Name of selected View.
	 *	@access		protected
	 *	@param		string		$controller		Name of called Controller
	 *	@return		string
	 */
	protected function getFilenameOfView( $controller )
	{
		$filename	= $this->config['paths']['views'].ucfirst( $controller)."View.php5";
		return $filename;
	}
	
	/**
	 *	Loads Modul Class of called Controller.
	 *	@access		protected
	 *	@return		void
	 */
	protected function loadModel()
	{
		$filename	= $this->getFilenameOfModel( ucfirst( $this->controller ) );
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
	 *	@access		protected
	 *	@return		void
	 */
	protected function loadView()
	{
		$filename	= $this->getFilenameOfView( ucfirst( $this->controller ) );
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
	 *	@access		protected
	 *	@param		Framework_Hydrogen_Framework	$application		Instance of Framework
	 *	@return		void
	 */
	protected function setEnv( &$application )
	{
		$this->application	=& $application;
		foreach( $this->envKeys as $key )
			$this->$key	=& $this->application->$key;
	}
}
?>