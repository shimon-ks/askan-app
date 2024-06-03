<?php

function getCrawlerTags (&$tags, $tagClass, $crawler) {
    $crawler->filter($tagClass)->each(function ($node) use (&$tags) {
        $tags[] = $node->text();
    });
}

function getCrawlerAuthor(&$tags, $authorClass, $crawler) {
    $author = $crawler->filter($authorClass)->first();
    if ($author->count() > 0) {
        $tags[] = $author->text();
    }
}

function getCrawlerContent($contentClass, $crawler) {
    $contentArray = $crawler->filter($contentClass)->each(function ($node) use (&$content) {

        // if ($imgNodes->count() > 0) {
        //     $imgNodes->each(function ($imgNode) {
        //         $imgNode->getNode(0)->parentNode->removeChild($imgNode->getNode(0));
        //     });
        // }

        $content = $content . "\n\n" . $node->text();
    });
    
    // $content = implode("\n", $contentArray);
    return $content;
}

function getImages(&$images, $imageClass, $crawler, $attr) {
    $crawler->filter($imageClass)->each(function ($node) use (&$images, $attr) {
        $images[] = $node->attr($attr);
    });
}