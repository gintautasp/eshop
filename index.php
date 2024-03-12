<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title> labas iš php</title>
	<meta name="description" content="sportas ir pramogoms, be kokiu oru, gėlės, sveikinimai ir puokštės visoms progoms">
	<meta name="keywords" content="sportas, pramogos, orai, gėlės, sveikinimai, puokštės">
	<style>
		#prekes {
			clear: both;
		}
		.preke {
			display: inline-block;
			margin: 13px;
			padding: 12px;
			border: 1px solid grey;
			height: 400px;
			width: 300px;
			-webkit-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
			-moz-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
			box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);	
		}
		#i_krepseli {
			padding: 10px;
			margin: 10px;
			border: 1px solid yellow;
			float: right;
		}
		#paieska {
			padding: 10px;
			margin: 10px;
			border: 1px solid yellow;
			float: left;		
		}
	</style>	
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script>
		
		pirkiniai = [];
		
		$( document ).ready ( function() {
		
			function setCookie(cname, cvalue, exdays) {
				const d = new Date();
				d.setTime(d.getTime() + (exdays*24*60*60*1000));
				let expires = "expires="+ d.toUTCString();
				document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
			}
		
			$( '#krepselis' ).hide();

			$( '.to_cart' ).click ( function() {
			
				kiekis = prompt( "Norodykite kiekį", "1" );
				
				kiek = parseInt ( kiekis );
			
				if ( isNaN ( kiek) ) {
				
					text = "Neteisingas kiekis";
					
				} else {
				
					pirkiniai.push ( { pav: $( this ).data ( 'pav' ), nr: $( this ).data ( 'prekes_nr' ), kiekis: kiek, kaina: $( this ).data ( 'kaina' ) } );
					$( '#sk_prekiu' ).html( pirkiniai.length ); 
					text = "Prekė įtraukta į krepšelį";
				}
			});
			
			$( '#to_shop' ).click ( function() {
	
				$( '#krepselis' ).hide();	
				$( '#lst_prekes' ).show();
			});
			
			$( '#to_cart' ).click ( function() {
			
				$( '#lst_prekes' ).hide();
				
				suma_viso = 0;

				cart = '<table>';
				cart += '<tr><th>Prekė</th><th>Kiekis</th><th>Kaina</th><th>Suma</th></tr>';
				
				suma_viso = 0;
				
				for ( i = 0;  i<pirkiniai.length; i++ ) {
				
					kiekis = parseInt ( pirkiniai [ i  ].kiekis );
					kaina = parseFloat ( pirkiniai [ i  ].kaina );
					
					suma = kiekis * kaina;
					suma_viso += suma;
				
					cart += '<tr><td>' + pirkiniai [ i  ].pav + '</td><td>'  + pirkiniai [  i  ].kiekis +  '</td><td>'  + pirkiniai [  i  ].kaina +  '</td><td>' + suma + '</tr>';
				}
				
				cart += '<tr><th colspan="3">Suma viso</th><th>' + suma_viso + '</th>'
				cart += '</table>';
				
				$( '#krepselio_prekes' ).html( cart );
				$( '#krepselis' ).show();
				
				$( '#uzsakyti' ).click ( function() {
				
					setCookie ( 'krepselis', JSON.stringify ( pirkiniai ), 1 );
					window.location.href = 'uzsakymas.php';
				});
			});
		});
	</script>
</head>
<body>
	<div id="lst_prekes">
	<div id="i_krepseli">
	Krepšelyje <span id="sk_prekiu">0</span> prekių<br>
	<input type="button" id="to_cart" value="Į krepšelį">	
	</div>
	<div id="paieska">
	<form method="post" action="">
		<label for="kaina_nuo">Kaina nuo:</label>
		<input type="text" name="kaina_nuo" value="">
		<label for="kaina_nuo">Kaina iki:</label>
		<input type="text" name="kaina_iki" value="">		
		<input type="submit" name="ieskoti" value="ieškoti">
	</form>
	</div>
	<div id="prekes"> 
<?php

	include 'conf.php';
	
	$kaina_nuo = 0;	
	
	if ( isset ( $_POST [ 'ieskoti' ] ) ) {
	
		$kaina_nuo = $_POST [ 'kaina_nuo' ];
	}	
	
	if ( $conf [ 'prekiu_vieta' ] == 'failas' ) {
	
		if ( ($handle = fopen( "paveiksleliai/prekes1.csv", "r") ) !== FALSE ) {
				
			$prekes_nr = 0;
				
			while ( ( $data = fgetcsv( $handle, 1000, "," ) ) !== FALSE ) {
		
				$prekes_nr++;
				
				if ( intval ( $data [ 1 ] ) > $kaina_nuo ) {
?>	
				<div class="preke">
					<h3><?= $data [ 0 ] ?></h3>
					<div class="kaina">
						<?= $data [ 1 ] ?>
					</div>
					<img src="paveiksleliai/<?= $data [ 2 ] ?>" alt="<?= $data [ 0 ] ?>" height="280" width="280">
					<input type="button" class="to_cart" value="Į krepšelį" data-prekes_nr="<?= $prekes_nr ?>" data-pav="<?= $data [ 0 ] ?>"  data-kaina="<?= $data [ 1 ] ?>">
				</div>
<?php		
				}
			}
		}
		
	}  elseif ( $conf [ 'prekiu_vieta' ] == 'duomenu_baze' ) {
	
		mysqli_report ( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
		
		$mysqli = new mysqli ( $conf [ 'db_server' ], $conf [ 'db_user' ], $conf [ 'db_passwod' ], $conf [ 'db_name' ] );
		
		mysqli_set_charset( $mysqli, "utf8" );

		$query = "SELECT * FROM `prekes`";

		$result = $mysqli -> query ($query );

		/* fetch associative array */
		while ( $row = $result->fetch_assoc() ) {
		    
			if ( intval ( $row [ 'kaina' ] ) > $kaina_nuo ) {
?>	
				<div class="preke">
					<h3><?= $row [ 'pav' ] ?></h3>
					<div class="kaina">
						<?= $row [ 'kaina' ] ?>
					</div>
					<img src="paveiksleliai/<?= $row [ 'paveiksl' ] ?>" alt="<?= $row [ 'paveiksl' ] ?>" height="280" width="280">
					<input type="button" class="to_cart" value="Į krepšelį" data-prekes_nr="<?= $row [ 'id' ] ?>" data-pav="<?= $row [ 'pav' ] ?>"  data-kaina="<?= $row [ 'kaina' ] ?>">
				</div>
<?php		
			}		    
		}
		$mysqli -> close();
	}
?>	
	</div>
	</div>
	<div id="krepselis">
		<input type="button" id="to_shop" value="Į prekes">
		<div id="krepselio_prekes">
		</div>
		<input type="button" id="uzsakyti" value="Užsakyti">
	</div>
</body>
</html>