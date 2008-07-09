<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.ui.html.Elements' );
/**
 *	Paging System for Lists.
 *	@package		ui.html
 *	@extends		ADT_OptionObject
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.6
 */
/**
 *	Paging System for Lists.
 *	@package		ui.html
 *	@extends		ADT_OptionObject
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.6
 */
class UI_HTML_Paging extends ADT_OptionObject
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->setOption( 'uri',			"./" );
		$this->setOption( 'param',			array() );
		$this->setOption( 'coverage',		"2" );
		$this->setOption( 'extreme',		"1" );
		$this->setOption( 'more',			"1" );
		$this->setOption( 'indent',			"\n\t" );
		$this->setOption( 'key_request',	"?" );
		$this->setOption( 'key_param',		"&" );
		$this->setOption( 'key_assign',		"=" );
		$this->setOption( 'key_offset',		"offset" );
		$this->setOption( 'class_span',		"paging" );
		$this->setOption( 'class_link',		"paging" );
		$this->setOption( 'class_text',		"text" );

		$this->setOption( 'text_first',		"<<" );
		$this->setOption( 'text_previous',	"<" );
		$this->setOption( 'text_next',		">" );
		$this->setOption( 'text_last', 		">>" );
		$this->setOption( 'text_more',		".." );

		$this->setOption( 'key_first', 		'' );
		$this->setOption( 'key_previous',	'' );
		$this->setOption( 'key_next',		'' );
		$this->setOption( 'key_last',		'' );
	}
	
	/**
	 *	Builds HTML for Paging of Lists.
	 *	@access		public
	 *	@param		int			$amount			Total amount of entries
	 *	@param		int			$limit			Maximal amount of displayed entries
	 *	@param		int			$offset			Currently offset entries
	 *	@return		string
	 */
	public function build( $amount, $limit, $offset )
	{
		$pages	= array();
		if( $limit && $amount > $limit )
		{
			$cover	= $this->getOption( 'coverage' );
			$extreme	= $this->getOption( 'extreme' );
			$more	= $this->getOption( 'more' );
			$offset	= ( (int)$offset >= 0 ) ? (int)$offset : 0;												//  reset invalid negative offsets
			$offset	= ( 0 !== $offset % $limit ) ? ceil( $offset / $limit ) * $limit : $offset;							//  synchronise invalid offsets
			$here	= ceil( $offset / $limit );																//  current page
			$before	= (int)$offset / (int)$limit;															//  pages before
			if( $before )
			{
				if( $extreme && $before > $extreme )														//  first page
					$pages[]	= $this->buildButton( 'text_first', 'class_link', 0, 'class_link' );
				$pages[]	= $this->buildButton( 'text_previous', 'class_link', ( $here - 1 ) * $limit, 'class_link' );			//  previous page
				if( $more && $before > $cover )															//  more before pages
					$pages[]	= $this->buildButton( 'text_more', 'class_text' );
				for( $i=max( 0, $before - $cover ); $i<$here; $i++ )											//  before pages
					$pages[]	= $this->buildButton( $i + 1, 'class_link', $i * $limit, 'class_link' );
/*				if( $this->getOption( 'key_previous' ) )
				{
					$latest	= count( $pages ) - 1;
					$button	= $this->buildButton( $i, 'class_link', ($i-1) * $limit, 'class_link', 'previous' );
					$pages[$latest]	= $button;
				}*/
			}
			
			$pages[]	= $this->buildButton( $here + 1, 'class_text' );												//  page here
			$after	= ceil( ( ( $amount - $limit ) / $limit ) - $here );											//  pages after
			if( $after )
			{
				for( $i=0; $i<min( $cover, $after ); $i++ )													//  after pages
					$pages[]	= $this->buildButton( $here + $i + 2, 'class_link', ( $here + $i + 1 ) * $limit, 'class_link' );
				if( $more && $after > $cover )															//  more after pages
					$pages[]	= $this->buildButton( 'text_more', 'class_text' );
				$pages[]	= $this->buildButton( 'text_next', 'class_link', ( $here + 1 ) * $limit, 'class_link' );			//  next page
				if( $extreme && $after > $extreme )														//  last page
					$pages[]	= $this->buildButton( 'text_last', 'class_link', ( $here + $after ) * $limit, 'class_link' );
			}
		}
		$pages	= implode( $this->getOption( "indent" ), $pages );
		return $pages;
	}
	
	/**
	 *	Builds Paging Button.
	 *	@access		protected
	 *	@param		string		$text			Text or HTML of Paging Button Span
	 *	@param		string		$spanClass		Additive Style Class of Paging Button Span
	 *	@param		int			$offset			Currently offset entries
	 *	@param		string		$linkClass		Style Class of Paging Button Link
	 *	@return		string
	 */
	protected function buildButton( $text, $spanClass, $offset = false, $linkClass = false, $key = false )
	{
		$text	= $this->hasOption( $text ) ? $this->getOption( $text ) : $text;
		if( "" !== $text )
		{
			$spanClass	= $this->getOption( $spanClass ) ? $this->getOption( $spanClass ) : "";
			if( false !== $offset )
			{
				$linkClass	= $this->getOption( $linkClass ) ? $this->getOption( $linkClass ) : "";
				$url		= $this->buildLink( $offset );
				$key		= $key ? $this->getOption( 'key_'.$key ) : "";
				$text		= UI_HTML_Elements::Link( $url, $text, $linkClass, false, false, false, $key );
			}
			return $this->buildSpan( $text, $spanClass );
		}
	}

	/**
	 *	Builds HTML Link of Paging Button.
	 *	@access		protected
	 *	@param		int			$offset			Currently offset entries
	 *	@return		string
	 */
	protected function buildLink( $offset )
	{
		$param	= $this->getOption( 'param' );
		$param[$this->getOption( 'key_offset' )] = $offset;
		$list	= array();
		foreach( $param as $key => $value )
			$list[]	= $key.$this->getOption( 'key_assign' ).$value;
		$param	= implode( $this->getOption( 'key_param' ), $list );
		$link	= $this->getOption( 'uri' ).$this->getOption( 'key_request' ).$param;
		return $link;
	}
	
	/**
	 *	Builds Span Link of Paging Button.
	 *	@access		protected
	 *	@param		string		$text			Text or HTML of Paging Button Span
	 *	@param		string		$class			Additive Style Class of Paging Button Span
	 *	@return		string
	 */
	protected function buildSpan( $text, $class = false )
	{
		$class 	= $class ? $this->getOption( 'class_span' )." ".$class : $this->getOption( 'class_span' );
		$span	= "<span class='".$class."'>".$text."</span>";
		return $span;
	}
}
?>