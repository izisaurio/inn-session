<?php

namespace Inn\Session;

use ArrayAccess;

class Session implements ArrayAccess
{
    /**
     * Session default cookie values
     * 
     * @access  protected
     * @var     array
     */
    protected $defaultOptions = [
        //Limitless by default
        'lifetime' => 0,
        //Cookie path root by default
        'path' => '/',
        //Domain none by default
        'domain' => '',
        //Secure on by default
        'secure' => true,
        //Http only by default
        'httponly' => true
    ];

    /**
     * Instance to use as singleton
     * 
     * @static
     * @access  private
     * @var     Session
     */
    private static $instance;

    /**
     * Construct
     * 
     * Initizializes session
     * 
     * @param   array   $options    Custom session cookie options
     */
    public function __construct(array $options = [])
    {
        $this->defaultOptions = array_merge($this->defaultOptions, $options);
        if (isset($this->defaultOptions['id'])) {
            session_id($this->defaultOptions['id']);
            unset($this->defaultOptions['id']);
        }
        session_set_cookie_params($this->defaultOptions);
        session_start();
    }

    /**
     * Destructor
     *
     * Close session
     *
     * @access	public
     */
    public function __destruct()
    {
        session_write_close();
    }

    /**
     * Gets default instance, custom options are only used on first call
     * 
     * @static
     * @access  public
     * @param   array   $options    Custom session cookie options plus the session id if needed
     * @return  Session
     */
    public static function instance(array $options = [])
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($options);
        }
        return self::$instance;
    }

    /**
     * Session is started
     * 
     * @access  public
     * @return  bool
     */
    public function isStarted()
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Destroys all session data
     * 
     * @access  public
     */
    public function destroy()
    {
        session_unset();
        session_destroy();
    }

    /**
     * Gets current session ID
     * 
     * @access  public
     * @return  string
     */
    public function id() {
        return session_id();
    }

    /**
     * Set a value to session
     * 
     * @access  public
     * @param   string|array   $key
     * @param   mixed           $value
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $_SESSION[$k] = $v;
            }
            return;
        }
        $_SESSION[$key] = $value;
    }

    /**
     * Same as above but for array access
     * 
     * @access  public
     * @param   string  $offset
     * @param   mixed   $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * Checks if a session key exists
     * 
     * @access  public
     * @param   string  $key
     * @return  bool
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Same as above but for array access
     * 
     * @access  public
     * @param   string  $offset
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * Get a value from session, has an option of a default value if none found
     * 
     * @access  public
     * @param   string  $key
     * @param   mixed   $default
     * @return  mixed
     */
    public function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    /**
     * Same as above but for array access
     * 
     * @access  public
     * @param   string  $offset
     */
    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * Removes a key from session
     * 
     * @access  public
     * @param   string  $key
     */
    public function unset($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Same as above but for array access
     * 
     * @access  public
     * @param   string  $offset
     */
    public function offsetUnset($offset): void
    {
        $this->unset($offset);
    }

    /**
     * Gets a session key and then removes it
     * 
     * @access  public
     * @param   string  $key
     * @param   mixed   $default
     * @return  mixed
     */
    public function flash($key, $default = null)
    {
        $value = $this->get($key, $default);
        $this->unset($key);
        return $value;
    }
}

