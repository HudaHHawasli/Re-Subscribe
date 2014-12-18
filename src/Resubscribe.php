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
    protected $ajaxHandler = 'resubscribe_add_email';
    protected $model;

    /**
     * Constructor, enqueue necessary scripts/styles
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new ResubscribeModel();

        // check if inside WordPress environment
        if (defined('ABSPATH')) {
            add_action('init', [$this, 'registerScripts']);
            add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
            add_action('wp_footer', [$this, 'appendHtml']);
            add_action('wp_ajax_'.$this->ajaxHandler, [$this, 'ajaxHandlerCallback']);
        }
    }

    /**
     * Register plugin scripts
     *
     * @return void
     */
    public function registerScripts()
    {
        wp_register_script('remodal', plugins_url('../assets/jquery.remodal.js', __FILE__), ['jquery'], null, true);
        wp_register_style('remodal', plugins_url('../assets/jquery.remodal.css', __FILE__));
        wp_register_script('resubscribe', plugins_url('../assets/resubscribe.js', __FILE__), ['jquery', 'remodal'], null, true);
    }

    /**
     * Enqueue plugin scripts/styles
     *
     * @return void
     */
    public function enqueueScripts()
    {
        wp_localize_script('resubscribe', 'resubscribe', [
                                                            'key'     => $this->cookieKey,
                                                            'domain'  => $this->getDomain(),
                                                            'ajaxurl' => admin_url('admin-ajax.php'),
                                                            'action'  => $this->ajaxHandler
                                                         ]);
        wp_enqueue_script('resubscribe');
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
        // set the cookie to expire after 30 days
        setcookie($this->cookieKey, true, time() + 60*60*24*30, '/', $this->getDomain());
        $_SESSION[$this->cookieKey] = true;
    }

    /**
     * Return domain without any leading www. or http://
     *
     * @return string
     */
    private function getDomain()
    {
        return preg_replace('/(?:https?:\/\/)?(?:www\.)?(.*)/', '$1', get_home_url());
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

    /**
     * Append HTML to footer
     *
     * @return void
     */
    public function appendHtml()
    {
        $content = <<<EEE
        <div class="remodal" data-remodal-id="modal" data-remodel-options="hashTracking: false">
            <h2>النشرة البريدية</h2>
            <p>يرجى إدخال بريدك الإلكتروني للاشتراك بالنشرة البريدية</p>
            <input type="email" name="email" placeholder="Email address (e.g. example@company.com)" dir="ltr">
            <br>
            <a class="remodal-confirm" href="#">تسجيل</a>
            <a class="remodal-cancel" href="#">إلغاء</a>
        </div>
EEE;
        echo $content;
    }

    /**
     * Handle AJAX requests
     *
     * @return void
     */
    public function ajaxHandlerCallback()
    {
        $email = isset($_POST['email']) ? is_email($_POST['email']) : false;

        if ($email != false) {
            $this->model->addEmail($email);
        }

        die();
    }
}
