<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

if(!$user["userOk"])
{//AUTOMATIC LOGOUT
  logout();
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="ZXing for JS">

  <title>Scanner</title>
 
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="../MainCSS/scanner.css">
  <link rel="stylesheet" href="../MainCSS/header.css">
  <script src="../MainJs/header.js"></script>
</head>

<body>

  <div class="container main-container">

    <?php 
        include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/MainElements/header.php");
    ?>
      <div class="scanner-container">
        
        <div id="sourceSelectPanel">
          <p style="font-weight: bold;">Change video source:</p>
          <select class="custom-select" id="sourceSelect" style="max-width:400px">
          </select>
        </div>

        <div class="btn-container">
          <a class="btn" id="startButton">Scan</a>
          <a class="btn" id="resetButton">Stop</a>
        </div>

        <div>
          <video id="video" width="300" height="200" style="border: 1px solid gray"></video>
        </div>

        <label style="font-weight: bold; margin-top: 20px">Bar Code:</label>
        <div id="result"></div>
      </div>
  </div>

  <script type="text/javascript" src="https://unpkg.com/@zxing/library@latest"></script>
  <script type="text/javascript">
    window.addEventListener('load', function () {
      let selectedDeviceId;
      const codeReader = new ZXing.BrowserMultiFormatReader()
      console.log('ZXing code reader initialized')
      codeReader.getVideoInputDevices()
        .then((videoInputDevices) => {
          const sourceSelect = document.getElementById('sourceSelect')
          selectedDeviceId = videoInputDevices[0].deviceId
          if (videoInputDevices.length >= 1) {
            videoInputDevices.forEach((element) => {
              const sourceOption = document.createElement('option')
              sourceOption.text = element.label
              sourceOption.value = element.deviceId
              sourceSelect.appendChild(sourceOption)
            })

            sourceSelect.onchange = () => {
              selectedDeviceId = sourceSelect.value;
            };

            const sourceSelectPanel = document.getElementById('sourceSelectPanel')
            sourceSelectPanel.style.display = 'block'
          }

          document.getElementById('startButton').addEventListener('click', () => {
            codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', (result, err) => {
              if (result) {
                console.log(result.text);
                document.getElementById('result').textContent = result.text;
                window.location.replace("https://localhost/STEMA/MainPhp/searchRes.php?userInput="+result.text);
              }
              if (err && !(err instanceof ZXing.NotFoundException)) {
                console.error(err)
                document.getElementById('result').textContent = err
              }
            })
            console.log(`Started continous decode from camera with id ${selectedDeviceId}`)
          })

          document.getElementById('resetButton').addEventListener('click', () => {
            codeReader.reset()
            document.getElementById('result').textContent = '';
            console.log('Reset.')
          })

        })
        .catch((err) => {
          console.error(err)
        })
    })
  </script>

</body>

</html> 