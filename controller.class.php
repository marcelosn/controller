<?
class Controller{
	public function __construct(){
		$this->init 		= array("inicio","");
		$this->paths 		= array();
		$this->erro404 		= null;
		$this->checkURL		= 0;
		$this->adminPath	= false;
		$this->goURLlabel	= '';
		$this->fileInclude	= null;
		$this->files 		= array();
		$this->URI 			= "";
		$this->ignoreAdd	= false;
		$this->gets 		= array();
		$this->get 			= null;
		$this->Root 		= null;

	}

	#################################################################
	#	TRATAMOS E VERIFICAMOS A URL E SETAMOS O ARQUIVO CORRETO
	#################################################################

	public function verifyURL(){
		$i 		= 0;
		$listner = 0;
		#################################################################
		# SE ESTIVER VAZIA A URL, DIRECIONA PRA URL INICIAL
		#################################################################
		$this->URI = $_SERVER['REQUEST_URI'];
		if($this->adminPath == true){
			if($this->URI==BASE_DIR."/" || $this->URI==BASE_DIR){
				header("Location:/".$this->init);
				exit;
			}
		}else{
			if($this->URI=="" || $this->URI=="/"){
				header("Location:/".$this->init);
				exit;
			}
		}
		$this->trataURL();

		#################################################################
		# VARRE A ARRAY DAS URLS CADASTRADAS
		#################################################################
		foreach($this->paths as $paths_registrados){
				$a 						= 0;
				$URL_TRATADA			= '';
				$ARRAY_URL_TRATADA 		= explode('/',implode(array_filter(explode('/',$paths_registrados)),'/'));
				$ARRAY_URLS_CADASTRADAS = explode('/',implode(array_filter(explode('/',$paths_registrados)),'/'));
				$ARRAY_URL_BROWSER 		= explode('/',implode(array_filter(explode('/',$this->URI)),'/'));
				$this->goURLlabel		= $paths_registrados;
				$qtdd_igual				= count($ARRAY_URLS_CADASTRADAS)==count($ARRAY_URL_BROWSER);
				$substr_ignore			= substr(implode($ARRAY_URL_BROWSER,'/'), 0,strlen(implode($ARRAY_URL_TRATADA,'/')))==implode($ARRAY_URL_TRATADA,'/');

				#################################################################
				# VERIFICA SE EXISTE ALGUMA CONDICIONAL
				#################################################################

				if(	($qtdd_igual || $this->ignoreAdd == true) && 	
					(	stripos($paths_registrados,'|')>-1 || 
						stripos($paths_registrados,'*')>-1 || 
						stripos($paths_registrados,'^')>-1 || 
						stripos($paths_registrados,'ˆ')>-1
					)
				){

					#################################################################
					# VARREMOS PATH A PATH ATÉ PODERMOS COMPARAR
					#################################################################
					$w = 0;
					foreach ($ARRAY_URLS_CADASTRADAS as $DIRETORIO_URL_CADASTRADA){ 
						#################################################################
						# AQUI APENAS SEPAREI EM VARIÁVEIS AS VERIFICAÇÕES PRA FACILITAR
						#################################################################
							$diferente	= (stripos($DIRETORIO_URL_CADASTRADA,'ˆ')>-1 || stripos($DIRETORIO_URL_CADASTRADA,'^')>-1) && substr(@$ARRAY_URLS_CADASTRADAS[$a],1)!=@$ARRAY_URL_BROWSER[$w];
							$multiplos	= (stripos($DIRETORIO_URL_CADASTRADA,'|') && in_array(@$ARRAY_URL_BROWSER[$w],explode('|',$DIRETORIO_URL_CADASTRADA)));
							$coringa	= $DIRETORIO_URL_CADASTRADA=='*';
						
						#################################################################
						# CASO O DIRETÓRIO SEJA DIFERENTE..
						#################################################################
						if($ARRAY_URL_BROWSER[$w]!=$DIRETORIO_URL_CADASTRADA ){

							#################################################################
							# E ESTEJA DENTRO DAS VERIFICAÇÕES, TRATA A ARRAY DE ENTRADA
							#################################################################
							if($diferente || $multiplos || $coringa){
								$ARRAY_URL_TRATADA[$w] 	= $ARRAY_URL_BROWSER[$w];
							}else{
								break;
							}
						}
						$w++;
					}


					#################################################################
					# VERIFICA SE ELE SE ENCAIXA NO IGNORE ADD
					#################################################################
					$substr_ignore 		= substr(implode($ARRAY_URL_BROWSER,'/'), 0,strlen(implode($ARRAY_URL_TRATADA,'/')))==implode($ARRAY_URL_TRATADA,'/');
					if($substr_ignore){
						$this->get 			= $this->gets[$i];
						$this->fileInclude 	= $this->files[$i];
						$listner=1;
						break;

					#################################################################
					# VERIFICAMOS SE A URL  TRATADA É IGUAL A URL DO BROWNSER
					#################################################################
					}elseif(implode($ARRAY_URL_TRATADA,'/') == implode($ARRAY_URL_BROWSER,'/')){
						$this->get 			= $this->gets[$i];
						$this->fileInclude 	= $this->files[$i];
						$listner=1;
						break;
					}
					$a++;

				#################################################################
				# VERIFICAMOS SE A URL  REGISTRADA É IGUAL A URL DO BROWNSER
				#################################################################
				}elseif($substr_ignore){
					$this->get 			= $this->gets[$i];
					$this->fileInclude 	= $this->files[$i];
					$listner=1;
					break;
				}elseif($paths_registrados==implode($ARRAY_URL_BROWSER,'/')){
					$this->get 			= $this->gets[$i];
					$this->fileInclude 	= $this->files[$i];
					$listner=1;
					break;
				}
			$i++;
		};


		#################################################################
		# CASO NAO ACHE NADA DIRECIONA PARA O ERRO 404
		#################################################################
		if($listner==0 || strlen($this->fileInclude)==0){		
			include($this->Root.'/'.$this->erro404);
		}else{
			#################################################################
			# SEPARAMOS AS VARIÁVEIS GET E JUNTAMOS AO GET ORIGINAL
			#################################################################
			$_GET = @array_merge($_GET,$this->get);
			include($this->Root.'/'.$this->fileInclude);
		}

	}


	public function setRoot($root=null){
		if($root==null){
			throw new RuntimeException('Por favor, o caminho $root nao está definido.');
			exit;
		}else{
			$this->Root=$root;
		}
	}
	public function isAdminPath(){ $this->adminPath = true;}
	public function ignoreAdd(){ $this->ignoreAdd = true;}
	public function get_URI(){
	//
		if($this->adminPath == true){
			$this->URI = $_SERVER['REQUEST_URI'];
		}else{
			if(empty($_SERVER['REQUEST_URI']) || substr($_SERVER['REQUEST_URI'],0,1)=="/"){
				$this->URI = '/';
			}else{
				$this->URI = $_SERVER['REQUEST_URI'];
			}

		}


	}
	public function initPath($path=null){
		if($path=="" || !is_string($path) || $path==null){
			throw new RuntimeException('Por favor, coloque uma URL válida de inicio ->initPath(string)');
			exit;
		}else{
			$this->init = $path;
		}
	}

	public function set404($page=null){
		if($page==null){throw new RuntimeException('Por favor, coloque uma URL válida de como erro404');
		exit;}
		if(!file_exists($this->Root.'/'.$page)){throw new RuntimeException('Este arquivo não existe: '.$page);exit;}
		$this->erro404=$page;
	}

	public function error404(){
		if($this->erro404==null){
			throw new RuntimeException('Por favor, coloque uma URL válida de como erro404');exit;
		}else{
			include($this->Root.'/'.$this->erro404);
		}
	}

	public function trataURL(){
		$REQUEST_URL 	= 	explode('?',$this->URI);
		if(isset($REQUEST_URL[1])){
			$urlGet	=	''.$REQUEST_URL[1];
			parse_str($urlGet, $parse);
			array_merge($_GET,$parse);
		}
		$this->URI = urldecode($REQUEST_URL[0]);
	}
	static function getPath($node=null){
		if(is_string($node)){		
			throw new RuntimeException("Erro: Isso não é um número ->	self::urlPath('".$node."')");
			exit;
		}
		elseif($node==null && $node==0){
			if(substr( $_SERVER['REQUEST_URI'],0,1)=='/') $_SERVER['REQUEST_URI']=substr($_SERVER['REQUEST_URI'],1,strlen($_SERVER['REQUEST_URI']));
			$REQUEST_URL 	= 	explode('?',$_SERVER['REQUEST_URI']);
			$url 			=	$REQUEST_URL[0];
			return $url;
			exit;
		}else{
			$REQUEST_URL	= 	substr( $_SERVER['REQUEST_URI'], strlen('/'));
			$REQUEST_URL 	= 	explode('?',$REQUEST_URL);
			$url 			=	$REQUEST_URL[0];
			if(substr( $url,-1)=='/')$url=substr($url,0,-1);
			$GET=explode( '/', $url);
			if($node>count($GET)){
				throw new RuntimeException("Erro: Não existe path nesta posição ->	self::urlPath(".$node.")");
			}else{
				return $GET[($node-1)];
			};
		}
	} 

	public function includeFile($file=null){
		if($file==null || $file==""){
			throw new RuntimeException("Erro: Por favor, insira o nome de um arquivo -> ".$file);
			exit;
		}elseif(!file_exists($this->Root.'/'.$file)){
			throw new RuntimeException("Erro: Este arquivo não existe -> ".$file);
			exit;
		}elseif(is_string($file)){
			include($this->Root.'/'.$file);
		}
	}

	public function setPath($file=null,$path=null){
		$REQUEST_URL 	= 	explode('?',$file);
		if(isset($REQUEST_URL[1])){
			parse_str(''.$REQUEST_URL[1], $parse);
			$this->gets[] = $parse;
			$file = $REQUEST_URL[0];
		}else{
			$this->gets[] = 0;				
		}

		if($file==null || $file==""){
			throw new RuntimeException("Erro: Por favor, insira o nome de um arquivo -> ".$file);
			exit;
		}elseif(!file_exists($this->Root.'/'.$file)){
			throw new RuntimeException("Erro: Este arquivo não existe -> ".$file);
			exit;
		}elseif(is_string($path)){
			array_push($this->paths,$path);
			array_push($this->files,$file);
		}else{
			throw new RuntimeException("Erro: Por favor, apenas strings -> ".$path);
		}
	}
	public function processPaths(){
		$this->get_URI();
		$this->verifyURL();
	}
}