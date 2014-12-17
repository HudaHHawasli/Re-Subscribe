<?php

class ResubscribeModel
{
    public $table;
    protected static $table_name = 'resubscribe_emails';
    protected $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $wpdb->prefix.static::$table_name;
    }

    public static function activation()
    {
        $charset_collate = '';
        global $wpdb;

        if (!empty($wpdb->charset)) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }

        if (!empty($wpdb->collate)) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }

        $table = $wpdb->prefix.static::$table_name;
        $query = "CREATE TABLE {$table} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            email varchar(120) NOT NULL,
            created_at timestamp DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  id (id),
            UNIQUE KEY email_unique_key(email)
        ) {$charset_collate};";

        require_once ABSPATH.'wp-admin/includes/upgrade.php';
        dbDelta($query);
    }

    public function addEmail($email)
    {
        if (is_email($email)) {
            $sql = "INSERT IGNORE INTO {$this->table} (email) VALUES ('%s');";
            $query = $this->wpdb->prepare($sql, $email);

            return $this->query($query);
        }

        return false;
    }

    public function query($query)
    {
        return $this->wpdb->query($query);
    }

    public function insert($data = [], $table = null)
    {
        if (! is_array($data) or ! count($data)) {
            throw new Exception('Invalid $data argument, it must be not empty associative array.');
        }
        $table = !empty($table) ? $table : $this->table;

        $this->wpdb->insert($table, $data);

        return $this->wpdb->insert_id;
    }

    public function get($page = 1, $limit = 2)
    {
        $start = ($page - 1) * $limit;
        $sql = "SELECT * FROM {$this->table} LIMIT %d, %d";
        $query = $this->wpdb->prepare($sql, $start, $limit);

        return $this->wpdb->get_results($query);
    }
}
