<!DOCTYPE html>
<html>

<head>
    <title>Computer Vision</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>

<body>
    <h3>Analisa Gambar Dengan Computer Vision</h3>



    <form action="index.php" method="post" enctype="multipart/form-data">
        <p>Pilih gambar yang dianalisa: </p>
        <input type="file" name="fileToUpload" id="fileToUpload"> <br><br>
        <input class="waves-effect waves-light btn" type="submit" value="Upload Image" name="submit"> <br>
    </form>
    <br><br>
    <img id="gambar" width="430" onchange="processImage()">
    <p id="deskripsi"></p>

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

    if (isset($_POST["submit"])) {
        $content = fopen($_FILES['fileToUpload']['tmp_name'], "r") or die("Unable to open file!");
        // upload blob
        $blobClient->createBlockBlob($containerName, $namaFile, $content);
    }

    ?>

    <script type="text/javascript">
        // Display the image.
        // var sourceImageUrl = document.getElementById("inputImage").value;
        // document.querySelector("#sourceImage").src = sourceImageUrl;
        // var urlSumberGambar = "https://cerdikiawan.blob.core.windows.net/wadah/" + "<?php echo $nama; ?>"
        var sourceImageUrl = "https://cerdikiawan.blob.core.windows.net/wadah/" + "<?php echo $namaFile; ?>";
        document.querySelector("#gambar").src = "https://cerdikiawan.blob.core.windows.net/wadah/" + "<?php echo $namaFile; ?>";



        // **********************************************
        // *** Update or verify the following values. ***
        // **********************************************

        // Replace <Subscription Key> with your valid subscription key.
        var subscriptionKey = "92a29ed86345438c8c17390f7617688a";

        // You must use the same Azure region in your REST API method as you used to
        // get your subscription keys. For example, if you got your subscription keys
        // from the West US region, replace "westcentralus" in the URL
        // below with "westus".
        //
        // Free trial subscription keys are generated in the "westus" region.
        // If you use a free trial subscription key, you shouldn't need to change
        // this region.
        var uriBase =
            "https://sipintar.cognitiveservices.azure.com/vision/v2.0/analyze";

        // Request parameters.
        var params = {
            "visualFeatures": "Categories,Description,Color",
            "details": "",
            "language": "en",
        };



        // Make the REST API call.
        $.ajax({
                url: uriBase + "?" + $.param(params),

                // Request headers.
                beforeSend: function(xhrObj) {
                    xhrObj.setRequestHeader("Content-Type", "application/json");
                    xhrObj.setRequestHeader(
                        "Ocp-Apim-Subscription-Key", subscriptionKey);
                },

                type: "POST",

                // Request body.
                data: '{"url": ' + '"' + "https://cerdikiawan.blob.core.windows.net/wadah/" + "<?php echo $namaFile; ?>" + '"}',
            })

            .done(function(data) {
                // Show formatted JSON on webpage.
                //var myData = JSON.parse(data);
                $("#deskripsi").html(JSON.stringify(data.description.captions[0].text));
                console.log(data);
                //document.getElementById("ini").innerHTML = myData.description.captions[0].text;
                //alert(typeof myData);
            })

            .fail(function(jqXHR, textStatus, errorThrown) {
                // Display error message.
                var errorString = (errorThrown === "") ? "Error. " :
                    errorThrown + " (" + jqXHR.status + "): ";
                errorString += (jqXHR.responseText === "") ? "" :
                    jQuery.parseJSON(jqXHR.responseText).message;
                alert(errorString);
            });
    </script>
</body>

</html>