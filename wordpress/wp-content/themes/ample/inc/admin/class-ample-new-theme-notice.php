<?php
/**
 * Ample New Theme Notice Class.
 *
 * @author  ThemeGrill
 * @package Ample
 * @since   1.2.3
 */

// Exit if directly accessed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Ample_New_Theme_Notice
 */
class Ample_New_Theme_Notice {

	/**
	 * Constructor function to include the required functionality for the class.
	 *
	 * Ample_New_Theme_Notice constructor.
	 */
	public function __construct() {

		add_action( 'after_switch_theme', array( $this, 'ample_theme_notice' ) );

		// Display the new theme notice when transient is present.
		if ( get_transient( 'ample_theme_switched' ) ) {
			add_action( 'admin_notices', array( $this, 'ample_new_theme_notice' ) );
			add_action( 'admin_init', array( $this, 'ample_ignore_new_theme_notice' ) );
		}

	}

	/**
	 * Set the transient after switch theme..
	 */
	public function ample_theme_notice() {

		set_transient( 'ample_theme_switched', 'ample_new_theme_notice', 3 * DAY_IN_SECONDS );
		add_action( 'admin_notices', array( $this, 'ample_new_theme_notice' ) );
		add_action( 'admin_init', array( $this, 'ample_ignore_new_theme_notice' ) );

	}

	/**
	 * Add a dismissible notice in the dashboard about new theme.
	 */
	public function ample_new_theme_notice() {
		global $current_user;
		$user_id        = $current_user->ID;
		$ignored_notice = get_user_meta( $user_id, 'ample_ignore_new_theme_notice' );
		if ( ! empty( $ignored_notice ) ) {
			return;
		}

		$dismiss_button = sprintf(
			'<a href="%s" class="notice-dismiss" style="text-decoration:none;"></a>',
			'?nag_ignore_new_theme=0'
		);

		$message = sprintf(
			/* translators: %1$s Zakra theme link %2$s Zakra theme demo link */
			esc_html__( 'Zakra - our new most flexible free WordPress theme. %1$s Zakra is fully compatible with Gutenberg, Elementor and other major page builders. Comes with %2$s to quickly setup your new website.', 'ample' ),
			sprintf(
				'<a target="_blank" href="%1$s"><strong>%2$s</strong></a>',
				esc_url( 'https://themegrill.com/themes/zakra/?utm_source=ample-dashboard&utm_medium=notice-link&utm_campaign=zakra-page' ),
				esc_html__( 'Check it out!', 'ample' )
			),
			sprintf(
				'<a target="_blank" href="%1$s"><strong>%2$s</strong></a>',
				esc_url( 'https://demo.themegrill.com/zakra-demos/?utm_source=ample-dashboard&utm_medium=notice-link&utm_campaign=zakra-demo' ),
				esc_html__( '10+ free starter sites', 'ample' )
			)
		);

		printf(
			'<div class="notice updated" style="position:relative;">%1$s<p>%2$s</p></div>',
			$dismiss_button,
			$message
		);
	}

	/**
	 * Update the ample_ignore_new_theme_notice option to true, to dismiss the notice from the dashboard
	 */
	public function ample_ignore_new_theme_notice() {
		global $current_user;
		$user_id = $current_user->ID;

		/* If user clicks to ignore the notice, add that to their user meta */
		if ( isset( $_GET['nag_ignore_new_theme'] ) && '0' == $_GET['nag_ignore_new_theme'] ) {
			add_user_meta( $user_id, 'ample_ignore_new_theme_notice', 'true', true );
		}
	}

}

new Ample_New_Theme_Notice();
