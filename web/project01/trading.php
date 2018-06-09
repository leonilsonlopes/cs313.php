<?php include 'header.php';?>
	<!-- START CUSTOM HEAD AREA -->
	
	<!-- CLOSE CUSTOM HEAD AREA -->
	<?php include 'bodyOpen.php';?>
	<?php include 'navbar.php';?>
	<?php include 'breadCrumbs.php';?>

	<!-- START CONTENT AREA -->
	  
		<div class="container">
			<h2>Trading Area</h2>
			<div class="panel panel-default">
				<div class="panel-body">Manage your wallet and record your Buy and Sell orders here</div>
			</div>
		</div>
		<br/>
		<hr class="style18">
		<p><h3><b>Your wallet:</b></h3></p>
			<table id="currencies" class="table table-hover table-striped table-bordered" style="width:100%">
				<thead>
					<tr>	
					<th scope="col">ID</th>
					<th scope="col">Coin Code</th>
					<th scope="col">Coin Name</th>
					<th scope="col">USD Price</th>
					<th scope="col">Quantity</th>
					<th scope="col">Total</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		
		<hr class="style18">
		
		<form action="trading.php" method="post">
			<div class="btn-group">	
				<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Select a Coin
				</button>		
				<div class="dropdown-menu">
				
					<?php
						foreach (getListOfCurrencies() as $row){	
							echo '<div class="dropdown-divider"></div><a class="dropdown-item" onclick="document.getElementById(\'selectedCoin\').value=\'' . $row['code'] . '\';javascript:$(\'form\').submit();">' . $row['code'] . ' - ' . $row['name'] .'</a>';						
						}
					?>			
				
				</div>
			</div>	
			<input type="hidden" id="selectedCoin" name="selectedCoin"/>
					
				<div class="input-group mb-3">
					<div class="input-group-prepend">
					<span class="input-group-text" id="inputGroup-sizing-default">Enter quantity:</span>
					</div>
					<input type="text" name="qtty" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
					<?php echo "<button type=\"submit\" class=\"btn btn-success\" name=\"btnBuyCoin\" value=\"" . $selectedCoin . "\">Buy <b>" .  $selectedCoin . "</b> Coin</button>"?>
				</div>			
			

		
			<?php 
		
			if($_POST['btnBuyCoin'] != ""){				
				
				$quantity = floatval($_POST['qtty']);
				$coinInfo = getCoinInfoRaw($_POST['btnBuyCoin']);				
				$lastPrice = floatval($coinInfo['price_usd']);
				

				if($quantity > 0){
					$total = $quantity * $lastPrice;
					saveBuyOrder($_POST['btnBuyCoin'], $lastPrice, $quantity, $total);
					showAlert(" purchase successfully recorded.", $quantity . " " . $_POST['btnBuyCoin'], "success");		
				}else{
					showAlert("cannot be empty or 0", "Quantity ", "danger");
				}			
			}
							
		?>
			
		
		
		<br/>
		<br/>
		<p><h3><b>Selected coin price:</b></h3></p>
		<table id="currencies" class="table table-hover table-striped table-bordered" style="width:100%">
			<thead>
				<tr>				
				<th scope="col">Coin Code</th>
				<th scope="col">Coin Name</th>
				<th scope="col">USD Price</th>
				<th scope="col">Last Hour Variation</th>
				<th scope="col">Last 24hs Variation</th>
				<th scope="col">Last 7 days Variation</th>
				<th scope="col">Last Update</th>
				</tr>
			</thead>
			<tbody>

		<!-- Retrieve Data	-->
		<?php 	
			$selectedCoin = strtoupper($_POST['selectedCoin']);
			if($selectedCoin != ""){							
				foreach (getListOfCurrencies() as $row){				
					if(strtoupper($row['code']) == $selectedCoin ){
						$coinData = getCoinInfoFormat($selectedCoin);
						echo '<tr>
						<th scope=\"row\">' . $row['code'] . '</th>				
						<td>' . $row['name'] . '</td>
						<td>' . $coinData['price_usd'] . '</td>
						<td>' . $coinData['percent_change_1h'] . '</td>
						<td>' . $coinData['percent_change_24h'] . '</td>
						<td>' . $coinData['percent_change_7d'] . '</td>
						<td>' . $coinData['last_updated'] . '</td>
						</tr>';
						break;
					}
				}
			}
			echo "</tbody></table>";
			
			
			
				
		?> 
		
		<hr class="style18">
	

	</form>
	
	<!-- CLOSE CONTENT AREA -->	
	
	</div>	
	
	<?php include 'upperFooter.php';?>
	
	</div>

</body>
</html>
