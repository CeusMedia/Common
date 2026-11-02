<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	Assertions for comparing images by their pixels instead of their file contents.
 *	@package		Tests.UI.Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\UI\Image;

use GdImage;

/**
 *	Assertions for comparing images by their pixels instead of their file contents.
 *	Encoders of different GD/libpng/libjpeg versions create different files for the same image,
 *	so image files cannot be compared byte by byte.
 *	@package		Tests.UI.Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
trait ImageAssertions
{
	/**
	 *	Asserts that two images have the same size and (nearly) the same pixels.
	 *	@param		string|GdImage		$expected		Expected image (file name or GD image)
	 *	@param		string|GdImage		$actual			Actual image (file name or GD image)
	 *	@param		int					$tolerance		Maximum difference per color channel (0-255), e.g. for JPEG
	 *	@return		void
	 */
	protected static function assertImageEquals( string|GdImage $expected, string|GdImage $actual, int $tolerance = 0 ): void
	{
		$expectedImage	= self::loadImageForComparison( $expected );
		$actualImage	= self::loadImageForComparison( $actual );
		self::assertSame( imagesx( $expectedImage ), imagesx( $actualImage ), 'Image width differs' );
		self::assertSame( imagesy( $expectedImage ), imagesy( $actualImage ), 'Image height differs' );

		$maxDifference	= 0;
		for( $x = 0; $x < imagesx( $expectedImage ); $x++ ){
			for( $y = 0; $y < imagesy( $expectedImage ); $y++ ){
				$a	= imagecolorsforindex( $expectedImage, imagecolorat( $expectedImage, $x, $y ) );
				$b	= imagecolorsforindex( $actualImage, imagecolorat( $actualImage, $x, $y ) );
				//  ignore colors of fully transparent pixels
				if( 127 === $a['alpha'] && 127 === $b['alpha'] )
					continue;
				foreach( ['red', 'green', 'blue', 'alpha'] as $channel )
					$maxDifference	= max( $maxDifference, abs( $a[$channel] - $b[$channel] ) );
			}
		}
		self::assertLessThanOrEqual( $tolerance, $maxDifference, 'Images differ by up to '.$maxDifference.' per color channel' );
	}

	/**
	 *	Asserts that two images have the same size and a small mean difference of their pixels.
	 *	Use this for images created by resampling or lossy encoders, which differ between library versions.
	 *	@param		string|GdImage		$expected		Expected image (file name or GD image)
	 *	@param		string|GdImage		$actual			Actual image (file name or GD image)
	 *	@param		float				$maxMean		Maximum mean difference per color channel (0-255)
	 *	@return		void
	 */
	protected static function assertImageSimilar( string|GdImage $expected, string|GdImage $actual, float $maxMean = 5.0 ): void
	{
		$expectedImage	= self::loadImageForComparison( $expected );
		$actualImage	= self::loadImageForComparison( $actual );
		self::assertSame( imagesx( $expectedImage ), imagesx( $actualImage ), 'Image width differs' );
		self::assertSame( imagesy( $expectedImage ), imagesy( $actualImage ), 'Image height differs' );

		$sum	= 0;
		$count	= 0;
		for( $x = 0; $x < imagesx( $expectedImage ); $x++ ){
			for( $y = 0; $y < imagesy( $expectedImage ); $y++ ){
				$a	= imagecolorsforindex( $expectedImage, imagecolorat( $expectedImage, $x, $y ) );
				$b	= imagecolorsforindex( $actualImage, imagecolorat( $actualImage, $x, $y ) );
				foreach( ['red', 'green', 'blue', 'alpha'] as $channel ){
					$sum	+= abs( $a[$channel] - $b[$channel] );
					$count++;
				}
			}
		}
		$mean	= $count > 0 ? $sum / $count : 0.0;
		self::assertLessThanOrEqual( $maxMean, $mean, 'Images differ by '.round( $mean, 2 ).' per color channel on average' );
	}

	protected static function loadImageForComparison( string|GdImage $image ): GdImage
	{
		if( $image instanceof GdImage )
			$resource	= $image;
		else{
			self::assertFileExists( $image );
			$resource	= imagecreatefromstring( (string) file_get_contents( $image ) );
			self::assertInstanceOf( GdImage::class, $resource, 'Image "'.$image.'" could not be loaded' );
		}
		if( !imageistruecolor( $resource ) ){
			$copy	= imagecreatetruecolor( imagesx( $resource ), imagesy( $resource ) );
			imagealphablending( $copy, FALSE );
			imagesavealpha( $copy, TRUE );
			imagefill( $copy, 0, 0, (int) imagecolorallocatealpha( $copy, 0, 0, 0, 127 ) );
			imagecopy( $copy, $resource, 0, 0, 0, 0, imagesx( $resource ), imagesy( $resource ) );
			$resource	= $copy;
		}
		return $resource;
	}
}
