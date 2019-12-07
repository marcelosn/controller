# CONTROLE DE ROTA
Classe em PHP simples para você controlar as rotas do seu website/sistema.

## Instalação
Inclua o arquivo da classe e inicie:

	include("./controller.class.php");
	$controller = new Controller();

	
## Configuração simples

	//Diretório ROOT padrão dos includes
	$controller->setRoot(__DIR__);
	
	//Caso não exista URL no Browser redireciona para: 
	$controller->initPath("pagina-inicial");
	
	//Caso não exista URL setamos o arquivo erro404: 
	$controller->set404	("erro404.php");

 ## Ignorando paths finais
ex:	
URL cadastrada = a/b
URL do browser = a/b/c/d/e/f
Sistema leva em consideração a primeira combinação

	$controller->ignoreAdd();

## Incluindo arquivos fixos

	$controller->includeFile("includeFile.php");

## Setando os paths (arquivo,path)
	$controller->setPath("inicio.php","pagina-inicial");
	$controller->setPath("produtos.php""produtos");


### Usando um arquivo pré processador
Podemos direcionar tudo para um arquivo que processará tudo dessa maneira
	
	$controller->setPath("desktop.php?path=prod""produtos");
	$controller->setPath("desktop.php?path=qs""quem-somos");
	$controller->setPath("desktop.php?path=init""inicio");

## Processando os paths
Ao final do cadastro das URLS, é necessário executar a função que dá inicio a tudo:

	$controller->processPaths();

## Variáveis de Paths


| Tipo | Descrição |
|--|--|
 path/path2 | Acessa o arquivo.php quando a URL for igual a path/path2  |
 path/a\|b\|c| Acessa o arquivo.php quando a URL for igual a **path/a** ou **path/b** ou **path/c**   |
path/^teste | Acessa o arquivo.php quando a URL for diferente de path/teste
path/*| Caractere coringa, representa qualquer coisa.


# EXEMPLO DE USO:

	<?
		include("./controller.class.php");
		$controller = new Controller();
		$controller->setRoot(__DIR__);
		$controller->initPath("pagina-inicial");
		$controller->set404	("erro404.php");
		$controller->ignoreAdd();
		$controller->includeFile("head.php");
		$controller->includeFile("header.php");
		$controller->setPath("inicio.php","pagina-inicial");
		$controller->setPath("lista-produtos.php,'produtos');
		$controller->setPath("produto.php","produto/*");
		$controller->processPaths();
		$controller->includeFile("footer.php");