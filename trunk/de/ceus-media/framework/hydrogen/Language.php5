<?php
/**
 *	Language Class of Framework Hydrogen.
 *	@package		framework.hydrogen
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			01.09.2006
 *	@version		0.5
 */
/**
 *	Language Class of Framework Hydrogen.
 *	@package		framework.hydrogen
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@since			01.09.2006
 *	@version		0.5
 */
class Framework_Hydrogen_Language
{
	/**	@var		string							$language		Set Language */
	var $language;
	/**	@var		array							$envKeys		Keys of Environment */
	var $envKeys	= array(
		'config',
		'messenger',
		);
	/**	@var		array							$config			Configuration Settings */
	var $config;
	/**	@var		Framework_Hydrogen_Messenger	$messenger		UI Messenger */
	var $messenger;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Framework_Hydrogen_Framework	$application	Instance of Framework
	 *	@param		string							$language		Language to select
	 *	@return		void
	 */
	public function __construct( &$application, $language = "" )
	{
		$this->setIn( $application );
		if( $language )
			$this->setLanguage( $language );
	}
	
	/**
	 *	Sets a Language.
	 *	@access		public
	 *	@param		string		$language		Language to select
	 *	@return		void
	 */
	public function setLanguage( $language )
	{
		$this->language	= $language;
	}
	
	/**
	 *	Returns selected Language.
	 *	@access		public
	 *	@return		string
	 */
	public function getLanguage()
	{
		return $this->language;
	}
	
	/**
	 *	Returns Array of Word Pairs of Language Topic.
	 *	@access		public
	 *	@param		string		$topic			Topic of Language
	 *	@return		array
	 */
	public function getWords( $topic )
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
	public function load( $topic )
	{
		$filename	= $this->getFilenameOfLanguage( $topic );
		if( file_exists( $filename ) )
		{
			$data	= parse_ini_file( $filename, true );
			$this->_data[$topic]	= $data;
		}
		else
			$this->messenger->noteFailure( "Language Topic '".$topic."' is not defined yet." );
	}

	/**
	 *	Returns File Name of Language Topic.
	 *	@access		protected
	 *	@param		string		$topic			Topic of Language
	 *	@return		void
	 */
	protected public function getFilenameOfLanguage( $topic )
	{
		$filename	= $this->config['paths']['languages'].$this->language."/".$topic.".ini";	
		return $filename;
	}

	/**
	 *	Sets Environment of Controller by copying Framework Member Variables.
	 *	@access		private
	 *	@param		Framework_Hydrogen_Framework	$application		Instance of Framework
	 *	@return		void
	 */
	protected function setIn( &$application )
	{
		foreach( $this->envKeys as $key )
			$this->$key	=& $application->$key;
	}
}