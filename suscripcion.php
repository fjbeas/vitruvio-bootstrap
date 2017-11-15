<?php

$nameErr = $emailErr = $skypeErr = "";
$name = $email = $skype = "";


$con = @mysqli_connect('shareddb1e.hosting.stackcp.net', 'Suscriptores-3233e527', 'vitrugroup2017', 'Suscriptores-3233e527');

if (!$con) {
    echo "Error: " . mysqli_connect_error();
  exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "El nombre es requerido";
  } else {
    $name = test_input($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $nameErr = "Solo se permite letras y espacios en blanco"; 
    }
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "El email es necesario";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format"; 
    }
  }

  if (empty($_POST["skype"])) {
    $skypeErr = "Es necesario ingresar el skype";
  } else {
    $skype = test_input($_POST["skype"]);
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$query = "INSERT INTO Subs(Nombre, Correo, Skype) VALUES ('$name', '$email', '$skype')";
$result = mysqli_query($con, $query);



if($result)
{
  require 'vendor/eoghanobrien/php-simple-mail/class.simple_mail.php';

   $send = SimpleMail::make()
    ->setTo($email,$name)
    ->setFrom('contacto@franciscobeas.com', 'VITRUVIO')
    ->setSubject('Confirmación')
    ->setMessage('ESTE MENSAJE ES UNA PRUEBA')
    ->setReplyTo('contacto@franciscobeas.com', 'VITRUVIO')
    //->setCc(['Bill Gates' => 'bill@example.com'])
    //->setBcc(['Steve Jobs' => 'steve@example.com'])
    ->setHtml()
    ->setWrap(100)
    ->addAttachment('Bienvenida.pdf')
    ->send();
    
echo ($send) ? 'Email sent successfully' : 'Could not send email';
 
}
else
{
echo "Error al guardar los datos en la BD";
}




mysqli_close($con);
?>