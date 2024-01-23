<?php 

session_start();

unset($_SESSION['LOGGED_USER']); /* pour deconnecter un utilisateur */

http_response_code(302); /* pour dire "j'ai rediriger" la requette - non obligatoire */
header('Location: /'); /* pour re-diriger la page */
exit(); /* pour arreter la re-direction */