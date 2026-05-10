<?php
namespace WpAdminEnhancer\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once WAE_DIR_PATH . 'core/ModuleInterface.php';

class ModuleBootstrap {
	protected array $modules = [
        'MoveAddNewButton' => true,
        'StickyListTable' => true,
    ];

    protected string $namespace = '\\WpAdminEnhancer\\Modules\\';
    protected bool $booted = false;

    public function __construct() {
		add_action( 'admin_init', [ $this, 'load_modules' ] );
    }

    public function load_modules() {
        if ( $this->booted ) {
            return;
        }

        foreach ( $this->modules as $module => $enabled ) {

            if ( !$enabled ) {
				continue;
            }

			$file = WAE_DIR_PATH . "modules/{$module}.php";

            if ( file_exists( $file ) ) {
				require_once $file;
            }

			$class = $this->namespace . $module;

            if ( class_exists( $class ) ) {
				new $class();
            }
        }

        $this->booted = true;
    }
}
