<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

/**
 *	Simple Subversion client.
 *
 *	Copyright (c) 2011-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_Net_SVN
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\SVN;

use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\XML\Element as XmlElement;
use Exception;

/**
 *	Simple Subversion client.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_SVN
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Client
{
	protected string $path;
	protected string $pathExp;

	/**
	 *	@param		string		$path
	 *	@throws		IoException
	 */
	public function __construct( string $path )
	{
		if( !file_exists( $path ) )
			throw new IoException( 'Invalid path', 0, $path );
		$this->path		= realpath( $path ).'/';
		$this->pathExp	= '^('.str_replace( '/', '\/', $this->path ).')';
	}

	/**
	 *	@param		string		$path
	 *	@return		bool
	 *	@throws		IoException
	 */
	public function add( string $path ): bool
	{
		$url	= $this->path.$path;
		if( !file_exists( $url ) )
			throw new IoException( 'Invalid path', 0, $path );
		$status	= @svn_add( $url );
		if( !$status )
			svn_revert( $url );
		return $status;
	}

	/**
	 *	@param		string		$username
	 *	@param		string		$password
	 *	@return		void
	 */
	public function authenticate( string $username, string $password ): void
	{
		svn_auth_set_parameter( SVN_AUTH_PARAM_NON_INTERACTIVE, 'true');
		svn_auth_set_parameter( SVN_AUTH_PARAM_DEFAULT_USERNAME, $username );
		svn_auth_set_parameter( SVN_AUTH_PARAM_DEFAULT_PASSWORD, $password );
		// <--- Important for certificate issues!
		svn_auth_set_parameter( PHP_SVN_AUTH_PARAM_IGNORE_SSL_VERIFY_ERRORS, 'true');
		svn_auth_set_parameter( SVN_AUTH_PARAM_NO_AUTH_CACHE, 'true');
	}

	/**
	 *	@param		string		$msg
	 *	@param		array		$list
	 *	@return		array
	 */
	public function commit( string $msg, array $list ): array
	{
		foreach( $list as $nr => $item )
			$list[$nr]	= realpath( $item );
		return svn_commit( $msg, $list );
	}

	/**
	 *	@param		string		$path
	 *	@return		array|string|string[]
	 */
	public function getRelativePath( string $path ): array|string
	{
		//  Windows fix
		$path	= str_replace( '\\', '/', $path );
		if( preg_match( '/'.$this->pathExp.'/U', $path ) )
			$path	= substr( $path, strlen( $this->path ) );
		return $path;
	}

	/**
	 *	@param		string		$path
	 *	@return		XmlElement
	 *	@throws		Exception
	 */
	public function info( string $path = '' ): XmlElement
	{
		$path	= $this->path.$path;
		if( !strlen( `svn info $path` ) )
			throw new Exception( 'Path '.$path.' is not under SVN conrol' );
		$xml	= `svn info $path --xml 2>&1`;
		return new XmlElement( $xml );
	}

	/**
	 *	@param		string		$path
	 *	@param		int			$revision
	 *	@param		bool		$recurse
	 *	@return		array
	 *	@throws		IoException
	 */
	public function ls( string $path, int $revision = SVN_REVISION_HEAD, bool $recurse = FALSE ): array
	{
		$url	= $this->path.$path;
		if( !file_exists( $url ) )
			throw new IoException( 'Invalid path', 0, $path );
		return svn_ls( $url, $revision, $recurse );
	}

	/**
	 *	@param		string		$path
	 *	@return		bool
	 *	@throws		IoException
	 */
	public function revert( string $path ): bool
	{
		$url	= $this->path.$path;
		if( !file_exists( $url ) )
			throw new IoException( 'Invalid path', 0, $path );
		return @svn_revert( $url );
	}

	/**
	 *	@param		string		$path
	 *	@param		int			$flags
	 *	@return		array
	 *	@throws		IoException
	 */
	public function status( string $path = '.', int $flags = 0 ): array
	{
		$url		= $this->path.$path;
		if( !file_exists( $url ) )
			throw new IoException( 'Invalid path', 0, $path );
		return svn_status( $url,  $flags );
	}
}
