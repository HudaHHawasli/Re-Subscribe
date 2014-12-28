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
    protected $cookieExpirationDays = 30;
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
        wp_register_style('resubscribe', plugins_url('../assets/resubscribe.css', __FILE__));
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
                                                            'key'             => $this->cookieKey,
                                                            'expiration_days' => $this->cookieExpirationDays,
                                                            'domain'          => $this->getDomain(),
                                                            'ajaxurl'         => admin_url('admin-ajax.php'),
                                                            'action'          => $this->ajaxHandler
                                                         ]);
        wp_enqueue_script('resubscribe');
        wp_enqueue_style('remodal');
        wp_enqueue_style('resubscribe');
    }

    /**
     * set cookie with the $cookieKey
     *
     * @return void
     */
    public function setCookies()
    {
        // set expiration date after (x) days
        $expiration =  time() + (60 * 60 * 24 * $this->cookieExpirationDays);
        // set cookie with expiration date
        setcookie($this->cookieKey, true, $expiration, '/');
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
     * Append HTML to footer
     *
     * @return void
     */
    public function appendHtml()
    {
        $content = <<<EEE
        <div id="resubscribe-footer-box">
            <div class="subscribe"><a href="#">انقر هنا للاشتراك بالنشرة البريدية</a></div>
            <div class="close"><a href="#">X</a></div>
        </div>
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
            $this->setCookies();
        }

        die();
    }
}
