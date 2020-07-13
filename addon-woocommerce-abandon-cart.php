<?php
/**
 * Plugin Name: Addon for WooCommerce Abandoned Cart Pro
 * Plugin URI: http://www.tychesoftwares.com/store/premium-plugins/woocommerce-abandoned-cart-pro
 * Description: This is an addon for Abandoned Cart Pro that allows the site admin to reset plugin table data.
 * Author: Tyche Softwares
 * Version: 1.0
 * Author URI: http://www.tychesoftwares.com/
 *
 * @package Addon for Abandon Cart Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Wcap_Addon_Reset_Data' ) ) {
	/**
	 * Reset class file.
	 */
	class Wcap_Addon_Reset_Data {

		/**
		 * Construct
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'wcap_add_custom_settings_tab', array( &$this, 'wcap_custom_reset_tab' ), 10, 1 );
			add_action( 'wcap_add_custom_settings_tab_content', array( &$this, 'wcap_custom_reset_tab_content' ), 10, 1 );
		}

		/**
		 * Add new tab.
		 *
		 * @param string $section - Section Name.
		 * @since 1.0
		 */
		public function wcap_custom_reset_tab( $section ) {

			$display_reset_settings = '';
			if ( 'wcap_reset_settings' === $section ) {
				$display_reset_settings = 'current';
			}
			?>
			<li>
				| <a href="admin.php?page=woocommerce_ac_page&action=emailsettings&wcap_section=wcap_reset_settings" class="<?php echo esc_attr( $display_reset_settings ); ?>"><?php esc_html_e( 'Reset Table Data', 'woocommerce-ac' ); ?> </a>                     
			</li>
			<?php
		}

		/**
		 * Content on the Reset Settings tab.
		 *
		 * @param string $section - Section name.
		 * @since 1.0
		 */
		public function wcap_custom_reset_tab_content( $section ) {

			if ( 'wcap_reset_settings' === $section ) {
				global $wpdb;
				if ( isset( $_GET['ts_action'] ) && 'reset_guest_id' === sanitize_text_field( wp_unslash( $_GET['ts_action'] ) ) ) { // phpcs:ignore
					$wpdb->query( 'ALTER TABLE '. $wpdb->prefix . 'ac_guest_abandoned_cart_history AUTO_INCREMENT = 63000000;' ); // phpcs:ignore
					update_option( 'wcap_guest_user_id_altered', 'yes' );
				}

				$last_id = $wpdb->get_var( 'SELECT id FROM `' . $wpdb->prefix . 'ac_guest_abandoned_cart_history` ORDER BY id DESC LIMIT 1' ); // phpcs:ignore
				$current_id = $last_id + 1;
				?>
				<h2><?php esc_html_e( 'Reset Plugin Table Data', 'woocommerce-ac' ); ?></h2>
				<table>
					<tr>
						<th>
							<?php esc_html_e( 'Guest Table Auto Increment ID:', 'woocommerce-ac' ); ?>
						</th>
						<td style="padding: 10px;">
							<?php echo esc_html( $current_id ); ?>
						</td>
						<td>
							<?php
							if ( $current_id < 63000000 ) { // Display a reset button.
								?>
								<a class='button button-large reset-guest-id' href='<?php echo esc_url( admin_url() ); ?>admin.php?page=woocommerce_ac_page&action=emailsettings&wcap_section=wcap_reset_settings&ts_action=reset_guest_id'><?php esc_html_e( 'Reset ID to 63000000', 'woocommerce-ac' ); ?></a>
								<?php
							}
							?>
						</td>
					</tr>
				</table>
				<?php
			}
		}

	}
} // Class Exists.
$wcap_addon_reset_data = new Wcap_Addon_Reset_Data();
