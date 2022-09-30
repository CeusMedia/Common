<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

use CeusMedia\Common\FS;
use CeusMedia\Common\FS\Folder;
use CeusMedia\Common\CLI;

class Tool_Migration_Applier
{
	protected $modifiers	= [];
	protected $folder;

	public function apply(): object
	{
		if( !$this->modifiers )
			throw new RangeException( 'No modifiers set' );
		return $this->handleFolder( $this->folder );
	}

	public function setModifiers( $modifiers ): self
	{
		$this->modifiers	= $modifiers;
		return $this;
	}

	public function setRootFolder( Folder $folder ): self
	{
		$this->folder	= $folder;
		return $this;
	}

	//  --  PRIVATE  --  //

	private function handleFolder( $folder ): object
	{
//		remark( "FOLDER: ".$folder->getPathName() );
		$nrFiles		= 0;
		$nrFilesChanged	= 0;
		foreach( $folder->index( FS::TYPE_FILE ) as $fileName => $file ){
			if( preg_match( '/\.php.2$/', $file->getName() ) )
				unlink( $file->getPathName() );
			if( !preg_match( '/\.php$/', $file->getName() ) )
				continue;
			$nrFiles++;
			$content	= $file->getContent();
			$lines		= preg_split( '/\r?\n/', $content );
			foreach( $this->modifiers as $modifier ){
				$modifierCallback	= array( $modifier[0], $modifier[1] );
				$modifierArguments	= array_slice( $modifier, 2 );
				$callbackArguments	= array_merge( array( $lines ), $modifierArguments );
				$lines	= call_user_func_array( $modifierCallback, $callbackArguments );
			}

			if( $content !== join( PHP_EOL, $lines ) ){
//				FS_File_Writer::saveArray( $file->getPathName().'.2', $lines );
				$nrFilesChanged++;
				CLI::out( "- File: ".$folder->getPathName().$file->getName() );
/*				foreach( $this->diff( preg_split( '/\r?\n/', $content ), $lines ) as $line ){
					if( !empty( $line['d'] ) )
						foreach( $line['d'] as $deletedLine )
							CLI::out( CLI_Color::colorize( $deletedLine, 'white', 'red' ) );
					if( !empty( $line['i'] ) )
						foreach( $line['i'] as $insertedLine )
							CLI::out( CLI_Color::colorize( $insertedLine, 'white', 'green' ) );
				}*/
			}
			$file->setContent( join( PHP_EOL, $lines ) );
		}

		foreach( $folder->index( FS::TYPE_FOLDER ) as $folderName => $folder ){
			$stats	= $this->handleFolder( $folder );
			$nrFiles		+= $stats->nrFiles;
			$nrFilesChanged	+= $stats->nrFilesChanged;
		}
		return (object) array(
			'nrFiles'			=> $nrFiles,
			'nrFilesChanged'	=> $nrFilesChanged,
		);
	}

	private function diff($old, $new){
		$matrix = [];
		$maxlen = 0;
		foreach($old as $oindex => $ovalue){
		$nkeys = array_keys($new, $ovalue);
		foreach($nkeys as $nindex){
			$matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
				$matrix[$oindex - 1][$nindex - 1] + 1 : 1;
			if($matrix[$oindex][$nindex] > $maxlen){
				$maxlen = $matrix[$oindex][$nindex];
				$omax = $oindex + 1 - $maxlen;
				$nmax = $nindex + 1 - $maxlen;
			}
		}
		}
		if($maxlen == 0) return array(array('d'=>$old, 'i'=>$new));
		return array_merge(
		$this->diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
		array_slice($new, $nmax, $maxlen),
		$this->diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
	}
}
