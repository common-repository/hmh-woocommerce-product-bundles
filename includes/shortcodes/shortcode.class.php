<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('Hmh_Wpb_Shortcode_Class')) {
    /**
     * Hmh_Wpb_Shortcode_Class Class
     *
     * @since	1.0
     */
    class Hmh_Wpb_Shortcode_Class
    {

        /**
         * Constructor  
         *
         * @return	void
         * @since	1.0
         */
        function __construct()
        {
            add_action('init', array($this, 'init'));
        }

        public function init()
        {

            add_shortcode(HMH_WPB_SHORTCODE, array($this, 'shortcode'));
            
            if (is_admin()) {
                add_action('admin_enqueue_scripts', array($this, 'adminEnqueueScripts'));
            }
            add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));

        }

        public function adminEnqueueScripts()
        {
			
        }

        public function enqueueScripts()
        {
            // wp_enqueue_style( 'fullpage', HMH_WPB_URL . '/assets/css/fullpage.css' );
            // wp_enqueue_style( 'bbfp-css', HMH_WPB_URL . '/assets/css/style.css' );
            // wp_enqueue_script( 'fullpage-js', HMH_WPB_URL . '/assets/js/fullpage.js', array( 'jquery' ), '1.0', true );
            // wp_enqueue_script( 'fullpage-front-js', HMH_WPB_URL . '/assets/js/script.js', array( 'jquery' ), '1.0', true );
            // wp_enqueue_script( 'fullpage-scrolloverflow-js', HMH_WPB_URL . '/assets/js/scrolloverflow.min.js', array( 'jquery' ), '1.0', true );
        }

        public function vc_shortcode()
        {
        }
        public function settings($attr = HMH_WPB_SHORTCODE)
        {
            return HMH_WPB_SHORTCODE;
        }

        public function shortcode($atts)
        {

            $atts = shortcode_atts(array(
                'id'    => '',
            ), $atts);

            $sections           = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'sections', true );
            $lockAnchors        = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'lockAnchors', true );
            $navigation         = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'navigation', true );
            $navigationPosition = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'navigationPosition', true );
            $navigation_Tooltips = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'navigationTooltips', true );
            $showActiveTooltip  = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'showActiveTooltip', true );
            $keyboardScrolling  = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'keyboardScrolling', true );
            $continuousVertical = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'continuousVertical', true );
            $loopbottom         = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'loop-bottom', true );
            $looptop            = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'loop-top', true );
            $responsiveWidth    = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'responsiveWidth', true );
            $responsiveHeight   = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'responsiveHeight', true );
            $touchSensitivity   = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'touchSensitivity', true );
            $scrollBar          = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'scrollBar', true );
            $autoScrolling      = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'autoScrolling', true);
            $scrollingSpeed     = get_post_meta( $atts['id'], HMH_WPB_PREFIX . 'scrollingSpeed', true);
            $color= array();
            $navigationTooltips = array();
            $archor= array();
            $archor_count=1;
            foreach ($sections as $key => $value) {
                $color[] = get_post_meta($value, '_Hmh_Wpb_background_color', false)[0];
                $navigationTooltips[] = get_post($value)->post_title;
                $archor[] = 'archor'.$archor_count++;
            }
            $config = array(
                'verticalCentered'      => true,
                'lockAnchors'           => $lockAnchors=='yes',
                'anchors'               => $archor,
                'menu' => '#menu',
                'sectionsColor'         => $color,
                'navigation'            => ($navigation=='yes'),
                'navigationPosition'    => $navigationPosition=='yes' ? 'left' : 'right',
                'navigationTooltips'    => $navigation_Tooltips=='yes' ? $navigationTooltips : '',
                'showActiveTooltip'     => $showActiveTooltip=='yes',
                'slidesNavigation'      => true,
                'slidesNavPosition'     => 'bottom',
                'touchSensitivity'      => $touchSensitivity,
                'normalScrollElementTouchThreshold' => 5,
                'bigSectionsDestination'=> null,
                'loopBottom'            => ($loopbottom=='yes'),
                'loopTop'               => ($looptop=='yes'),
                'responsiveWidth'       => $responsiveWidth,
                'responsiveHeight'      => $responsiveHeight,
                'keyboardScrolling'     => $keyboardScrolling,
                'continuousVertical'    => $continuousVertical=='yes',
                'scrollBar'             => $scrollBar=='yes',
                'autoScrolling'         => $autoScrolling=='yes',
                'scrollingSpeed'        => $scrollingSpeed,
                'scrollOverflow'        => true,
                'fitToSection'          => false,
                'licenseKey'=> '0E0B2727-E16C4012-B6FB1F35-A979257B',
            );
            ob_start();
                ?>
                <div class="bbfp-fullpage" id="bbfp-fullpage-<?php echo esc_attr($atts['id']); ?>" data-fullpage='<?php echo json_encode($config); ?>'>
                <?php
                $i=0;
                foreach ($sections as $key => $value) {
                    $post = get_post( $value ); 
                    $background_id = get_post_meta($value, '_Hmh_Wpb_background_image', false)[0];
                    $background_link = wp_get_attachment_image_src($background_id,'full')[0];
                    $custom_css = get_post_meta( $value , '_wpb_shortcodes_custom_css', true );
                    ?>
                    <div class="section" id="section<?php echo esc_attr($i); $i++;?>" style="background-image: url(<?php echo esc_attr($background_link);?>); ">
                    <div id="slide<?php echo esc_attr($i) ?>-1" class="slide">
                    <?php echo do_shortcode($post->post_content); ?>
                    <style>
                        <?php 
                            echo bb_esc_html($custom_css);
                        ?>
                    </style>
                    </div>
                    <?php
                    $child = get_post_meta($value, '_Hmh_Wpb_child_sections', false)[0];
                    if (is_array($child) || is_object($child))
                    {
                        $j=2;
                        foreach ($child as $key => $value) {
                            $background_id = get_post_meta($value, '_Hmh_Wpb_background_image', false)[0];
                            $background_link = wp_get_attachment_image_src($background_id,'full')[0];
                            ?>
                            <div id="slide'<?php echo esc_attr($i.'-'.$j++); ?>" class="slide" style="background-image: url(<?php echo esc_attr($background_link);?>); ;">
                            <?php
                            $child_post = get_post( $value ); 
                            echo do_shortcode($child_post->post_content);
                            ?> 
                            </div>
                    <?php
                        }
                        
                    }
                    ?> </div> 
                <?php
                }
                ?>
                </div>
               
                <?php
            $shortcode_content = ob_get_contents(); 
            ob_end_clean();
            return $shortcode_content;
    
        }

    }

    new Hmh_Wpb_Shortcode_Class();
}

