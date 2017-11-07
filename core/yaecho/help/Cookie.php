<?php

namespace core\yaecho\help;

class Cookie
{
    /**
     * Cookie name - the name of the cookie.
     * @var string
     */
    private $name = '';
    
    /**
     * Cookie value
     * @var string
     */
    private $value = '';

    /**
     * Cookie life time
     * @var int
     */
    private $time = 0;

    /**
     * Cookie domain
     * @var string
     */
    private $domain = '';

    /**
     * Cookie path
     * @var string
     */
    private $path = '';

    /**
     * Cookie secure
     * @var bool
     */
    private $secure = false;

    /**
     * 只允许http协议
     * @var bool
     */
    private $httponly = false;

    /**
     * Create or Update cookie.
     */
    public function create() {
        if (empty($this->name)) {
            return false;
        }
        return setcookie($this->name, $this->value, $this->time, $this->path, $this->domain, $this->secure, $this->httponly);
    }

    /**
     * Return a cookie
     * @return mixed
     */
    public function get(){
        if (empty($this->name)) {
            return $_COOKIE;
        }
        return $_COOKIE[$this->name];
    }

    /**
     * Delete cookie.
     * @return bool
     */
    public function delete(){
        return setcookie($this->name, '', time() - 3600, $this->path, $this->domain, $this->secure, $this->httponly);
    }

    /**
     * @param $domain
     */
    public function setDomain($domain) {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @param $id
     */
    public function setName($id) {
        $this->name = $id;
        return $this;
    }

    /**
     * @param $path
     */
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }

    /**
     * @param $secure
     */
    public function setSecure($secure) {
        $this->secure = $secure;
        return $this;
    }

    /**
     * @param $time
     */
    public function setTime($time) {
        // Create a date
        $date = new \DateTime();
        // Modify it (+1hours; +1days; +20years; -2days etc)
        $date->modify($time);
        // Store the date in UNIX timestamp.
        $this->time = $date->getTimestamp();
        return $this;
    }

    /**
     * @param string $value
     */
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    /**
     * @param bool $httponly
     */
    public function setHttpOnly($httponly)
    {
        $this->httponly = $httponly;
        return $this;
    }
}