<?php

/**
 * WooMultistore Sync Custom Metadata Add-on
 *
 * Compatibility plugin to sync custom metadata.
 *
 * @link              https://woomultistore.com/
 * @since             1.0.1
 * @package           Woocommerce_Multistore_Usermeta_Sync
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Multistore Sync Custom Metadata Add-on
 * Plugin URI:        https://woomultistore.com
 * Description:       This is a compability plugin to sync user-defined metadata
 * Version:           1.0.1
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
			'my_white_listed_metadata_key',
		);

		if ( is_multisite() ) {
			add_filter( 'WOO_MSTORE_admin_product/slave_product_meta_to_update', array( $this, 'sync_whitelist' ), PHP_INT_MAX, 2 );
		} else {
			add_filter( 'WOO_MSTORE_SYNC/process_json/meta', array( $this, 'sync_whitelist_standalone' ), PHP_INT_MAX, 3 );
		}
	}

	/**
	 * Syncs the whitelisted metadata for multisite
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


	/**
	 * Sync metadata on regular WordPress site.
	 *
	 * @since 1.0.1
	 *
	 * @param mixed $_whitelisted_meta
	 * @param mixed $product_id
	 * @param mixed $wc_product
	 * @return void
	 */
	public function sync_whitelist_standalone( $_whitelisted_meta, $product_id, $wc_product ) {
		foreach ( $this->whitelist as $whitelisted_metakey ) {
			$meta_value                        = get_post_meta( $product_id, $whitelisted_metakey, true );
			$_whitelisted_meta[ $whitelisted_metakey ] = $meta_value;
		}

		return $_whitelisted_meta;
	}
}

new Woocommerce_Multistore_Sync_Custom_Meta();
