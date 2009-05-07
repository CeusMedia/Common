<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.ui.html.Tag' );
/**
 *	Builds HTML of Bar Indicator.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		ui.html
 *	@extends		ADT_OptionObject
 *	@uses			UI_HTML_Tag
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	Builds HTML of Bar Indicator.
 *	@package		ui.html
 *	@extends		ADT_OptionObject
 *	@uses			UI_HTML_Tag
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class UI_HTML_Indicator extends ADT_OptionObject
{
	/**	@var		string		$classIndicator			CSS Class of Indicator Block */
	public $classIndicator		= "indicator";
	/**	@var		string		$classIndicator			CSS Class of inner Block */
	public $classInner			= "indicator-inner";
	/**	@var		string		$classIndicator			CSS Class of outer Block */
	public $classOuter			= "indicator-outer";
	/**	@var		string		$classPercentage		CSS Class of Percentage Block */
	public $classPercentage		= "indicator-percentage";
	/**	@var		string		$classRatio				CSS Class of Ratio Block */
	public $classRatio			= "indicator-ratio";
	/**	@var		array		$optionKeys				List of Indicator Option Keys */
	public $optionKeys			= array(
		'useColor',
		'usePercentage',
		'useRatio'
	);

	/**
	 *	Constructor, sets Default Options, sets useColor and usePercentage to TRUE.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$default	= array(
			'useColor'		=> TRUE,
			'usePercentage'	=> TRUE,
			'useRatio'		=> FALSE,
		);
		parent::__construct( $default );
	}

	/**
	 *	Builds HTML of Indicator.
	 *	@access		public
	 *	@param		int			$found		Amount of positive Cases
	 *	@param		int			$count		Amount of all Cases
	 *	@param		int			$length		Length of inner Indicator Bar
	 *	@return		string
	 */
	public function build( $found, $count, $length = 100 )
	{
		$ratio	= $found / $count;
		$length	= floor( $ratio * $length );

		$divBar			= $this->renderBar( $ratio, $length );
		$divRatio		= $this->renderRatio( $found, $count );
		$divPercentage	= $this->renderPercentage( $ratio );
		$divIndicator	= new UI_HTML_Tag( "div" );
		$divIndicator->setContent( $divBar.$divPercentage.$divRatio );
		$divIndicator->setAttribute( 'class', $this->classIndicator );
		return $divIndicator->build();
	}

	/**
	 *	Returns CSS Class of Indicator DIV.
	 *	@access		public
	 *	@return		void
	 */
	public function getIndicatorClass()
	{
		return $this->classIndicator;
	}

	/**
	 *	Returns CSS Class of inner DIV.
	 *	@access		public
	 *	@return		void
	 */
	public function getInnerClass()
	{
		return $this->classInner;
	}

	/**
	 *	Returns CSS Class of outer DIV.
	 *	@access		public
	 *	@return		void
	 */
	public function getOuterClass()
	{
		return $this->classOuter;
	}

	/**
	 *	Returns CSS Class of Percentage DIV.
	 *	@access		public
	 *	@return		void
	 */
	public function getPercentageClass()
	{
		return $this->classPercentage;
	}

	/**
	 *	Returns CSS Class of Ratio DIV.
	 *	@access		public
	 *	@return		void
	 */
	public function getRatioClass()
	{
		return $this->classRatio;
	}

	/**
	 *	Builds HTML Code of Indicator Bar.
	 *	@access		protected
	 *	@param		float		$ratio		Ratio (between 0 and 1)
	 *	@param		int			$length		Length of inner Indicator Bar
	 *	@return		string
	 */
	protected function renderBar( $ratio, $length = 100 )
	{
		$css			= array();
		$css['width']	= $length.'px';
		if( $this->getOption( 'useColor' ) )
		{
			$colorR	= ( 1 - $ratio ) > 0.5 ? 255 : round( ( 1 - $ratio ) * 2 * 255 );
			$colorG	= $ratio > 0.5 ? 255 : round( $ratio * 2 * 255 );
			$colorB	= "0";
			$css['background-color']	= "rgb(".$colorR.",".$colorG.",".$colorB.")";
		}

		$style	= array();		
		foreach( $css as $key => $value )
			$style[]	= $key.": ".$value;
		$style	= implode( "; ", $style );

		$attributes	= array(
			'class'	=> $this->classInner,
			'style'	=> $style,
		);
		$bar	= UI_HTML_Tag::create( 'div', "", $attributes );
		$div	= UI_HTML_Tag::create( "span", $bar, array( 'class' => $this->classOuter ) );
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
		$attributes	= array( 'class' => $this->classPercentage );
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
		$attributes	= array( 'class' => $this->classRatio );
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
		$this->classIndicator	= $class;
	}

	/**
	 *	Sets CSS Class of inner DIV.
	 *	@access		public
	 *	@param		string		$class		CSS Class Name
	 *	@return		void
	 */
	public function setInnerClass( $class )
	{
		$this->classInner	= $class;
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
		if( !in_array( $key, $this->optionKeys ) )
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
		$this->classOuter	= $class;
	}

	/**
	 *	Sets CSS Class of Percentage DIV.
	 *	@access		public
	 *	@param		string		$class		CSS Class Name
	 *	@return		void
	 */
	public function setPercentageClass( $class )
	{
		$this->classPercentage	= $class;
	}

	/**
	 *	Sets CSS Class of Ratio DIV.
	 *	@access		public
	 *	@param		string		$class		CSS Class Name
	 *	@return		void
	 */
	public function setRatioClass( $class )
	{
		$this->classRatio	= $class;
	}
}
?>