<?php
session_start(); // Inicia a sessão se ainda não estiver iniciada

session_unset(); // Remove todas as variáveis de sessão
session_destroy(); // Destroi a sessão

// Redireciona o usuário para a página de login
header( "refresh:0;url=login.php" );    
exit();
?>