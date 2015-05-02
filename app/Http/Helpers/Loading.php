<?php

namespace App\Http\Helpers;

/**
 * Generates styled loading icons
 */
class Loading
{

    /**
     * Show a dark-coloured loading icon
     * @static
     * @access public
     * @param  integer $size width and height, default 64
     * @return string        rendered HTML
     */
    public static function dark($size = 64)
    {
        return self::spinner('black', $size);
    }

    /**
     * Show a dark-coloured loading iconS
     * @static
     * @access public
     * @param  integer $size width and height, default 64
     * @return string        rendered HTML
     */
    public static function light($size = 64)
    {
        return self::spinner('white', $size);
    }

    /**
     * Create the spinner HTML
     * @access private
     * @static
     * @param  string  $style colour used for the fill
     * @param  integer $size  size to generate
     * @return string         rendered HTML
     */
    private static function spinner($style, $size)
    {
        return '<svg class="spinner" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="'.$size.'" height="'.$size.'" fill="'.$style.'">
                  <circle transform="translate(8 0)" cx="0" cy="16" r="0">
                    <animate attributeName="r" values="0; 4; 0; 0" dur="1.2s" repeatCount="indefinite" begin="0" keytimes="0;0.2;0.7;1" keySplines="0.2 0.2 0.4 0.8;0.2 0.6 0.4 0.8;0.2 0.6 0.4 0.8" calcMode="spline"/>
                  </circle>
                  <circle transform="translate(16 0)" cx="0" cy="16" r="0">
                    <animate attributeName="r" values="0; 4; 0; 0" dur="1.2s" repeatCount="indefinite" begin="0.3" keytimes="0;0.2;0.7;1" keySplines="0.2 0.2 0.4 0.8;0.2 0.6 0.4 0.8;0.2 0.6 0.4 0.8" calcMode="spline"/>
                  </circle>
                  <circle transform="translate(24 0)" cx="0" cy="16" r="0">
                    <animate attributeName="r" values="0; 4; 0; 0" dur="1.2s" repeatCount="indefinite" begin="0.6" keytimes="0;0.2;0.7;1" keySplines="0.2 0.2 0.4 0.8;0.2 0.6 0.4 0.8;0.2 0.6 0.4 0.8" calcMode="spline"/>
                  </circle>
                </svg>';
    }
}
