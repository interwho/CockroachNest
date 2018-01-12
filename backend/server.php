<?php
ini_set('default_socket_timeout', 1);
$NODE_ADDR = "http://localhost:8080";
$PAGERDUTY_SERVICE_KEY = 'ENTER_YOURS_HERE';

// URLs
$status_url = $NODE_ADDR . "/_status/nodes";
$problem_url = $NODE_ADDR . "/_status/problemranges?node_id=";

// Get Page Data
$status = json_decode(file_get_contents($status_url))->nodes;

// Compile Page Data
$result = [];
foreach ($status as $node) {
    // Get ID of each node
    $id = $node->desc->nodeId;

    // Get IP of each node
    $ip = explode(":", $node->desc->address->addressField)[0];

    // Geocode IP address
    $loc = explode(",", json_decode(file_get_contents("http://ipinfo.io/" . $ip . "/json"))->loc);

    // Get current node errors and append to array node
    $status = (json_decode(file_get_contents($problem_url . $id))->problemsByNodeId->{$id}->errorMessage == "") ? "green" : "red";

    $result[] = [
        "id" => rand(1,100000),
        "type" => "Feature",
        "properties" => [
            "Name" => "CockroachDB Node $id",
            "Status" => $status
        ],
        "geometry" => [
            "type" => "Point",
            "coordinates" => [
                $loc[1], $loc[0]
            ]
        ]
    ];

    // Send PagerDuty Alert
    if ($status == "red") {
        $url = 'https://events.pagerduty.com/generic/2010-04-15/create_event.json';
        $data = [
            'service_key' => $PAGERDUTY_SERVICE_KEY,
            'event_type' => 'trigger',
            'description' => 'Cockroach DB node ' . $id . ' is not responding',
            'incident_key' => $id,
            "contexts" => []
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
}

$final = [
    "type" => "FeatureCollection",
    "features" => $result
];

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token');

echo json_encode($final);
