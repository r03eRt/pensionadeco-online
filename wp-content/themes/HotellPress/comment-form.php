

<div class="contact-form">
    	
    <?php
    /***
     * Javascript validate
     */ 
    ?>
    <script type="text/javascript">
   	jQuery(document).ready(function($){
   	   	$('#commentform').submit(function(){
			<?php if (get_option('require_name_email')) {?>
			if ($('#comment-name').val() == ''){
				$('#name-error').html('<?php _e('Name is required, please check again', 'hotel')?>');
				return false;
			}
			else $('#name-error').html('&nbsp;');

			if ($('#comment-email').val() == ''){
				$('#email-error').html('<?php _e('Email is required, please check again', 'hotel')?>');
				return false;
			}
			else $('#email-error').html('&nbsp;');

			if (!validateEmail($('#comment-email').val())){
				$('#email-error').html('<?php _e('Email is invalid, please check again', 'hotel')?>');
				return false;
			}
			else $('#email-error').html('&nbsp;');
			<?php } ?>

			if ($('#comment-content').val() == ''){
				$('#comment-error').html('<?php _e('Comment is required, please check again', 'hotel')?>');
				return false;
			}
			else $('#comment-error').html('&nbsp;');
   	   	   	
   	   	});
   	});
    </script>

	<p style="color:#2C2C2C; font-family:Georgia; font-size:25px; font-style:italic; padding-bottom:15px;">
		<?php comment_form_title(__('Post Your Comment', 'hotel'), __('Post Your Comment', 'hotel')); ?>
	</p> 
	
	<?php
	/**
	 * check if comment require registration
	 */ 
	?>	
	<?php if ( get_option('comment_registration') && !$user_ID ) 
	{
//		$loggin_string = __('You must be %1$s to post a comment ', 'hotel');
//		$loggin_link = '<a href="' . get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode(get_permalink()) . '"> ' . __('loggin in', 'hotel') . '</a>';
	?>
	
	<p> 
	<?php 
		_e('You must be ','hotel');
		echo '<a href="' . get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode(get_permalink()) . '"> ' . __('loggin in', 'hotel') . '</a> ';
		_e(' to post a comment', 'hotel'); 
		//printf( $loggin_string,  $loggin_link)  
	?>  
	</p>
	<?php 
	} 
	else // unless, comment's opened for everyone 
	{?>	
	
	<form id="commentform" method="post" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php">
	
		<?php 
		if ($user_ID) { // if user has logged in
		?>
		<p> <?php 
			// print the string "logged as user . log out >>"
			_e('Logged in as ', 'hotel');
			echo '<a href="' . get_option('siteurl') . '/wp-admin/profile.php">' . $user_identity . '</a> ,';
			echo '<a href="' . wp_logout_url(get_permalink()) . '" title="' . __('Log out of this account', 'hotel') . '">'. __('Log out &raquo;', 'hotel' ) . '</a>';
			
		?>  
		</p>
	    <?php 
		}
	    else { // if not, show full field
	    ?>
    
		<div class="input-content">
			<p> <?php _e('Your Name:', 'hotel') ?> <?php if (get_option('require_name_email')) echo '<span style="color:red">*</span>'?></p>
	  		<input id="comment-name" class="input" type="text" name="author" size="15" value="<?php echo empty($comment_author)? '' : $comment_author; ?>"/>
	  		<p id="name-error" style="color: red; margin: -15px 0 6px 5px; font-weight: normal">&nbsp;</p>
	    </div>
		<div class="input-content">
	    	<p> <?php _e('E-mail Address:', 'hotel') ?>  <?php if (get_option('require_name_email')) echo '<span style="color:red">*</span>'?></p>
	        <input id="comment-email" class="input" type="text" name="email" size="15" value="<?php echo empty($comment_author_email) ? '': $comment_author_email; ?>"/>
	  		<p id="email-error" style="color: red; margin: -15px 0 6px 5px; font-weight: normal">&nbsp;</p>
	    </div>
	    <div style="clear: both"> </div>
	<?php }?>
    <div class="input-content">
    	<p> <?php _e('Comment:', 'hotel') ?> <span style="color:red">*</span></p>
        <textarea id="comment-content" class="input-textarea" name="comment" rows="5" cols="50"></textarea>
    	<p id="comment-error" style="color: red; margin: -20px 0 30px 5px; font-weight: normal">&nbsp;</p>
	</div>
    <div style="clear:left;"></div>
    	<div class="button" style="margin-top:0; margin-left:220px;">  
	        <div class="button_left"></div>        
	        <div class="button_center"><input id="submit-comment" type="submit" value="<?php _e('Submit', 'hotel') ?>" class="button" name="submit" /></div>
	        <div class="button_right"></div>
        </div> 
    <?php comment_id_fields(); ?>
    <?php do_action('comment_form', $post->ID); ?>
	</form>
	
	<?php } ?>
</div>