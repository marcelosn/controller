<?php 
	######################################################################
	# IMPORTA CLASSE
	######################################################################
	include("./controller.class.php");

	######################################################################
	# INICIA A CLASSE
	######################################################################
	$controller = new Controller();

	######################################################################
	# SETAMOS O PATH QUE SERÁ IMPORTADO O ARQUIVO POR DEFAULT
	######################################################################
	$controller->setRoot(__DIR__);

	######################################################################
	# CASO NAO EXISTA PATH NO BROWSER, REDIRECIONA PARA pagina-inicial
	######################################################################
	$controller->initPath("pagina-inicial");

	######################################################################
	# SE NAO ENCONTRAR A URL EXPECIFICADA, REDIRECIONA PARA O ERRO 404
	######################################################################
	$controller->set404	("erro404.php");

	######################################################################
	# AQUI IGNORA OS PATHS QUE SOBRAREM QUANDO ENCONTRAREM O PRIMEIRO:
	# ex:	URL cadastrada = a/b
	# 		URL do browser = a/b/c/d
	#		Sistema leva em consideração a primeira combinação
	######################################################################
	$controller->ignoreAdd();

	######################################################################
	# INCLUDE DE ARQUIVOS
	######################################################################
	$controller->includeFile(	"includeFile.php");
	$controller->includeFile(	"includeFile.php");

	######################################################################
	#	CONFIGURAÇAO DOS PATHS E SEUS ARQUIVOS 
	#	OBS.: Apenas aqui funciona $_GET direto na string
	######################################################################
	$controller->setPath(		"exemplo.php?session=pagina inicial"		,"pagina-inicial");
	$controller->setPath(		"exemplo.php?session=Path Coringa"			,"coringa/*");
	$controller->setPath(		"exemplo.php?session=Path comopções"		,'vars/tipo1|tipo2');
	$controller->setPath(		"exemplo.php?session=Diferente que string"	,"diff/^string");

	######################################################################
	# PROCESSA TUDO E RETORNA A PÁGINA CORRETA
	######################################################################
	$controller->processPaths();

	######################################################################
	# INCLUI MAIS UM ARQUIVO QUALQUER
	######################################################################
	$controller->includeFile(	"includeFile.php");


