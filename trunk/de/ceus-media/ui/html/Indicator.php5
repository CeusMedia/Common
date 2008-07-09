<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.ui.html.Tag' );
/**
 *	Builds HTML of Bar Indicator.
 *	@package		ui.html
 *	@extends		ADT_OptionObject
 *	@uses			UI_HTML_Tag
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Builds HTML of Bar Indicator.
 *	@package		ui.html
 *	@extends		ADT_OptionObject
 *	@uses			UI_HTML_Tag
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
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
	 *	Sets Option.
	 *	@access		public
	 *	@param		string		$key		Option Key (useColor|usePercentage|useRatio)
	 *	@param		bool		$values		Flag: switch Option
	 *	@return		bool
	 */
	public function setOption( $key, $value )
	{
		parent::setOption( $key, $value );
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
		$div	= UI_HTML_Tag::create( "div", $bar, array( 'class' => $this->classOuter ) );
		return $div;
	}

	protected function renderPercentage( $ratio )
	{
		if( !$this->getOption( 'usePercentage' ) )
			return "";
		$value		= floor( $ratio * 100 )." %";
		$attributes	= array( 'class' => $this->classPercentage );
		$div		= UI_HTML_Tag::create( "div", $value, $attributes );
		return $div;
	}
	
	protected function renderRatio( $found, $count )
	{
		if( !$this->getOption( 'useRatio' ) )
			return "";
		$content	= $found."/".$count;
		$attributes	= array( 'class' => $this->classRatio );
		$div		= UI_HTML_Tag::create( "div", $content, $attributes );
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
}
?>