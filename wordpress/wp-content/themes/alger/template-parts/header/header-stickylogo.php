<?php

if (  $custom_logo = get_theme_mod( 'custom_logo' ) ) {

		$image_attributes = wp_get_attachment_image_src( $custom_logo, 'full' );
		$logo = $image_attributes[0];
		$logo = '<a href="'.esc_url( home_url( '/' ) ).'"><img src="' . esc_url( $logo ) . '" alt="" /></a>';
		echo $logo;

	}else{

		$header_text_color = get_header_textcolor();

?>

<?php if ( 'blank' != $header_text_color ) :?>

  <a href="<?php echo esc_url( home_url( '/' ) ); ?>">

  <h2 class="site-name">

    <?php bloginfo( 'name' ); ?>

  </h2>

  </a>

<?php endif;?>

<?php	

		}		
