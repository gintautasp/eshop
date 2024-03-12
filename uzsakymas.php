<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title> Užsakymas </title>
	<meta name="description" content="sportas ir pramogoms, be kokiu oru, gėlės, sveikinimai ir puokštės visoms progoms">
	<meta name="keywords" content="sportas, pramogos, orai, gėlės, sveikinimai, puokštės">
	<style>
		#uzsakymas {
		
			width: 430px;
		}
		input, label {
			width: 100%;
			padding: 12px;
			margin: 12px;
			text-align: right;
		}
		label {
			margin-botton: 7px;
		}
		
		table {
			border-collapse: collapse;
		}
		th, td {
			padding: 7px;
			border: 1px solid lightgrey;
		}
		.sujungti {
			background-color: lightgreen;
		}
		#prekes {
			margin: 20px;
		}
	</style>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>	
	<script>
<?php	
	if ( ! isset ( $_POST [ 'uzsakyk' ] ) ) {	
?>
		$( document ).ready ( function() {
		
			function getCookie(cname) {
				  let name = cname + "=";
				  let decodedCookie = decodeURIComponent(document.cookie);
				  let ca = decodedCookie.split(';');
				  for(let i = 0; i <ca.length; i++) {
				    let c = ca[i];
				    while (c.charAt(0) == ' ') {
				      c = c.substring(1);
				    }
				    if (c.indexOf(name) == 0) {
				      return c.substring(name.length, c.length);
				    }
				  }
				  return "";
			}
			
			pirkiniai_json = getCookie ( 'krepselis' ); 
			
			pirkiniai = JSON.parse ( pirkiniai_json  );
			
			suma_viso = 0;

			cart = '<table>';
			cart += '<tr><th>Preke</th><th>Kiekis</th><th>Kaina</th><th>Suma</th></tr>';
			
			suma_viso = 0;
			
			for ( i = 0;  i<pirkiniai.length; i++ ) {
			
				kiekis = parseInt ( pirkiniai [ i  ].kiekis );
				kaina = parseFloat ( pirkiniai [ i  ].kaina );
				
				suma = kiekis * kaina;
				suma_viso += suma;
			
				cart += '<tr><td>' + pirkiniai [ i  ].pav + '</td><td>'  + pirkiniai [  i  ].kiekis +  '</td><td>'  + pirkiniai [  i  ].kaina +  '</td><td>' + suma + '</tr>';
			}
			
			cart += '<tr><th colspan="3">Suma viso</th><th>' + suma_viso + '</th>';
			cart += '</table>';
	
			cart += '<input type="hidden" name="krepselis" value="' + pirkiniai_json.replace( /"/g, "'" ) + '">';
			
			$( '#prekes' ).html( cart );	

			$( '#uzsakyk' ).click ( function() {
			
				
			});
		});
<?php
	}
?>
	</script>
</head>
<body>
		<div id="uzsakymas">
<?php

	include 'conf.php';
	
	// print_r ( $_POST );

	if ( isset ( $_POST [ 'uzsakyk' ] ) && ( $_POST [ 'uzsakyk' ] == 'Užsakyti' ) ) {
	
		$id_uzsakymo = 0;
		$uzsakymo_suma = 0;

		mysqli_report ( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
		
		$mysqli = new mysqli ( $conf [ 'db_server' ], $conf [ 'db_user' ], $conf [ 'db_passwod' ], $conf [ 'db_name' ] );
		
		$query = "INSERT INTO `uzsakovai` (`pav`, `adresas`, `telefonai`, `email` ) VALUES ( '" . $_POST  [ 'pav' ].  "', '" . $_POST  [ 'adresas' ] . "', '" . $_POST  [ 'telefonai' ]. "', '". $_POST  [ 'email' ] . "' )";

		$result = $mysqli -> query ( $query );
		
		$query = "SELECT LAST_INSERT_ID() AS `id_uzsakovo`";
		
		$result = $mysqli -> query ( $query );
		
		if ( $row = $result->fetch_assoc() ) {
			
			$query = "INSERT INTO `uzsakymai` (`id_uzsakovo`, `adresas_pristatymo` ) VALUES ( '" . $row [ 'id_uzsakovo' ] . "', '" .  $_POST  [ 'adresas' ] . "' )";
			
			$result = $mysqli -> query ( $query );
			
			// echo $query;
		
			$query = "SELECT LAST_INSERT_ID() AS `id_uzsakymo`";
			
			$result = $mysqli -> query ( $query );
			
			if ( $row = $result->fetch_assoc() ) {		
			
				$id_uzsakymo = $row [ 'id_uzsakymo' ];

				$query = 
					"INSERT INTO `uzsakymai_prekes` (`id_uzsakymo`, `id_prekes`, `kaina_prekes`, `kiekis` ) "
					. " VALUES "
				;
					
				$lst_prekes = json_decode ( str_replace ( "'", '"', $_POST [ 'krepselis' ] ) );
				$glue = "( ";
				$html_table_prekes = '';
				
				foreach ( $lst_prekes as $preke1 ) {
				
					$query .= $glue ."'" .  $id_uzsakymo . "', '" .  $preke1 -> nr . "', '" . $preke1 -> kaina . "', '" . $preke1 -> kiekis  . "'";
					$glue = " ) , ( ";
					
					$suma =  floatval ( $preke1 -> kaina ) * floatval ( $preke1 -> kiekis );
					
					$html_table_prekes .= '<tr><td>' .  $preke1 -> pav . '</td><td>' . $preke1 -> kaina . '</td><td>' . $preke1 -> kiekis  . '</td><td>' . $suma . '</td></tr>';
					
					$uzsakymo_suma += $suma;
				}
				$query .= " ) ";	
				
				//  echo $query;
				
				if ( $mysqli -> query ( $query ) ) {
?>
			Jūsų užsakymas priimtas ! <br>
			užsakymo nr: <?= $id_uzsakymo ?><br>
			<table>
				<tr>
					<th>prekė</th><th>kaina</th><th>kiekis</th><th>suma</th>
				</tr>
				<?= $html_table_prekes ?>
				<tr>
					<th colspan="3">suma viso:</th><td><?= $uzsakymo_suma ?></td>
				</tr>
			</table>
			<table>
				<tr>
					<th> Užsakovas </th><td><?= $_POST  [ 'pav' ] ?></td>
				</tr>
				<tr>
					<th> Adresas </th><td><?= $_POST  [ 'adresas' ] ?></td>
				</tr>
				<tr>
					<th>Telefonai </th><td><?= $_POST  [ 'telefonai' ] ?></td>  
				</tr>
				<tr>
					<th>El.paštas </th><td><?= $_POST  [ 'email' ] ?></td>
				</tr>
			</table>
			<table>
				<caption> Duomenys apmokėjimui</caption>
				<tr>				
					<th> Gavejas </th><td><?=  $conf [ 'gavejas' ] ?></td>
				</tr>
				<tr>				
					<th>Sąskaitos nr.</th><td><?= $conf [ 'saskaitos_nr' ] ?></td>
				</tr>
				<tr>					
					<td>Apmokama suma</th><td><?= $uzsakymo_suma ?>
				</tr>
				<tr>					
					<td>Paskirtis</th><td>užsakymas nr: <?= $id_uzsakymo ?></td>
				</tr>
			</table>
<?php					
				}
			}
		}
		
	} else {
?>
			<form method="post" action=""> 
			<div id="prekes">
			</div>	
			<div id="uzsakovas">	
				<label for="pav">Vardas, Pavardė, Organizacija</label>
				<input type="text" id="pav" name="pav">
				<label for="adresas">Adresas</label>
				<input type="text" id="adresas" name="adresas">
				<label for="telefonai">Telefonas(-ai)</label>
				<input type="text" id="telefonai" name="telefonai">
				<label for="pav">El. paštas(-ai)</label>
				<input type="text" id="email" name="email">
				<input type="submit" id="uzsakyk" name="uzsakyk" value="Užsakyti">
			</div>
			</form>
<?php
	}
?>
		</div>
</body>
</html>