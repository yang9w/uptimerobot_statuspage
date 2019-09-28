<?php

#Title of your page.
$title = "Foxtrot";
#Page description.
$description = "[Github Project] Status Page of Foxtrot.";
#Favicon link.
$favicon = "/img/favicon.png";

#Main API Key of UptimeRobot.
$api = "xxxx";

?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <!-- Responsive -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <!-- Page title -->
  <title>Status Page - <?php echo htmlentities($title); ?></title>
  <!-- Page description -->
  <meta name="description" content="<?php echo htmlentities($description); ?>" />
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?php echo htmlentities($favicon); ?>" />
  <!-- Reload the page every minute -->
  <meta http-equiv="refresh" content="60">
  <!-- BootstrapCDN -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <!-- FontAwesome -->
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

</head>

  <body>

    <div class="container">
      <h1 class="mt-5 font-weight-light">Status Page - <strong><?php echo htmlentities($title); ?></strong></h1>
      <p class="lead text-50">The status of servers is updated every minute.</p>

      <?php 
      #API Request.            
      $curl = curl_init();
      curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.uptimerobot.com/v2/getMonitors",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 5,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "api_key=".$api."&format=xml&logs=1&all_time_uptime_ratio=1",
      CURLOPT_HTTPHEADER => array("cache-control: no-cache", "content-type: application/x-www-form-urlencoded"),));
      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      if ($err) {} 
      else {
        $xml = New SimpleXMLElement($response);
        foreach($xml->monitor as $monitor) {
          if ($monitor['status'] == 9) {
            $offline = $offline + 1;
          }
        }
      }

      #Display the global status.
      if ($err) {
        echo '
        <div class="alert alert-warning" role="alert">
          <i class="fa fa-exclamation-triangle"></i> The API is not responding.
        </div>';
        exit();
      }
      else
      {
        if(is_null($offline))
        {
          echo '
          <div class="alert alert-success" role="alert">
            <i class="fa fa-check"></i> The servers are working properly.
          </div>';
          }
        else {
          echo '
          <div class="alert alert-danger" role="alert">
            <i class="fa fa-check"></i> Disruption of services.
          </div>';
        }
      }
      ?> 

        <div class="row">
          <div class="col-sm-12">
            <div class="card" >
              <ul class="list-group list-group-flush">

                <?php
                foreach($xml->monitor as $monitor) {
                ?>

                <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo htmlentities($monitor['friendly_name']); ?>
                <?php                               
                if ($monitor['status'] == 2) {
                  echo "<span class='badge badge-success'>Available</span>";
                }
                elseif ($monitor['status'] == 9) {
                  echo "<span class='badge badge-danger'>Unavailable</span>";
                }
                else {
                  echo "<span class='badge badge-warning'>Information unavailable</span>";
                }
                ?>
                </li>

                <?php
                }
                ?>

              </ul>
          </div>
          </div>
      </div>

    </div>

    <!-- BootstrapCDN -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  </body>

</html>
