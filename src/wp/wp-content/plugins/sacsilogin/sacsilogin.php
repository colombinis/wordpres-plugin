<?php

/*
 * Plugin Name: Sacsi Login by ajax
 * Description: Registra un ShortCode para integrar login [sacsilogin]
 * Version: 0.1.0
 * Author: Sebastian A Colombini
 * License: GPLv2 or later
 * Text Domain: sacsilogin
 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sacsilogin {

	public static $version = '0.1.0';
	public static $security = 'secret';
	public static $slug = 'sacsilogin';
	protected static $instance = null;
    protected static $view = null;
    public function __construct() {
		self::$view = dirname( __FILE__ ) . '/views/';

        add_shortcode('sacsilogin', array( $this, 'sacsilogin_shortcode') );

        add_action( 'wp_enqueue_scripts', array( $this, 'sacsilogin_load_scripts' ) );

        add_action('wp_ajax_nopriv_ajaxlogin', array( $this,'server_ajax_login') );
        add_action('wp_ajax_ajaxlogin', array( $this,'server_ajax_login') );

	}

	public function sacsilogin_load_scripts() {

        wp_enqueue_script('sacsilogin_js', plugins_url( '/', __FILE__ ) . 'assets/js/sacsilogin.js', array( 'jquery' ), self::$version );

        wp_localize_script('sacsilogin_js', 'ajax_login_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'extradata' => home_url(),
            'loadingmessage' => __('Sending user info, please wait...')
        ));

    }

    public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
    }

    static function getView($name){
        $HTML="";

        ob_start();
        require_once (self::$view . $name ."_view.php");
        $HTML = ob_get_contents();
        ob_end_clean();

        return $HTML;
    }

    public function server_ajax_login()
    {

        // First check the nonce, if it fails the function will break
        //check_ajax_referer('ajax-login-nonce', self::$security );

        if(is_user_logged_in()){
            echo json_encode(array('loggedin' => false, 'message' => __('Ya esta logueado.')));
            die();
        }

        // Nonce is checked, get the POST data and sign user on
        $info = array();
        $info['user_login'] = $_POST['username'];
        $info['user_password'] = $_POST['password'];
        $info['remember'] = true;

        $user_signon = wp_signon($info, false);
        if (is_wp_error($user_signon)) {
            echo json_encode(array('loggedin' => false, 'message' => __('Wrong username or password.')));
        } else {
            echo json_encode(array('loggedin' => true, 'message' => __('Login successful, redirecting...')));
        }
        die();
    }

    public function sacsilogin_shortcode($atts = [], $content = null)
    {
        return self::getView('form-login');
    }
}

Sacsilogin::get_instance();