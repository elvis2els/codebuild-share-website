<?php
//ѭ��ɾ��Ŀ¼���ļ�����  
 function delDirAndFile( $dirName ) {
 	if ( $handle = opendir( "$dirName" ) ) {
 		while ( false !== ( $item = readdir( $handle ) ) ) {
 			if ( $item != "." && $item != ".." ) {
 				if ( is_dir( "$dirName/$item" ) ) {
 					delDirAndFile( "$dirName/$item" );
 				}
 				else {
 					unlink( "$dirName/$item" );
 				}
 			}
 		}
    closedir( $handle );
    //if( rmdir( $dirName ) )echo "�ɹ�ɾ��Ŀ¼�� $dirName<br />\n";
    rmdir( $dirName );
 	}
 }
 //���Ŀ¼
 function clearDir($dirname){
 	if ( $handle = opendir( "$dirName" ) ) {
 		while ( false !== ( $item = readdir( $handle ) ) ) {
 			if ( $item != "." && $item != ".." ) {
 				if ( is_dir( "$dirName/$item" ) ) {
 					delDirAndFile( "$dirName/$item" );
 				}
 				else {
 					unlink( "$dirName/$item" );
 				}
 			}
 		}
    closedir( $handle );
 	}
 }
?>
