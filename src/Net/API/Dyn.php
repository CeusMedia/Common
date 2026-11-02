<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Access to Dyn (dyn.com) API.
 *
 *	Copyright (c) 2015-2026 Christian Würker (ceusmedia.de)
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
 *	@copyright		2015-2026 Christian Würker
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
use RuntimeException;

/**
 *  Access to Dyn (dyn.com) API.
 *
 *  @category       Library
 *  @package        CeusMedia_Common_Net_API
 *  @author         Christian Würker <christian.wuerker@ceusmedia.de>
 *  @copyright      2015-2026 Christian Würker
 *  @license        https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *  @link           https://github.com/CeusMedia/Common
 *  @see            http://dyn.com/support/developers/api/
 */
class Dyn
{
	protected ?string $cacheFile	= NULL;
	protected ?string $lastIp		= NULL;
	protected int $lastCheck		= 0;
	protected ?string $lastUpdatedIp	= NULL;
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
				$data	= json_decode( (string) FileReader::load( $cacheFile ), TRUE );
				if( is_array( $data ) ){
					$this->lastIp			= is_string( $data['ip'] ?? NULL ) ? $data['ip'] : NULL;
					$this->lastCheck		= (int) ( $data['timestamp'] ?? 0 );
					$this->lastUpdatedIp	= is_string( $data['updated'] ?? NULL ) ? $data['updated'] : NULL;
				}
			}
		}
		$this->reader	= new NetReader();
		$this->reader->setUserAgent( "CeusMedia - DynUpdateBot - 0.1" );
	}

	/**
	 *	Returns external IP of this server identified by Dyn service.
	 *	The IP is cached for 10 minutes.
	 *	@access		public
	 *	@return		string		IP address to be identified
	 *	@throws		IoException
	 *	@throws		RuntimeException	if no IP address could be identified
	 */
	public function getIp(): string
	{
		if( NULL !== $this->lastIp && time() - $this->lastCheck < 10 * 60 )
			return $this->lastIp;
		$this->reader->setUrl( 'https://checkip.dyndns.org' );
		$html	= $this->reader->read();
		$parts	= explode( ": ", strip_tags( $html ) );
		$ip		= trim( (string) array_pop( $parts ) );
		if( FALSE === filter_var( $ip, FILTER_VALIDATE_IP ) )
			throw new RuntimeException( 'No IP address identified' );
		$this->lastIp		= $ip;
		$this->lastCheck	= time();
		$this->save();
		return $ip;
	}

	/**
	 *	Save cache.
	 *	@access		protected
	 *	@return		integer		Number of bytes written to cache file
	 */
	protected function save(): int
	{
		if( !$this->cacheFile )
			return 0;
		$data	= [
			'ip'		=> $this->lastIp,
			'timestamp'	=> $this->lastCheck,
			'updated'	=> $this->lastUpdatedIp,
		];
		return (int) FileWriter::save( $this->cacheFile, JsonEncoder::create()->encode( $data ) );
	}

	/**
	 *	Updates IP of host registered by Dyn.
	 *	Does nothing and returns "noop" if this IP has already been sent successfully.
	 *	@access		public
	 *	@param		string		$username		Dyn user name
	 *	@param		string		$password		Dyn user password
	 *	@param		string		$host			Dyn registered host
	 *	@param		string		$ip				Ip address to set for host
	 *	@return		string		Update code string returned by Dyn service (e.g. good, nochg, badauth) or "noop"
	 *	@throws		IoException
	 */
	public function update( string $username, string $password, string $host, string $ip ): string
	{
		if( $ip === $this->lastUpdatedIp )
			return "noop";
		$url	= "https://%s:%s@members.dyndns.org/nic/update?hostname=%s&myip=%s&wildcard=NOCHG&mx=NOCHG&backmx=NOCHG";
		$url	= sprintf( $url, rawurlencode( $username ), rawurlencode( $password ), rawurlencode( $host ), rawurlencode( $ip ) );
		$this->reader->setUrl( $url );
		$parts	= explode( " ", trim( $this->reader->read() ) );
		$code	= (string) array_shift( $parts );
		if( in_array( $code, ['good', 'nochg'], TRUE ) ){
			$this->lastUpdatedIp	= $ip;
			$this->save();
		}
		return $code;
	}
}
