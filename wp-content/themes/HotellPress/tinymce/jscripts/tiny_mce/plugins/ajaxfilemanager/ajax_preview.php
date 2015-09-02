<?
	/**
	 * sysem base config setting
	 * @author Logan Cai (cailongqun@yahoo.com.cn)
	 * @link www.phpletter.com
	 * @since 22/April/2007
	 *
	 */
	//$_GET['path'] = "../../blank.htm";
	if(!empty($_GET['path']) && file_exists($_GET['path']) && is_file($_GET['path']))
	{
		include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "config.php");	
/*		$wwwroot = removeTrailingSlash(toUnixPath(getWWWRoot()));
		$urlprefix = "";
		$urlsuffix = "";
		$path = toUnixPath(realpath($_GET['path']));		
		$pos = strpos($path, $wwwroot);*/
		$fp = @fopen('test.php', 'w');
		@fwrite($fp, __FILE__ . " line: " . __LINE__ . "\n");
		@fwrite($fp , $_GET['path']);
		@fclose($fp);
		include_once(CLASS_MANAGER);
		$manager = new manager($_GET['path'], false);
		$fileTypes = getFileType(basename($_GET['path']));
		//echo $_GET['path'];
		//displayArray($fileTypes);
		if($fileTypes['preview'])
		{
			switch($fileTypes['fileType'])
			{
				case "image":
					$imageInfo = @getimagesize($_GET['path']);
					if(!empty($imageInfo[0]) && !empty($imageInfo[1]))
					{
						$thumInfo = getThumbWidthHeight($imageInfo[0], $imageInfo[1], 400, 135);
						printf("<img src=\"%s\" width=\"%s\" height=\"%s\" />", getFileUrl($_GET['path']), $thumInfo['width'], $thumInfo['height']);
													
					}else 
					{
						echo PREVIEW_IMAGE_LOAD_FAILED;
					}

					break;
				case "txt":
					if(($fp = @fopen($_GET['path'], 'r')))
					{
						echo fread($fp, @filesize($_GET['path']));
						//echo html_entity_decode(fread($fp, @filesize($_GET['path'])));
						@fclose($fp);
					}else 
					{
						echo PREVIEW_OPEN_FAILED . "1";
					}
					break;
				case "video":
					break;
			}
		}else 
		{
			echo PREVIEW_NOT_PREVIEW . "2";
		}		
/*		if (($absPath =getFileUrl($_GET['path'])))
		{
			$path = toUnixPath(realpath($_GET['path']));
			//$absPath  = $urlprefix . substr($path, strlen($wwwroot)) . $urlsuffix;
			include_once(CLASS_MANAGER);
			$manager = new manager($path, false);
			$fileTypes = getFileType(basename($_GET['path']));
			//echo $_GET['path'];
			//displayArray($fileTypes);
			if($fileTypes['preview'])
			{
				switch($fileTypes['fileType'])
				{
					case "image":
						$imageInfo = @getimagesize($_GET['path']);
						if(!empty($imageInfo[0]) && !empty($imageInfo[1]))
						{
							$thumInfo = getThumbWidthHeight($imageInfo[0], $imageInfo[1], 400, 135);
							printf("<img src=\"%s\" width=\"%s\" height=\"%s\" />", $absPath, $thumInfo['width'], $thumInfo['height']);
														
						}else 
						{
							echo PREVIEW_IMAGE_LOAD_FAILED;
						}

						break;
					case "txt":
						if(($fp = @fopen($_GET['path'], 'r')))
						{
							echo fread($fp, @filesize($_GET['path']));
							//echo html_entity_decode(fread($fp, @filesize($_GET['path'])));
							@fclose($fp);
						}else 
						{
							echo PREVIEW_OPEN_FAILED . "1";
						}
						break;
					case "video":
						break;
				}
			}else 
			{
				echo PREVIEW_NOT_PREVIEW . "2";
			}
		}*/
			
	}else 
	{
		echo PREVIEW_NOT_PREVIEW . "3";
	}
	


?>