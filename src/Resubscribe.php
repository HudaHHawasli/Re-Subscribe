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
    static public $footer_box_text = 'انقر هنا للاشتراك بالنشرة البريدية';
    static public $main_text = 'يرجى إدخال بريدك الإلكتروني للاشتراك بالنشرة البريدية';
    static public $main_title = 'النشرة البريدية';

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
            add_action('admin_menu', [$this, 'addDashboardPage']);
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
        $main_title = get_option('resubscribe-main-title', static::$main_title);
        $main_text = get_option('resubscribe-main-text', static::$main_text);
        $footer_box_text = get_option('resubscribe-footer-box-text', static::$footer_box_text);

        $content = <<<EEE
        <div class="remodal" data-remodal-id="modal" data-remodel-options="hashTracking: false">
            <h2>{$main_title}</h2>
            <p>{$main_text}</p>
            <input type="email" name="email" placeholder="Email address (e.g. example@company.com)" dir="ltr">
            <br>
            <a class="remodal-confirm" href="#">تسجيل</a>
            <a class="remodal-cancel" href="#">إلغاء</a>
        </div>
        <div id="resubscribe-footer-box">
            <div class="subscribe"><a href="#">{$footer_box_text}</a></div>
            <div class="close"><a href="#">X</a></div>
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

    public function addDashboardPage()
    {
        add_options_page( 'Re-Subscribe Options', 'Re-Subscribe Options', 'manage_options', 're-subscribe', function() {
            include_once 'inc/dashboard_menu.php';
        });
    }
}
