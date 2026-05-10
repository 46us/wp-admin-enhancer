<?php
namespace WpAdminEnhancer\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

interface ModuleInterface {

    /**
     * Whether this module should load.
     */
	public function should_load(): bool;
}