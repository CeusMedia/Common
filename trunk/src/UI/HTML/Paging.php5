<?php
/**
 *	Paging System for Lists.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.12.2005
 *	@version		$Id$
 */
/**
 *	Paging System for Lists.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@extends		ADT_OptionObject
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.12.2005
 *	@version		$Id$
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
		$this->setOption( 'linebreak',		"\n" );
		$this->setOption( 'key_request',	"?" );
		$this->setOption( 'key_param',		"&" );
		$this->setOption( 'key_assign',		"=" );
		$this->setOption( 'key_offset',		"offset" );
		$this->setOption( 'class_span',		"pagingSpan" );
		$this->setOption( 'class_link',		"pagingLink" );
		$this->setOption( 'class_text',		"pagingText" );

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
			$cover		= $this->getOption( 'coverage' );
			$extreme	= $this->getOption( 'extreme' );
			$more		= $this->getOption( 'more' );
			$offset		= ( (int)$offset >= 0 ) ? (int)$offset : 0;												//  reset invalid negative offsets
			$offset		= ( 0 !== $offset % $limit ) ? ceil( $offset / $limit ) * $limit : $offset;				//  synchronise invalid offsets
			$here		= ceil( $offset / $limit );																//  current page
			$before		= (int)$offset / (int)$limit;															//  pages before
			if( $before )
			{
				//  --  FIRST PAGE --  //
				if( $extreme && $before > $extreme )															//  first page
					$pages[]	= $this->buildButton( 'text_first', 'class_link', 'class_link', 0 );

				//  --  PREVIOUS PAGE --  //
				$previous	= ( $here - 1 ) * $limit;
				$pages[]	= $this->buildButton( 'text_previous', 'class_link', 'class_link', $previous );		//  previous page

				//  --  MORE PAGES  --  //
				if( $more && $before > $cover )																	//  more previous pages
					$pages[]	= $this->buildButton( 'text_more', 'class_text' );

				//  --  PREVIOUS PAGES --  //
				for( $i=max( 0, $before - $cover ); $i<$here; $i++ )											//  previous pages
					$pages[]	= $this->buildButton( $i + 1, 'class_link', 'class_link', $i * $limit );
/*				if( $this->getOption( 'key_previous' ) )
				{
					$latest	= count( $pages ) - 1;
					$button	= $this->buildButton( $i, 'class_link', 'class_link', ($i-1) * $limit, 'previous' );
					$pages[$latest]	= $button;
				}*/
			}
			
			$pages[]	= $this->buildButton( $here + 1, 'class_text' );										//  page here
			$after	= ceil( ( ( $amount - $limit ) / $limit ) - $here );										//  pages after
			if( $after )
			{
				//  --  NEXT PAGES --  //
				for( $i=0; $i<min( $cover, $after ); $i++ )														//  after pages
				{
					$offset		= ( $here + $i + 1 ) * $limit;
					$pages[]	= $this->buildButton( $here + $i + 2, 'class_link', 'class_link', $offset );
				}

				//  --  MORE PAGES --  //
				if( $more && $after > $cover )																	//  more after pages
					$pages[]	= $this->buildButton( 'text_more', 'class_text' );

				//  --  NEXT PAGE --  //
				$offset		= ( $here + 1 ) * $limit;
				$pages[]	= $this->buildButton( 'text_next', 'class_link', 'class_link', $offset );			//  next page

				//  --  LAST PAGE --  //
				if( $extreme && $after > $extreme )																//  last page
				{
					$offset		= ( $here + $after ) * $limit;
					$pages[]	= $this->buildButton( 'text_last', 'class_link', 'class_link', $offset );
				}
			}
		}
		$pages	= implode( $this->getOption( "linebreak" ), $pages );
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
	protected function buildButton( $text, $spanClass, $linkClass = NULL, $offset = NULL, $key = NULL )
	{
		$label	= $this->hasOption( $text ) ? $this->getOption( $text ) : $text;
		if( empty( $label ) )
			throw new InvalidArgumentException( 'Button Label cannot be empty.' );
		$spanClass	= $this->getOption( $spanClass ) ? $this->getOption( $spanClass ) : "";
		if( $offset !== NULL )
		{
			$linkClass	= (string) $this->getOption( $linkClass );
			$url		= $this->buildLinkUrl( $offset );
			$key		= $key ? $this->getOption( 'key_'.$key ) : "";
#			if( $label == $text )
#				$linkClass	.= " page";
			$label		= UI_HTML_Elements::Link( $url, $label, $linkClass, NULL, NULL, NULL, $key );
		}
#		if( $label == $text )
#			$spanClass	.= " page";
		return $this->buildSpan( $label, $spanClass );
	}

	/**
	 *	Builds Link URL of Paging Button.
	 *	@access		protected
	 *	@param		int			$offset			Currently offset entries
	 *	@return		string
	 */
	protected function buildLinkUrl( $offset )
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
	protected function buildSpan( $text, $class = NULL )
	{
		$class 	= $class ? $this->getOption( 'class_span' )." ".$class : $this->getOption( 'class_span' );
		$span	= UI_HTML_Tag::create( "span", $text, array( 'class' => $class ) );
		return $span;
	}
}
?>