<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Builds HTML of Bar Indicator.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\HTML;

use CeusMedia\Common\ADT\OptionObject as OptionObject;
use OutOfRangeException;

/**
 *	Builds HTML of Bar Indicator.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Indicator extends OptionObject
{
	/**	@var		array		$defaultOptions			Map of default options */
	public $defaultOptions		= [
		'id'					=> NULL,
		'classIndicator'		=> 'indicator',
		'classInner'			=> 'indicator-inner',
		'classOuter'			=> 'indicator-outer',
		'classPercentage'		=> 'indicator-percentage',
		'classRatio'			=> 'indicator-ratio',
		'length'				=> 100,
		'invertColor'			=> FALSE,
		'useColor'				=> TRUE,
		'useColorAtBorder'		=> FALSE,
  		'useData'				=> TRUE,
		'usePercentage'			=> FALSE,
		'useRatio'				=> FALSE,
	];

	/**
	 *	Constructor, sets Default Options, sets useColor and usePercentage to TRUE.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( array $options = [] )
	{
		parent::__construct( $this->defaultOptions, $options );
	}

	/**
	 *	Builds HTML of Indicator.
	 *	@access		public
	 *	@param		int			$found		Amount of positive Cases
	 *	@param		int			$count		Amount of all Cases
	 *	@param		int|NULL	$length		Length of inner Indicator Bar
	 *	@return		string
	 */
	public function build( int $found, int $count, ?int $length = NULL ): string
	{
		$length			= is_null( $length ) ? $this->getOption( 'length' ) : $length;
		$found			= min( $found, $count );
		$ratio			= $count ? $found / $count : 0;
		$divBar			= $this->renderBar( $ratio, $length );
		$divRatio		= $this->renderRatio( $found, $count );
		$divPercentage	= $this->renderPercentage( $ratio );
		$divIndicator	= new Tag( "div" );
		$divIndicator->setContent( $divBar.$divPercentage.$divRatio );
		$divIndicator->setAttribute( 'class', $this->getOption( 'classIndicator' ) );
		if( $this->getOption( 'id' ) )
			$divIndicator->setAttribute( 'id', $this->getOption( 'id' ) );
		if( $this->getOption( 'useData' ) ){
			$divIndicator->setAttribute( 'data-total', $count );
			$divIndicator->setAttribute( 'data-value', $found );
			foreach( $this->getOptions() as $key => $value )
//				if( strlen( $value ) )
//				if( preg_match( "/^use/", $key ) )
					$divIndicator->setAttribute( 'data-option-'.$key, (string) $value );
		}
		if( $this->getOption( 'useColorAtBorder' ) ){
			$color	= $this->getColorFromRatio( $ratio );
			$divIndicator->setAttribute( 'style', sprintf("border-color: rgb(%s,%s,%s)", $color[0], $color[1], $color[2]));
		}
		return $divIndicator->build();
	}

	/**
	 *	Returns RGB list of calculated color
	 *	@access		public
	 *	@param		int			$found		Amount of positive Cases
	 *	@param		int			$count		Amount of all Cases
	 *	@return		array		List of RGB values
	 */
	public function getColor( int $found, int $count ): array
	{
		$ratio			= $count ? $found / $count : 0;
		return $this->getColorFromRatio( $ratio );
	}

	/**
	 *	Returns RGB list of color calculated by ratio.
	 *	@access		public
	 *	@param		float		$ratio		Ratio (between 0 and 1)
	 *	@return		array		List of RGB values
	 */
	public function getColorFromRatio( float $ratio ): array
	{
		if( $this->getOption( 'invertColor' ) )
			$ratio	= 1 - $ratio;
		$colorR	= ( 1 - $ratio ) > 0.5 ? 255 : round( ( 1 - $ratio ) * 2 * 255 );
		$colorG	= $ratio > 0.5 ? 255 : round( $ratio * 2 * 255 );
		$colorB	= "0";
		return [$colorR, $colorG, $colorB];
	}

	/**
	 *	Returns CSS Class of Indicator DIV.
	 *	@access		public
	 *	@return		string|NULL
	 */
	public function getIndicatorClass(): ?string
	{
		return $this->getOption( 'classIndicator' );
	}

	/**
	 *	Returns CSS Class of inner DIV.
	 *	@access		public
	 *	@return		string|NULL
	 */
	public function getInnerClass(): ?string
	{
		return $this->getOption( 'classInner' );
	}

	/**
	 *	Returns CSS Class of outer DIV.
	 *	@access		public
	 *	@return		string|NULL
	 */
	public function getOuterClass(): ?string
	{
		return $this->getOption( 'classOuter' );
	}

	/**
	 *	Returns CSS Class of Percentage DIV.
	 *	@access		public
	 *	@return		string|NULL
	 */
	public function getPercentageClass(): ?string
	{
		return $this->getOption( 'classPercentage' );
	}

	/**
	 *	Returns CSS Class of Ratio DIV.
	 *	@access		public
	 *	@return		string|NULL
	 */
	public function getRatioClass(): ?string
	{
		return $this->getOption( 'classRatio' );
	}

	public static function render( int $count, int $found, array $options = [] ): string
	{
		$indicator	= new Indicator( $options );
		return $indicator->build( $count, $found );
	}

	/**
	 *	Builds HTML Code of Indicator Bar.
	 *	@access		protected
	 *	@param		float		$ratio		Ratio (between 0 and 1)
	 *	@param		int			$length		Length of Indicator
	 *	@return		string
	 */
	protected function renderBar( float $ratio, int $length = 100 ): string
	{
		$width		= max( 0, min( 100, $ratio * 100 ) );
		$cssDiv		= ['width' => $width.'%'];
		$cssSpan	= [];
		if( $this->getOption( 'useColor' ) ){
			$color	= $this->getColorFromRatio( $ratio );
			$cssDiv['background-color']	= sprintf("rgb(%s,%s,%s)", $color[0], $color[1], $color[2]);
			if( $this->getOption( 'useColorAtBorder' ) )
				$cssSpan['border-color']	= sprintf("rgb(%s,%s,%s)", $color[0], $color[1], $color[2]);
		}

		$bar	= Tag::create( 'div', "", [
			'class'	=> $this->getOption( 'classInner' ),
			'style'	=> $cssDiv,
		] );

		$attributes	= ['class' => $this->getOption( 'classOuter' )];
		if( $length !== 100 )
			$cssSpan['width']	= preg_match( "/%$/", (string) $length ) ? $length : $length.'px';
		$attributes['style']	= $cssSpan;
		return Tag::create( "span", $bar, $attributes );
	}

	/**
	 *	Builds HTML Code of Percentage Block.
	 *	@access		protected
	 *	@param		float		$ratio		Ratio (between 0 and 1)
	 *	@return		string
	 */
	protected function renderPercentage( float $ratio ): string
	{
		if( !$this->getOption( 'usePercentage' ) )
			return '';
		$value		= floor( $ratio * 100 )."&nbsp;%";
		$attributes	= ['class' => $this->getOption( 'classPercentage' )];
		return Tag::create( "span", $value, $attributes );
	}

	/**
	 *	Builds HTML Code of Ratio Block.
	 *	@access		protected
	 *	@param		int			$found		Amount of positive Cases
	 *	@param		int			$count		Amount of all Cases
	 *	@return		string
	 */
	protected function renderRatio( int $found, int $count ): string
	{
		if( !$this->getOption( 'useRatio' ) )
			return "";
		$content	= $found."/".$count;
		$attributes	= ['class' => $this->getOption( 'classRatio' )];
		return Tag::create( "span", $content, $attributes );
	}

	/**
	 *	Sets CSS Class of Indicator DIV.
	 *	@access		public
	 *	@param		string|NULL		$class		CSS Class Name
	 *	@return		self
	 */
	public function setIndicatorClass( ?string $class ): self
	{
		$this->setOption( 'classIndicator', $class );
		return $this;
	}

	/**
	 *	Sets CSS Class of inner DIV.
	 *	@access		public
	 *	@param		string|NULL		$class		CSS Class Name
	 *	@return		self
	 */
	public function setInnerClass( ?string $class ): self
	{
		$this->setOption( 'classInner', $class );
		return $this;
	}

	/**
	 *	Sets Option.
	 *	@access		public
	 *	@param		string		$key		Option Key (useColor|usePercentage|useRatio)
	 *	@param		mixed		$value		Flag: switch Option
	 *	@return		bool
	 */
	public function setOption( string $key, $value ): bool
	{
		if( !array_key_exists( $key, $this->defaultOptions ) )
			throw new OutOfRangeException( 'Option "'.$key.'" is not a valid Indicator Option.' );
		return parent::setOption( $key, $value );
	}

	/**
	 *	Sets CSS Class of outer DIV.
	 *	@access		public
	 *	@param		string|NULL		$class		CSS Class Name
	 *	@return		self
	 */
	public function setOuterClass( ?string $class ): self
	{
		$this->setOption( 'classOuter', $class );
		return $this;
	}

	/**
	 *	Sets CSS Class of Percentage DIV.
	 *	@access		public
	 *	@param		string|NULL		$class		CSS Class Name
	 *	@return		self
	 */
	public function setPercentageClass( ?string $class ): self
	{
		$this->setOption( 'classPercentage', $class );
		return $this;
	}

	/**
	 *	Sets CSS Class of Ratio DIV.
	 *	@access		public
	 *	@param		string|NULL		$class		CSS Class Name
	 *	@return		self
	 */
	public function setRatioClass( ?string $class ): self
	{
		$this->setOption( 'classRatio', $class );
		return $this;
	}
}
