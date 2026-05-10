<?php
namespace WpAdminEnhancer\Modules;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use \WpAdminEnhancer\Core\ModuleInterface;

class StickyListTable implements ModuleInterface {
    public function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
    }

    public function should_load(): bool {
        if ( ! is_admin() ) {
            return false;
        }

        global $pagenow;

        return $pagenow === 'edit.php';
    }

    public function enqueue( $hook ): void {
        if ( ! $this->should_load() || $hook !== 'edit.php' ) {
            return;
        }

        wp_register_style( 'wpe-sticky-table', '', [], '1.0' );

        wp_enqueue_style( 'wpe-sticky-table' );

        wp_add_inline_style( 'wpe-sticky-table', $this->style() );
    }

    private function style(): string {
        return <<<CSS
        /* Sticky headers for post/page list tables in wp-admin edit screens */
        .wp-admin .wp-list-table thead th {
            position: sticky;
            top: 32px;
            z-index: 20;
            background: #f6f7f7;
            background-clip: padding-box;
        }

        /* WP admin bar is taller on smaller screens */
        @media screen and (max-width: 782px) {
            .wp-admin .wp-list-table thead th {
                top: 46px;
            }
        }
        CSS;
    }
}