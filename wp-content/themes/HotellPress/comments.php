
<?php 


/**
* COMMENT LIST
*/ 
                
if ( have_comments() ) {?>
	<div id="comments" class="comment" name="comments">
		<p style="color:#2C2C2C; font-family:Georgia; font-size:25px; font-style:italic; padding-bottom:15px;">
		<?php comments_number(__('Comment', 'hotel'), __('Comment (1)', 'hotel'), __('Comments (%)','hotel')) ?>
		</p>
		<?php wp_list_comments('type=comment&callback=hotel_comment&comment_approved=1');  ?>
		
		<div class="comment-container" style="margin-bottom:20px; border-bottom:5px solid #F0EBEA; background:none; border-top:none; padding-top:0;">
			<?php tgt_the_comment_pagination() ?>
		</div>
	</div>
<?php 
}
?>