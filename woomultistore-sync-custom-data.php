<?php

/**
 * WooMultistore Sync Custom Metadata Add-on
 *
 * Compatibility plugin to sync custom metadata.
 *
 * @link              https://woomultistore.com/
 * @since             1.0.0
 * @package           Woocommerce_Multistore_Usermeta_Sync
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Multistore Sync Custom Metadata Add-on
 * Plugin URI:        https://woomultistore.com
 * Description:       This is a compability plugin to sync user-defined metadata
 * Version:           1.0.0
 * Author:            Lykke Media AS
 * Author URI:        https://woomultistore.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-multistore-usermeta-sync
 * Domain Path:       /languages
 * Network: true
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Whitelisted metadata
 *
 * Maintain a list of metadata that should be synced automatically
 *
 * @package    Woocommerce_Multistore_Usermeta_Sync
 * @subpackage Woocommerce_Multistore_Usermeta_Sync/includes
 * @author     Lykke Media AS <a@gmail.com>
 */
class Woocommerce_Multistore_Sync_Custom_Meta {

	/**
	 * The array of whitelisted metadata
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $whitelist    The array of whitelisted metadata for automatic syncing.
	 */
	protected $whitelist;


	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->whitelist = array(
			'brand',
			'merchantproductno',
			'shipping_time',
			'merchant',
			'image_urls',
			'category_trail',
			'parent_id',
			'type',
			'wc_type',
			'pf_color',
			'pf_size',
			'pf_dimensions',
			'pf_weight',
			'pf_volume',
			'pf_version',
			'pf_gender',
			'pf_strength',
		);

		add_filter( 'WOO_MSTORE_admin_product/slave_product_meta_to_update', array( $this, 'sync_whitelist' ), PHP_INT_MAX, 2 );
	}

	/**
	 * Syncs the whitelisted metadata
	 *
	 * @since    1.0.0
	 * @return  array array of metada key and meta value.
	 */
	public function sync_whitelist( $meta_data, $data ) {
		foreach ( $this->whitelist as $whitelisted_metakey ) {
			$meta_value                        = $data['master_product']->get_meta( $whitelisted_metakey, true );
			$meta_data[ $whitelisted_metakey ] = $meta_value;
		}

		return $meta_data;
	}
}

new Woocommerce_Multistore_Sync_Custom_Meta();
