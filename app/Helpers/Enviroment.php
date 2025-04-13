<?php

if (!function_exists('env_config')) {
    function env_config($key, $default = null)
    {
        return config('environment.current.' . $key, $default);
    }
}