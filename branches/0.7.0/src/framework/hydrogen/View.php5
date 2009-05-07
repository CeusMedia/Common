<?php
/**
 *	Generic View Class of Framework Hydrogen.
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
 *	@package		framework.hydrogen
 *	@uses			UI_HTML_Elements
 *	@uses			Alg_TimeConverter
 *	@author			Christian W�rker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.09.2006
 *	@version		0.1
 */
import( 'de.ceus-media.ui.html.Elements' );
import( 'de.ceus-media.alg.TimeConverter' );
/**
 *	Generic View Class of Framework Hydrogen.
 *	@package		framework.hydrogen
 *	@uses			UI_HTML_Elements
 *	@uses			Alg_TimeConverter
 *	@author			Christian W�rker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.09.2006
 *	@version		0.1
 */
class Framework_Hydrogen_View
{
	/**	@var		Framework_Hydrogen_Framework	$application	Instance of Framework */
	var $application;
	/**	@var		array							$data			Collected Data for View */
	var $data	= array();
	/**	@var		array							$envKeys		Keys of Environment */
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

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Framework_Hydrogen_Framework	$application		Instance of Framework
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
	 *	@param		Framework_Hydrogen_Framework	$application		Instance of Framework
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