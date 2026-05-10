<?php
namespace WpAdminEnhancer\Modules;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use \WpAdminEnhancer\Core\ModuleInterface;

class MoveAddNewButton implements ModuleInterface {
    public function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
    }

    public function should_load(): bool {

        if ( ! is_admin() ) {
			return false;
        }

		global $pagenow;

        // Only on post list screen
        if ( $pagenow !== 'edit.php' ) {
            return false;
        }

        // Only for Pages and Posts
        $post_type = $_GET['post_type'] ?? 'post';

        return in_array($post_type, ['page', 'post']);
    }

    public function enqueue( $hook ): void {
        if ( ! $this->should_load() ) {
            return;
        }

        // Only load on post list screens
        if ( $hook !== 'edit.php' ) {
            return;
        }

		wp_register_script( 'move-add-new-btn', '', [ 'jquery' ], '1.0.0', true );

		wp_enqueue_script( 'move-add-new-btn' );

        wp_add_inline_script(
            'move-add-new-btn',
            $this->script()
        );

        // Style
		wp_register_style( 'move-add-new-btn', '', [], '1.0.0' );

		wp_enqueue_style( 'move-add-new-btn' );

		wp_add_inline_style(
			'move-add-new-btn',
			$this->style()
		);
    }

    private function script(): string {
        return <<<JS
    jQuery(function($) {

        const \$original = $('.wrap > .page-title-action');

        if (!\$original.length) return;

        // Prevent duplicate injection
        if ($('.cae-add-new-duplicate').length) return;

        const href = \$original.attr('href');
        const text = \$original.text();

        // Create duplicate button
        const \$duplicate = $('<a></a>', {
            href: href,
            text: text,
            class: 'button button-primary cae-add-new-duplicate',
            css: {
                marginRight: '8px'
            }
        });

        // Add to top tablenav (left side)
        const \$topNav = $('.tablenav.top');
        if (\$topNav.length) {
            \$topNav.prepend(\$duplicate.clone());
        }

        // Add to bottom tablenav (optional but recommended)
        const \$bottomNav = $('.tablenav.bottom');
        if (\$bottomNav.length) {
            \$bottomNav.prepend(\$duplicate.clone());
        }

    });
    JS;
    }

    private function style(): string {
		return '
        .cae-add-new-duplicate {
            float: left;
            margin-right: 12px;
        }
        ';
    }
}