<?php

namespace osslibs\Curl;

class CurlHandler implements Curl
{
    private $resource;

    public function __construct($resource = null)
    {
        $this->resource = $resource ?? curl_init();
    }

    /**
     * @inheritDoc
     */
    public function resource()
    {
        return $this->resource;
    }

    /**
     * @inheritDoc
     */
    public function setopt($option, $value)
    {
        return curl_setopt($this->resource, $option, $value);
    }

    /**
     * @inheritDoc
     */
    public function setopt_array(array $options)
    {
        return curl_setopt_array($this->resource, $options);
    }

    /**
     * @inheritDoc
     */
    public function exec()
    {
        return curl_exec($this->resource);
    }

    /**
     * @inheritDoc
     */
    public function getinfo($opt = null)
    {
        return curl_getinfo($this->resource, $opt);
    }

    /**
     * @inheritDoc
     */
    public function error()
    {
        return curl_error($this->resource);
    }

    /**
     * @inheritDoc
     */
    public function errno()
    {
        return curl_errno($this->resource);
    }

    /**
     * @inheritDoc
     */
    public function escape($str)
    {
        return curl_escape($this->resource, $str);
    }

    /**
     * @inheritDoc
     */
    public function close()
    {
        curl_close($this->resource);
        $this->resource = null;
    }

    /**
     * @inheritDoc
     */
    public function pause($bitmask)
    {
        return curl_pause($this->resource, $bitmask);
    }

    /**
     * @inheritDoc
     */
    public function reset()
    {
        return curl_reset($this->resource);
    }
}
