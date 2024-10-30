<?php

// Vamos garantir que é o WordPress que chama esta pasta
// e que realmente está desinstalando o plugin.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
  die();
 
// Vamos remover as opções que criámos na instalação 
delete_option( 'lbfm_menu' );
delete_option( 'lbfm_width' );
delete_option( 'lbfm_height' );
delete_option( 'lbfm_position' );
delete_option( 'lbfm_font_color' );
delete_option( 'lbfm_font_color_hover' );
delete_option( 'lbfm_background_color' );
delete_option( 'lbfm_background_color_hover' );

?>
