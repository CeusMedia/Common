<?php
/**
 *	Language Class of Framework Hydrogen.
 *	@package		framework
 *	@subpackage		hydrogen
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			01.09.2006
 *	@version		0.1
 */
/**
 *	Language Class of Framework Hydrogen.
 *	@package		framework
 *	@subpackage		hydrogen
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			01.09.2006
 *	@version		0.1
 */
class Language
{
	/**	@var		string		$_language		Set Language */
	var $_language;
	/**	@var		array		$_env_keys		Keys of Environment */
	var $_env_keys	= array(
		'config',
		'messenger',
		);
	/**	@var		array		$config			Configuration Settings */
	var $config;
	/**	@var		Messenger	$messenger		UI Messenger */
	var $messenger;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Framework	$application		Instance of Framework
	 *	@param		string		$language		Language to select
	 *	@return		void
	 */
	public function __construct( &$application, $language = "" )
	{
		$this->_setEnv( $application );
		if( $language )
			$this->setLanguage( $language );
	}
	
	/**
	 *	Sets a Language.
	 *	@access		public
	 *	@param		string		$language		Language to select
	 *	@return		void
	 */
	function setLanguage( $language )
	{
		$this->_language	= $language;
	}
	
	/**
	 *	Returns selected Language.
	 *	@access		public
	 *	@return		string
	 */
	function getLanguage()
	{
		return $this->_language;
	}
	
	/**
	 *	Returns Array of Word Pairs of Language Topic.
	 *	@access		public
	 *	@param		string		$topic			Topic of Language
	 *	@return		array
	 */
	function getWords( $topic )
	{
		if( isset( $this->_data[$topic] ) )
			return $this->_data[$topic];
		else
			$this->messenger->noteFailure( "Language Topic '".$topic."' is not defined yet." );
	}
	
	/**
	 *	Loads Language File by Topic.
	 *	@access		public
	 *	@param		string		$topic			Topic of Language
	 *	@return		void
	 */
	function load( $topic )
	{
		$filename	= $this->_getFilenameOfLanguage( $topic );
		if( file_exists( $filename ) )
		{
			$data	= parse_ini_file( $filename, true );
			$this->_data[$topic]	= $data;
		}
		else
			$this->messenger->noteFailure( "Language Topic '".$topic."' is not defined yet." );
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Returns File Name of Language Topic.
	 *	@access		private
	 *	@param		string		$topic			Topic of Language
	 *	@return		void
	 */
	function _getFilenameOfLanguage( $topic )
	{
		$filename	= $this->config['paths']['languages'].$this->_language."/".$topic.".ini";	
		return $filename;
	}

	/**
	 *	Sets Environment of Controller by copying Framework Member Variables.
	 *	@access		private
	 *	@param		Framework	$application		Instance of Framework
	 *	@return		void
	 */
	function _setEnv( &$application )
	{
		foreach( $this->_env_keys as $key )
			$this->$key	=& $application->$key;
	}
}