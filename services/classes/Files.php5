<?php
import( 'de.ceus-media.net.service.Response' );
import( 'de.ceus-media.file.RecursiveIterator' );
class Services_Public_Files extends Net_Service_Response
{
	public function __construct()
	{
		parent::__construct();
		$this->path		= "./images/";
	}

	private function correctPath( $pathName )
	{
		$pathName	= preg_replace( "@^(.*)/*$@U", "\\1/", $pathName );
		$pathName	= preg_replace( "@^\./@", "", $pathName );
		return $pathName;	
	}
	
	public function copyFile( $format, $sourceFile, $targetFile)
	{
		$fileUri	= $this->path.$sourceFile;
		if( !file_exists( $fileUri ) )
			throw new RuntimeException( 'File "'.$sourceFile.'" is not existing.' );
		
		if( !copy( $this->path.$sourceFile, $this->path.$targetFile ) )
			throw new RuntimeException( 'File "'.$sourceFile.'" cannot be copied to "'.$targetFile.'".' );
		$size	= filesize( $this->path.$targetFile );
		$data	= array(
			'sourcePath'	=> $sourceFile,
			'targetPath'	=> $targetFile,
			'success'		=> TRUE,
			'bytes'			=> $size,
		);
		return $this->convertToOutputFormat( $data, $format );
	}

	public function createFolder( $format, $folderName, $targetPath )
	{
		$targetPath	= $this->correctPath( $targetPath );

		if( file_exists( $this->path.$targetPath.$folderName ) )
			throw new RuntimeException( 'Folder "'.$targetPath.$folderName.'" is already existing.' );
		if( !@mkDir( $this->path.$targetPath.$folderName ) )
			throw new RuntimeException( 'Folder "'.$targetPath.$folderName.'" cannot be created.' );
		return $this->convertToOutputFormat( TRUE, $format );
	}

	public function downloadFile( $format, $filePath )
	{
		$fileUri	= $this->path.$filePath;
		if( !file_exists( $fileUri ) )
			throw new RuntimeException( 'File "'.$filePath.'" is not existing.' );

		$content	= file_get_contents( $fileUri );
		if( $format == "xml" )
		{
			$root	= new XML_Element( '<response/>' );
			$root->addChild( 'content', base64_encode( $content ) );
			header('Content-Type: text/xml');
			return $root->asXml();
		}
		else if( $format == "json" )
			return $this->getJson( $content );
		else if( $format == "php" )
			return $this->getPhp( $content );
		else if( $format == "txt" )
			return $content;
	}
	
	public function getFileList( $format )
	{
		$list	= array();
		$index	= new File_RecursiveIterator( $this->path );
		foreach( $index as $entry )
		{
			if( $entry->isDir() )
				continue;
			if( $index->isDot() )
				continue;
			$relativeUri	= substr( $entry->getPathname(), strlen( $this->path ) );
			$relativeUri	= str_replace( "\\", "/", $relativeUri );
			$list[]	= $relativeUri;
		}
		$data	= array(
			'number'	=> count( $list ),
			'files'		=> $list,
		);
		return $this->convertToOutputFormat( $data, $format );
	}
	
	public function moveFile( $format, $sourceFile, $targetPath )
	{
		$targetPath	= $this->correctPath( $targetPath );

		$fileUri	= $this->path.$sourceFile;
		if( !file_exists( $fileUri ) )
			throw new RuntimeException( 'File "'.$sourceFile.'" is not existing.' );
		if( !file_exists( $this->path.$targetPath ) )
			throw new RuntimeException( 'Target path "'.$targetPath.'" is not existing.' );
		
		$fileName	= basename( $sourceFile );
		$sourceUri	= $this->path.$sourceFile;											//  Source URI is Base Path + File Path
		$targetUri	= $this->path.$targetPath.$fileName;								//  Target URI is Base Path + Target Path + File Name
		if( !rename( $sourceUri, $targetUri ) )
			throw new RuntimeException( 'File "'.$sourceFile.'" cannot be moved to "'.$targetPath.'".' );
		$size	= filesize( $targetUri );
		$data	= array(
			'sourceFile'	=> $sourceFile,
			'targetPath'	=> $targetPath,
			'success'		=> TRUE,
			'bytes'			=> $size,
		);
		return $this->convertToOutputFormat( $data, $format );
	}
	
	public function renameFile( $format, $sourceFile, $targetName )
	{
		$fileUri	= $this->path.$sourceFile;
		if( !file_exists( $fileUri ) )
			throw new RuntimeException( 'File "'.$sourceFile.'" is not existing.' );
		
		$filePath	= $this->correctPath( dirname( $sourceFile ) );						//  extract 
		$sourceUri	= $this->path.$sourceFile;											//  Source URI is Base Path + File Path
		$targetUri	= $this->path.$filePath.$targetName;								//  Target URI is Base Path + File Path + Target Name
		if( !@rename( $sourceUri, $targetUri ) )
			throw new RuntimeException( 'File "'.$sourceFile.'" cannot be renamed to "'.$targetName.'".' );
		$size	= filesize( $targetUri );												//  read File Size to prove handled Bytes
		$data	= array(
			'sourceFile'	=> $sourceFile,
			'targetName'	=> $targetName,
			'success'		=> TRUE,
			'bytes'			=> $size,
		);
		return $this->convertToOutputFormat( $data, $format );
	}

	public function renameFolder( $format, $sourcePath, $targetPath )
	{
	}
}
?>