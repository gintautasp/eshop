<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
} else {
    // echo "<p>Hello {$_SERVER['PHP_AUTH_USER']}.</p>";
   // echo "<p>You entered {$_SERVER['PHP_AUTH_PW']} as your password.</p>";
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title> Užsakymai </title>
	<style>
		table {
			border-collapse: collapse;
		}
		th, td {
			padding: 7px;
			border: 1px solid lightgrey;
		}	
	</style>
</head>
<body>
		<div id="uzsakymai">
			<table>
				<tr><th colspan="2">Užsakymo informacija</th><th colspan="4">Užsakovo informacija</th><th colspan="3">Prekės informacija</tr>
				<tr><th>data</th><th>pristatymo adresas</th><th>užsakovas</th><th>adresas</th><th>telefonai</th><th>el. paštas</th><th>prekė</th><th>kiekis</th><th>kaina</th></tr>
<?php

	include 'conf.php';
	
	mysqli_report ( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
	
	$mysqli = new mysqli ( $conf [ 'db_server' ], $conf [ 'db_user' ], $conf [ 'db_passwod' ], $conf [ 'db_name' ] );	
	
	mysqli_set_charset( $mysqli, "utf8" );
	
	$query = 
			"
		SELECT
			`uzsakymai`.`data` AS `uzsakymo_data`
			,`adresas_pristatymo`
			, `uzsakovai`.`pav` AS `uzsakovas`
			, `uzsakovai`.`adresas` AS `adresas_uzsakovo`
			, `uzsakovai`.`telefonai`
			, `uzsakovai`.`email`
			, GROUP_CONCAT( 
				CONCAT_WS( '|', `uzsakymai_prekes`.`kaina_prekes`, `uzsakymai_prekes`.`kiekis`, `prekes`.`pav`)
				SEPARATOR ':'
			) AS `prekes`
		FROM 
			`uzsakymai`
		LEFT JOIN `uzsakovai` ON(
			`uzsakymai`.`id_uzsakovo`=`uzsakovai`.`id`
		)
		LEFT JOIN `uzsakymai_prekes` ON(
			`uzsakymai`.`id`=`uzsakymai_prekes`.`id_uzsakymo`
		)
		LEFT JOIN `prekes` ON(
			`uzsakymai_prekes`.`id_prekes`=`prekes`.`id`
		)
		WHERE
			`uzsakymai`.`busena`!='pristatytas'		
		GROUP BY
			`uzsakymai`.`id`
			";

	$result = $mysqli -> query ( $query );

	/* fetch associative array */
	while ( $row = $result->fetch_assoc() ) {
	
		$prekiu_eilute = $row [ 'prekes' ];
		
		$prekes = explode ( ':', $prekiu_eilute );
		
		$kiekis_prekiu = count ( $prekes );
		
?>	
		<tr>
			<td rowspan="<?= $kiekis_prekiu ?>"><?= $row [ 'uzsakymo_data' ] ?></td>
			<td rowspan="<?= $kiekis_prekiu ?>"><?= $row [ 'adresas_pristatymo' ] ?></td>
			<td rowspan="<?= $kiekis_prekiu ?>"><?= $row [ 'uzsakovas' ] ?></td>
			<td rowspan="<?= $kiekis_prekiu ?>"><?= $row [ 'adresas_uzsakovo' ] ?></td>
			<td rowspan="<?= $kiekis_prekiu ?>"><?= $row [ 'telefonai' ] ?></td>
			<td rowspan="<?= $kiekis_prekiu ?>"><?= $row [ 'email' ] ?></td>
<?php
			$prekes_nr = 0;

			foreach ( $prekes AS $preke1 ) {
			
				if ( $prekes_nr != 0 ) {
?>
		<tr>
<?php				
				}
				$prekes_inf = explode ( '|', $preke1 );				
?>
			<td><?= $prekes_inf [ 2 ] ?></td><td><?= $prekes_inf [ 1 ] ?></td><td><?= $prekes_inf [ 0 ] ?></td>
			</tr>
<?php
			}	
	}
	$mysqli -> close();
?>	
		</table>
	</div>
</body>
</html>	