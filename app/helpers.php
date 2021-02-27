<?php

if (! function_exists('dealer_url')) {
    /**
     * Get admin url.
     *
     * @param string $path
     * @param mixed  $parameters
     * @param bool   $secure
     *
     * @return string
     */
    function dealer_url($path = '', $parameters = [], $secure = null)
    {
        if (url()->isValidUrl($path)) {
            return $path;
        }

        $secure = $secure ?: (config('admin.https') || config('admin.secure'));

        return url(admin_prefix_path($path, config('dealer.route.prefix')), $parameters, $secure);
    }
}

if (! function_exists('admin_prefix_path')) {
    /**
     * Get admin url.
     *
     * @param string $path URL主要路径
     * @param string $prefix 要组合的前缀
     * @return string
     */
    function admin_prefix_path($path = '', $prefix = '')
    {
        $prefix = '/'.trim($prefix ?: config('admin.route.prefix'), '/');

        $prefix = ($prefix == '/') ? '' : $prefix;

        $path = trim($path, '/');

        if (is_null($path) || strlen($path) == 0) {
            return $prefix ?: '/';
        }

        return $prefix.'/'.$path;
    }
}