<?php
//循环删除目录和文件函数  
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
    //if( rmdir( $dirName ) )echo "成功删除目录： $dirName<br />\n";
    rmdir( $dirName );
 	}
 }
 //清空目录
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
