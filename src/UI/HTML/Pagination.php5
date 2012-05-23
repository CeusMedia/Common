<?php
/**
 *	Pagination System for limited Tables and Lists.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.12.2005
 *	@version		$Id$
 */
/**
 *	Pagination System for limited Tables and Lists.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@extends		ADT_OptionObject
 *	@uses			UI_HTML_Elements
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
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
		if( !is_array( $options ) )
			throw new InvalidArgumentException( 'Option map is not an array' );
		$defaultOptions	= array(
			'uri'			=> "./",
			'param'			=> array(),
			'coverage'		=> 10,
			'showMore'		=> TRUE,
			'showPrevNext'	=> TRUE,
			'showFirstLast'	=> TRUE,
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
			'classDisabled'	=> "disabled",
			'textFirst'		=> "&laquo;",
			'textPrevious'	=> "&lsaquo;",
			'textNext'		=> "&rsaquo;",
			'textLast' 		=> "&raquo;",
			'textMore'		=> "&minus;"
		);

#		//  --  LEFT JOIN  --  //
#		foreach( $defaultOptions as $defaultKey => $defaultValue )
#			if( array_key_exists( $defaultKey, $options ) )
#				$this->setOption( $option[$defaultKey] : $defaultValue );

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
			$showMore		= $this->getOption( 'showMore' );
			$showFirstLast	= $this->getOption( 'showFirstLast' );
			$showPrevNext	= $this->getOption( 'showFirstLast' );
			$offset		= ( (int)$offset >= 0 ) ? (int)$offset : 0;												//  reset invalid negative offsets
			$offset		= ( 0 !== $offset % $limit ) ? ceil( $offset / $limit ) * $limit : $offset;				//  synchronise invalid offsets
			$here		= ceil( $offset / $limit );																//  current page
			$before		= (int)$offset / (int)$limit;															//  pages before

				//  --  FIRST PAGE --  //
			if( $showFirstLast )																					//  show first link
			{
				if( $before )																						//  first link if not at first page 
					$pages[]	= $this->buildButton( 'textFirst', 'classExtreme', 0 );
				else
					$pages[]	= $this->buildButton( 'textFirst', 'classExtreme classDisabled' );					//  first link disabled if at first page
			}

				//  --  PREVIOUS PAGE --  //
			if( $showPrevNext )
			{
				$previous	= ( $here - 1 ) * $limit;
				if( $before )
					$pages[]	= $this->buildButton( 'textPrevious', 'classSkip', $previous );		//  previous page
				else
					$pages[]	= $this->buildButton( 'textPrevious', 'classSkip classDisabled' );					//  previous page
			}

			if( $before )
			{
				//  --  MORE PAGES  --  //
				if( $showMore && $before > $cover )																		//  more previous pages
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
				if( $showMore && $after > $cover )																		//  more after pages
					$pages[]	= $this->buildButton( 'textMore', 'classMore' );
			}

				//  --  NEXT PAGE --  //
			if( $showPrevNext )
			{
				$offset		= ( $here + 1 ) * $limit;
				if( $after )
					$pages[]	= $this->buildButton( 'textNext', 'classSkip', $offset );							//  next link if not at last page
				else
					$pages[]	= $this->buildButton( 'textNext', 'classSkip disabled' );							//  next link disabled it at last page
			}

				//  --  LAST PAGE --  //
			if( $showFirstLast )
			{
				$offset		= ( $here + $after ) * $limit;
				if( $after )																					//  last page
					$pages[]	= $this->buildButton( 'textLast', 'classExtreme', $offset );
				else
					$pages[]	= $this->buildButton( 'textLast', 'classExtreme disabled' );		//  last link disabled if at last page
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
			throw new InvalidArgumentException( 'Button Label cannot be empty' );
		$classes	= array();
		foreach( explode( " ", $class ) as $class )
			$classes[]	= ( $class && $this->hasOption( $class ) ) ? $this->getOption( $class ) : $class;
		$class	= implode( " ", $classes );
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