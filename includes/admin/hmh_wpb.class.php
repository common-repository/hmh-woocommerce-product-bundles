<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('Hmh_Wpb_Admin_Class')) {
    /**
     * Hmh_Wpb_Admin_Class Class
     *
     * @since	1.0
     */
    class Hmh_Wpb_Admin_Class
    {

        /**
         * Constructor
         *
         * @return	void
         * @since	1.0
         */
        function __construct()
        {
            $this->init();
        }

        public function init()
        {

            if (is_admin()) {
                add_action('admin_enqueue_scripts', array($this, 'adminEnqueueScripts'));
            }

        }

        public function adminEnqueueScripts()
        {

        }

        public function enqueueScripts()
        {

        }

    }

    new Hmh_Wpb_Admin_Class();
}

