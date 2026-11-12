```php
use CeusMedia\Common\Net\FTP\Client as FtpClient;

$ftp	= new FtpClient( 'ftp.example.com', 21, NULL, 'user', 'secret' );
$ftp->createFolder( 'reports' );
$ftp->putFile( '/local/report.txt', 'reports/report.txt' );
foreach( $ftp->getFileList( 'reports' ) as $file )
	echo $file['name'].' '.$file['size'].PHP_EOL;
$ftp->getFile( 'reports/report.txt', '/local/copy.txt' );
```

See guide: [Network](../../../guide/network.md)
