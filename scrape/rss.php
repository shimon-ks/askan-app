<?php

function getRssTags(&$tags, $item) {
    foreach ($item->category as $category) {
        $tags[] = $category;
    }

}

function getRssAuthor(&$tags, $item) {
    if (isset($item->children('dc', true)->creator)){
        $tags[] =(string)$item->children('dc', true)->creator;
    }
}

function getRssContent($item) {
    if (isset($item->children('content', true)->encoded)) {
        return (string)$item->children('content', true)->encoded;
    }
    return null;
}