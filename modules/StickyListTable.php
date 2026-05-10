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

    public function should_load(string $hook = ''): bool {
        return is_admin() && $hook === 'edit.php';
    }

    public function enqueue( $hook ): void {
        if ( ! $this->should_load( $hook ) ) {
            return;
        }

        wp_register_style( 'wpe-sticky-table', '', [], '1.0' );

        wp_enqueue_style( 'wpe-sticky-table' );

        wp_add_inline_style( 'wpe-sticky-table', $this->style() );
    }

    private function style(): string {
        return <<<CSS
        /* Sticky headers for post/page list tables in wp-admin edit screens */
        .wp-admin .tablenav.top {
            position: sticky;
            top: 32px;
            z-index: 30;
            background: #f0f0f1;
            background-clip: padding-box;
        }

        .wp-admin .wp-list-table thead th,
        .wp-admin .wp-list-table thead td {
            position: sticky;
            top: calc(32px + 36px);
            z-index: 30;
            background: #fff;
            background-clip: padding-box;
        }

        .wp-list-table {
            border-collapse: separate;
        }

        /* WP admin bar is taller on smaller screens */
        @media screen and (max-width: 782px) {
            .wp-admin .tablenav.top {
                top: 50px;
            }

            .wp-admin .wp-list-table thead th,
            .wp-admin .wp-list-table thead td {
                top: calc(50px + 108px);
            }
        }
        CSS;
    }
}