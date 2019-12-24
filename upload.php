<?php

require_once 'vendor/autoload.php';

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
use MicrosoftAzure\Storage\Blob\Models\SetBlobPropertiesOptions;

// koneksi
$connectionString = "DefaultEndpointsProtocol=https;AccountName=cerdikiawan;AccountKey=C9/lDSvx0F6Cl3U2F2pwr+rUIVm6j36iZ/V0OKwbvkoXaz2Luj2wFYsbBqk+JT2BXw3Q6OCIp13jvQ44/Lro6g==";
$containerName = "wadah";
$blobClient = BlobRestProxy::createBlobService($connectionString);

// ambil data file
$namaFile = $_FILES['fileToUpload']['name'];
$namaSementara = $_FILES['fileToUpload']['tmp_name'];

$content = fopen($_FILES['fileToUpload']['tmp_name'], "r") or die("Unable to open file!");

// upload blob
$blobClient->createBlockBlob($containerName, $namaFile, $content);
