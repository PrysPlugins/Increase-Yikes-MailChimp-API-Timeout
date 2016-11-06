<?php
/**
 * Plugin Name: Increase Yikes MailChimp API Timeout
 * Plugin URI: https://github.com/PrysPlugins/Increase-Yikes-MailChimp-API-Timeout
 * Description: Increase the time limit for API requests for the Yikes MailChimp Extender plugin to 45 seconds.
 * Version: 1.0
 * Author: Jeremy Pry
 * Author URI: http://jeremypry.com/
 * License: GPL2
 */

// Prevent direct access to this file
if ( ! defined( 'ABSPATH' ) ) {
	die( "You can't do anything by accessing this file directly." );
}

class JPry_Increase_Yikes_MailChimp_API_Timeout {

	/**
	 * Hook our methods into the appropriate actions and filters.
	 *
	 * This should be run on or after the `plugins_loaded` hook.
	 */
	public function hooks() {
		if ( ! $this->is_mailchimp_plugin_active() ) {
			return;
		}
		
		add_filter( 'http_request_args', array( $this, 'increase_timeout' ), 10, 2 );
	}
	
	/**
	 * Filters the arguments used in an HTTP request.
	 *
	 * @param array  $args An array of HTTP request arguments.
	 * @param string $url  The request URL.
	 *
	 * @return array The filtered args.
	 */
	public function increase_timeout( $args, $url ) {
		// Bail if we're not modifying the right request.
		if ( false === strpos( $url, 'api.mailchimp.com' ) ) {
			return $args;
		}
		
		$args['timeout'] = 45;
		
		return $args;
	}
	
	/**
	 * Determine if the Yikes MailChimp plugin is active.
	 *
	 * @return bool
	 */
	protected function is_mailchimp_plugin_active() {
		return defined( 'YIKES_MC_VERSION' );
	}
}

$jpry_increase_yikes_mailchimp_timeout = new JPry_Increase_Yikes_MailChimp_API_Timeout();
add_action( 'plugins_loaded', array( $jpry_increase_yikes_mailchimp_timeout, 'hooks' ) );
