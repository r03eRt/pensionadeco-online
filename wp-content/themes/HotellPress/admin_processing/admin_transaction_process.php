<?php 
// message for the processing
$message = '';
// error for the processing
$errors = '';
if(!empty($_COOKIE['message_delete_trans']))
{
	$message = $_COOKIE['message_delete_trans'];	
	setcookie("message_delete_trans", $message, time()-3600);	
}

if(isset($_GET['paged']) && $_GET['paged']!="")
	$page_num= is_numeric($_GET['paged'])?$_GET['paged']:0;
else
	$page_num= 0;
	
$date_from = '';
$date_to ='';
//Get data by filter
if(isset($_POST['calculate']) && !empty($_POST['calculate']))
{
	if(isset($_POST['date_from']) && !empty($_POST['date_from']))
		$date_from = $_POST['date_from'];
	if(isset($_POST['date_to']) && !empty($_POST['date_to']))
		$date_to = $_POST['date_to'];
}
if(isset($_GET['customer']) && $_GET['customer'] != '')
{
	$list_all = get_transactions(1,$_GET['customer'], '', '', '', '', $date_from,$date_to);// Get all data by user display name
}
else if(isset($_GET['date']) && $_GET['date'] != '')
{
	$list_all = get_transactions(1, '', '', $_GET['date'], '', '', '', '');// Get all data by user display name
}
else if(isset($_GET['currency']) && $_GET['currency'] != '')
{
	$list_all = get_transactions (1, '', '', '', '', $_GET['currency'], $date_from,$date_to);// Get all data by user display name
}
else
	$list_all = get_transactions(1, '', '', '', '', '', $date_from, $date_to);// Get all data in the first time

if(( isset($_POST['doaction']) && !empty($_POST['doaction'])) || (isset($_POST['doaction1']) && !empty($_POST['doaction1']) ) )
{
	if( (isset($_POST['action']) && $_POST['action'] == 'delete') || (isset($_POST['action1']) && $_POST['action1'] == 'delete1') ) 
	{
		if(isset($_POST['tid']) && !empty($_POST['tid']))
		{
			$status = '';
			$status = doDelete($_POST['tid']);			
			if($status == true)
			{
				$message = __('Transaction(s) has been deleted successfully!','hotel');
				setcookie("message_delete_trans", $message, time()+3600);
				echo "<script language='javascript'>window.location = '"."admin.php?page=my-submenu-transaction-log"."'</script>";
			}		
		}
	}
}else if(isset($_GET['action']) && $_GET['action'] == 'delete')
{
	if(isset($_GET['tid']) && !empty($_GET['tid']))
	{
		$status = '';
		$status = doDelete($_GET['tid']);			
		if($status == true)
		{
			$message = __('Transaction has been deleted successfully!','hotel');			
			setcookie("message_delete_trans", $message, time()+3600);
			echo "<script language='javascript'>window.location = '"."admin.php?page=my-submenu-transaction-log"."'</script>";
		}				
	}
}
?>

<?php
/**
 * Pagination for transactions log list 
 * @author: James
 */
$items_per_page= 10;
if($page_num==0)
	$start= 0;
else
	$start= ($page_num-1)*$items_per_page;
	
$link= HOME_URL."/wp-admin/admin.php?page=my-submenu-transaction-log".
		(!empty($_GET['customer'])?"&customer=".$_GET['customer']:"").
		(!empty($_GET['date'])?"&date=".$_GET['date']:"").
		(!empty($_GET['currency'])?"&currency=".$_GET['currency']:"");

$numrows= count($list_all);

$pageNum= ($page_num==0)?1:$page_num;
$maxPage = ceil($numrows/$items_per_page); 
$nav = '';
$first = '';
$last = '';
if($pageNum>7)
{
if (1 == $pageNum) 
{ 
	$nav .= '<strong style="font-size:11px; color:#284A70; font-weight:bold;">' . "1" . '</strong>...';  
} 
else 
{ 	
	$nav .= ' <a style="font-size:11px;" href="'.$link.'&paged=1" >'."1".'</a> ...'; 
} 
}
for($page = (($pageNum>3)?($pageNum-3):1); $page <= (($pageNum<$maxPage-3)?($pageNum+3):$maxPage); $page++) 
{ 
	if ($page == $pageNum) 
	{ 
		$nav .= '<strong style="font-size:11px; color:#284A70; font-weight:bold;">' . display_number($page) . '</strong>';
	} 
	else 
	{ 	
		$nav .= ' <a style="font-size:11px;" href="'.$link.'&paged='.$page.'" >'.display_number($page).'</a> '; 
	}   
} 
if($maxPage>($pageNum+7))
{
if ($maxPage == $pageNum) 
{ 
	$nav .= '...<strong style="font-size:12px; color:#284A70; font-weight:bold;">' . display_number($maxPage) . '</strong>';
} 
else 
{ 	
	$nav .= '... <a style="font-size:11px;" href="'.$link.'&paged='.$maxPage.'" >'.display_number($maxPage).'</a> '; 
} 
}
if ($pageNum > 1) 
{ 
	$page = $pageNum - 1; 
	$prev = ' <a style="font-size:11px;" href="'.$link.'&paged='.$page.'" >&laquo; </a> '; 
}  
else 
{ 
	$prev  = '&nbsp;'; 
	$first = '&nbsp;'; 
} 
if ($pageNum < $maxPage) 
{ 
	$page = $pageNum + 1; 
	$next = ' <a style="font-size:11px;" href="'.$link.'&paged='.$page.'" > &raquo;</a> '; 
}  
else 
{ 
	$next = '&nbsp;'; // we're on the last page, don't print next link 
	$last = '&nbsp;'; // nor the last page link 
} 
$page_div_str= "";
if($items_per_page< $numrows)
	$page_div_str= "<i style=\"font-family:Georgia,'Times New Roman','Bitstream Charter',Times,serif\">Transactions ".display_number(($pageNum-1)*$items_per_page+1)."-".display_number($pageNum*$items_per_page<$numrows?$pageNum*$items_per_page:$numrows)." of ".display_number($numrows)."</i>".$first . $prev . $nav . $next . $last;
?>