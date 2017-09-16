<?php
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();

// Pages **Just in Case**
$collection->add(
    'PageController_home',
    new Route(
        '/',
        array(
            '_controller' => 'HackTheNorth\CockroachNest\Controllers\PageController::home'
        ),
        array(
            '_method' => 'GET'
        )
    )
);

$collection->add(
    'PageController_about',
    new Route(
        '/about',
        array(
            '_controller' => 'HackTheNorth\CockroachNest\Controllers\PageController::about'
        ),
        array(
            '_method' => 'GET'
        )
    )
);

$collection->add(
    'PageController_contact',
    new Route(
        '/contact',
        array(
            '_controller' => 'HackTheNorth\CockroachNest\Controllers\PageController::contact'
        ),
        array(
            '_method' => 'GET'
        )
    )
);

// DataStream API
$collection->add(
    'DataStreamController_transactions',
    new Route(
        '/api/datastream/transactions',
        array(
            '_controller' => 'HackTheNorth\CockroachNest\Controllers\DataStreamController::transactions'
        ),
        array(
            '_method' => 'POST'
        )
    )
);

$collection->add(
    'DataStreamController_posdevices',
    new Route(
        '/api/datastream/posdevices',
        array(
            '_controller' => 'HackTheNorth\CockroachNest\Controllers\DataStreamController::posDevices'
        ),
        array(
            '_method' => 'POST'
        )
    )
);

$collection->add(
    'DataStreamController_zipcodes',
    new Route(
        '/api/datastream/zipcodes',
        array(
            '_controller' => 'HackTheNorth\CockroachNest\Controllers\DataStreamController::zipCodes'
        ),
        array(
            '_method' => 'POST'
        )
    )
);

// Dashboard API
$collection->add(
    'DashboardController_transactions',
    new Route(
        '/api/dashboard/transactions',
        array(
            '_controller' => 'HackTheNorth\CockroachNest\Controllers\DashboardController::transactions'
        ),
        array(
            '_method' => 'GET'
        )
    )
);

$collection->add(
    'DashboardController_transactionsSearch',
    new Route(
        '/api/dashboard/transactions/search',
        array(
            '_controller' => 'HackTheNorth\CockroachNest\Controllers\DashboardController::searchTransactions'
        ),
        array(
            '_method' => 'GET'
        )
    )
);

$collection->add(
    'DashboardController_posDeviceSearch',
    new Route(
        '/api/dashboard/pos_devices/search',
        array(
            '_controller' => 'HackTheNorth\CockroachNest\Controllers\DashboardController::searchPosDevices'
        ),
        array(
            '_method' => 'GET'
        )
    )
);

$collection->add(
    'DashboardController_zipCodeSearch',
    new Route(
        '/api/dashboard/zip_codes/search',
        array(
            '_controller' => 'HackTheNorth\CockroachNest\Controllers\DashboardController::searchZipCodes'
        ),
        array(
            '_method' => 'GET'
        )
    )
);

$collection->add(
    'DashboardController_otransactionsSearch',
    new Route(
        '/api/dashboard/transactions/search',
        array(
            '_controller' => 'HackTheNorth\CockroachNest\Controllers\DashboardController::searchTransactions'
        ),
        array(
            '_method' => 'OPTIONS'
        )
    )
);

$collection->add(
    'DashboardController_oposDeviceSearch',
    new Route(
        '/api/dashboard/pos_devices/search',
        array(
            '_controller' => 'HackTheNorth\CockroachNest\Controllers\DashboardController::searchPosDevices'
        ),
        array(
            '_method' => 'OPTIONS'
        )
    )
);

$collection->add(
    'DashboardController_ozipCodeSearch',
    new Route(
        '/api/dashboard/zip_codes/search',
        array(
            '_controller' => 'HackTheNorth\CockroachNest\Controllers\DashboardController::searchZipCodes'
        ),
        array(
            '_method' => 'OPTIONS'
        )
    )
);

return $collection;
