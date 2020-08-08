<?php
include "vendor/autoload.php";

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

$client = new \GuzzleHttp\Client(['timeout' => 5]);

$url='https://tiffany.cn/gifts/shop/valentines-day-gifts?omcid=dis-cn_sohu+news_op_2020cvd&utm_medium=display-cn&utm_source=sohu+news_op&utm_campaign=2020cvd';

$onRedirect = function(
    RequestInterface $request,
    ResponseInterface $response,
    UriInterface $uri
) {
    echo 'Redirecting! ' . $request->getUri() . ' to ' . $uri . "\n";
};

$conf = [
    'allow_redirects' => [
        'max'             => 10,        // allow at most 10 redirects.
        'strict'          => true,      // use "strict" RFC compliant redirects.
        'referer'         => true,      // add a Referer header
        'on_redirect'     => $onRedirect,
        'track_redirects' => true 
    ]
];

$start = microtime(true);
$response = $client->request('GET', $url, $conf);
$end = microtime(true);

$status = $response->getStatusCode();

$rc = $response->getHeaderLine('X-Guzzle-Redirect-Status-History');
$hops = $rc ? $rc.', '.$status : $status;


$format = 'request uri: '.$url. "\n";
$format .= 'hops: ['. $hops ."]\n";
$format .= 'request_time: '. round($end - $start, 2)."s\n";

echo $format;
