<?php
namespace WpAdminEnhancer\Modules;

use Elementor\Modules\GlobalClasses\Usage\Css_Class_Usage;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use \WpAdminEnhancer\Core\ModuleInterface;

class MoveAddNewButton implements ModuleInterface {
    public function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
    }

    public function should_load(string $hook = ''): bool {
        return is_admin() && $hook === 'edit.php' && in_array($_GET['post_type'] ?? 'post', ['page', 'post']);
    }

    public function enqueue( $hook ): void {
        if ( ! $this->should_load( $hook ) ) {
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
        if ($('.wae-add-new-duplicate').length) return;

        const href = \$original.attr('href');
        const text = \$original.text();

        // Create duplicate button
        const \$duplicate = $('<a></a>', {
            href: href,
            text: text,
            class: 'button button-primary wae-add-new-duplicate',
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
		return <<<CSS
        .wae-add-new-duplicate {
            float: left;
            margin-right: 12px;
        }
        CSS;
    }
}