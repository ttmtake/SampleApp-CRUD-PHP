<?php

require_once('../v3-php-sdk-2.4.1/config.php');

require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');
require_once(PATH_SDK_ROOT . 'Utility/Configuration/ConfigurationManager.php');
require_once('helper/VendorHelper.php'); 

//Specify QBO or QBD
$serviceType = IntuitServicesType::QBO;

// Get App Config
$realmId = ConfigurationManager::AppSettings('RealmID');
if (!$realmId)
	exit("Please add realm to App.Config before running this sample.\n");

// Prep Service Context
$requestValidator = new OAuthRequestValidator(ConfigurationManager::AppSettings('AccessToken'),
                                              ConfigurationManager::AppSettings('AccessTokenSecret'),
                                              ConfigurationManager::AppSettings('ConsumerKey'),
                                              ConfigurationManager::AppSettings('ConsumerSecret'));
$serviceContext = new ServiceContext($realmId, $serviceType, $requestValidator);
if (!$serviceContext)
	exit("Problem while initializing ServiceContext.\n");

// Prep Data Services
$dataService = new DataService($serviceContext);
if (!$dataService)
	exit("Problem while initializing DataService.\n");

// Add a vendor
$addVendor = $dataService->Add(VendorHelper::getVendorFields($dataService));
echo "Vendor created :::  Id={$addVendor->Id} :::: active flag={$addVendor->Active}\n";

$addVendor->Active = 'false';

//Name-list resources can only be soft deleted meaning, marked as inactive
$deletedVendor = $dataService->Update($addVendor);
echo "Vendor deleted :::  Id={$deletedVendor->Id} :::: active flag={$deletedVendor->Active}\n";

?>
