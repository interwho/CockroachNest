<?php
namespace HackTheNorth\CockroachNest\Controllers;

use HackTheNorth\CockroachNest\Entities\POSDevice;
use HackTheNorth\CockroachNest\Entities\Transaction;
use HackTheNorth\CockroachNest\Entities\ZipCode;
use HackTheNorth\CockroachNest\Repositories\POSDeviceRepository;
use HackTheNorth\CockroachNest\Repositories\TransactionRepository;
use HackTheNorth\CockroachNest\Repositories\ZipCodeRepository;
use HackTheNorth\CockroachNest\Services\DoctrineService;
use HackTheNorth\CockroachNest\Services\GeocodeService;

/**
 * Class DashboardController
 * @package HackTheNorth\CockroachNest\Controllers
 */
class DashboardController extends Controller
{
    /**
     * Returns the most recent transactions for the map dashboard
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function transactions()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token');

        $doctrine = new DoctrineService();

        /** @var Transaction[] $transactions */
        $transactions = $doctrine->getRepository('Transaction')->findBy(array(), array('dateAdded' => 'DESC'), 100);

        /** @var POSDeviceRepository $posDeviceRepository */
        $posDeviceRepository = $doctrine->getRepository('POSDevice');

        /** @var ZipCodeRepository $zipCodeRepository */
        $zipCodeRepository = $doctrine->getRepository('ZipCode');

        $featuresArray = array();
        foreach ($transactions as $transaction) {
            // Determine point type
            if ($transaction->getIsFraud()) {
                $name = 'FraudulentTransaction-' . $transaction->getFraudReason();
                $status = 'red';
            } else {
                $name = 'NormalTransaction';
                $status = 'green';
            }

            // Determine lat/lon
            $deviceId = $transaction->getDeviceId();
            $device = $posDeviceRepository->findOneBy(['id' => $deviceId]);
            if (empty($device)) {
                //Fraud
                continue;
            }
            $location = explode(", ", $device->getLocation());
            $county = $location[0];
            $state = $location[1];

            /** @var ZipCode[] $zipCodes */
            $zipCodes = $zipCodeRepository->findBy(['county' => $county, 'name' => $state]);
            if (empty($zipCodes)) {
                $newCounty = explode(" ", $county);
                array_pop($newCounty);
                $newCounty = implode(" ", $newCounty);
                $zipCodes = $zipCodeRepository->findBy(['county' => $newCounty, 'name' => $state]);
                if (empty($zipCodes)) {
                    continue;
                }
            }

            $zipCode = null;
            foreach ($zipCodes as $currZipCode) {
                if (!empty($currZipCode->getLatitude()) && !empty($currZipCode->getLongitude())) {
                    $zipCode = $currZipCode;
                    break;
                }
            }

            if (empty($zipCode)) {
                $zipCode = $zipCodes[0];
                $results = (new GeocodeService())->geocodeCounty($county, $state);
                if (empty($results)) {
                    continue;
                }
                $zipCode->setLatitude($results[0]);
                $zipCode->setLongitude($results[1]);
                $zipCodeRepository->save($zipCode);
            }

            $featuresArray[] = [
                "id" => $transaction->getId(),
                "type" => "Feature",
                "properties" => [
                    "Name" => $name,
                    "Status" => $status
                ],
                "geometry" => [
                    "type" => "Point",
                    "coordinates" => [
                        $zipCode->getLongitude(), $zipCode->getLatitude()
                    ]
                ]
            ];
        }

        $outputArray = [
            "type" => "FeatureCollection",
            "features" => $featuresArray
        ];

        return $this->createResponse(json_encode($outputArray));
    }

    /**
     * Returns transactions from region_name
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchTransactions()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token');

        $doctrine = new DoctrineService();

        /** @var TransactionRepository $transactionRepository */
        $transactionRepository = $doctrine->getRepository('Transaction');

        /** @var POSDeviceRepository $posDeviceRepository */
        $posDeviceRepository = $doctrine->getRepository('POSDevice');

        /** @var ZipCodeRepository $zipCodeRepository */
        $zipCodeRepository = $doctrine->getRepository('ZipCode');

        $regionName = $this->params['region_name'];

        $responseArray = [];

        /** @var ZipCode[] $zipCodes */
        $zipCodes = $zipCodeRepository->findBy(['name' => $regionName]);
        foreach ($zipCodes as $zipCode) {
            $location = $zipCode->getCounty() . ' County, ' . $zipCode->getName();

            /** @var POSDevice[] $posDevices */
            $posDevices = $posDeviceRepository->findBy(['location' => $location]);
            foreach ($posDevices as $posDevice) {

                /** @var Transaction[] $transactions */
                $transactions = $transactionRepository->findBy(['deviceId' => $posDevice->getId()]);

                foreach ($transactions as $transaction) {
                    $responseArray[] = [
                        'id' => $transaction->getId(),
                        'device' => [
                            'id' => $posDevice->getId(),
                            'location' => [
                                'zip' => $zipCode->getZip(),
                                'latitude' => $zipCode->getLatitude(),
                                'longitude' => $zipCode->getLongitude(),
                                'city' => $zipCode->getCity(),
                                'county' => $zipCode->getCounty(),
                                'name' => $zipCode->getName()
                            ],
                            'merchant_name' => $posDevice->getMerchantName()
                        ],
                        'transaction_value' => $transaction->getTransactionValue(),
                        'account_id' => $transaction->getAccountId(),
                        'ts_millis' => $transaction->getTsMillis(),
                        'is_fraud' => $transaction->getIsFraud(),
                        'fraud_reason' => $transaction->getFraudReason()
                    ];
                }
            }
        }

        return $this->createResponse(json_encode($responseArray));
    }

    /**
     * Returns a list of region names
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchZipCodes()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token');

        $doctrine = new DoctrineService();

        /** @var ZipCode[] $regionNames */
        $regionNames = $doctrine->getRepository('ZipCode')->findAll();

        $regionList = [];
        foreach ($regionNames as $region) {
            $regionList[] = $region->getName();
        }

        $uniqueList = array_unique($regionList);
        $finalList = [];
        foreach ($uniqueList as $unique) {
            $finalList[] = $unique;
        }

        return $this->createResponse(json_encode($finalList));
    }

    public function searchPosDevices()
    {
        return $this->createResponse("Not Implemented");
    }
}
