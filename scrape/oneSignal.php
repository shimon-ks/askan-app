<?php

function sendPush($post_id) {
    require_once('vendor/autoload.php');

    $guzzleClient = new \GuzzleHttp\Client();
    
    $post = get_post($post_id);
    createSegment($post_id);
    //TODO: send push by segment
    //TODO: delete segment
}

function createSegment($post_id) {
    $requestBody = [
        'name' => 'segment-post-' . $post_id,
        'filters' => getFilters($post_id)
    ];

    $response = $client->request('POST', "https://onesignal.com/api/v1/apps/$app_id/segments", [ //TODO: save app id
    'body' => json_encode($requestBody),
    'headers' => [
        'Authorization' => 'Basic ' . $api_key, //TODO: save api key
        'Content-Type' => 'application/json; charset=utf-8',
        'accept' => 'application/json',
    ],
    ]);

    echo $response->getBody();
}

function getFilters($post_id) {
    $post_tags = wp_get_post_terms($post_id, 'post_tag');

    $filters = array();
    if (!empty($post_tags)) {
        foreach($post_tags as $tag) {
            $filters[] = [
                "field" => "tag",
                "relation" => "=",
                "key" => $tag,
                "value" => $tag
            ];
            $filters[] = [
                "operator" => "OR"
            ];
        }
    }
    return $filters;
}
 