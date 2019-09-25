<?php

#Business name.
$title = "Foxtrot";
#Page description.
$description = "[Github Project] Status Page of Foxtrot.";
#Favicon link.
$favicon = "/img/favicon.png";

#UptimeRobot API.
$api = "xxxx";

?>
<!DOCTYPE html>
<html lang="fr">

<head>

  <meta charset="utf-8">
  <title>Status Page - <?php echo htmlentities($title); ?></title>
  <meta name="description" content="<?php echo htmlentities($description); ?>" />
  <link rel="icon" type="image/png" href="<?php echo htmlentities($favicon); ?>" />
  <link href="css/style.css" rel="stylesheet">

</head>

<body>

	<h1><span class="blue"></span>Status Page<span class="blue"> - </span> <span class="yellow"><?php echo htmlentities($title); ?></pan></h1>
	<h2>Data updated every 5 minutes. Monitoring by <strong>UptimeRobot</strong> system.</h2>

	<table class="container">
		<thead>
			<tr>
				<th><h1>Server</h1></th>
				<th><h1>Total Uptime</h1></th>
				<th><h1>Status</h1></th>
			</tr>
		</thead>
		<tbody>

			<?php
			#Request API.
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
  			CURLOPT_HTTPHEADER => array(
    			"cache-control: no-cache",
    			"content-type: application/x-www-form-urlencoded"
    			),
  			));
  			$response = curl_exec($curl);
  			$err = curl_error($curl);
  			curl_close($curl);
  			if (empty($err)) {}
  			$xml = New SimpleXMLElement($response);
  			foreach($xml->monitor as $monitor) {
  			?>

    			<tr>
				<td><?php echo htmlentities($monitor['friendly_name']); ?></td>
				<td><strong><?php echo htmlentities($monitor['all_time_uptime_ratio']); ?></strong> %</td>
				<td>
				<?php                               
                    		if ($monitor['status'] == 2) {
                    		echo "<b style=\"color:green;\">✔️ Online</b>";
                    		}
                    		elseif ($monitor['status'] == 9) {
                    		echo "<b style=\"color:red;\">😔 Offline</b>";
                    		}
                    		else {
                    		echo "Not Available";
                    		}
                    		?>	
                		</td>
			</tr>

		<?php
            	}
            	?>

		</tbody>
	</table>

</body>

</html>
