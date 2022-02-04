<?php

namespace osslibs\Curl;

interface Curl
{
    public function resource();

    public function setopt($option, $value);

    public function setopt_array(array $options);

    public function exec();

    public function getinfo($opt = null);

    public function error();

    public function errno();

    public function escape($str);

    public function close();

    public function pause($bitmask);

    public function reset();
}