<?php
/*
 Plugin Name: Teamacle
 Plugin URI: https://wordpress.org/plugins/teamacle
 Description: Official <a href="https://www.teamacle.com">Teamacle</a> support for WordPress.
 Author: Teamacle
 Author URI: https://www.teamacle.com
 Version: 1.0
 License: GPLv2 or later
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
class TeamacleSettingsPage {
    private $settings = array();
    private $styles = array();
    
    public function __construct($settings)
    {
        $this->settings = $settings;
        $this->styles = $this->setStyles($settings);
    }
    
    public function dismissibleMessage($text)
    {
        return <<<END
  <div id="message" class="updated notice is-dismissible">
    <p>$text</p>
    <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
  </div>
END;
    }
    
    public function getAuthUrl() {
        return "http://chat.teamacle.com/wp_auth/confirm?state=".get_site_url()."::".wp_create_nonce('teamacle-oauth');
    }
    
    public function getCreateUrl() {
        return "http://chat.teamacle.com/wpCreateCenter?state=".get_site_url()."::".wp_create_nonce('teamacle-oauth');
    }
    
    public function htmlUnclosed()
    {
        $settings = $this->getSettings();
        $styles = $this->getStyles();
        $center_id = esc_attr($settings['center_id']);
        $center_state = esc_attr($settings['center_state']);
        $auth_url = $this->getAuthUrl();
        $create_url = $this->getCreateUrl();
        $dismissable_message = '';
        $home_page = get_home_url();
        $wp_connect_icon_img = plugins_url('public/images/wp_connect_icon.png',__FILE__);
        $wp_create_live_chat_icon = plugins_url('public/images/wp_create_live_chat_icon.png',__FILE__);
        $arrow_right_img = plugins_url('public/images/arrow_right.svg',__FILE__);
        $plugin_icon = plugins_url('public/images/plugin_icon.svg',__FILE__);
        $teamacle_logo_icon = plugins_url('public/images/teamacle-logo.png',__FILE__);
	    $plugin_install_page = admin_url()."/options-general.php?page=Teamacle";
        if (isset($_GET['authenticated'])) {
            $dismissable_message = $this->dismissibleMessage('You successfully authenticated with Teamacle');
        }
        if (isset($_GET['center_state']) && $center_state == 0) {
            $dismissable_message = $this->dismissibleMessage('You need to create your own chat center with Teamacle account');
        }
        
        return <<<END
<div class="wrap">
$dismissable_message
    <section id="main_content" style="padding-top: 70px;">
        <div class="container">
            <div class="teamacle-title">
                <img src='$teamacle_logo_icon'>
                <p>Enhance Your Website with <strong>Live Chat</strong> Customer Support</p>
            </div>
            <div class="cta">
                <div class="sp__2--lg sp__2--xlg"></div>
                <div id="oauth_content" style="$styles[center_style_unbind]">
                    <div class="t__h1 c__red">Get started with Teamacle Chat Center</div>
                    
                    <div class="cta__desc">
                        Chat with visitors to your website in real-time, capture them as leads, and convert them to customers. Install Teamacle on your WordPress site in a couple of clicks.
                    </div>
                    
                    <div id="get_teamacle_btn_container" style="position:relative;margin-top:30px;">
                        <a href='$auth_url'>
                            <img style="max-width: 204px;" src="$wp_connect_icon_img"/>
                        </a>
                        <p>Need a Teamacle account? <a href="http://chat.teamacle.com">Get started</a>.</p>
                    </div>
                    
                </div>
                <div id="app_id_and_secret_content" style="$styles[center_style_bind]">
                    <div class="t__h1 c__red" style="">Teamacle Chat Center has been installed</div>
                    
                    <div class="cta__desc">
                        <div style="">
                            Teamacle is now set up and ready to go. You can now chat with your existing and potential new customers, send them targeted messages, and get feedback.
                        </div>
                        <div>
                            <form method="post" action="" name="update_settings">
                                <table class="form-table" align="center" style="margin-top: 16px; width: inherit;">
                                    <tbody>
                                        <tr>
                                            <th scope="row" style="text-align: center; vertical-align: middle;"><label for="teamacle_chat_center_id">Chat Center ID</label></th>
                                                <td>
                                                    <input id="teamacle_chat_center_id" name="chat_center_id" type="text" value="$center_id" class="" readonly>
                                                </td>
                                            </tr>
                                    </tbody>
                                </table>
                            </form>
                            <div style="">
                                <br/>
                                <div style="font-size:1em"><a class="c__blue" href="$home_page" target="_blank">Start a test chat on your homepage</a></div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 80px;">
                        <div class="teamacle-install-card-messenger-visibility">
                            <p>VISIBLE TO YOUR VISITORS AND USERS <image src="$arrow_right_img"/></p>
                            <img src="$plugin_icon"/>
                        </div>
                    </div>
                </div>
                <div id="oauth_content" style="$styles[center_style_create]">
                    <div class="t__h1 c__red">No Live Chat In Your Teamacle Account</div>
                    
                    <div class="cta__desc">
                        Teamacle provides everything your company needs for a Live Chat enabled website. Would you like to create a Live Chat?
                    </div>
                    
                    <div id="get_teamacle_btn_container" style="position:relative;margin-top:30px;">
                        <a onclick="window.open('$plugin_install_page', '_top'); window.open('$create_url', '_blank');" href="#">
                            <img style="max-width: 204px;" src="$wp_create_live_chat_icon"/>
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</div>

END;
    }
    
    public function setStyles($settings) {
        $styles = array();
        $center_id = esc_attr($settings['center_id']);
        $center_state = esc_attr($settings['center_state']);
        if(isset($_GET['center_state']) && $center_state == 0) {
            $styles['center_style_bind'] = 'display: none;';
            $styles['center_style_unbind'] = 'display: none;';
            $styles['center_style_create'] = '';
        } else {
            if (empty($center_id)) {
                $styles['center_style_bind'] = 'display: none;';
                $styles['center_style_unbind'] = '';
                $styles['center_style_create'] = 'display: none;';
            } else {
                $styles['center_style_bind'] = '';
                $styles['center_style_unbind'] = 'display: none;';
                $styles['center_style_create'] = 'display: none;';
            }
        }
        
        return $styles;
    }
    
    private function getSettings()
    {
        return $this->settings;
    }
    
    private function getStyles()
    {
        return $this->styles;
    }
}

class TeamacleVal
{
    private $inputs = array();
    private $validation;
    
    public function __construct($inputs, $validation)
    {
        $this->input = $inputs;
        $this->validation = $validation;
    }
    
    public function validCenterId()
    {
        return $this->validate($this->input["center_id"]);
    }
    
    private function validate($x)
    {
        return call_user_func($this->validation, $x);
    }
}

function add_teamacle_settings_page() {
    
    add_options_page(
        'Teamacle Settings',
        'Teamacle',
        'manage_options',
        'Teamacle',
        'render_teamacle_options_page'
        );
}

function render_teamacle_options_page() {
    if ( !current_user_can( 'manage_options' ) )
    {
        wp_die('You do not have sufficient permissions to access Teamacle settings.');
    }
    $options = get_option('teamacle');
    $settings_page = new TeamacleSettingsPage(array("center_id" => $options['center_id']));
    wp_register_style( 'teamacle-plugin-install', plugins_url('public/css/teamacle-plugin-install.css',__FILE__), false, '1.0', false );
    wp_enqueue_style( 'teamacle-plugin-install' );
    echo $settings_page->htmlUnclosed();
}

function teamacle_settings() {
    register_setting('teamacle', 'teamacle');
    $options = get_option('teamacle');
    if (isset($_GET['state']) && wp_verify_nonce($_GET[ 'state'], 'teamacle-oauth') && current_user_can('manage_options') && isset($_GET['center_id']) ) {
        $teamacle_val = new TeamacleVal($_GET, function($x) { return wp_kses(trim($x), array()); });
        update_option("teamacle", array("center_id" => $teamacle_val->validCenterId()));
        $redirect_to = 'options-general.php?page=Teamacle&authenticated=1';
        wp_safe_redirect(admin_url($redirect_to));
    }
    if (isset($_GET['state']) && wp_verify_nonce($_GET[ 'state'], 'teamacle-oauth') && current_user_can('manage_options') && isset($_GET['center_state']) ) {
        $redirect_to = 'options-general.php?page=Teamacle&center_state=0';
        wp_safe_redirect(admin_url($redirect_to));
    }
}

function add_teamacle_plugin() {
    $options = get_option('teamacle');
    if(!empty($options['center_id'])){
        echo "<script>
            window.teamacleSettings = {
                center_id: '$options[center_id]',
            };
         </script>";
        wp_enqueue_script('teamacle-center-enter.js', '//chat.teamacle.com/static/js/plugin/center-enter.js', array(), null, true);
    }
}

add_action('wp_footer', 'add_teamacle_plugin');
add_action('admin_menu', 'add_teamacle_settings_page');
add_action('network_admin_menu', 'add_teamacle_settings_page');
add_action('admin_init', 'teamacle_settings');
?>