<?php
/**
 *	Public Net Services of CeuS Media.
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.3
 */
import( 'de.ceus-media.net.service.Response' );
import( 'de.ceus-media.alg.InputFilter' );
/**
 *	Public Net Services of CeuS Media.
 *	@extends		Net_Service_Response
 *	@uses			Alg_InputFilter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.3
 */
final class Services_Public extends Net_Service_Response
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->urlForum			= "http://ceusmedia.com/forum/index.php?type=rss;action=.xml";
		$this->urlBlogArticles	= "http://ceusmedia.com/blog/habari/atom/1";
		$this->urlBlogComments	= "http://ceusmedia.com/blog/habari/atom/comments";
		$this->urlPipeAtomToRss	= "http://pipes.yahoo.com/pipes/pipe.run?_id=PicO8ejT3BG6sr1Uy6ky6g&_render=rss&atom_feed_url=";
	}
	
	/**
	 *	Adds two Integers and returns Result.
	 *	@access		public
	 *	@param		string		$format			Response Format
	 *	@param		int			$a				First Integer
	 *	@param		int			$b				Second Integer
	 *	@return		string
	 */
	public function addIntegers( $format, $a, $b )
	{
		$result	= (int) $a + (int) $b;	
		return $this->convertToOutputFormat( $result, $format );
	}
	
	public function getBlogArticlesFeed( $format )
	{
		$cacheFile	= "blog.articles.".$format.".cache";
		if( @filemtime( $cacheFile ) > time() - 300 )

			return file_get_contents( $cacheFile );

		$url	= $this->urlBlogArticles;
		if( $format == "rss" )
			$url	= $this->urlPipeAtomToRss.$url;
		$content	= file_get_contents( $url );
		file_put_contents( $cacheFile, $content );
		return $content;
	}

	public function getBlogCommentsFeed( $format )
	{
		$cacheFile	= "blog.comments.".$format.".cache";
		if( @filemtime( $cacheFile ) > time() - 300 )
			return file_get_contents( $cacheFile );

		$url	= $this->urlBlogComments;
		if( $format == "rss" )
			$url	= $this->urlPipeAtomToRss.$url;
		$content	= file_get_contents( $url );
		file_put_contents( $cacheFile, $content );
		return $content;
	}
	
	public function getForumFeed()
	{
		$cacheFile	= "forum.rss.cache";
		if( @filemtime( $cacheFile ) > time() - 300 )
			return file_get_contents( $cacheFile );

		$content	= file_get_contents( $this->urlForum );
		file_put_contents( $cacheFile, $content );
		return $content;
	}

	/**
	 *	Creates a Exception and throws it or returns it encoded.
	 *	@access		public
	 *	@param		string		$format			Response Format
	 *	@param		bool		$throw			Flag: throw Exception, otherwise return Exception
	 *	@return		string
	 */
	public function getTestException( $format, $throw = FALSE )
	{
		$method	= $throw ? "thrown" : "responded";
		$e	= new Exception( "Test Exception (".$method.")" );
		if( $throw )
			throw $e;
		return $this->convertToOutputFormat( $e, $format );
	}
	
	/** 
	 *	Returns current Timestamp on Server.
	 *	@access		public
	 *	@param		string		$format			Response Format
	 *	@param		string		$output			Output Format, see http://php.net/date
	 *	@return		string
	 */
	public function getTimestamp( $format, $output )
	{
		$time	= time();
		if( $output )
			$time	= date( $output, time() );
		return $this->convertToOutputFormat( $time, $format );
	}

	/** 
	 *	Returns the given String back to the client, filtered by Script Tags.
	 *	@access		public
	 *	@param		string		$format			Response Format
	 *	@param		string		$input			Input String to reflect
	 *	@return		string
	 */
	public function reflectInput( $format, $input )
	{
		$input	= Alg_InputFilter::stripScripts( $input );
#		$input	= Alg_InputFilter::stripTags( $input );
		return $this->convertToOutputFormat( $input, $format );
	}
}
?>