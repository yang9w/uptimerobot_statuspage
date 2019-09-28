<?php

#Title of your page.
$title = "Foxtrot";
#Page description.
$description = "[Github Project] Status Page of Foxtrot.";
#Favicon link.
$favicon = "/img/favicon.png";

#UptimeRobot API.
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
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
  <link href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link href="https://bootswatch.com/yeti/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <script src="https://code.jquery.com/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>


</head>

<body>

  <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1>Status Page - <strong><?php echo htmlentities($title); ?></strong></h1>
        </div>
      </div>
      <div class="row clearfix">
          <div class="col-md-12 column">

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
                <div class="panel panel-warning">
                  <div class="panel-heading">
                    <h3 class="panel-title">
                      The API is not responding.
                      <small class="pull-right">It is a error, sorry!</small>
                    </h3>
                  </div>                
                </div>';
                exit();
              }
              else
              {
                if(is_null($offline))
                {
                  echo '
                  <div class="panel panel-success">
                    <div class="panel-heading">
                      <h3 class="panel-title">
                        The servers are working properly.
                        <small class="pull-right">Refreshing every minute.</small>
                      </h3>
                    </div>                
                  </div>';
                }
                else {
                echo '
                  <div class="panel panel-danger">
                    <div class="panel-heading">
                      <h3 class="panel-title">
                        Disruption of services.
                        <small class="pull-right">Refreshing every minute.</small>
                      </h3>
                    </div>                
                  </div>';
                }
              }
              ?>      

              <div class="row clearfix">
                  <div class="col-md-12 column">
                      <div class="list-group">

                          <?php
                          foreach($xml->monitor as $monitor) {
                          ?>
                          <div class="list-group-item">
                              <span class="badge"><?php echo htmlentities($monitor['all_time_uptime_ratio']); ?>%</span>
                              <h4 class="list-group-item-heading">
                                  <?php echo htmlentities($monitor['friendly_name']); ?>
                              </h4>
                              <p class="list-group-item-text">
                                <?php                               
                                if ($monitor['status'] == 2) {
                                  echo "<span class='label label-success'>Available</span>";
                                 }
                                elseif ($monitor['status'] == 9) {
                                  echo "<span class='label label-danger'>Unavailable</span>";
                                }
                                else {
                                  echo "<span class='label label-warning'>Information unavailable</span>";
                                }
                                ?>  
                              </p>
                          </div>  
                          <?php
                          }
                          ?>

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

</body>

</html>
