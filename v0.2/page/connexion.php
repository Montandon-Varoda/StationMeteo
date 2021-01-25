<?php
	/********************************************************
	*Titre:			Connexion Météo							*
	*Auteur:		Maxime Montandon						*
	*Date:			20.01.2021								*
	*Lieux:			Force Aérienne de Payerne 				*
	*Description:	Il permet d'initialiser les sessions et * 
	*directement rediriger vers la page du formulaire		*						
	*Modification:	Maxime Montandon 25.01.2021 			*
	*				Programme de base						*
	********************************************************/
	// On lance la session
	session_start();

	// Initialisation des sessions
	$_SESSION['messageError'] = NULL;
	$_SESSION['messageDate'] = NULL;
	$_SESSION['messageRain'] = NULL;
	$_SESSION['messageHumidity'] = NULL;
	$_SESSION['messageTemperature'] = NULL;
	$_SESSION['dateDebut'] = NULL;
	$_SESSION['dateFin'] = NULL;
	$_SESSION['Rain'] = NULL;
	$_SESSION['Humidity'] = NULL;
	$_SESSION['Temperature'] = NULL;

	// Redirection a la page principal
	header('Location: formulaire.php');
?>