<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Handles Upload Error Codes by throwing Exceptions.
 *
 *	Copyright (c) 2010-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP;

use InvalidArgumentException;
use RuntimeException;

/**
 *	Handles Upload Error Codes by throwing Exceptions.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			code doc
 */
class UploadErrorHandler
{
	/** @var array<int,string> $messages  */
	protected array $messages	= [
		UPLOAD_ERR_INI_SIZE		=> 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
		UPLOAD_ERR_FORM_SIZE	=> 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
		UPLOAD_ERR_PARTIAL		=> 'The uploaded file was only partially uploaded',
		UPLOAD_ERR_NO_FILE		=> 'No file was uploaded',
		UPLOAD_ERR_NO_TMP_DIR	=> 'Missing a temporary folder',
		UPLOAD_ERR_CANT_WRITE	=> 'Failed to write file to disk',
		UPLOAD_ERR_EXTENSION	=> 'File upload stopped by extension',
	];

	public function getErrorMessage( int $code ): string
	{
		if( !isset( $this->messages[$code] ) )
			throw new InvalidArgumentException( 'Invalid Error Code ('.$code.')' );
		return $this->messages[$code];
	}

	public function handleErrorCode( int $code ): void
	{
		if( $code === 0 )
			return;
		if( !isset( $this->messages[$code] ) )
			throw new InvalidArgumentException( 'Invalid Error Code ('.$code.')' );
		$msg	= $this->messages[$code];
		switch( $code ){
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
			case UPLOAD_ERR_PARTIAL:
			case UPLOAD_ERR_NO_FILE:
			case UPLOAD_ERR_EXTENSION:
				throw new InvalidArgumentException( $msg );
			case UPLOAD_ERR_NO_TMP_DIR:
			case UPLOAD_ERR_CANT_WRITE:
				throw new RuntimeException( $msg );
		}
	}

	public function handleErrorFromUpload( array $upload ): void
	{
		$code	= $upload['error'];
		$this->handleErrorCode( $code );
	}

	/**
	 *	Sets Error Messages.
	 *	@access		public
	 *	@param		array		$messages		Map of Error Messages assigned to official PHP Upload Error Codes Constants
	 *	@return		static
	 */
	public function setMessages( array $messages ): static
	{
		$this->messages	= array_merge( $this->messages, $messages );
		return $this;
	}
}
