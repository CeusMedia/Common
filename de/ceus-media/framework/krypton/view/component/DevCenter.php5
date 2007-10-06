<?php
import( 'de.ceus-media.framework.krypton.core.View' );
class Framework_Krypton_View_Component_DevCenter extends Framework_Krypton_Core_View
{
	private $tabs	= array();
	private $divs	= array();
	private $topics	= array(
		'show_request'		=> 'showRequest',
		'show_session'		=> 'showSession',
		'show_cookie'		=> 'showCookie',
		'show_classes'		=> 'showClasses',
		'show_config'		=> 'showConfig',
		'show_queries'		=> 'showQueries',
		'show_languages'	=> 'showLanguages',
		'show_words'		=> 'showWords',
		'show_sources'		=> 'showSources',
	);

	public function buildContent( $content )
	{
		$config		= $this->registry->get( 'config' );
		if( $config['debug']['show'] )
		{
			$showForAll	= $config['debug']['show'] == "*";
			$showForIp	= in_array( getEnv( 'REMOTE_ADDR' ), explode( ",", $config['debug']['show'] ) );
			if( $showForAll || $showForIp )
			{
				$this->buildTopics( $config, $content );
				return $this->buildTabs( $config );
			}
		}
	}

	private function buildTabs( $config )
	{
		if( count( $this->tabs ) )
		{
			foreach( $this->tabs as $id => $label )
			{
				$listTabs[]	= Elements::ListItem( Elements::Link( "#".$id, "<span>".$label."</span>" ) );
				$listDivs[]	= '<div id="'.$id.'">'.$this->divs[$id].'</div>';
			}
			$ui		= array(
				'path_js'	=> $config['paths']['javascripts'],
				'tabs'		=> Elements::unorderedList( $listTabs ),
				'divs'		=> implode( "\n", $listDivs ),
			);
			return $this->loadTemplate( 'dev', $ui );
		}
	}

	private function buildTopics( $config, $content )
	{
		if( $content )
			$this->showRemarks( $content );
		foreach( $this->topics as $option => $method )
			if( isset( $config['debug'][$option] ) && $config['debug'][$option] )
				if( method_exists( $this, $method ) )
					$this->$method();
	}
	
	public function setTopics( $topics )
	{
		$this->topics	= $topics;
	}
	
	private function showClasses()
	{
		if( isset( $GLOBALS['imported'] ) )
		{
			$imports	= $GLOBALS['imported'];
			if( count( $imports ) )
			{
				$list	= implode( "<br/>", array_keys( $imports ) ) ;
				natcasesort( $imports );
				$sorted	= implode( "<br/>", array_keys( $imports ) );

				$table	= "<table><tr><th>Classes</th><th>Classes sorted</th></tr><tr><td>".$list."</td><td>".$sorted."</td></tr></table>";
				$this->tabs['devTabClasses']	= "Classes <small>(".count($imports).")</small>";
				$this->divs['devTabClasses']	= $table;
			}
		}
	}

	private function showConfig()
	{
		$config	= $this->registry->get( 'config' );
		if( count( $config ) )
		{
			ob_start();
			print_m( $config );
			$this->tabs['devTabConfig']	= "Config</small>";
			$this->divs['devTabConfig']	= ob_get_clean();
		}
	}

	private function showCookie()
	{
		if( count( $_COOKIE ) )
		{
			ob_start();
			print_m( $_COOKIE );
			$this->tabs['devTabCookie']	= "Cookie <small>(".count( $_COOKIE ).")</small>";
			$this->divs['devTabCookie']	= ob_get_clean();
		}
	}

	private function showLanguages()
	{
		$language	= $this->registry->get( 'language' );
		$files	= $language->getLoadedFiles();
		if( count( $files ) )
		{
			$list	= array();
			natcasesort( $files );
			foreach( $files as $file => $key )
				$list[]	= "<tr><td>".$file."</td><td>".$key."</td></tr>";
			$table	= "<table><tr><th>Language File</th><th>Language Key</th></tr>".implode( "\n", $list )."</table>";
			$this->tabs['devTabLanguages']	= "Languages <small>(".count( $list ).")</small>";
			$this->divs['devTabLanguages']	= $table;
		}
	}

	private function showQueries()
	{
		$logFile	= "logs/database/queries_".getEnv( 'REMOTE_ADDR' ).".log";
		if( file_exists( $logFile ) )
		{
			$content	= file_get_contents( $logFile );
			$count		= substr_count( $content, str_repeat( "-", 80 ) );
			$this->tabs['devTabQueries']	= "Queries <small>(".$count.")</small>";
			$this->divs['devTabQueries']	= "<xmp>".$content."</xmp>";
		}		
	}

	private function showRemarks( $content )
	{
		$content	= trim( $content );
		$content	= preg_replace( "@^<br ?/>@", "", $content );
		$content	= preg_replace( "@<br ?/>$@", "", $content );
		$content	= preg_replace( "@<br />@", "<br/>", $content );
		$count	= substr_count( $content, "<br/>" ) + 1;
		$this->tabs['devTabRemarks']	= "Remarks <small>(".$count.")";
		$this->divs['devTabRemarks']	= $content;
	}

	private function showRequest()
	{
		$request	= $this->registry->get( 'request' );
		$all	= $request->getAll(); 
		if( count( $all ) )
		{
			ob_start();
			print_m( $all );
			$content	= ob_get_clean();
			$content	= str_replace( array( "<%", "%>" ), array( "[[%", "%]]" ), $content );
			$this->tabs['devTabRequest']	= "Request <small>(".count( $all ).")</small>";
			$this->divs['devTabRequest']	= $content;
		}
	}

	private function showSession()
	{
		$session	= $this->registry->get( 'session' );
		$all		= $session->getAll(); 
		if( count( $all ) )
		{
			ob_start();
			print_m( $all );
			$this->tabs['devTabSession']	= "Session <small>(".count( $all ).")</small>";
			$this->divs['devTabSession']	= ob_get_clean();
		}
	}

	private function showSources()
	{
		$this->tabs['devTabSources']	= "Sources ";
		$this->divs['devTabSources']	= $this->loadTemplate( 'dev_sources', array() );
	}

	private function showWords()
	{
		$language	= $this->registry->get( 'language' );
		$words	= $language->getWords();
		if( count( $words ) )
		{
			ob_start();
			print_m( $words );
			$list	= ob_get_clean();
			$this->tabs['devTabWords']	= "Words</small>";
			$this->divs['devTabWords']	= $list;
		}
	}
}
?>