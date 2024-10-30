<?php
/*
Plugin Name: Larbous Floating Menu
Plugin URI: http://larbo.us/plugins/larbous-floating-menu
Description: This plugin creates a fixed floating menu at left position. Set the options in Settings/Floating Menu
Version: 1.1
Author: Luiz Sobral
Author URI: http://luizsobral.com.br
License: GPLv2
*/

/*
 *      Copyright 2014 Luiz Sobral (larbous Internet) <luizsobral@larbo.us>
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 3 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */
 
 // Registramos a função para rodar na ativação do plugin
register_activation_hook( __FILE__, 'lbfm_install_hook' );
 
function lbfm_install_hook() {
  // Vamos testar a versão do PHP e do WordPress
  // caso as versões sejam antigas, desativamos
  // o nosso plugin.
  if ( version_compare( PHP_VERSION, '5.2.1', '<' )
    or version_compare( get_bloginfo( 'version' ), '3.3', '<' ) ) {
      deactivate_plugins( basename( __FILE__ ) );
  }
}

/** 
 * Action: init 
 */  
function lbfm_action_init()  
{  
    // Localization  
    load_plugin_textdomain('larbous-floating-menu', false, dirname(plugin_basename(__FILE__)) . '/languages' );
}  
  
// Add actions  
add_action('init', 'lbfm_action_init');  


// starting the plugin
// Adicionar menu ao admin
if (is_admin()) {
    add_action('admin_menu', 'lbfm_menu');
    add_action('admin_init', 'lbfm_register_mysettings' );
}

function lbfm_menu() {
    add_options_page(__('Floating Menu Settings','larbous-floating-menu'), __('Larbous Floating Menu','larbous-floating-menu'), 8, 'lbfm-floating-menu', 'lbfm_options' );
}

// registrando variaveis e carregando css e js
function lbfm_register_mysettings() {
	//register our settings
    register_setting( 'lbfm-settings-group', 'lbfm_menu' ); // selected menu
	register_setting( 'lbfm-settings-group', 'lbfm_width' ); // Width of menu
	register_setting( 'lbfm-settings-group', 'lbfm_height' ); // Height of menu
	register_setting( 'lbfm-settings-group', 'lbfm_position' ); // top position at screen
	register_setting( 'lbfm-settings-group', 'lbfm_font_color' ); // bar font color
	register_setting( 'lbfm-settings-group', 'lbfm_font_color_hover' ); //bar font color hover
	register_setting( 'lbfm-settings-group', 'lbfm_background_color' ); // bar color
	register_setting( 'lbfm-settings-group', 'lbfm_background_color_hover' ); //bar color hover
    add_action( 'admin_enqueue_scripts', 'lbfm_scripts_css_admin' );
}


// Carregando os Scripts e CSS no admin
function lbfm_scripts_css_admin( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'lbfm_script_admin', plugin_dir_url( __FILE__ ).'js/admin.js', array( 'wp-color-picker' ), false, true );
}

// Carregando os Scripts e CSS no frontend
function lbfm_scripts_css(){
    wp_enqueue_script('jquery');
    //wp_register_script('lbfm_script', plugin_dir_url( __FILE__ ).'js/navigation.js', array('jquery'), '1.0', false);
    //wp_enqueue_script('lbfm_script');
    wp_register_style( 'lbfm_css', plugin_dir_url( __FILE__ ).'css/style.css' );
    wp_enqueue_style('lbfm_css');
}
add_action('wp_enqueue_scripts', 'lbfm_scripts_css'); 



function lbfm_options () { 
    $list_menus = wp_get_nav_menus();
    
    //variaveis
    $lbfm_menu = get_option('lbfm_menu');
    
?>
    <div class="wrap">
        <div id="icon-options-general" class="icon32"></div>
        <h2><?php _e('Floating Menu Settings'); ?></h2>
        
        <h3><?php _e('Tutorial','larbous-floating-menu'); ?></h3>
        
        <p><?php _e('Before you set any options you need to create a custom menu in <em> "Appearance / Menu" <em> and then select here. <br> By default, this plugin takes a menu created with the name "Floating Menu".','larbous-floating-menu'); ?></p>
        
        <form method="post" action="options.php">
            <?php settings_fields( 'lbfm-settings-group' ); ?>
            <?php do_settings_sections( 'lbfm-settings-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                <th scope="row"><?php _e('Choose a menu','larbous-floating-menu'); ?></th>
                    <td>
                        <select name="lbfm_menu">
                            <?php foreach ($list_menus as $lm) : ?>
                            <option value="<?php echo $lm->slug; ?>"<?php echo $lm->slug == $lbfm_menu ? ' selected' : ''; ?>><?php echo $lm->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                 
                <tr valign="top">
                <th scope="row"><?php _e('Dimensions (Width X Height)','larbous-floating-menu'); ?></th>
                <td><input type="text" name="lbfm_width" size="5" maxlength="5" value="<?php echo get_option('lbfm_width'); ?>" /> X <input type="text" name="lbfm_height" size="5" maxlength="5" value="<?php echo get_option('lbfm_height'); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row"><?php _e('Vertical position on the screen','larbous-floating-menu'); ?></th>
                <td><input type="text" name="lbfm_position" maxlength="5" value="<?php echo get_option('lbfm_position'); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row"><?php _e('Font Color Menu','larbous-floating-menu'); ?></th>
                <td><input type="text" class="lbfm-colorpicker" name="lbfm_font_color" maxlength="7" value="<?php echo get_option('lbfm_font_color'); ?>" /></td>
                </tr>
                <tr valign="top">
                <th scope="row"><?php _e('Color Menu','larbous-floating-menu'); ?></th>
                <td><input type="text" class="lbfm-colorpicker" name="lbfm_background_color" maxlength="7" value="<?php echo get_option('lbfm_background_color'); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row"><?php _e('Font Color Menu Hover','larbous-floating-menu'); ?></th>
                <td><input type="text"  class="lbfm-colorpicker" name="lbfm_font_color_hover" maxlength="7" value="<?php echo get_option('lbfm_font_color_hover'); ?>" /></td>
                </tr>

                <tr valign="top">
                <th scope="row"><?php _e('Color Menu Hover','larbous-floating-menu'); ?></th>
                <td><input type="text"  class="lbfm-colorpicker" name="lbfm_background_color_hover" maxlength="7" value="<?php echo get_option('lbfm_background_color_hover'); ?>" /></td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
            
            
            <p style="font-style: italic;">Copyright Larbous Internet - http://larbo.us</p>
        
        </form>    
    </div>
<?    
}


// loading floating menu
function lbfm_carregar_menu() {

    //Recuperando o menu salvo
    $menu_slug = get_option( 'lbfm_menu', 'floating-menu' );
    
    // Se o menu não existe, sai.
    if ( ! is_nav_menu( $menu_slug ) ) {
        exit;
    }
    
    $defaults = array(
    	'theme_location'  => '',
    	'menu'            => $menu_slug,
    	'container'       => 'div',
    	'container_class' => '',
    	'container_id'    => '',
    	'menu_class'      => 'menu',
    	'menu_id'         => 'lbfm-navigation',
    	'echo'            => true,
    	'fallback_cb'     => 'wp_page_menu',
    	'before'          => '',
    	'after'           => '',
    	'link_before'     => '',
    	'link_after'      => '',
    	'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
    	'depth'           => 0,
    	'walker'          => ''
    );
    
    wp_nav_menu( $defaults );

    //Buscando as cores que o usuário gravou
    $li_width  = get_option( 'lbfm_width', '148' );
    $li_height = get_option( 'lbfm_height', '48' );
    $li_margin = $li_height-$li_width;
    $li_position = is_admin_bar_showing() ? get_option( 'lbfm_position', '200' )+32 : get_option( 'lbfm_position', '200' );
    
    
    echo '<style>
    ul#lbfm-navigation {
        top: '.$li_position.'px;
    }
    ul#lbfm-navigation li { 
        width: '.$li_width.'px; 
    }
    ul#lbfm-navigation li a {
        margin-left: '.($li_margin).'px;
        width: '.$li_width.'px; 
        height: '.$li_height.'px; 
        color: '.get_option( 'lbfm_font_color', '#FFFFFF' ).'; 
        background-color: '.get_option( 'lbfm_background_color', '#333333' ).'
    }
    ul#lbfm-navigation li a:hover { 
        color: '.get_option( 'lbfm_font_color_hover', '#FFFFFF' ).'; 
        background-color:'.get_option( 'lbfm_background_color_hover', '#CCCCCC' ).';
    }
    </style>';
    echo "
    <script>
    jq = jQuery.noConflict();
    jq( document ).ready(function() {
        jq(function() {
            jq('#lbfm-navigation > li a').hover(
                //Mouseover
                function () {
                    jq(this).stop().animate({'marginLeft':'-2px'},200);
                },  
                function () {
                    jq(this).stop().animate({'marginLeft':'".$li_margin."px'},200);
                }
            );
        });    
    });    
    </script>";

}
add_action('wp_footer', 'lbfm_carregar_menu');
?>