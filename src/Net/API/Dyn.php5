<?php
/**
 *	Access to Dyn (dyn.com) API.
 *
 *	Copyright (c) 2013 Christian Würker (ceusmedia.com)
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
 *	@package		Net.API
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@see			http://dyn.com/support/developers/api/
 *	@since			0.7.6
 *	@version		$Id$
 */
/**
 *  Access to Dyn (dyn.com) API.
 *
 *  @category       cmClasses
 *  @package        Net.API
 *  @author         Christian Würker <christian.wuerker@ceusmedia.de>
 *  @copyright      2013 Christian Würker
 *  @license        http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *  @link           http://code.google.com/p/cmclasses/
 *  @see            http://dyn.com/support/developers/api/
 *  @since          0.7.6
 *  @version        $Id$
 */
class Net_API_Dyn{

	protected $cacheFile	= NULL;
	protected $lastIp;
	protected $lastCheck	= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$cacheFile		Name of cache file
	 *	@return		void
	 */
	public function __construct( $cacheFile = NULL ){
		if( is_string( $cacheFile ) ){
			$this->cacheFile	= $cacheFile;
			if( file_exists( $cacheFile ) ){
				$data	= json_decode( File_Reader::load( $cacheFile ) );
				$this->lastIp		= $data->ip;
				$this->lastCheck	= $data->timestamp;
			}
		}
		Net_Reader::setUserAgent( "CeusMedia - DynUpdateBot - 0.1" );
	}

	/**
	 *	Returns external IP of this server identified by Dyn service.
	 *	@access		public
	 *	@return		string		IP address to be identified 
	 */
	public function getIp(){
		if( (int) $this->lastCheck > 0 && time() - $this->lastCheck < 10 * 60 )
			return $this->lastIp;
		$html	= Net_Reader::readUrl( 'http://checkip.dyndns.org' );
		$parts	= explode( ": ", strip_tags( $html ) );
		$ip		= trim( array_pop( $parts ) );
		$this->save( array( 'ip' => $ip, 'timestamp' => time() ) );
		return $ip;
	}

	/**
	 *	Save cache.
	 *	@access		protected
	 *	@param		array		$data			Map of IP and timestamp
	 *	@return		integer		Number of bytes written to cache file
	 */
	protected function save( $data ){
		if( !$this->cacheFile )
			return;
		$last	= array(
			'ip'		=> $this->lastIp,
			'timestamp'	=> $this->lastCheck
		);
		$data	= array_merge( $last,  $data );
		return File_Writer::save( $this->cacheFile, json_encode( $data ) );
	}

	/**
	 *	Updates IP of host registered by Dyn.
	 *	@access		public
	 *	@param		string		$username		Dyn user name
	 *	@param		string		$password		Dyn user password
	 *	@param		string		$host			Dyn registered host
	 *	@param		string		$ip				Ip address to set for host
	 *	@return		string		Update code string returned by Dyn service
	 */
	public function update( $username, $password, $host, $ip ){
		if( (int) $this->lastCheck > 0 && time() - $this->lastCheck < 10 * 60 )
			return "noop";
		$url	= "http://%s:%s@members.dyndns.org/nic/update?hostname=%s&myip=%s&wildcard=NOCHG&mx=NOCHG&backmx=NOCHG";
		$url	= sprintf( $url, $username, $password, $host, $ip );
		$parts	= explode( " ", Net_Reader::readUrl( $url ) );
		return array_shift( $parts );
	}
}
?>
