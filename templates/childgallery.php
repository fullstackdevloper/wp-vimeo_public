<div id="wp-vimeo_gallery">
    

<div class="demo-gallery">
            <ul id="lightgallery" class="list-unstyled row">
			
			<?php 
            $all_subscribers = get_users([ 'role__in' => [ 'author', 'subscriber' ] ] );
			$i=0;
            foreach($all_subscribers as $all_subscriber){
                $user_link = get_avatar_url( $all_subscriber->data->ID,960);
                $user_thumb_link = get_avatar_url( $all_subscriber->data->ID,96);
				if($i>14){
					break;
				}
            ?>
                <li class="col-xs-6 col-sm-4 col-md-3" data-responsive="<?php echo $user_link; ?>" data-src="<?php echo $user_link; ?>">
                    <a href="">
                        <img class="img-responsive" width="150" height="150" attr="" src="<?php echo $user_thumb_link; ?>">
                    </a>
					<span><?php echo $all_subscriber->data->user_login; ?></span>
                </li>
             <?php  $i++; } 	?>
            </ul>
        </div>



	<script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery('#lightgallery').lightGallery();
        });
        </script>
</div>