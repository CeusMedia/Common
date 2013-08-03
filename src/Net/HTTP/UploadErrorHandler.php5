<?php
/**
 *	Handes Upload Error Codes by throwing Exceptions.
 *
 *	Copyright (c) 2010-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Net.HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	Handes Upload Error Codes by throwing Exceptions.
 *	@category		cmClasses
 *	@package		Net.HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 *	@todo			code doc
 */
class Net_HTTP_UploadErrorHandler
{
	protected $messages	= array(
		UPLOAD_ERR_INI_SIZE		=> 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
		UPLOAD_ERR_FORM_SIZE	=> 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
		UPLOAD_ERR_PARTIAL		=> 'The uploaded file was only partially uploaded',
		UPLOAD_ERR_NO_FILE		=> 'No file was uploaded',
		UPLOAD_ERR_NO_TMP_DIR	=> 'Missing a temporary folder',
		UPLOAD_ERR_CANT_WRITE	=> 'Failed to write file to disk',
		UPLOAD_ERR_EXTENSION	=> 'File upload stopped by extension',
	);

	public function getErrorMessage( $code ){
		if( !isset( $this->messages[(string)$code] ) )
			throw new InvalidArgumentException( 'Invalid Error Code ('.$code.')' );
		return $this->messages[$code];
	}
	
	public function handleErrorCode( $code )
	{
		if( (int)$code === 0 )
			return;
		if( !isset( $this->messages[(string)$code] ) )
			throw new InvalidArgumentException( 'Invalid Error Code ('.$code.')' );
		$msg	= $this->messages[$code];
		switch( $code )
		{
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

	public function handleErrorFromUpload( $upload )
	{
		$code	= $upload['error'];
		return $this->handleErrorCode( $code );
	
	}
	
	/**
	 *	Sets Error Messages.
	 *	@access		public
	 *	@param		array		Map of Error Messages assigned to official PHP Upload Error Codes Constants
	 *	@return		string
	 */
	public function setMessages( $messages )
	{
		foreach( $messages as $code => $label )
			$this->messages[$code]	= $label;
	}
}
?>