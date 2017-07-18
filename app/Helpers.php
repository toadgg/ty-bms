<?php
/**
 * Created by PhpStorm.
 * User: zhangxl
 * Date: 2017/6/12
 * Time: 下午3:25
 */

if (! function_exists('array_column_multi')) {
    function array_column_multi(array $input, array $column_keys)
    {
        $result = array();
        $column_keys = array_flip($column_keys);
        foreach ($input as $key => $el) {
            $result[$key] = array_intersect_key($el, $column_keys);
        }
        return $result;
    }
}

if (! function_exists('format_currency')) {
    function format_currency($input)
    {
        $prefix = "¥ ";
        $result = '';
        if (isset($input) && !empty($input)) {
            $result = $prefix . number_format($input * 1.0, 2);
        }
        return $result;
    }
}

if (! function_exists('format_decimal')) {
    function format_decimal($input, $suffix = '')
    {
        $result = '';
        if (isset($input) && !empty($input)) {
            $result = number_format($input * 1.0, 2).$suffix;
        }
        return $result;
    }
}


if (! function_exists('single_page_paginator')) {
    function single_page_paginator($items, $currentId) {
        $currentItem = $items->find($currentId);
        $queueableIds = $items->getQueueableIds();

        $currentItemIndex = array_search($currentId, $queueableIds);
        $hasPerItem = array_key_exists($currentItemIndex - 1, $queueableIds);
        $hasNextItem = array_key_exists($currentItemIndex + 1, $queueableIds);

        $perItem = $hasPerItem ? $items[$currentItemIndex-1] : null;
        $nextItem = $hasNextItem ? $items[$currentItemIndex+1] : null;

        $singlePagePaginator = compact('currentItem', 'perItem', 'nextItem', 'hasPerItem', 'hasNextItem');
        return $singlePagePaginator;
    }
}

if (! function_exists('file_type_is_image')) {
    function file_type_is_image($file) {
        $extensions = ['gif', 'jpg', 'jpeg', 'png', 'bmp', 'tif'];
        return in_array(pathinfo($file, PATHINFO_EXTENSION), $extensions);
    }
}