	<html>
 <head>
 	<title>SQL injection</title>
 	<style>
 		body{
 		}
 		.user {
 			background-color: yellow;
 		}
 	</style>
 </head>
 
 <body>
 	<h1>PDO vulnerable a SQL injection</h1>
 
 	<?php
 		// sql injection possible:
 		// coses'); drop table test;'select 
		if( isset($_POST["user"])) {

			$dbhost = $_ENV["DB_HOST"];
			$dbname = $_ENV["DB_NAME"];
			$dbuser = $_ENV["DB_USER"];
			$dbpass = $_ENV["DB_PASSWORD"];

			# Connectem a MySQL (host,usuari,contrassenya)
			$pdo = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
	 
			$username = $_POST["user"];
			$pass = $_POST["password"];


			$stmt = $pdo->prepare("select * from users where name=? AND password=SHA2(?,512);");
			// Bind
			$stmt->bindParam(1, $username);
			$stmt->bindParam(2, $pass);
			// Excecute
			$stmt->execute();

			if( $stmt->errorInfo()[1] ) {
				echo "<p>ERROR: ".$stmt->errorInfo()[2]."</p>\n";
				die;
			}

			if( $stmt->rowCount() >= 1 )
				# hi ha 1 resultat o més d'usuaris amb nom i password
				foreach( $stmt as $user ) {
					echo "<div class='user'>Hola ".$user["name"]." (".$user["role"].").</div>";
				}
			else
				echo "<div class='user'>No hi ha cap usuari amb aquest nom o contrasenya.</div>";
			}
 	?>
 	
 	<fieldset>
 	<legend>Login form</legend>
  	<form method="post">
		User: <input type="text" name="user" /><br>
		Pass: <input type="text" name="password" /><br>
		<input type="submit" /><br>
 	</form>
  	</fieldset>
	
 </body>
 
 </html>
