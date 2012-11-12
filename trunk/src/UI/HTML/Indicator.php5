<?php
/**
 *	Builds HTML of Bar Indicator.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Builds HTML of Bar Indicator.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@extends		ADT_OptionObject
 *	@uses			UI_HTML_Tag
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class UI_HTML_Indicator extends ADT_OptionObject
{
	/**	@var		array		$defaultOptions			Map of default options */
	public $defaultOptions		= array(
		'id'					=> NULL,
		'classIndicator'		=> 'indicator',
		'classInner'			=> 'indicator-inner',
		'classOuter'			=> 'indicator-outer',
		'classPercentage'		=> 'indicator-percentage',
		'classRatio'			=> 'indicator-ratio',
		'length'				=> 100,
		'useColor'				=> TRUE,
		'useData'				=> TRUE,
		'usePercentage'			=> FALSE,
		'useRatio'				=> FALSE,
	);

	/**
	 *	Constructor, sets Default Options, sets useColor and usePercentage to TRUE.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $options = array() )
	{
		parent::__construct( $this->defaultOptions, $options );
	}

	/**
	 *	Builds HTML of Indicator.
	 *	@access		public
	 *	@param		int			$found		Amount of positive Cases
	 *	@param		int			$count		Amount of all Cases
	 *	@param		int			$length		Length of inner Indicator Bar
	 *	@return		string
	 */
	public function build( $found, $count, $length = NULL )
	{
		$length			= is_null( $length ) ? $this->getOption( 'length' ) : $length;
		$found			= min( $found, $count );
		$ratio			= $count ? $found / $count : 0;
		$divBar			= $this->renderBar( $ratio, $length );
		$divRatio		= $this->renderRatio( $found, $count );
		$divPercentage	= $this->renderPercentage( $ratio );
		$divIndicator	= new UI_HTML_Tag( "div" );
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
		return $divIndicator->build();
	}

	/**
	 *	Returns CSS Class of Indicator DIV.
	 *	@access		public
	 *	@return		void
	 */
	public function getIndicatorClass()
	{
		return $this->getOption( 'classIndicator' );
	}

	/**
	 *	Returns CSS Class of inner DIV.
	 *	@access		public
	 *	@return		void
	 */
	public function getInnerClass()
	{
		return $this->getOption( 'classInner' );
	}

	/**
	 *	Returns CSS Class of outer DIV.
	 *	@access		public
	 *	@return		void
	 */
	public function getOuterClass()
	{
		return $this->getOption( 'classOuter' );
	}

	/**
	 *	Returns CSS Class of Percentage DIV.
	 *	@access		public
	 *	@return		void
	 */
	public function getPercentageClass()
	{
		return $this->getOption( 'classPercentage' );
	}

	/**
	 *	Returns CSS Class of Ratio DIV.
	 *	@access		public
	 *	@return		void
	 */
	public function getRatioClass()
	{
		return $this->getOption( 'classRatio' );
	}

	static public function render( $count, $found, $options = array() ){
		$indicator	= new UI_HTML_Indicator( $options );
		return $indicator->build( $count, $found );
	}

	/**
	 *	Builds HTML Code of Indicator Bar.
	 *	@access		protected
	 *	@param		float		$ratio		Ratio (between 0 and 1)
	 *	@param		int			$length		Length of Indicator
	 *	@return		string
	 */
	protected function renderBar( $ratio, $length = 100 )
	{
		$css			= array();
		$width			= floor( $ratio * $length );
		$css['width']	= $width.'px';
		if( $this->getOption( 'useColor' ) )
		{
			$colorR	= ( 1 - $ratio ) > 0.5 ? 255 : round( ( 1 - $ratio ) * 2 * 255 );
			$colorG	= $ratio > 0.5 ? 255 : round( $ratio * 2 * 255 );
			$colorB	= "0";
			$css['background-color']	= "rgb(".$colorR.",".$colorG.",".$colorB.")";
		}

		$attributes	= array(
			'class'	=> $this->getOption( 'classInner' ),
			'style'	=> $css,
		);
		$bar		= UI_HTML_Tag::create( 'div', "", $attributes );
		
		$attributes	= array( 'class' => $this->getOption( 'classOuter' ) );
		if( $length != 100 )
			$attributes['style']	= array( 'width' => $length.'px' );
		$div		= UI_HTML_Tag::create( "span", $bar, $attributes );
		return $div;
	}

	/**
	 *	Builds HTML Code of Percentage Block.
	 *	@access		protected
	 *	@param		float		$ratio		Ratio (between 0 and 1)
	 *	@return		string
	 */
	protected function renderPercentage( $ratio )
	{
		if( !$this->getOption( 'usePercentage' ) )
			return "";
		$value		= floor( $ratio * 100 )."&nbsp;%";
		$attributes	= array( 'class' => $this->getOption( 'classPercentage' ) );
		$div		= UI_HTML_Tag::create( "span", $value, $attributes );
		return $div;
	}

	/**
	 *	Builds HTML Code of Ratio Block.
	 *	@access		protected
	 *	@param		int			$found		Amount of positive Cases
	 *	@param		int			$count		Amount of all Cases
	 *	@return		string
	 */
	protected function renderRatio( $found, $count )
	{
		if( !$this->getOption( 'useRatio' ) )
			return "";
		$content	= $found."/".$count;
		$attributes	= array( 'class' => $this->getOption( 'classRatio' ) );
		$div		= UI_HTML_Tag::create( "span", $content, $attributes );
		return $div;
	}

	/**
	 *	Sets CSS Class of Indicator DIV.
	 *	@access		public
	 *	@param		string		$class		CSS Class Name
	 *	@return		void
	 */
	public function setIndicatorClass( $class )
	{
		$this->setOption( 'classIndicator', $class );
	}

	/**
	 *	Sets CSS Class of inner DIV.
	 *	@access		public
	 *	@param		string		$class		CSS Class Name
	 *	@return		void
	 */
	public function setInnerClass( $class )
	{
		$this->setOption( 'classInner', $class );
	}

	/**
	 *	Sets Option.
	 *	@access		public
	 *	@param		string		$key		Option Key (useColor|usePercentage|useRatio)
	 *	@param		bool		$values		Flag: switch Option
	 *	@return		bool
	 */
	public function setOption( $key, $value )
	{
		if( !array_key_exists( $key, $this->defaultOptions ) )
			throw new OutOfRangeException( 'Option "'.$key.'" is not a valid Indicator Option.' );
		return parent::setOption( $key, $value );
	}

	/**
	 *	Sets CSS Class of outer DIV.
	 *	@access		public
	 *	@param		string		$class		CSS Class Name
	 *	@return		void
	 */
	public function setOuterClass( $class )
	{
		$this->setOption( 'classOuter', $class );
	}

	/**
	 *	Sets CSS Class of Percentage DIV.
	 *	@access		public
	 *	@param		string		$class		CSS Class Name
	 *	@return		void
	 */
	public function setPercentageClass( $class )
	{
		$this->setOption( 'classPercentage', $class );
	}

	/**
	 *	Sets CSS Class of Ratio DIV.
	 *	@access		public
	 *	@param		string		$class		CSS Class Name
	 *	@return		void
	 */
	public function setRatioClass( $class )
	{
		$this->setOption( 'classRatio', $class );
	}
}
?>