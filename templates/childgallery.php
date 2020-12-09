<h3>Our Kids</h3>

<div id="gallery">

        <div id="slide">
            <a class="prev">❮</a>
            <a class="next">❯</a>
            <img id='preview' />
        </div>


        <div id="thumbnails">
            <div class="wrapper">
			<?php 
				$all_subscribers = get_users([ 'role__in' => [ 'author', 'subscriber' ] ] );
				
				foreach($all_subscribers as $all_subscriber){
					echo $user_link = get_avatar( $all_subscriber->data->ID, $size = 960,'',$all_subscriber->data->user_login, ['class'=>'thumbnail']);
				}
			?>              
               
            </div>
        </div>
</div>