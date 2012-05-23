<?php
/**
 *	The DokuWiki parser.
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
 *	@license		GPL 2 (http://www.gnu.org/licenses/gpl.html)
 *	@author			Andreas Gohr <andi@splitbrain.org>
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.03.2006
 *	@version		0.1
 */
/**
 *	The DokuWiki parser.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@extends		ADT_OptionObject
 *	@license		GPL 2 (http://www.gnu.org/licenses/gpl.html)
 *	@author			Andreas Gohr <andi@splitbrain.org>
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.03.2006
 *	@version		0.1
 *	@deprecated		will be removed in 0.7.1
 */
class UI_HTML_WikiParser extends ADT_OptionObject
{
	/**	@var	array		$_text_formats	Array of regular Expressions and Replacements for Text Formats */
	private	$_text_formats	= array(
		 '/^\s*----+\s*$/m'							=> "</p>\n<hr noshade=\"noshade\" size=\"1\" />\n<p>",	//hr
		 '/__(.+?)__/s'								=> '<u>\1</u>',										//underline
		 '/\/\/(.+?)\/\//s'							=> '<em>\1</em>',									//emphasize
		 '/\*\*(.+?)\*\*/s'							=> '<strong>\1</strong>',								//bold
		 '/\#\-(.+?)\-\#/s'							=> '<small>\1</small>',								//small
		 '/\#\+(.+?)\+\#/s'							=> '<big>\1</big>',									//big
		 '/\-\-(.+)\-\-/s'							=> '<strike>\1</strike>',								//strike
		 '/\'\'(.+?)\'\'/s'							=> '<code>\1</code>',								//code
		 '#&lt;del&gt;(.*?)&lt;/del&gt;#is'			=> '<del>\1</del>',									//deleted
		 '#&lt;ins&gt;(.*?)&lt;/ins&gt;#is'			=> '<ins>\1</ins>',									//inserted
		 '/!!(.+?)!!/s'								=> ' <span class="hilite">\1</span>',						//inserted
		 '/\#\=(.+?)\=\#/s'							=> '<div align="center">\1</div>',						//center
		 '/\#&lt;(.+?)&lt;\#/s'						=> '<div align="left">\1</div>',							//left-justify
		 '/\#&gt;(.+?)&gt;\#/s'						=> '<div align="right">\1</div>',							//right-justify
		 '#&lt;su([bp])&gt;(.*?)&lt;/su\1&gt;#is'	=> '<su\1>\2</su\1>',									//sub and superscript
		 "/\n((&gt;)[^\n]*?\n)+/se"					=> "'\n'.UI_HTML_WikiParser::_formatQuote('\\0').'\n'",				//do quoting 
	);
	/**	@var	array		$_typo			Array of regular Expressions and Replacements for Typo Formats */
	private	$_typo	= array(
		'/(?<!-)--(?!-)/s'								=> '&ndash;',									//endash
		'/(?<!-)---(?!-)/s'								=> '&mdash;',									//emdash
		'/&quot;([^\"]+?)&quot;/s'						=> '&laquo;\1&raquo;',							//curly quotes
		'/\.\.\./'										=> '\1&hellip;\2',								//ellipse
		'/(\d+)x(\d+)/i'								=> '\1&times;\2',								//640x480
		'/&gt;&gt;/i' 									=> '&rsaquo;',								// >>
		'/&lt;&lt;/i'									=> '&lsaquo;',									// <<
		'/&lt;-&gt;/i'									=> '&harr;',									// <->
		'/&lt;-/i'										=> '&larr;',									// <-
		'/-&gt;/i'										=> '&rarr;',									// ->
		'/&lt;=&gt;/i'									=> '&hArr;',									// <=>
		'/&lt;=/i'										=> '&lArr;',									// <=
		'/=&gt;/i'										=> '&rArr;',									// =>
		'#\\\\\\\\(?=\s)#'								=> "<br />",									//forced linebreaks
		"/(\n( {2,}|\t)[\*\-][^\n]+)(\n( {2,}|\t)[^\n]*)*/se"	=> "\"\\n\".UI_HTML_WikiParser::_formatList('\\0')",			// lists (blocks leftover after blockformat)
		"/\n(([\|\^][^\n]*?)+[\|\^] *\n)+/se"				=> "\"\\n\".UI_HTML_WikiParser::_formatTable('\\0')",			// tables
	);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path		Path to Pages
	 *	@param		string		$url			Base Link
	 *	@param		string		$carrier		URL Carrier
	 *	@param		string		$images		Path to Images
	 *	@return		void
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
 	public function __construct()
 	{
 		$this->setOption( 'url', "" );
 		$this->setOption( 'carrier', "?link=" );
 		$this->setOption( 'path', "" );
 		$this->setOption( 'extension', ".txt" );
 		$this->setOption( 'namespace_separator', ":" );
		$this->setOption( 'image_path', "images/" );
 	}

	/**
	 *	The main parser function.
	 *	Accepts raw data and returns valid xhtml
	 *	@access		public
	 *	@param		string		$text		Text to parse
	 *	@return		string
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	public function parse( $text )
	{
		$table	= array();
		$hltable	= array();
		$text	= str_replace("\r\n","\n",$text);													//preparse
		$text	= str_replace("\r","\n",$text);
		$text	= $this->_preparse($text,$table,$hltable);
		$text	= "\n".$text."\n";																//padding with a newline
		$urls	= '(https?|telnet|gopher|file|wais|ftp|ed2k|irc)';										// link matching
		$ltrs		= '\w';
		$gunk	= '/\#~:.?+=&%@!\-';
		$punc	= '.:?\-;,';
		$host	= $ltrs.$punc;
		$any	= $ltrs.$gunk.$punc;

		/* first pass */
		//preformated texts
		$this->_performFirstPass($table,$text,"#<nowiki>(.*?)</nowiki>#se","UI_HTML_WikiParser::_preformat('\\1','nowiki')");
		$this->_performFirstPass($table,$text,"#%%(.*?)%%#se","UI_HTML_WikiParser::_preformat('\\1','nowiki')");
		$this->_performFirstPass($table,$text,"#<code( (\w+))?>(.*?)</"."code>#se","UI_HTML_WikiParser::_preformat('\\3','code','\\2')");
		$this->_performFirstPass($table,$text,"#<file>(.*?)</file>#se","UI_HTML_WikiParser::_preformat('\\1','file')");
		// html includes
		$this->_performFirstPass($table,$text,"#<html>(.*?)</html>#se","UI_HTML_WikiParser::_preformat('\\1','html')");
		// codeblocks
		$this->_performFirstPass($table,$text,"/(\n( {2,}|\t)[^\*\-\n ][^\n]+)(\n( {2,}|\t)[^\n]*)*/se","UI_HTML_WikiParser::_preformat('\\0','block')","\n");
		//links
		$this->_performFirstPass($table,$text,"#\[\[([^\]]+?)\]\]#ie","UI_HTML_WikiParser::_formatLink('\\1')");
		//media
		$this->_performFirstPass($table,$text,"/\{\{([^\}]+)\}\}/se","UI_HTML_WikiParser::_formatMedia('\\1')");
		//match full URLs (adapted from Perl cookbook)
		$this->_performFirstPass($table,$text,"#(\b)($urls://[$any]+?)([$punc]*[^$any])#ie","UI_HTML_WikiParser::_formatLink('\\2')",'\1','\4');
		//short www URLs 
		$this->_performFirstPass($table,$text,"#(\b)(www\.[$host]+?\.[$host]+?[$any]+?)([$punc]*[^$any])#ie","UI_HTML_WikiParser::_formatLink('http://\\2|\\2')",'\1','\3');
		//short ftp URLs 
		$this->_performFirstPass($table,$text,"#(\b)(ftp\.[$host]+?\.[$host]+?[$any]+?)([$punc]*[^$any])#ie","UI_HTML_WikiParser::_formatLink('ftp://\\2')",'\1','\3');
		// email@domain.tld
		$this->_performFirstPass($table,$text,"#<([\w0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)>#ie", "UI_HTML_WikiParser::_formatLink('\\1@\\2')");
		//headlines
		$this->_formatHeadlines($table,$hltable,$text);
		$text = htmlspecialchars($text);
		$text = str_replace('&amp;amp;','&amp;',$text);
		$text = str_replace('&amp;gt;','&gt;',$text);
		$text = str_replace('&amp;lt;','&lt;',$text);
		$text = $this->_formatText($text);								/* second pass for simple formating */
		reset($table);													/* third pass - insert the matches from 1st pass */
		while (list($key, $val) = each($table))
			$text = str_replace($key,$val,$text);
		$text = preg_replace('"<p>\n*</p>"','',$text);						/* remove empty paragraphs */
		$text = str_replace("\n", '', $text);
		$text = trim($text);											/* remove padding */
		return $text;
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Stripped down version of the cleanID function from DokuWiki, no UTF-8 handling.
	 *	@access		private
	 *	@param		string		$id		ID of Wiki Page
	 *	@return		string
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _cleanID( $id )
	{
		$id = trim( $id );
/*		$id = strtolower($id);*/
		$id = strtr( $id,';',':' );
		$id = strtr( $id,'/','_' );
		//clean up
		$id = preg_replace( '#__#','_',$id );
		$id = preg_replace( '#:+#',':',$id );
		$id = trim( $id,':._-' );
		$id = preg_replace( '#:[:\._\-]+#',':',$id );
		return $id;
	}

	/**
	 *	Footnote formating.
	 *	@access		private
	 *	@param		string		$text	String to build Footnode from 
	 *	@return		string
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _buildFootNotes( $text )
	{
		$num = 0;
		while( preg_match( '/\(\((.+?)\)\)/s', $text, $match ) )
		{
			$num++;
			$fn		= $match[1];
			$linkt	= '<a href="#fn'.$num.'" name="fnt'.$num.'" class="fn_top">'.$num.')</a>';
			$linkb	= '<a href="#fnt'.$num.'" name="fn'.$num.'" class="fn_bot">'.$num.')</a>';
			$text	= preg_replace( '/ ?\(\((.+?)\)\)/s', $linkt, $text, 1 );
			if( $num == 1 )
				$text	.= '<div class="footnotes">';
			$text	.= '<div class="fn">'.$linkb.' '.$fn.'</div>';
		}
		if($num)
			$text	.= '</div>';
		return $text;
	}

	/**
	 *	Assembles all parts defined by the link formater below and returns HTML for the link.
	 *	@access		private
	 *	@param		array		$link		Link Information
	 *	@return		string
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _buildLink($link)
	{
		if(substr($link['url'],0,7) != 'mailto:')								//make sure the url is XHTML compliant (skip mailto)
		{
			$link['url'] = str_replace('&','&amp;',$link['url']);
			$link['url'] = str_replace('&amp;amp;','&amp;',$link['url']);
		}
		if( isset( $link['title'] ) )											//remove double encodings in titles
			$link['title'] = str_replace('&amp;amp;','&amp;',$link['title']);
		$ret  = '';
		$ret .= '<a href="'.$link['url'].'"';
		if( isset( $link['class'] ) && $link['class'] )
			$ret .= ' class="'.$link['class'].'"';
		if( isset( $link['target'] ) && $link['target'] )
			$ret .= ' target="'.$link['target'].'"';
		if( isset( $link['title'] ) && $link['title'] )
			$ret .= ' title="'.$link['title'].'"';
		$ret .= '>';
		$ret .= $link['name'];
		$ret .= '</a>';
		return $ret;
	}

	/**
	 *	format internal URLs
	 *	@access		private
	 *	@param		array		$link		Link Information
	 *	@return		array
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _formatInternalLink( $link )
	{
		$link['class']	= 'urlintern';
		$link['target']	= '';
		$link['title']	= htmlspecialchars( $link['url'] );
		if(!$link['name'])
			$link['name'] = htmlspecialchars($link['url']);
		return $link;
	}

	/**
	 *	format external URLs
	 *	@access		private
	 *	@param		array		$link		Link Information
	 *	@return		array
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _formatExternalLink( $link )
	{
		$link['class']	= 'urlextern';
		$link['target']	= '_blank';
		$link['title']	= htmlspecialchars( $link['url'] );
		if(!$link['name'])
			$link['name'] = htmlspecialchars($link['url']);
		return $link;
	}

	/**
	 *	Headline Formatter.
	 *	@access		private
	 *	@param		array		$table 		Reference to Replacement Table 
	 *	@param		array		$hltable		Reference to Headline Table 
	 *	@param		string		$text		Reference to Text
	 *	@return		void
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _formatHeadlines(&$table,&$hltable,&$text)
	{
		$last  = 0;										// walk the headline table prepared in preparsing
		$cnt   = 0;
		foreach($hltable as $hl)
		{
			$cnt++;										// build headline
			$headline	= "</p>\n";							//close paragraph
			if($cnt - 1)
				$headline .= '</div>';						//no close on first HL
			$headline	.= '<h'.$hl['level'].'>';
			$headline	.= $hl['name'];
			$headline	.= '</h'.$hl['level'].'>';
			$headline	.= '<div class="level'.$hl['level'].'">';
			$headline	.= "\n<p>";							//open new paragraph
			$table[$hl['token']]	= $headline;					//put headline into firstpasstable
		}
		if ($cnt)											//close last div
		{
			$token	= $this->_createToken();
			$text	.= $token;
			$table[$token]	= '</div>';
		}
	}

	/**
	 *	Formats various link types using the functions from format.php
	 *	@access		private
	 *	@param		string		$match		Link Information as String
	 *	@return		string
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _formatLink( $match )
	{
		$match	= str_replace( '\\"', '"', $match );									//unescape
		$link	= array();														//prepare variables for the formaters
		$link['url']		= $match;
		$link['name']	= $match;
		if( substr_count( $match, "|" ) )
			list( $link['url'], $link['name'] )	= split( '\|', $match, 2 );
		$link['url']		= str_replace( " ", "_", trim( $link['url'] ) );
		$link['name']	= trim( $link['name'] );
		$link['class']	= '';
		$link['target']	= '';
		$realname = $link['name'];																//save real name for image check
		if( preg_match( '#^([a-z0-9]+?)://#i', $link['url'] ) )											// put it into the right formater
			$link = $this->_formatExternalLink( $link );											// external URL
		elseif( preg_match( '#([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i', $link['url'] ) )
			$link = $this->_formatMailLink( $link );												// email
		elseif( preg_match( '#(.html|.php)#i', $link['url'] ) )
			$link = $this->_formatInternalLink( $link );												// internal URL
		else
			$link = $this->_formatWikiLink( $link );													// wiki link
		if( preg_match( '#^{{.*?\.(gif|png|jpe?g)(\?.*?)?\s*(\|.*?)?}}$#', $realname ) )					//is realname an image? use media formater
		{
			$link['name']	= substr( $realname, 2, -2 );
			$link	= $this->_formatMediaLink( $link );
		}
		return $this->_buildLink( $link );														// build the replacement with the variables set by the formaters
	}

	/**
	 *	format lists
	 *	@access		private
	 *	@return		string
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _formatList( $block )
	{
		$block = substr($block,1);									//remove 1st newline 
		$block = str_replace('\\"','"',$block);							//unescape
		$ret='';													//walk line by line
		$lst=0;
		$lvl=0;
		$enc=0;
		$lines = split("\n",$block);
		$cnt=0;													//build an item array 
		$items = array();
		foreach ($lines as $line)
		{
			$lvl  = 0;												//get indention level
			$lvl += floor(strspn($line,' ')/2);
			$lvl += strspn($line,"\t");
			$line = preg_replace('/^[ \t]+/','',$line);					//remove indents
			(substr($line,0,1) == '-') ? $type='ol' : $type='ul';			//get type of list
			$line = preg_replace('/^[*\-]\s*/','',$line);					// remove bullet and following spaces
			$items[$cnt]['level'] = $lvl;								//add item to the list
			$items[$cnt]['type']  = $type;
			$items[$cnt]['text']  = $line;								//increase counter
			$cnt++;
		}
		$level	= 0;
		$opens	= array();
		foreach( $items as $item )
		{
			if( $item['level'] > $level )
			{
				$ret .= "\n<".$item['type'].">\n";						//open new list
				array_push( $opens, $item['type'] );
			}
			else if( $item['level'] < $level )
			{
				$ret .= "</li>\n";									//close last item
				for( $i=0; $i<( $level - $item['level']); $i++ )
					$ret .= '</'.array_pop($opens).">\n</li>\n";		//close higher lists
			}
			elseif($item['type'] != $opens[count($opens)-1])
			{
				$ret .= '</'.array_pop($opens).">\n</li>\n";			//close last list and open new
				$ret .= "\n<".$item['type'].">\n";
				array_push($opens,$item['type']);
			}
			else
				$ret .= "</li>\n";									//close last item
			$level = $item['level'];									//remember current level and type
			$ret .= '<li class="level'.$item['level'].'">';					//print item
			$ret .= '<span class="li">'.$item['text'].'</span>';
		}
		while ($open = array_pop($opens))							//close remaining items and lists
		{
			$ret .= "</li>\n";
			$ret .= '</'.$open.">\n";
		}
		return "</p>\n".$ret."\n<p>";
	}

	/**
	 *	format email addresses
	 *	@access		private
	 *	@param		array		$link		Link Information
	 *	@return		array
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _formatMailLink( $link )
	{
		//simple setup
		$link['class']  = 'mail';
		$link['target'] = '';

		$link['name']   = htmlspecialchars($link['name']);

		//shields up
		$encode	= "";
		for( $x=0; $x < strlen( $link['url'] ); $x++ )
			$encode	.= '&#x'.bin2hex( $link['url'][$x] ).';';
		$link['url']		= $encode;
		$link['title']	= $link['url'];
		if(!$link['name'])
			$link['name']	= $link['url'];
		$link['url']		= 'mailto:'.$link['url'];
		return $link;
	}

	/**
	 * Format embedded media (images)
	 *
	 *	@author  Andreas Gohr <andi@splitbrain.org>
	 *	@access		private
	 *	@return		string
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _formatMedia( $text )
	{
		$text	= str_replace( '\\"', '"',$text );			//unescape
		$link		= array();							//handle normal media stuff
		$link['name']	= $text;
		$link		= $this->_formatMediaLink($link);
		return $this->_buildLink($link);
	}

	/**
	 *	format embedded media
	 *	@access		private
	 *	@param		array		$link		Link Information
	 *	@return		array
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _formatMediaLink($link)
	{
		$link['class']	= 'media';
		$class		= 'media';
		$title		= "";
		if( substr_count( $link['name'], "|" ) )
			list($link['name'],$title) = split( '\|',$link['name'], 2 );
		$t	= htmlspecialchars( $title );
		$a	= $w = $h = $t = "";
		if( substr( $link['name'], 0, 1 ) == ' ' && substr( $link['name'], -1, 1 ) == ' ' )					//set alignment from spaces
		{
			$link['pre'] = "</p>\n<div align=\"center\">";
			$link['suf'] = "</div>\n<p>";
		}
		elseif(substr($link['name'],0,1)==' ')
			$class = 'mediaright';
		elseif(substr($link['name'],-1,1)==' ')
			$class = 'medialeft';
		else
			$a = ' align="middle"';
		$link['name'] = trim($link['name']);
		$src		= $link['name'];									//split into src and parameters
		$param	= "";
		if( substr_count( $link['name'], "?" ) )
			list($src,$param) = split('\?',$link['name'],2);
		if(preg_match('#(\d*)(x(\d*))?#i',$param,$size))							//parse width and height
		{
			if( isset( $size[1] ) )
				$w	= $size[1];
			if( isset( $size[3] ) )
				$h	= $size[3];
		}
		if( !( isset( $link['url'] ) && $link['url'] ) )												//set link to src if none given 
		{
			$link['url'] = $src;
			$link['target'] = '_blank';
		}

		//prepare name
		if(preg_match('#\.(jpe?g|gif|png)$#i',$src,$match))										//check if it is an image
		{
			if( !substr_count( $src, "tp://" ) )
			{
				$src	= str_replace( ":", "/", $src );
				$src	= $this->getOption( 'path' ).$this->getOption( 'image_path' ).$src;
			}
			$link['name'] = '<img src="'.$src.'"';
			if($w) $link['name'] .= ' width="'.$w.'"';
			if($h) $link['name'] .= ' height="'.$h.'"';
			if($t) $link['name'] .= ' title="'.$t.'"';
			if($a) $link['name'] .= $a;
			$link['name'] .= ' class="'.$class.'" border="0" alt="'.$t.'" />';
		}
		else
		{
			$link['class']  = 'file';
			if($t)
				$link['name'] = $t;
			else
				$link['name'] = basename($src);
		}
		return $link;
	}

	/**
	 *	Do quote blocks
	 *	@access		private
	 *	@param		string		$block		Block tu quote
	 *	@return		string
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _formatQuote( $block )
	{
		$block	= trim( $block );
		$lines	= split( "\n", $block );
		$lvl		= 0;
		$ret		= "";
		foreach( $lines as $line )
		{
			$cnt = 0;													//remove '>' and count them
			while( substr( $line, 0, 4 ) == '&gt;' )
			{
				$line	= substr( $line, 4 );
				$cnt++;
			}
			if( $cnt > $lvl )											//compare to last level and open or close new divs if needed
			{
				$ret	.= "</p>\n";
				for( $i=0; $i< $cnt - $lvl; $i++ )
					$ret	.= '<div class="quote">';
				$ret	.= "\n<p>";
			}
			else if( $cnt < $lvl )
			{
				$ret	.= "\n</p>";
				for( $i=0; $i< $lvl - $cnt; $i++ )
					$ret	.= "</div>\n";
				$ret	.= "<p>\n";
			}
			else if( empty( $line ) )
				$ret	.= "</p>\n<p>";
			$ret	.= ltrim( $line )."\n";									//keep rest of line but trim left whitespaces
			$lvl = $cnt;												//remember level
		}
		$ret .= "</p>\n";												//close remaining divs
		for ($i=0; $i< $lvl; $i++)
			$ret .= "</div>\n";
		$ret .= "<p>\n";
		return "$ret";
	}

	/**
	 *	format inline tables
	 *	@access		private
	 *	@return		string
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 *	@author		Aaron Evans <aarone@klamathsystems.com>
	 */
	private function _formatTable($block)
	{
		$block = trim($block);
		$lines = split("\n",$block);
		$ret = "";
		$rows = array();													//build a row array 
		for($r=0; $r < count($lines); $r++)
		{
			$line = $lines[$r];
			$line = preg_replace('/[\|\^]\s*$/', '', $line);							//remove last seperator and trailing whitespace
			$c = -1;														//prepare colcounter
			for($chr=0; $chr < strlen($line); $chr++)
			{
				if($line[$chr] == '^')
				{
					$c++;
					$rows[$r][$c]['head'] = true;
					$rows[$r][$c]['data'] = '';
				}
				elseif($line[$chr] == '|')
				{
					$c++;
					$rows[$r][$c]['head'] = false;
					$rows[$r][$c]['data'] = '';
				}
				else
					$rows[$r][$c]['data'].= $line[$chr];
			}
		}
		$ret .= "</p>\n<table class=\"inline\">\n";								//build table
		for($r=0; $r < count($rows); $r++)
		{
			$ret .= "  <tr>\n";
			for ($c=0; $c < count($rows[$r]); $c++)
			{
				$cspan=1;
				$data = $rows[$r][$c]['data'];
				$head = $rows[$r][$c]['head'];
				while($c < count($rows[$r])-1 && $rows[$r][$c+1]['data'] == '')		//join cells if next is empty
				{
					$c++;
					$cspan++;
				}
				if($cspan > 1)
					$cspan = 'colspan="'.$cspan.'"';
				else
					$cspan = '';
				if (preg_match('/^\s\s/', $data))								//determine alignment from whitespace
				{
					$td_class = "rightalign";									// right indentation
					if (preg_match('/\s\s$/', $data))							// both left and right indentation
						$td_class = "centeralign";
				}
				else
					$td_class = "leftalign";									// left indentation (default)
				$data = trim($data);
				if ($head)
					$ret .= "    <th class=\"$td_class\" $cspan>$data </th>\n";	// set css class for alignment
				else
					$ret .= "    <td class=\"$td_class\" $cspan>$data </td>\n";	// set css class for alignment
			}
			$ret .= "  </tr>\n";
		}
		$ret .= "</table><br />\n<p>";
		return $ret;
	}

	/**
	 *	Simple text formating and typography is done here
	 *	@access		private
	 *	@param		string		$text 	Text to be formated
	 *	@return		string
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _formatText($text)
	{
		foreach( $this->_text_formats as $key =>$value )						// Text Formats
			$text	= preg_replace($key, $value, $text );
		foreach( $this->_typo as $key =>$value )								// Typography
			$text	= preg_replace($key, $value, $text );
		$text = $this->_buildFootNotes($text);								// footnotes
		return $text;
	}

	/**
	 *	format wiki links
	 *	@access		private
	 *	@param		array		$link		Array of Link Information
	 *	@return		array
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _formatWikiLink( $link )
	{
		$link['target']	= '';											//obvious setup
		$hash	= "";											//keep hashlink if exists
		if( substr_count( $link['url'], "#" ) )
		{
			list( $link['url'], $hash )	= split( '#', $link['url'], 2 );
			$hash	= $this->_cleanID( $hash );
		}
		if( empty( $link['name'] ) )									//use link without namespace as name
			$link['name']	= preg_replace( '/.*:/','', $link['url'] );
		$link['name']	= htmlspecialchars( $link['name'] );
		$link['url']		= $this->_cleanID( $link['url'] );
		$link['title']	= $link['url'];
		$file	= str_replace( $this->getOption( 'namespace_separator' ), '/', $link['url'] );							//set class depending on existance
		$file	= $this->getOption( 'path' ).$file.$this->getOption( 'extension' );
		if( @file_exists( $file ))
			$link['class']="wikilink1";
		else
			$link['class']="wikilink2";
		$link['url'] = $this->getOption( 'url' ).$this->getOption( 'carrier' ).$link['url'];				//construct the full link
		if($hash)													//add hash if exists
			$link['url'] .= '#'.$hash;
		return $link;
	}

	/**
	 *	create a random and hopefully unique token
	 *	@access		private
	 *	@return		string
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _createToken()
	{
		return '~'.md5(uniqid(rand(), true)).'~';
	}

	/**
	 *	Replace regexp with token
	 *	@access		private
	 *	@return		void
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _performFirstPass(&$table,&$text,$regexp,$replace,$lpad='',$rpad='')
	{
		//extended regexps have to be disabled for inserting the token
		//and later reenabled when handling the actual code:
		$ext='';
		if(substr($regexp,-1) == 'e')
		{
			$ext='e';
			$regexp = substr($regexp,0,-1);
		}
		while(preg_match($regexp,$text,$matches))
		{
			$token	= $this->_createToken();
			$match	= $matches[0];
			$text	= preg_replace($regexp,$lpad.$token.$rpad,$text,1);
			$table[$token] = preg_replace($regexp.$ext,$replace,$match);
		}
	}

	/**
	 *	Handle preformatted blocks
	 *	@access		private
	 *	@return		string
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _preformat( $text, $type, $option='' )
	{
		$text = str_replace( '\\"','"',$text );								 	//unescape
		switch( $type )
		{
			case 'html':
				break;
			case 'nowiki':
				$text = htmlspecialchars( $text );
				break;
			case 'file':
				$text = htmlspecialchars( $text );
				$text = "</p>\n<pre class=\"file\">".$text."</pre>\n<p>";
				break;
			case 'code':
				if( empty( $option ) )
				{
					$text = htmlspecialchars( $text );
					$text = nl2br( trim( $text ) );
					$text = '<pre class="code">'.$text.'</pre>';
				}
				else
					$text = preg_replace('/^\s*?\n/','',$text);					//strip leading blank line
				$text = "</p>\n".$text."\n<p>";
				break;
			case 'block':
				$text  = substr($text,1);									//remove 1st newline
				$lines = split("\n",$text);									//break into lines
				$text  = '';
				foreach($lines as $line)
					$text .= substr($line,2)."\r\n";								//remove indents
				$text = htmlspecialchars($text);
				$text = "</p>\n<pre class=\"pre\">".$text."</pre>\n<p>";
				break;
		}
		return $text;
	}

	/**
	 *	Line by line preparser
	 *
	 *	This preparses the text by walking it line by line. This
	 *	is the only place where linenumbers are still available (needed
	 *	for section edit. Some precautions have to be taken to not change
	 *	any noparse block.
	 *
	 *	@access		private
	 *	@return		string
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _preparse($text,&$table,&$hltable)
	{
		$lines	= split("\n",$text);
		$po		= $this->_createToken();													//prepare a tokens for paragraphs
		$table[$po]	= "<p>";
		$pc			= $this->_createToken();
		$table[$pc]	= "</p>";
		for( $l=0; $l<count( $lines ); $l++ )
		{
			$line = $lines[$l];																//temporay line holder
			if( isset( $noparse ) && $noparse )													//look for end of multiline noparse areas
			{
				if(preg_match("#^.*?$noparse#",$line))
				{
					$noparse = '';
					$line = preg_replace("#^.*?$noparse#",$line,1);
				}
				else
					continue;
			}
			if( !( isset( $noparse ) && $noparse ) )
			{
				if(preg_match('#^(  |\t)#',$line))												//skip indented lines
					continue;
				$line = preg_replace("#<nowiki>(.*?)</nowiki>#","",$line);						//remove norparse areas which open and close on the same line
				$line = preg_replace("#%%(.*?)%%#","",$line);
				$line = preg_replace("#<code( (\w+))?>(.*?)</"."code>#","",$line);
				$line = preg_replace("#<(file|html|php)>(.*?)</\\1>#","",$line);
				if(preg_match('#^.*?<(nowiki|code|php|html|file)( (\w+))?>#',$line,$matches))		//check for start of multiline noparse areas
				{
					list($noparse) = split(" ",$matches[1]);										//remove options
					$noparse = '</'.$noparse.'>';
					continue;
				}
				elseif(preg_match('#^.*?%%#',$line))
				{
					$noparse = '%%';
					continue;
				}
			}
			if(preg_match('/^(\s)*(==+)(.+?)(==+)(\s*)$/',$lines[$l],$matches))					//handle headlines
			{
				$tk = $this->_tokenizeHeadline($hltable,$matches[2],$matches[3],$l);				//get token
				$lines[$l] = $tk;															//replace line with token
			}
			if(empty($lines[$l]))															//handle paragraphs
				$lines[$l] = "$pc\n$po";
		}
		$text	= join("\n",$lines);															//reassemble full text
		$text	= "$po\n$text\n$pc";														//open first and close last paragraph
		return $text;
	}

	/**
	 *	Build TOC lookuptable
	 *	This function adds some information about the given headline
	 *	to a lookuptable to be processed later. Returns a unique token
	 *	that idetifies the headline later
	 *	@access		private
	 *	@param		array		$hltable		Reference to Headline Table
	 *	@param		string		$pre			Level of Heading as String
	 *	@param		string		$hline		Title of Heading
	 *	@param		int			$lno			Number of Line
	 *	@return		string
	 *	@author		Andreas Gohr <andi@splitbrain.org>
	 */
	private function _tokenizeHeadline( &$hltable, $pre, $hline, $lno )
	{
		switch( strlen( $pre ) )
		{
			case 2:
				$lvl = 5;
				break;
			case 3:
				$lvl = 4;
				break;
			case 4:
				$lvl = 3;
				break;
			case 5:
				$lvl = 2;
				break;
			default:
				$lvl = 1;
				break;
		}
		$token = $this->_createToken();
		$hltable[] = array(
			'name'  => htmlspecialchars(trim($hline)),
			'level' => $lvl,
			'line'  => $lno,
			'token' => $token );
		return $token;
	}
} 
?>