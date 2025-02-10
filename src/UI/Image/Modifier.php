<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpComposerExtensionStubsInspection */

/**
 *	Abstract basic class for all image modifying classes.
 *
 *	Copyright (c) 2009-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\Image;

use Exception;
use GdImage;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Abstract basic class for all image modifying classes.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
abstract class Modifier
{
	/*	@var		int				$quality		Quality of Target Image */
	protected int $quality;

	/*	@var		array			$sourceInfo		Sizes of Source Image */
	protected array $sourceInfo		= [];

	/*	@var		GdImage|NULL	$source			Source image */
	protected ?GdImage $source		= NULL;

	/*	@var		string|NULL		$sourceUri		Source image URI */
	protected ?string $sourceUri	= NULL;

	/*	@var		GdImage|NULL	$target			Target image */
	protected ?GdImage $target		= NULL;

	/*	@var		string|NULL		$targetUri		Target image URI */
	protected ?string $targetUri	= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string|NULL		$sourceUri 		File URI of Source Image
	 *	@param		string|NULL		$targetUri 		File URI of Target Image
	 *	@param		int				$quality 		Quality of Target Image
	 *	@return		void
	 */
	public function __construct( ?string $sourceUri = NULL, ?string $targetUri = NULL, int $quality = 100 )
	{
		if( !is_null( $sourceUri ) )
			$this->setSourceUri( $sourceUri );
		if( !is_null( $targetUri ) )
			$this->setTargetUri( $targetUri );
		$this->setQuality( $quality );
	}

	public function loadImage(): void
	{
		if( !$this->sourceUri )
			throw new RuntimeException( 'No source image URI set' );
		$this->source = match( $this->sourceInfo[2] ){
			IMAGETYPE_GIF	=> imagecreatefromgif($this->sourceUri),
			IMAGETYPE_JPEG	=> imagecreatefromjpeg($this->sourceUri),
			IMAGETYPE_PNG	=> imagecreatefrompng($this->sourceUri),
			default			=> throw new Exception('Image type "' . $this->sourceInfo['mime'] . '" is no supported'),
		};
	}

	/**
	 *	Saves target image source to image file.
	 *	@access		public
	 *	@param		int|NULL		$type			Output format type
	 *	@return		bool
	 */
	public function saveImage( ?int $type = NULL ): bool
	{
		if( !$this->source )
			throw new RuntimeException( 'No image loaded' );
		if( !$this->target )
			throw new RuntimeException( 'No modification applied' );
		if( !$this->targetUri )
			throw new RuntimeException( 'No target image URI set' );
		$type	= $type ?: $this->sourceInfo[2];
		return match( $type ){
			IMAGETYPE_GIF	=> imagegif($this->target, $this->targetUri),
			IMAGETYPE_JPEG	=> imagejpeg($this->target, $this->targetUri, $this->quality),
			IMAGETYPE_PNG	=> imagepng($this->target, $this->targetUri),
			default			=> throw new Exception('Image Type "' . $type . '" is no supported'),
		};
	}

	/**
	 *	Sets the Quality of resulting Image.
	 *	@access		public
	 *	@param		int			$quality 		Quality of resulting Image
	 *	@return		self
	 */
	public function setQuality( int $quality ): self
	{
		$this->quality	= $quality;
		return $this;
	}

	/**
	 *	Sets the File Name of Source Image.
	 *	@access		public
	 *	@param		string		$sourceUri 		File URI of Source Image
	 *	@return		self
	 */
	public function setSourceUri( string $sourceUri ): self
	{
		if( !file_exists( $sourceUri ) )
			throw new InvalidArgumentException( 'Image source "'.$sourceUri.'" is not existing' );
		$info = @getimagesize( $sourceUri );
		if( !$info )
			throw new Exception( 'Image source "'.$sourceUri.'" is not of a supported type' );
		$this->sourceUri	= $sourceUri;
		$this->sourceInfo	= $info;
		$this->loadImage();
		return $this;
	}

	/**
	 *	Sets the File Name of Target Image.
	 *	@access		public
	 *	@param		string		$targetUri 		File URI of resulting Target Image
	 *	@return		self
	 */
	public function setTargetUri( string $targetUri ): self
	{
		$this->targetUri	= $targetUri;
		return $this;
	}
}
