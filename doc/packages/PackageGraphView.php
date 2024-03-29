<?php
class PackageGraphView
{
	public $baseCss			= '//css.ceusmedia.com/';
	public $baseJs			= '//js.ceusmedia.com/';
	public $regExpFilename	= '/^[A-Z].+\.php$/';
	public $rexExpSignature	= '/class [a-z]|interface [a-z] /i';
	public $fileSerial		= 'cache.files.serial';

	public function __construct( $path )
	{
		$this->path		= $path;
	}

	public function buildView( $refresh )
	{
		$clock	= new Alg_Time_Clock();
		$this->readData( $refresh );
		$this->prepareData();

		$view	= new UI_Image_PieGraph( 600, 400 );
		$page	= new UI_HTML_PageFrame();
		$page->addStyleSheet( $this->baseCss.'blueprint/reset.css' );
		$page->addStyleSheet( $this->baseCss.'blueprint/typography.css' );
		$page->addStyleSheet( $this->baseJs.'jquery/tabs/2.7.4/jquery.tabs.css' );
		$page->addJavaScript( $this->baseJs.'jquery/1.2.6.pack.js' );
		$page->addJavaScript( $this->baseJs.'jquery/history/1.0.3.pack.js' );
		$page->addJavaScript( $this->baseJs.'jquery/tabs/2.7.4.pack.js' );
		$page->addJavaScript( 'script.js' );
		$page->addStyleSheet( 'css/style.css' );
		$page->addStyleSheet( 'css/tabs.office2.css' );
		$page->addBody( '<h2><a href="https://github.com/CeusMedia/Common">CeusMedia/Common</a> Packages</h2>' );

		$samples	= $this->getPackages();

		$data	= [
			'values'	=> [],
			'uris'		=> [],
			'legends'	=> [],
			'labels'	=> [],
			'total'		=> 0
		];
		foreach( $samples as $sampleName => $sampleData )
		{
			$data['values'][]	= $sampleData['count'];
			$data['legends'][]	= $sampleData['label']." (".$sampleData['count'].")";
			$data['uris'][]		= "#".$sampleData['label'];
			$data['labels'][]	= $sampleData['label'];
			$data['total']		+= $sampleData['count'];
		}
		$view->setHeading( 'Packages by file count' );
		@unlink( 'cache.packageFileCount.png' );
		$page->addBody( $view->build( "test1", $data, 'cache.packageFileCount.png' ) );



		$data	= array(
			'values'	=> [],
			'uris'		=> [],
			'legends'	=> [],
			'labels'	=> [],
			'total'		=> 0
		);
		foreach( $samples as $sampleName => $sampleData )
		{
			$data['values'][]	= $sampleData['size'];
			$data['legends'][]	= $sampleData['label']." (".Alg_UnitFormater::formatBytes( $sampleData['size'], 0, "", 1 ).")";
			$data['uris'][]		= "#".$sampleData['label'];
			$data['labels'][]	= $sampleData['label'];
			$data['total']		+= $sampleData['count'];
		}
		$view->setHeading( 'Packages by file size' );
		@unlink( 'cache.packageFileSize.png' );
		$page->addBody( $view->build( "test2", $data, 'cache.packageFileSize.png' ) );
		$page->addBody( '<div style="clear: both"></div>' );

		$tabs	= new UI_HTML_Tabs;
		$tabs->setOption( 'fxAutoHeight', TRUE );
		$tabs->setOption( 'initial', 0 );
		$tabs->setOption( 'navClass', 'tabs-office2' );
		foreach( $this->getPackages() as $package => $data )
		{
			$list	= [];
			foreach( $data['files'] as $fileName ){
				$ext	= pathinfo( $fileName, PATHINFO_EXTENSION );
				$fileName	= str_replace( '.', '_', $fileName );
				$className	= substr( $fileName, strlen( $package ) + 1 );
				$className	= substr( $className, 0, - strlen( $ext ) - 1 );
				$fileName	=  $package.'/'.str_replace( '_', '/', $className ).'.'.$ext;
#				$list[]		= '<a href="../../src/'.$fileName.'">'.$className.'</a>';
				$list[]		= $className;
			}
			$data		= array(
				'label'	=> $package,
				'count'	=> $data['count'],
				'size'	=> Alg_UnitFormater::formatBytes( $data['size'] ),
				'list'	=> '<ul><li>'.join( '</li><li>', $list ).'</li></ul>',
			);
			$content	= UI_Template::render( 'templates/package.html', $data );
			$tabs->addTab( $package, $content, 'package_'.$package );
		}
		$html		= $tabs->buildTabs( "tabs-packages" );
		$script		= $tabs->buildScript( "#tabs-packages" );
		$page->addBody( $html );
		$page->addHead( UI_HTML_Tag::create( 'script', $script ) );
		$page->addBody( "<br/>".$clock->stop( 3, 0 )." ms" );
		return $page->build();
	}

	protected function readData( $refresh = FALSE )
	{
		if( file_exists( $this->fileSerial ) && !$refresh )
			return;
		$files	= [];
		$index	= new FS_File_RecursiveRegexFilter( $this->path, $this->regExpFilename, $this->rexExpSignature );
		foreach( $index as $entry )
		{
			$pathName	= substr( $entry->getPathname(), strlen( $this->path ) );
			$files[$pathName]	= filesize( $entry->getPathname() );
		}
		FS_File_Editor::save( $this->fileSerial, serialize( $files ) );
	}

	protected function prepareData()
	{
		$this->map	= new ADT_List_LevelMap();
		$files		= unserialize( FS_File_Editor::load( $this->fileSerial ) );
		foreach( $files as $pathName => $size )
		{
			if( !substr_count( $pathName, DIRECTORY_SEPARATOR ) )
				continue;
			$key	= str_replace( DIRECTORY_SEPARATOR, ".", $pathName );
			$this->map->set( $key, $size );
		}
	}

	protected function getPackages( $key = NULL )
	{
		$packages	= [];
		$list		= $key ? $this->map->get( $key ) : $this->map->getAll();
		foreach( $list as $pathName => $size )
		{
			if( !preg_match( '/^[a-z]+/i', $pathName ) )
				continue;
			$package	= array_shift( explode( ".", $pathName ) );
			if( !isset( $packages[$package] ) )
				$packages[$package]	= array(
					'label'		=> $package,
					'count'		=> 0,
					'size'		=> 0
				);
			else
			{
				$packages[$package]['count'] ++;
				$packages[$package]['size'] += $size;
				$packages[$package]['files'][]	= $pathName;
			}
		}
		return $packages;
	}
}
?>
