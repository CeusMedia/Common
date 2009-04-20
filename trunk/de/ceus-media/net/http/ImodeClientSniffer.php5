<?php
/**
 *	Sniffer for iMode Clients.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		net.http
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
import( 'de.ceus-media.file.Configuration' );
define( 'IMODE_COLOUR_BW',			0 );
define( 'IMODE_COLOUR_GREYSCALE',	1 );
define( 'IMODE_COLOUR_256',			2 );
define( 'IMODE_COLOUR_4096',		3 );
define( 'IMODE_COLOUR_65536',		4 );
define( 'IMODE_DEFAULT_CACHE',		5 );
define( 'IMODE_UNKNOWN_USER_AGENT',	1 );
/**
 *	Sniffer for iMode Clients.
 *	@package		net.http
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 *	@deprecated		needs to be rearanged with File_Configuration_Reader
 */
class Net_HTTP_ImodeClientSniffer
{
	/**	@var		array		$data			Data of all known Imode Clients */
	protected $data				= array();
	/**	@var		array		$extra			List of Extra */
	protected $extra			= array();
	/**	@var		array		$extra			List of Manufacturs */
	protected $manufacturer		= array();
	/**	@var		string		$user_agent		Given User Agent */
	protected $user_agent;
	/**	@var		string		$model			Recognized Model */
	protected $model;
	/**	@var		string		$model			Recognized Manufactur */
	protected $manufacturer;
	/**	@var		string		$model			HTTP Version of Client */
	protected $httpversion;
	/**	@var		string		$cache			Cache Size of Client */
	protected $cache;
	/**	@var		string		$extra			Extra Features of Client */
	protected $extra;
	/**	@var		string		$error			Error during Recongition */
	protected $error;

	/**
	 *	Constructor
	 *	@access		public
	 *	@param		string	$input	HTTP User Agent
	 *	@return		void
	 *	@example	$ua = new Imode_User_Agent($HTTP_USER_AGENT);
	 */
	public function __construct( $input )
	{
		$fc	= new File_Configuration();
		$fc->setOption( 'pathConfig', "" );
		$fc->setOption( 'pathCache', dirname( __FILE__ )."/cache/" );
		$fc->setOption( 'useCache', true );
		$fc->loadConfig( dirname( __FILE__ )."/imode_data.xml", "data" );
		$fc->loadConfig( dirname( __FILE__ )."/imode_config.xml" );
		$config				= $fc->getConfigValues();
		$this->data			= $config['data'];
		$this->extra		= $config['extra'];
		$this->manufacturer	= $config['manufacturer'];
		$error	= 0;
		$temp	= explode( "/", $input );
		$this->user_agent	= $input;
		$this->httpversion	= $temp[1];
		$this->model			= $temp[2];
		if( isset( $temp[3] ) )
			$this->cache		= substr($temp[3], 1);
		else
			$this->cache		= IMODE_DEFAULT_CACHE;
		preg_match( "/(^[a-zA-Z]+)([0-9]+i)(.*)\/?(.*)/", $this->model, $matches );
		$this->manufacturer	= $this->manufacturer[$matches[1]];
		$this->extra			= $this->extra[$matches[3]];
		if( !( $this->data[$this->model] ) )
			$error = IMODE_UNKNOWN_USER_AGENT;
	}

	/**
	 *	Returns Dimensions of Client.
	 *	@access		public
	 *	@return		array
	 */
	public function getImageDimensions()
	{
		$data	= $this->data["$this->model"];
		$width	= $data["imagewidth"];
		$height	= $data["imageheight"];
		$retval	= array( $width, $height );
		return	 $retval;
	}

	/**
	 *	Returns Text Dimensions of Client.
	 *	@access		public
	 *	@return		array
	 */
	public function getTextDimensions()
	{
		$data	= $this->data[$this->model];
		$width	= $data['textwidth'];
		$height	= $data['textheight'];
		$retval	= array($width, $height);
		return	$retval;
	}

	/**
	 *	Returns the amount of handset cache in  kilobytes.
	 *	@access		public
	 *	@return		int
	 */
	public function getCache()
	{
		return	(int)$this->cache;
	}

	/**
	 *	Returns Manufacturer of Client.
	 *	@access		public
	 *	@return		string
	 */
	public function getManufacturer()
	{
		return	$this->manufacturer;
	}

	/**
	 *	Returns Manufacturer of Client.
	 *	@access		public
	 *	@return		string
	 */
	public function getExtra()
	{
		return	$this->extra;
	}

	public function getImageFormats()
	{
		$data	= $this->data[$this->model];
		$retval	= $data['imageformats'];
		return	$retval;
	}

	/**
	 *	Returns Version of HTTP Protocol of Client.
	 *	@access		public
	 *	@return		string
	 */
	public function getHttpVersion()
	{
		return	$this->httpversion;
	}

	/**
	 *	Returns Colours of Client.
	 *	@access		public
	 *	@return		string
	 */
	public function getColours()
	{
		$data   = $this->data[$this->model];
		$colour = $data['colour'];
		if( $colour == IMODE_COLOUR_65536 )
			return 65536;
		else if( $colour == IMODE_COLOUR_4096 )
			return 4096;
		else if( $colour == IMODE_COLOUR_256 )
			return 256;
		else if( $colour == IMODE_COLOUR_256 )
			return 256;
		else	if( $colour == IMODE_COLOUR_GREYSCALE )
			return 1;
		else if( $colour == IMODE_COLOUR_BW )
			return 1;
	}

	/**
	 *	Indicates whether the Clients Display is coloured.
	 *	@access		public
	 *	@return		bool
	 */
	public function isColour()
	{
		$data   = $this->data[$this->model];
		$colour = $data['colour'];
		$retval = 0;
		if ($colour == IMODE_COLOUR_256)
			$retval = 1;
		return	$retval;
	}

	/**
	 *	Indicates whether the Clients Display is colored.
	 *	@access		public
	 *	@return		bool
	 */
	public function isGreyScale()
	{
		$data	= $this->data[$this->model];
		$colour	= $data['colour'];
		$retval	= 0;
		if ($colour == IMODE_COLOUR_GREYSCALE)
			$retval = 1;
		return	$retval;
	}

	/**
	 *	Indicates whether the Clients Display is colored.
	 *	@access		public
	 *	@return		bool
	 */
	public function isBlackAndWhite()
	{
		$data   = $this->data[$this->model];
		$colour = $data['colour'];
		$retval = 0;
		if ($colour == IMODE_COLOUR_BW)
			$retval = 1;
		return	$retval;
	}

	/**
	 *	Indicates whether GIF is a supported Image Format.
	 *	@access		public
	 *	@return		bool
	 */
	public function supportsGif()
	{
		$data   = $this->data[$this->model];
		$formats	= array();
		$list	= explode( ",", $data['imageformats'] );
		foreach( $list as $entry )
			$formats[]	= strtolower( trim( $entry ) );
		$retval	= 0;
		if( in_array( "gif", $formats ) )
			$retval = 1;
		return $retval;
	}

	/**
	 *	Indicates whether JPG is a supported Image Format.
	 *	@access		public
	 *	@return		bool
	 */
	public function supportsJpeg()
	{
		$data   = $this->data[$this->model];
		$formats	= array();
		$list	= explode( ",", $data['imageformats'] );
		foreach( $list as $entry )
			$formats[]	= strtolower( trim( $entry ) );
		$retval	= 0;
		if( in_array( "jpg", $formats ) || in_array( "jpeg", $formats ) || in_array( "jpe", $formats ) )
			$retval = 1;
		return $retval;
	}

	/**
	 *	Indicates whether PNG is a supported Image Format.
	 *	@access		public
	 *	@return		bool
	 */
	public function supportsPng()
	{
		$data   	= $this->data[$this->model];
		$formats	= array();
		$list		= explode( ",", $data['imageformats'] );
		foreach( $list as $entry )
			$formats[]	= strtolower( trim( $entry ) );
		$retval		= 0;
		if( in_array( "png", $formats ) )
			$retval = 1;
		return $retval;
	}

	/**
	 *	Returns all Information about Client as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function getAllInfo()
	{
		$data	= array(
			'model'		=> array(
				'manufactor'	=> $this->getManufacturer(),
				'model'			=> $this->model,
				'http_version'	=> $this->getHTTPVersion(),
				),
			'sizes'		=> array(
				'image'		=> $this->getImageDimensions(),
				'text'		=> $this->getTextDimensions(),
				'cache'		=> $this->getCache(),
				),
			'formats'	=> array(
				'supported'	=> $this->getImageFormats(),
				'gif'		=> (bool) $this->supportsGig(),
				'jpg'		=> (bool) $this->supportsJpeg(),
				'png'		=> (bool) $this->supportsPng(),
				),
			'colors'	=> array(
				'bw'		=> (bool) $this->isBlackAndWhite(),
				'grayscale'	=> (bool) $this->isGreyScale(),
				'colored'	=> (bool) $this->isColour(),
				'colors'	=> $this->getColours(),
				),
			'extra'			=> $this->getExtra(),
			);
		return $data;
	}
}
?>