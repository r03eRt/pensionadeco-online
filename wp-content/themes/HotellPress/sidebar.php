			<div class="widget">
            	<div class="sidebar">
                	<div class="top">
                    	<?php
						if ( is_active_sidebar( 'sidebar_right_top' ) ) : 				                                       
						dynamic_sidebar( 'sidebar_right_top' );  											
						endif; ?>
                    </div>
                    <div class="sidebar-content">                    	
                        <?php
						if ( is_active_sidebar( 'sidebar_right_content' ) ) : 				                                       
						dynamic_sidebar( 'sidebar_right_content' );  											
						endif; ?>						
                    </div>
                </div>
            </div>
       
       <div style="clear:left;"></div>