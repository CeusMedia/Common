<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Access to Dyn (dyn.com) API.
 *
 *	Copyright (c) 2015-2025 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_API
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://dyn.com/support/developers/api/
 */

namespace CeusMedia\Common\Net\API;

use CeusMedia\Common\ADT\JSON\Encoder as JsonEncoder;
use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\FS\File\Writer as FileWriter;
use CeusMedia\Common\Net\Reader as NetReader;

/**
 *  Access to Dyn (dyn.com) API.
 *
 *  @category       Library
 *  @package        CeusMedia_Common_Net_API
 *  @author         Christian Würker <christian.wuerker@ceusmedia.de>
 *  @copyright      2015-2025 Christian Würker
 *  @license        https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *  @link           https://github.com/CeusMedia/Common
 *  @see            http://dyn.com/support/developers/api/
 */
class Dyn
{
	protected ?string $cacheFile	= NULL;
	protected ?string $lastIp		= NULL;
	protected int $lastCheck		= 0;
	protected NetReader $reader;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string|NULL		$cacheFile		Name of cache file
	 *	@return		void
	 */
	public function __construct( ?string $cacheFile = NULL )
	{
		if( is_string( $cacheFile ) ){
			$this->cacheFile	= $cacheFile;
			if( file_exists( $cacheFile ) ){
				$data	= json_decode( FileReader::load( $cacheFile ) );
				$this->lastIp		= $data->ip;
				$this->lastCheck	= $data->timestamp;
			}
		}
		$this->reader	= new NetReader();
		$this->reader->setUserAgent( "CeusMedia - DynUpdateBot - 0.1" );
	}

	/**
	 *	Returns external IP of this server identified by Dyn service.
	 *	@access		public
	 *	@return		string		IP address to be identified
	 *	@throws		IoException
	 */
	public function getIp(): string
	{
		if( (int) $this->lastCheck > 0 && time() - $this->lastCheck < 10 * 60 )
			return $this->lastIp;
		$this->reader->setUrl( 'https://checkip.dyndns.org' );
		$html	= $this->reader->read();
		$parts	= explode( ": ", strip_tags( $html ) );
		$ip		= trim( array_pop( $parts ) );
		$this->save( ['ip' => $ip, 'timestamp' => time()] );
		return $ip;
	}

	/**
	 *	Save cache.
	 *	@access		protected
	 *	@param		array		$data			Map of IP and timestamp
	 *	@return		integer		Number of bytes written to cache file
	 */
	protected function save( array $data ): int
	{
		if( !$this->cacheFile )
			return 0;
		$last	= [
			'ip'		=> $this->lastIp,
			'timestamp'	=> $this->lastCheck
		];
		$data	= array_merge( $last,  $data );
		return FileWriter::save( $this->cacheFile, JsonEncoder::create()->encode( $data ) );
	}

	/**
	 *	Updates IP of host registered by Dyn.
	 *	@access		public
	 *	@param		string		$username		Dyn user name
	 *	@param		string		$password		Dyn user password
	 *	@param		string		$host			Dyn registered host
	 *	@param		string		$ip				Ip address to set for host
	 *	@return		string		Update code string returned by Dyn service
	 *	@throws		IoException
	 */
	public function update( string $username, string $password, string $host, string $ip ): string
	{
		if( (int) $this->lastCheck > 0 && time() - $this->lastCheck < 10 * 60 )
			return "noop";
		$url	= "https://%s:%s@members.dyndns.org/nic/update?hostname=%s&myip=%s&wildcard=NOCHG&mx=NOCHG&backmx=NOCHG";
		$url	= sprintf( $url, $username, $password, $host, $ip );
		$this->reader->setUrl( $url );
		$parts	= explode( " ", $this->reader->read() );
		return array_shift( $parts );
	}
}
