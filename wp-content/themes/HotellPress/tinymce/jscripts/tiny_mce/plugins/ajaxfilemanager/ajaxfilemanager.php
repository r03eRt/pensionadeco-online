<?
	/**
	 * sysem base config setting
	 * @author Logan Cai (cailongqun@yahoo.com.cn)
	 * @link www.phpletter.com
	 * @since 22/April/2007
	 *
	 */
	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "config.php");
	require_once(CLASS_MANAGER);
	define('URL_AJAX_FILE_MANAGER', $_SERVER['PHP_SELF']);

	require_once(DIR_AJAX_INC . "class.manager.php");
	$manager = new manager();
	$fileList = $manager->getFileList();
	$folderInfo = $manager->getFolderInfo();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/javascript" src="jscripts/popup.js"></script>
<script type="text/javascript" src="jscripts/jquery.js"></script>
<script type="text/javascript" src="jscripts/jeditable.js"></script>
<script type="text/javascript" src="jscripts/tablesorter.js"></script>
<script type="text/javascript" src="jscripts/form.js"></script>
<script type="text/javascript" src="jscripts/ajaxfileupload.js"></script>
<script type="text/javascript" src="jscripts/select.js"></script>
<script type="text/javascript" src="jscripts/general.js"></script>

<script type="text/javascript">
$(document).ready(
	function()
	{
		tableRuler('#tableList tbody tr');
		 $("#fileList tr[@id^=row] td.left").editable("ajax_save_image_name.php",
		 {
					 submit    : 'Save',
					 width	   : '150',
					 height    : '14',
					 loadtype  : 'POST',
					 event	   :  'dblclick',
					 indicator : "<img src='images/loading.gif'>",
					 tooltip   : '<?=TIP_DOC_RENAME; ?>'
		 }
		 );

/*		var allLinks = document.getElementsByTagName("link");
		allLinks[allLinks.length-1].parentNode.removeChild(allLinks[allLinks.length-1]);	*/	 
	} );
	var urlPreview = '<?=CONFIG_URL_PREVIEW; ?>';
	var msgNotPreview = '<?=PREVIEW_NOT_PREVIEW; ?>';
</script>
<link rel="stylesheet" type="text/css" href="css/general.css" />
<title>Ajax File Manager</title>
</head>
<body>
	<div id="container">
		<div id="leftCol">
			<div id="header">
				<ul id="action">
					<li><a href="#" id="actionRefresh" onclick="windowRefresh();"><span><?=LBL_ACTION_REFRESH; ?></span></a></li>
					<li><a href="#" id="actionDelete" onclick="deleteDocuments('<?=ERR_NOT_DOC_SELECTED; ?>', '<?=ERR_DELTED_FAILED; ?>', '<?=WARNING_DELETE; ?>');"><span><?=LBL_ACTION_DELETE; ?></span></a><form action="<?=CONFIG_URL_DELETE ?>" method="POST" name="formDelete" id="formDelete"><select name="selectedDoc[]" id="selectedDoc" style="display:none;" multiple="multiple"></select><input type="hidden" name="currentFolderPath"  value="<?=$folderInfo['path']; ?>" /></form></li>
<!--					<li ><a href="#" id="actionCut"><span>Cut</span></a><li>
					<li ><a href="#" id="actionPaste"><span>Paste</span></a><li>
					<li ><a href="#" id="actionCopy"><span>Copy</span></a><li>
					<li ><a href="#" id="actionZip"><span>Zip</span></a><li>
					<li ><a href="#" id="actionUnzip"><span>Unzip</span></a><li>-->
				</ul>
				<img src="images/loading.gif" id="loading" width="32" height="32" style="display:none;" />
			</div>
			<div id="body">
				<table class="tableList" id="tableList" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th width="5%"><a href="#" class="check_all" id="tickAll" title="<?=TIP_SELECT_ALL; ?>" onclick="checkAll('<?=TIP_SELECT_ALL; ?>', '<?=TIP_UNSELECT_ALL; ?>');">&nbsp;</a></th>
							<th width="6%" class="center">&nbsp;</th>
							<th width="48%" class="left"><?=LBL_NAME; ?></th>
							<th width="10%" class="center"><?=LBL_SIZE; ?></th>
							<th width="31%" class="center"><?=LBL_MODIFIED; ?></th>
						</tr>
					</thead>
					<tbody id="fileList">

						<tr class="even" id="topRow" onclick="setDocInfo('folder', '0');">
							<td><input type="checkbox" name="check[]" id="check0" disabled="disabled"  />
								<input type="hidden" name="folderPath0" value="<?=getUserFriendlyPath($folderInfo['path']); ?>" id="folderPath0" />
								<input type="hidden" name="folderFile0" value="<?=$folderInfo['file']; ?>" id="folderFile0" />
								<input type="hidden" name="folderSubdir0" id="folderSubdir0" value="<?=$folderInfo['subdir']; ?>" />
								<input type="hidden" name="folderCtime0" id="folderCtime0" value="<?=date(DATE_TIME_FORMAT,$folderInfo['ctime']); ?>" />
								<input type="hidden" name="folderMtime0" id="folderMtime0" value="<?=date(DATE_TIME_FORMAT,$folderInfo['mtime']); ?>" />
								<input type="hidden" name="fileReadable0" id="folderReadable0" value="<?=$folderInfo['is_readable']; ?>" />
								<input type="hidden" name="folderWritable0" id="folderWritable0" value="<?=$folderInfo['is_writable']; ?>" />

								<input type="hidden" name="itemType0" id="itemType0" value="folder" />
							</td>
							<td>
							<?
								if(strtolower($folderInfo['path']) ==  strtolower(CONFIG_SYS_ROOT_PATH))
								{
									?>
									<span class="folderParent">&nbsp;</span>
									<?
								}else
								{
									?>

									<a href="<?=appendQueryString(URL_AJAX_FILE_MANAGER, "path=" . getParentPath($folderInfo['path'])); ?>" title="<?=TIP_FOLDER_GO_UP; ?>"><span class="folderParent">&nbsp;</span></a>
									<?
								}
						?>

							</td>

							<td class="left" id="<?=$folderInfo['path']; ?>">
							<?
							if($folderInfo['path'] ==  CONFIG_SYS_ROOT_PATH)
							{
								echo "&nbsp;";
							}else
							{
							?>
									<a href="<?=appendQueryString(URL_AJAX_FILE_MANAGER, "path=" . getParentPath($folderInfo['path'])); ?>" title="<?=TIP_FOLDER_GO_UP; ?>">...</a>
							<?
							}
						?>
							</td>
							<td >&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<?
							$count = 1;
							$css = "";
							foreach($fileList as $file)
							{
								$css = ($css == "" || $css == "even"?"odd":"even");
								$strDisabled = ($file['is_writable']?"":" disabled");
								$strClass = ($file['is_writable']?"left":" leftDisabled");
								if($file['type'] == 'file')
								{

								?>
								<tr class="<?=$css; ?>" id="row<?=$count; ?>"  >
									<td onclick="setDocInfo('<?=$file['type']; ?>', '<?=$count; ?>');"><input type="checkbox" name="check[]" id="check<?=$count; ?>" value="<?=$file['name']; ?>" <?=$strDisabled; ?> />
										<input type="hidden" name="fileName<?=$count; ?>" value="<?=$file['name']; ?>" id="fileName<?=$count; ?>" />
										<input type="hidden" name="fileSize<?=$count; ?>" value="<?=getSizeStr($file['size']); ?>" id="fileSize<?=$count; ?>" />
										<input type="hidden" name="fileType<?=$count; ?>" value="<?=$file['fileType']; ?>" id="fileType<?=$count; ?>" />
										<input type="hidden" name="fileCtime<?=$count; ?>" id="fileCtime<?=$count; ?>" value="<?=date(DATE_TIME_FORMAT,$file['ctime']); ?>" />
										<input type="hidden" name="fileMtime<?=$count; ?>" id="fileMtime<?=$count; ?>" value="<?=date(DATE_TIME_FORMAT,$file['mtime']); ?>" />
										<input type="hidden" name="fileReadable<?=$count; ?>" id="fileReadable<?=$count; ?>" value="<?=$file['is_readable']; ?>" />
										<input type="hidden" name="fileWritable<?=$count; ?>" id="fileWritable<?=$count; ?>" value="<?=$file['is_writable']; ?>" />
										<input type="hidden" name="filePreview<?=$count; ?>" id="filePreview<?=$count; ?>" value="<?=$file['preview']; ?>" />
										<input type="hidden" name="filePath<?=$count; ?>" id="filePath<?=$count; ?>" value="<?=$file['path']; ?>" />
										<input type="hidden" name="fileUrl<?=$count; ?>" id="fileUrl<?=$count; ?>" value="<?=getFileUrl($file['path']); ?>" />
									</td>
									<td><a href="<?=$file['path']; ?>" target="_blank"><span class="<?=$file['cssClass']; ?>">&nbsp;</span></a></td>
									<td class="<?=$strClass; ?>"  id="<?=$file['path']; ?>"><?=$file['name']; ?></td>
									<td ><?=getSizeStr($file['size']); ?></td>
									<td><?=date(DATE_TIME_FORMAT,$file['mtime']); ?></td>
								</tr>
								<?
								}else
								{
									?>
									<tr class="<?=$css; ?>" id="row<?=$count; ?>" >
										<td onclick="setDocInfo('folder', '<?=$count; ?>');"><input type="checkbox" name="check[]" id="check<?=$count; ?>" value="<?=$file['name']; ?>" <?=$strDisabled; ?>/>
											<input type="hidden" name="folderName<?=$count; ?>" id="folderName<?=$count; ?>" value="<?=$file['name']; ?>" />
											<input type="hidden" name="folderPath<?=$count; ?>" value="<?=getUserFriendlyPath($file['path']); ?>" id="folderPath<?=$count; ?>" />
											<input type="hidden" name="folderFile<?=$count; ?>" value="<?=$file['file']; ?>" id="folderFile<?=$count; ?>" />
											<input type="hidden" name="folderSubdir<?=$count; ?>" id="folderSubdir<?=$count; ?>" value="<?=$file['subdir']; ?>" />
											<input type="hidden" name="folderCtime<?=$count; ?>" id="folderCtime<?=$count; ?>" value="<?=date(DATE_TIME_FORMAT,$file['ctime']); ?>" />
											<input type="hidden" name="folderMtime<?=$count; ?>" id="folderMtime<?=$count; ?>" value="<?=date(DATE_TIME_FORMAT,$file['mtime']); ?>" />
											<input type="hidden" name="fileReadable<?=$count; ?>" id="folderReadable<?=$count; ?>" value="<?=$file['is_readable']; ?>" />
											<input type="hidden" name="folderWritable<?=$count; ?>" id="folderWritable<?=$count; ?>" value="<?=$file['is_writable']; ?>" />
											<input type="hidden" name="itemType<?=$count; ?>" id="itemType<?=$count; ?>" value="folder" />
										</td>
										<td><a href="<?=appendQueryString(URL_AJAX_FILE_MANAGER, "path=" . $file['path']); ?>" title="<?=TIP_FOLDER_GO_DOWN; ?>"><span class="<?=($file['file']||$file['subdir']?$file['cssClass']:"folderEmpty"); ?>">&nbsp;</span></a></td>
										<td class="<?=$strClass; ?>" id="<?=$file['path']; ?>"><?=$file['name']; ?></td>
										<td >&nbsp;</td>
										<td><?=date(DATE_TIME_FORMAT,$file['mtime']); ?></td>
									</tr>
									<?
								}
								$count++;
							}
						?>

					</tbody>
				</table>
			</div>
			<div id="footer">
					<form name="hiddenForm" id="hiddenForm" action="" method="POST">
					   <input type="hidden" name="selectedFileRowNum" id="selectedFileRowNum" value="" />
					</form>
					<div id="divNewFolder">
    					<form id="formNewFolder" name="formNewFolder" action="<?=CONFIG_URL_CREATE_FOLDER; ?>" method="POST">
    						<p><input type="hidden" name="currentFolderPath" value="<?=$folderInfo['path']; ?>" />
    						<input class="input" type="text" name="new_folder" id="new_folder"  value="<?=LBL_BTN_NEW_FOLDER; ?>" size="44"/>
    						<button class="button" id="create" onclick="return createFolder( '<?=ERR_FOLDER_FORMAT; ?>');"><?=LBL_BTN_CREATE; ?></button></p>
    					</form>
					</div>
					<div id="divFormFile">
						<form name="formFile" action="<?=CONFIG_URL_UPLOAD; ?>" method="post" id="formFile" enctype="multipart/form-data">
    						<p><input type="hidden" name="currentFolderPath"  value="<?=$folderInfo['path']; ?>" />
    						<input class="inputFile" type="file" name="new_file" id="new_file" size="34"/>
    						<button class="button" id="upload" onclick="return uploadFile('<?=ERR_FILE_NAME_FORMAT; ?>', '<?=ERR_FILE_NOT_UPLOADED; ?>');"><?=LBL_BTN_UPLOAD; ?></button></p>
						</form>
					</div>
					<div class="clear"></div>
			</div>
		</div>
		<div id="rightCol">
			<fieldset id="fileFieldSet" style="display:none" >
				<legend><?=LBL_FILE_INFO; ?></legend>
				<table cellpadding="0" cellspacing="0" class="tableSummary" id="fileInfo">
					<tbody>
						<tr>
							<th><?=LBL_FILE_NAME; ?></th>
							<td colspan="3" id="fileName"></td>
						</tr>
						<tr>
							<th><?=LBL_FILE_CREATED; ?></th>
							<td colspan="3" id="fileCtime"></td>

						</tr>
						<tr>
							<th><?=LBL_FILE_MODIFIED; ?></th>
							<td colspan="3" id="fileMtime"></td>
						</tr>
						<tr>
							<th><?=LBL_FILE_SIZE; ?></th>
							<td id="fileSize"></td>
							<th><?=LBL_FILE_TYPE; ?></th>
							<td id="fileType"></td>
						</tr>
						<tr>
							<th><?=LBL_FILE_WRITABLE; ?></th>
							<td id="fileWritable"><span class="flagYes">&nbsp;</span></td>
							<th><?=LBL_FILE_READABLE; ?></th>
							<td id="fileReadable"><span class="flagNo">&nbsp;</span></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
			<fieldset id="folderFieldSet" >
				<legend><?=LBL_FOLDER_INFO; ?></legend>
				<table cellpadding="0" cellspacing="0" class="tableSummary" id="folderInfo">
					<tbody>
						<tr>
							<th><?=LBL_FOLDER_PATH; ?></th>
							<td colspan="3" id="folderPath"><?=getUserFriendlyPath($folderInfo['path']); ?></td>
						</tr>
						<tr>
							<th><?=LBL_FOLDER_CREATED; ?></th>
							<td colspan="3" id="folderCtime"><?=date(DATE_TIME_FORMAT,$folderInfo['ctime']); ?></td>

						</tr>
						<tr>
							<th><?=LBL_FOLDER_MODIFIED; ?></th>
							<td colspan="3" id="folderMtime"><?=date(DATE_TIME_FORMAT,$folderInfo['mtime']); ?></td>
						</tr>
						<tr>
							<th><?=LBL_FOLDER_SUDDIR; ?></th>
							<td id="folderSubdir"><?=$folderInfo['subdir']; ?></td>
							<th><?=LBL_FOLDER_FIELS; ?></th>
							<td id="folderFile"><?=$folderInfo['file']; ?></td>
						</tr>
						<tr>
							<th><?=LBL_FOLDER_WRITABLE; ?></th>
							<td id="folderWritable"><span class="<?=($folderInfo['is_readable']?'flagYes':'flagNo'); ?>">&nbsp;</span></td>
							<th><?=LBL_FOLDER_READABLE; ?></th>
							<td id="folderReadable"><span class="<?=($folderInfo['is_writable']?'flagYes':'flagNo'); ?>">&nbsp;</span></td>
						</tr>


					</tbody>
				</table>
			</fieldset>
			<fieldset>
				<legend><?=LBL_PREVIEW; ?></legend>
				<div id="preview">
				<?=PREVIEW_NOT_PREVIEW; ?>
				</div>

			</fieldset>
			<div id="previewFooter">
				<p><button class="button" id="select" onclick="selectFile('<?=ERR_NOT_FILE_SELECTED; ?>');"><?=LBL_BTN_SELECT; ?></button> <button class="button" id="cancel" onclick="cancelSelectFile();"><?=LBL_BTN_CANCEL; ?></button></p>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</body>
</html>
