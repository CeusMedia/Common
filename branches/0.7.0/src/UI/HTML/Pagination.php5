<?php
/**
 *	Pagination System for limited Tables and Lists.
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
 *	@package		ui.html
 *	@extends		ADT_OptionObject
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.12.2005
 *	@version		$Id$
 */
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.ui.html.Elements' );
/**
 *	Pagination System for limited Tables and Lists.
 *	@category		cmClasses
 *	@package		ui.html
 *	@extends		ADT_OptionObject
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.12.2005
 *	@version		$Id$
 */
class UI_HTML_Pagination extends ADT_OptionObject
{	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $options = array() )
	{
		$defaultOptions	= array(
			'uri'			=> "./",
			'param'			=> array(),
			'coverage'		=> 3,
			'extreme'		=> 1,
			'more'			=> TRUE,
			'keyRequest'	=> "?",
			'keyParam'		=> "&",
			'keyAssign'		=> "=",
			'keyOffset'		=> "offset",
			'classList'		=> "pagination buttons",
			'classItem'		=> "",
			'classExtreme'	=> "extreme",
			'classSkip'		=> "skip",
			'classPage'		=> "",
			'classCurrent'	=> "current",
			'classMore'		=> "more",
			'textFirst'		=> "&laquo;",
			'textPrevious'	=> "&lsaquo;",
			'textNext'		=> "&rsaquo;",
			'textLast' 		=> "&raquo;",
			'textMore'		=> "&minus;"
		);
		$options	= array_merge( $defaultOptions, $options );
		
		foreach( $options as $key => $value )
			$this->setOption( $key, $value );
		
	
	}
	
	/**
	 *	Builds HTML for Paging of Lists.
	 *	@access		public
	 *	@param		int			$amount			Total amount of entries
	 *	@param		int			$limit			Maximal amount of displayed entries
	 *	@param		int			$offset			Currently offset entries
	 *	@return		string
	 */
	public function build( $amount, $limit, $offset = 0 )
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
					$pages[]	= $this->buildButton( 'textFirst', 'classExtreme', 0 );

				//  --  PREVIOUS PAGE --  //
				$previous	= ( $here - 1 ) * $limit;
				$pages[]	= $this->buildButton( 'textPrevious', 'classSkip', $previous );		//  previous page

				//  --  MORE PAGES  --  //
				if( $more && $before > $cover )																	//  more previous pages
					$pages[]	= $this->buildButton( 'textMore', 'classMore' );

				//  --  PREVIOUS PAGES --  //
				for( $i=max( 0, $before - $cover ); $i<$here; $i++ )											//  previous pages
					$pages[]	= $this->buildButton( $i + 1, 'classPage', $i * $limit );
/*				if( $this->getOption( 'keyPrevious' ) )
				{
					$latest	= count( $pages ) - 1;
					$button	= $this->buildButton( $i, 'classLink', ($i-1) * $limit, 'previous' );
					$pages[$latest]	= $button;
				}*/
			}
			
			$pages[]	= $this->buildButton( $here + 1, 'classCurrent' );											//  page here
			$after	= ceil( ( ( $amount - $limit ) / $limit ) - $here );										//  pages after
			if( $after )
			{
				//  --  NEXT PAGES --  //
				for( $i=0; $i<min( $cover, $after ); $i++ )														//  after pages
				{
					$offset		= ( $here + $i + 1 ) * $limit;
					$pages[]	= $this->buildButton( $here + $i + 2, 'classPage', $offset );
				}

				//  --  MORE PAGES --  //
				if( $more && $after > $cover )																	//  more after pages
					$pages[]	= $this->buildButton( 'textMore', 'classMore' );

				//  --  NEXT PAGE --  //
				$offset		= ( $here + 1 ) * $limit;
				$pages[]	= $this->buildButton( 'textNext', 'classSkip', $offset );				//  next page

				//  --  LAST PAGE --  //
				if( $extreme && $after > $extreme )																//  last page
				{
					$offset		= ( $here + $after ) * $limit;
					$pages[]	= $this->buildButton( 'textLast', 'classExtreme', $offset );
				}
			}
		}
		return UI_HTML_Elements::unorderedList( $pages, 0, array( 'class' => $this->getOption( 'classList' ) ) );
	}
	
	/**
	 *	Builds Paging Button.
	 *	@access		protected
	 *	@param		string		$text			Text or HTML of Paging Button Span
	 *	@param		string		$classItem		Additive Style Class of Paging Button Span
	 *	@param		int			$offset			Currently offset entries
	 *	@param		string		$linkClass		Style Class of Paging Button Link
	 *	@return		string
	 */
	protected function buildButton( $text, $class, $offset = NULL )
	{
		$label	= $this->hasOption( $text ) ? $this->getOption( $text ) : $text;
		if( empty( $label ) )
			throw new InvalidArgumentException( 'Button Label cannot be empty.' );
		$class	= ( $class && $this->hasOption( $class ) ) ? $this->getOption( $class ) : NULL;
		if( $offset !== NULL )
		{
			$url		= $this->buildLinkUrl( $offset );
#			if( $label == $text )
#				$linkClass	.= " page";
			$label		= UI_HTML_Elements::Link( $url, $label, $class );
		}
		else
			$label	= UI_HTML_Tag::create( "span", $label, array( 'class' => $class ) );
#		if( $label == $text )
#			$spanClass	.= " page";
		return $this->buildItem( $label, NULL );
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
		$param[$this->getOption( 'keyOffset' )] = $offset;
		$list	= array();
		foreach( $param as $key => $value )
			$list[]	= $key.$this->getOption( 'keyAssign' ).$value;
		$param	= implode( $this->getOption( 'keyParam' ), $list );
		$link	= $this->getOption( 'uri' ).$this->getOption( 'keyRequest' ).$param;
		return $link;
	}
	
	/**
	 *	Builds List Item of Pagination Link.
	 *	@access		protected
	 *	@param		string		$text			Text or HTML of Paging Button Span
	 *	@param		string		$class			Additive Style Class of Paging Button Span
	 *	@return		string
	 */
	protected function buildItem( $text, $class = NULL )
	{
		$list	= array();
		if( $class )
			$list[]	= $class;
		$item	= UI_HTML_Elements::ListItem( $text, 0, array( 'class' => $class ) );
		return $item;
	}
}
?>