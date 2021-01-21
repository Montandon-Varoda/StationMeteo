<?php
	/********************************************************
	*Titre:			Connexion Météo							*
	*Auteur:		Maxime Montandon						*
	*Date:			20.01.2021								*
	*Lieux:			Force Aérienne de Payerne 				*
	*Description:	Il permet d'initialiser les sessions et * 
	*directement rediriger vers la page du formulaire		*						
	*Modification:	Maxime Montandon 20.01.2020 			*
	*				Programme de base						*
	********************************************************/
	// On lance la session
	session_start();

	// Initialisation des sessions
	$_SESSION['message'] = NULL;

	// Redirection a la page principal
	header('Location: formulaire.php');
?>