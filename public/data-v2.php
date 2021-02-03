<?php

require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

$validAreas = include dirname(__DIR__) . '/areas.php';

$area = null;
if (!empty($_GET['area'])) {
    $area = filter_var(urldecode($_GET['area']), FILTER_SANITIZE_STRING);

    if (!in_array($area, $validAreas)) {
        $area = null;
    }
}

$areaType = $area ? 'ltla' : 'nation';
$areaName = $area ? $area : 'scotland';

$queryString = "areaType={$areaType}&areaName={$areaName}";
$queryString .= '&metric=' . implode('&metric=', [
    'newCasesByPublishDate',
    'newAdmissions',
    'newDeaths28DaysByPublishDate',
]);
$queryString .= '&format=json';

$url = "https://api.coronavirus.data.gov.uk/v2/data?{$queryString}";

$fileName = 'v2-' . preg_replace('/[^a-z0-9]+/', '-', strtolower("{$areaType}-{$areaName}"));
$filePath = __DIR__ . "/../storage/{$fileName}.json";

if (file_exists($filePath) && (time() - filemtime($filePath)) < 21600) {
    header('X-Data-Cached: true');
    echo file_get_contents($filePath);
    exit;
}

try {
    $client = new GuzzleHttp\Client(['timeout' => 10]);
    $response = $client->request('GET', $url);
    $json     = (string) $response->getBody();

    if (!$json) {
        if (file_exists($filePath)) {
            // If empty response, send last data if it exists
            header('X-Data-Cached: true');
            echo file_get_contents($filePath);
            exit;
        }

        http_response_code(400);
        echo json_encode([
            'error' => 'Empty response',
        ]);
        exit;
    }

    file_put_contents($filePath, $json);

    header('X-Data-Cached: false');
    echo $json;
} catch (\Exception $e) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Error fetching data',
        'debug' => $e->getMessage(),
    ]);
}
