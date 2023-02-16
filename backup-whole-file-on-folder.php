<?php

// FTP INFO
$ftpServer	= "ftpServer";
$ftpUsername = "ftpUsername";
$ftpPassword = "ftpPassword";
$ftpCon = ftp_connect($ftpServer) or die("Could not connect to $ftp_server");
$ftpLogin = ftp_login($ftpCon, $ftpUsername, $ftpPassword);
ftp_chdir($ftpCon, "folderFTP");


// CONDITION ONLY BACKUP LAST MONTH
$yearMonth = date("Ym");
$yearThen = date('Ym', strtotime("-1 month", strtotime($yearMonth)));
$userFolder = array_diff(scandir("folderSERVER"), array('..', '.'));


// SYNC USER FOLDER //
foreach ($userFolder as $i=>$userFolders) {
	$dateFolder = array_diff(scandir("folderSERVER/".$userFolders), array('..', '.'));

	// CHANGE DIR TRUE FALSE
	function ftp_isdir($ftpCon,$userFolders) {
	    if(@ftp_chdir($ftpCon,$userFolders)) {
	        ftp_cdup($ftpCon); 
	        return true; 
	    } else {
	        return false; 
	    } 
	}


	// TRANSFER ALL FILE IF SUCCESS CREATING FOLDER
	if (!ftp_isdir($ftpCon, $userFolders)) {
		ftp_mkdir($ftpCon, $userFolders);
		ftp_chdir($ftpCon, $userFolders);

		foreach ($dateFolder as $i=>$dateFolders) {
			$userFile = array_diff(scandir("folderSERVER/".$userFolders."/".$dateFolders), array('..', '.'));

			if ($dateFolders != "$yearMonth") {

				if (ftp_mkdir($ftpCon, $dateFolders)) {

					foreach ($userFile as $i=>$userFiles){

						if (ftp_put($ftpCon, $dateFolders."/".$userFiles, "folderSERVER/".$userFolders."/".$dateFolders."/".$userFiles, FTP_BINARY)){
							echo "Successfully uploaded $userFiles. ";
						}
						else{
							echo "Error uploading $userFiles. ";
						}

					}
				}

			}

		}

		ftp_cdup($ftpCon);
	}


	else{

		$userFile = array_diff(scandir("folderSERVER/".$userFolders."/".$yearThen), array('..', '.'));		
		ftp_chdir($ftpCon, $userFolders);
		ftp_mkdir($ftpCon, $yearThen);

		foreach ($userFile as $i=>$userFiles){
			if (ftp_put($ftpCon, $yearThen."/".$userFiles, "folderSERVER/".$userFolders."/".$yearThen."/".$userFiles, FTP_BINARY)){
				echo "Successfully uploaded $userFiles. ";
			}
			else{
				echo "Error uploading $userFiles. ";
			}
		}

		ftp_cdup($ftpCon);
	}

}
?>
