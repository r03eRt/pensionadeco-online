<?
	/**
	 * sysem base config setting
	 * @author Logan Cai (cailongqun@yahoo.com.cn)
	 * @link www.phpletter.com
	 * @since 22/April/2007
	 *
	 */
	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "config.php");
	if(empty($_POST['value']))
	{
		echo "error:" . ERR_RENAME_EMPTY;
	}elseif(!preg_match("/^[a-zA-Z0-9_\-.]+$/", $_POST['value']))
	{
		echo "error:" . ERR_RENAME_FORMAT;
	}
	elseif(empty($_POST['id']) || !file_exists(removeTrailingSlash($_POST['id'])))
	{
		echo "error:" . ERR_RENAME_FILE_NOT_EXISTS ;
	}elseif(substr(toUnixPath(removeTrailingSlash($_POST['id'])), strrpos(toUnixPath(removeTrailingSlash($_POST['id'])), "/") + 1) == $_POST['value']) 
	{
		echo "error:" . ERR_NO_CHANGES_MADE;
	}
	elseif(file_exists(addTrailingSlash(getParentPath($_POST['id'])) . $_POST['value']))
	{
		echo "error:" . ERR_RENAME_EXISTS;
	}elseif(is_file($_POST['id']) && !isValidExt($_POST['value'], explode(",", CONFIG_UPLOAD_VALID_EXTS), explode(",", CONFIG_UPLOAD_INVALID_EXTS)))
	{
		echo "error:" . ERR_RENAME_FILE_TYPE_NOT_PERMITED;
	}
	elseif(!rename(removeTrailingSlash($_POST['id']), addTrailingSlash(getParentPath($_POST['id'])) . $_POST['value']) )
	{
		echo "error:" . ERR_RENAME_FAILED;
	}else 
	{
		echo  "path:" . toUnixPath(addTrailingSlash(getParentPath($_POST['id']))  . $_POST['value']) . "name:" . $_POST['value'];

	}
	
?>