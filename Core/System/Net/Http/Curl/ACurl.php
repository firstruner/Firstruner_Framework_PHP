<?php

/**
 * Copyright 2024-2026 Firstruner and Contributors
 * Firstruner is an Registered Trademark & Property of Christophe BOULAS
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Freemium License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@firstruner.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit, reproduce ou modify this file.
 * Please refer to https://firstruner.fr/ or contact Firstruner for more information.
 *
 * @author    Firstruner and Contributors <contact@firstruner.fr>
 * @copyright 2024-2026 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */



/** 
 * -- File description --
 * @Type : MethodClass
 * @Mode : XP/BDD Creation
 * @Author : Christophe BOULAS
 * @Update on : 21/01/2026 by : Christophe BOULAS
 */

namespace System\Net\Http\Curl;

use System\Net\Http\ICurl;

abstract class ACurl implements ICurl
{
    public $beforeSendCallback = null;
    public $afterSendCallback = null;
    public $successCallback = null;
    public $errorCallback = null;
    public $completeCallback = null;

    protected $options = [];
    protected $userSetOptions = [];

    abstract public function Close();
    abstract public function SetCookie($key, $value);
    abstract public function SetCookieFile($cookie_file);
    abstract public function SetCookieJar($cookie_jar);
    abstract public function SetCookieString($string);
    abstract public function SetCookies($cookies);
    abstract public function SetHeader($key, $value);
    abstract public function SetHeaders($headers);
    abstract public function SetJsonDecoder($mixed);
    abstract public function SetOption($option, $value);
    abstract public function SetOptions($options);
    abstract public function SetRetry($mixed);
    abstract public function SetUrl($url, $mixed_data = '');
    abstract public function SetXmlDecoder($mixed);
    abstract public function Stop();
    abstract public function unsetHeader($key);

    /**
     * Before Send
     *
     * @param $callback callable|null
     */
    public function BeforeSend($callback)
    {
        $this->beforeSendCallback = $callback;
    }

    /**
     * Complete
     *
     * @param $callback callable|null
     */
    public function Complete($callback)
    {
        $this->completeCallback = $callback;
    }

    /**
     * Disable Timeout
     */
    public function DisableTimeout()
    {
        $this->SetTimeout(null);
    }

    /**
     * Error
     *
     * @param $callback callable|null
     */
    public function Error($callback)
    {
        $this->errorCallback = $callback;
    }

    /**
     * Get Opt
     *
     * @param        $option
     * @return mixed
     */
    public function GetOption($option)
    {
        return $this->options[$option] ?? null;
    }

    /**
     * Remove Header
     *
     * Remove an internal header from the request.
     * Using `curl -H "Host:" ...' is equivalent to $curl->removeHeader('Host');.
     *
     * @param $key
     */
    public function RemoveHeader($key)
    {
        $this->SetHeader($key, '');
    }

    /**
     * Set auto referer
     *
     * @param mixed $auto_referer
     */
    public function SetAutoReferer($auto_referer = true)
    {
        $this->SetAutoReferrer($auto_referer);
    }

    /**
     * Set auto referrer
     *
     * @param mixed $auto_referrer
     */
    public function SetAutoReferrer($auto_referrer = true)
    {
        $this->SetOption(CURLOPT_AUTOREFERER, $auto_referrer);
    }

    /**
     * Set Basic Authentication
     *
     * @param $username
     * @param $password
     */
    public function SetBasicAuthentication($username, $password = '')
    {
        $this->SetOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $this->SetOption(CURLOPT_USERPWD, $username . ':' . $password);
    }

    /**
     * Set Connect Timeout
     *
     * @param $seconds
     */
    public function SetConnectTimeout($seconds)
    {
        $this->SetOption(CURLOPT_CONNECTTIMEOUT, $seconds);
    }

    /**
     * Set Digest Authentication
     *
     * @param $username
     * @param $password
     */
    public function SetDigestAuthentication($username, $password = '')
    {
        $this->SetOption(CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        $this->SetOption(CURLOPT_USERPWD, $username . ':' . $password);
    }

    /**
     * After Send
     *
     * This function is called after the request has been sent.
     *
     * It can be used to override whether or not the request errored. The
     * instance is passed as the first argument to the function and the instance
     * has attributes like $instance->httpStatusCode and $instance->response to
     * help decide if the request errored. Set $instance->error to true or false
     * within the function.
     *
     * When $instance->error is true indicating a request error, the error
     * callback set by Curl::error() is called. When $instance->error is false,
     * the success callback set by Curl::success() is called.
     *
     * @param $callback callable|null
     */
    public function AfterSend($callback)
    {
        $this->afterSendCallback = $callback;
    }

    /**
     * Set File
     *
     * @param $file
     */
    public function SetFile($file)
    {
        $this->SetOption(CURLOPT_FILE, $file);
    }

    protected function setFileInternal($file)
    {
        $this->SetInternalOption(CURLOPT_FILE, $file);
    }

    /**
     * Set follow location
     *
     * @param mixed $follow_location
     * @see    Curl::setMaximumRedirects()
     */
    public function SetFollowLocation($follow_location = true)
    {
        $this->SetOption(CURLOPT_FOLLOWLOCATION, $follow_location);
    }

    /**
     * Set forbid reuse
     *
     * @param mixed $forbid_reuse
     */
    public function SetForbidReuse($forbid_reuse = true)
    {
        $this->SetOption(CURLOPT_FORBID_REUSE, $forbid_reuse);
    }

    /**
     * Set Interface
     *
     * The name of the outgoing network interface to use.
     * This can be an interface name, an IP address or a host name.
     *
     * @param $interface
     */
    public function SetInterface($interface)
    {
        $this->SetOption(CURLOPT_INTERFACE, $interface);
    }

    /**
     * Set maximum redirects
     *
     * @param mixed $maximum_redirects
     * @see    Curl::setFollowLocation()
     */
    public function SetMaximumRedirects($maximum_redirects)
    {
        $this->SetOption(CURLOPT_MAXREDIRS, $maximum_redirects);
    }

    protected function SetInternalOption($option, $value) {}

    /**
     * Set Port
     *
     * @param $port
     */
    public function SetPort($port)
    {
        $this->SetOption(CURLOPT_PORT, (int) $port);
    }

    /**
     * Set Proxy
     *
     * Set an HTTP proxy to tunnel requests through.
     *
     * @param $proxy    - The HTTP proxy to tunnel requests through. May include port number.
     * @param $port     - The port number of the proxy to connect to. This port number can also be set in $proxy.
     * @param $username - The username to use for the connection to the proxy.
     * @param $password - The password to use for the connection to the proxy.
     */
    public function SetProxy($proxy, $port = null, $username = null, $password = null)
    {
        $this->SetOption(CURLOPT_PROXY, $proxy);
        if ($port !== null) {
            $this->SetOption(CURLOPT_PROXYPORT, $port);
        }
        if ($username !== null && $password !== null) {
            $this->SetOption(CURLOPT_PROXYUSERPWD, $username . ':' . $password);
        }
    }

    /**
     * Set Proxy Auth
     *
     * Set the HTTP authentication method(s) to use for the proxy connection.
     *
     * @param $auth
     */
    public function SetProxyAuth($auth)
    {
        $this->SetOption(CURLOPT_PROXYAUTH, $auth);
    }

    /**
     * Set Proxy Tunnel
     *
     * Set the proxy to tunnel through HTTP proxy.
     *
     * @param $tunnel boolean
     */
    public function SetProxyTunnel($tunnel = true)
    {
        $this->SetOption(CURLOPT_HTTPPROXYTUNNEL, $tunnel);
    }

    /**
     * Set Proxy Type
     *
     * Set the proxy protocol type.
     *
     * @param $type
     */
    public function SetProxyType($type)
    {
        $this->SetOption(CURLOPT_PROXYTYPE, $type);
    }

    /**
     * Set Range
     *
     * @param $range
     */
    public function SetRange($range)
    {
        $this->SetOption(CURLOPT_RANGE, $range);
    }

    protected function setRangeInternal($range)
    {
        $this->SetInternalOption(CURLOPT_RANGE, $range);
    }

    /**
     * Set Referer
     *
     * @param $referer
     */
    public function SetReferer($referer)
    {
        $this->SetReferrer($referer);
    }

    /**
     * Set Referrer
     *
     * @param $referrer
     */
    public function SetReferrer($referrer)
    {
        $this->SetOption(CURLOPT_REFERER, $referrer);
    }

    /**
     * Set Timeout
     *
     * @param $seconds
     */
    public function SetTimeout($seconds)
    {
        $this->SetOption(CURLOPT_TIMEOUT, $seconds);
    }

    protected function setTimeoutInternal($seconds)
    {
        $this->SetInternalOption(CURLOPT_TIMEOUT, $seconds);
    }

    /**
     * Set User Agent
     *
     * @param $user_agent
     */
    public function SetUserAgent($user_agent)
    {
        $this->SetOption(CURLOPT_USERAGENT, $user_agent);
    }

    protected function setUserAgentInternal($user_agent)
    {
        $this->SetInternalOption(CURLOPT_USERAGENT, $user_agent);
    }

    /**
     * Success
     *
     * @param $callback callable|null
     */
    public function Success($callback)
    {
        $this->successCallback = $callback;
    }

    /**
     * Unset Proxy
     *
     * Disable use of the proxy.
     */
    public function UnsetProxy()
    {
        $this->SetOption(CURLOPT_PROXY, null);
    }

    /**
     * Verbose
     *
     * @param bool            $on
     * @param resource|string $output
     */
    public function Verbose($on = true, $output = 'STDERR')
    {
        if ($output === 'STDERR') {
            if (defined('STDERR')) {
                $output = STDERR;
            } else {
                $output = fopen('php://stderr', 'wb');
            }
        }

        // Turn off CURLINFO_HEADER_OUT for verbose to work. This has the side
        // effect of causing Curl::requestHeaders to be empty.
        if ($on) {
            $this->SetOption(CURLINFO_HEADER_OUT, false);
        }
        $this->SetOption(CURLOPT_VERBOSE, $on);
        $this->SetOption(CURLOPT_STDERR, $output);
    }
}
