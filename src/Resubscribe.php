<?php

/**
 * Re-subscribe wordpress plugin
 *
 * @category Wordpress-plugin
 * @package  Haykalmedia
 * @author   Ammar Alakkad <am.alakkad@gmail.com>
 * @license  MIT
 */
class Resubscribe
{
    protected $cookieKey = 'resubscribe-visited';

    /**
     * Constructor, enqueue necessary scripts/styles
     *
     * @return void
     */
    public function __construct()
    {
        // check if inside WordPress environment
        if (defined('ABSPATH')) {
            add_action('init', [$this, 'registerScripts']);
            add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        }
    }

    /**
     * Register plugin scripts
     *
     * @return void
     */
    public function registerScripts()
    {
        wp_register_script('remodal', plugins_url('../assets/jquery.remodal.min.js', __FILE__), ['jquery'], null, true);
        wp_register_style('remodal', plugins_url('../assets/jquery.remodal.css', __FILE__));
    }

    /**
     * Enqueue plugin scripts/styles
     *
     * @return void
     */
    public function enqueueScripts()
    {
        wp_enqueue_script('remodal');
        wp_enqueue_style('remodal');
    }
    /**
     * check if cookies with $cookieKey exists
     *
     * @return bool
     */
    public function checkCookies()
    {
        if (isset($_COOKIES[$this->cookieKey]) and $_COOKIES[$this->cookieKey] == true) {
            return true;
        }
        if (isset($_SESSION[$this->cookieKey]) and $_SESSION[$this->cookieKey] == true) {
            return true;
        }

        return false;
    }

    /**
     * set cookie with the $cookieKey
     *
     * @return void
     */
    public function setCookies()
    {
        $_COOKIES[$this->cookieKey] = true;
        $_SESSION[$this->cookieKey] = true;
    }

    /**
     * Wrapper for checkCookies()
     *
     * @return bool
     */
    public function canDisplay()
    {
        return $this->checkCookies();
    }
}
