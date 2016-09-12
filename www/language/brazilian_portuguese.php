<?php
/**
* @version $Id: english.php 6085 2006-12-24 18:59:57Z robs $
* @package Joomla / Tradução JoomlaClube 2008 - www.joomlaclube.com.br
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'O acesso direto a esta página não foi autorizado' );

// Site page note found
DEFINE( '_404', 'Lamentamos mas a página solicitada não foi encontrada.');
DEFINE( '_404_RTS', 'Voltar ao site');

define( '_SYSERR1', 'O adaptador do banco de dados não está disponível' );
define( '_SYSERR2', 'Não foi possível conectar ao servidor de banco de dados' );
define( '_SYSERR3', 'Não foi possível conectar ao banco de dados' );

// common
DEFINE('_LANGUAGE','pt-BR');
DEFINE('_NOT_AUTH','Você não tem permissão para acessar esta área do site.');
DEFINE('_DO_LOGIN','É necessário efetuar a sua autenticação para entrar. Se já tinha efetuado a autenticação a sua sessão terá terminado. Volte à Página Inicial e efetue de novo a autenticação');
DEFINE('_VALID_AZ09',"Por favor, introduza uma %s válida. Sem espaços, com mais de %d caracteres e utilizando apenas caracteres nos intervalos 0-9,a-z,A-Z");
DEFINE('_VALID_AZ09_USER',"Por favor, introduza um %s válido. Sem espaços ou acentos, com mais de %d caracteres e utilizando apenas caracteres nos intervalos 0-9,a-z,A-Z");
DEFINE('_CMN_YES','Sim');
DEFINE('_CMN_NO','Não');
DEFINE('_CMN_SHOW','Exibir');
DEFINE('_CMN_HIDE','Ocultar');

DEFINE('_CMN_NAME','Nome');
DEFINE('_CMN_DESCRIPTION','Descrição');
DEFINE('_CMN_SAVE','Salvar');
DEFINE('_CMN_APPLY','Aplicar');
DEFINE('_CMN_CANCEL','Cancelar');
DEFINE('_CMN_PRINT','Imprimir');
DEFINE('_CMN_PDF','PDF');
DEFINE('_CMN_EMAIL','E-mail');
DEFINE('_ICON_SEP','|');
DEFINE('_CMN_PARENT','Dependência superior');
DEFINE('_CMN_ORDERING','Ordem');
DEFINE('_CMN_ACCESS','Nível de acesso');
DEFINE('_CMN_SELECT','Selecione');

DEFINE('_CMN_NEXT','Página seguinte');
DEFINE('_CMN_NEXT_ARROW'," &gt;&gt;");
DEFINE('_CMN_PREV','Página anterior');
DEFINE('_CMN_PREV_ARROW',"&lt;&lt; ");

DEFINE('_CMN_SORT_NONE','Não ordenar');
DEFINE('_CMN_SORT_ASC','Ascendente');
DEFINE('_CMN_SORT_DESC','Descendente');

DEFINE('_CMN_NEW','Novo');
DEFINE('_CMN_NONE','Nenhum');
DEFINE('_CMN_LEFT','Esquerda');
DEFINE('_CMN_RIGHT','Direita');
DEFINE('_CMN_CENTER','Centro');
DEFINE('_CMN_ARCHIVE','Arquivar');
DEFINE('_CMN_UNARCHIVE','Desarquivar');
DEFINE('_CMN_TOP','Topo');
DEFINE('_CMN_BOTTOM','Baixo');

DEFINE('_CMN_PUBLISHED','Publicado');
DEFINE('_CMN_UNPUBLISHED','Despublicar');

DEFINE('_CMN_EDIT_HTML','Editar HTML');
DEFINE('_CMN_EDIT_CSS','Editar CSS');

DEFINE('_CMN_DELETE','Apagar');

DEFINE('_CMN_FOLDER','Pasta');
DEFINE('_CMN_SUBFOLDER','Sub-pasta');
DEFINE('_CMN_OPTIONAL','Opcional');
DEFINE('_CMN_REQUIRED','Obrigatório');

DEFINE('_CMN_CONTINUE','Continuar');

DEFINE('_STATIC_CONTENT','Conteúdo estático');

DEFINE('_CMN_NEW_ITEM_LAST','Os novos itens, por padrão, serão adicionados ao final da lista. A ordem pode ser alterada após este item serem salvos.');
DEFINE('_CMN_NEW_ITEM_FIRST','Os novos itens, por padrão, serão adicionados ao início da lista. A ordem pode ser alterada após este item serem salvos.');
DEFINE('_LOGIN_INCOMPLETE','Por favor, complete os campos do Nome de Usuário e Senha.');
DEFINE('_LOGIN_BLOCKED','A sua autenticação foi bloqueada. Por favor, entre em contato com o administrador.');
DEFINE('_LOGIN_INCORRECT','Nome de Usuário ou Senha incorretos. Tente novamente.');
DEFINE('_LOGIN_NOADMINS','Não pode efetuar a autenticação. É necessário configurar administradores. Se tinha administradores já configurados o problema pode ser o resultado da instalação de bridges');
DEFINE('_CMN_JAVASCRIPT','!AVISO! O Javascript deve ser ativado para poder administrar o site sem problemas.');

DEFINE('_NEW_MESSAGE','Recebeu uma nova mensagem privada.');
DEFINE('_MESSAGE_FAILED','O Usuário bloqueou a sua caixa de correio. Mensagem não enviada.');

DEFINE('_CMN_IFRAMES', 'Esta opção não irá funcionar corretamente. Infelizmente, o seu browser não suporta frames Inline');

DEFINE('_INSTALL_3PD_WARN','Alerta: Instalar extensões de outras empresas pode comprometer a segurança do seu servidor. A atualização da sua instalação Joomla! não atualiza as extensões independentes.<br />Para mais informações sobre como manter o seu site seguro, visite <a href="http://www.joomlapt.com/index.php?option=com_smf&Itemid=51&board=32.0" target="_blank" style="color: blue; text-decoration: underline;">Fórum de Segurança Joomla!</a>.');
DEFINE('_INSTALL_WARN','Para sua segurança remova completamente o diretório installation incluindo todos os arquivos e sub-pastas - e depois atualize esta página');
DEFINE('_TEMPLATE_WARN','<font color=\"red\"><b>O arquivo do tema não foi encontrado! Procure pelo tema:</b></font>');
DEFINE('_NO_PARAMS','Não existem parâmetros para este item');
DEFINE('_HANDLER','Ação não definida para este tipo');

/** mambots */
DEFINE('_TOC_JUMPTO','Índice de Artigos');

/**  content */
DEFINE('_READ_MORE','Leia mais...');
DEFINE('_READ_MORE_REGISTER','Registe-se para ler mais...');
DEFINE('_MORE','Mais...');
DEFINE('_ON_NEW_CONTENT', "Um novo conteúdo foi submetido por [ %s ]  com o título [ %s ]  na secção [ %s ]  e categoria  [ %s ]" );
DEFINE('_SEL_CATEGORY','- Selecionar Categoria -');
DEFINE('_SEL_SECTION','- Selecionar Seção -');
DEFINE('_SEL_AUTHOR','- Selecionar Autor -');
DEFINE('_SEL_POSITION','- Selecionar Função -');
DEFINE('_SEL_TYPE','- Selecionar Tipo -');
DEFINE('_EMPTY_CATEGORY','Esta categoria ainda não possui conteúdos');
DEFINE('_EMPTY_BLOG','Não existem itens a exibir');
DEFINE('_NOT_EXIST','A página a que pretendia acessar não existe.<br />Por favor, selecione uma página a partir do Menu Principal.');
DEFINE('_SUBMIT_BUTTON','Enviar');

/** classes/html/modules.php */
DEFINE('_BUTTON_VOTE','Votar');
DEFINE('_BUTTON_RESULTS','Resultados');
DEFINE('_USERNAME','Nome de Usuário');
DEFINE('_LOST_PASSWORD','Lembrar Senha!');
DEFINE('_PASSWORD','Senha');
DEFINE('_BUTTON_LOGIN','Entrar');
DEFINE('_BUTTON_LOGOUT','Sair');
DEFINE('_NO_ACCOUNT','');
DEFINE('_CREATE_ACCOUNT','Criar Conta!');
DEFINE('_VOTE_POOR','Fraco');
DEFINE('_VOTE_BEST','Bom');
DEFINE('_USER_RATING','Classificação');
DEFINE('_RATE_BUTTON','Avaliar');
DEFINE('_REMEMBER_ME','Memorizar');

/** contact.php */
DEFINE('_ENQUIRY','Contato');
DEFINE('_ENQUIRY_TEXT','Este é um e-mail de pedido de informações via %s de');
DEFINE('_COPY_TEXT','Esta é uma cópia da mensagem por si enviada para %s via %s');
DEFINE('_COPY_SUBJECT','Cópia de: ');
DEFINE('_THANK_MESSAGE','Obrigado pelo seu e-mail');
DEFINE('_CLOAKING','Este endereço de e-mail está protegido contra spam bots, pelo que o Javascript terá de estar activado para poder visualizar o endereço de email');
DEFINE('_CONTACT_HEADER_NAME','Nome');
DEFINE('_CONTACT_HEADER_POS','Cargo');
DEFINE('_CONTACT_HEADER_EMAIL','E-mail');
DEFINE('_CONTACT_HEADER_PHONE','Telefone');
DEFINE('_CONTACT_HEADER_FAX','Fax');
DEFINE('_CONTACTS_DESC','Lista de Contatos para este Site.');
DEFINE('_CONTACT_MORE_THAN','Não pode indicar mais que um endereço de e-mail.');

/** classes/html/contact.php */
DEFINE('_CONTACT_TITLE','Contato');
DEFINE('_EMAIL_DESCRIPTION','Enviar um e-mail para este contato:');
DEFINE('_NAME_PROMPT',' O seu nome:');
DEFINE('_EMAIL_PROMPT',' O seu endereço de e-mail:');
DEFINE('_MESSAGE_PROMPT',' A sua mensagem:');
DEFINE('_SEND_BUTTON','Enviar');
DEFINE('_CONTACT_FORM_NC','Por favor, certifique-se de que o formulário esteja corretamente preenchido.');
DEFINE('_CONTACT_TELEPHONE','Telefone: ');
DEFINE('_CONTACT_MOBILE','Celular: ');
DEFINE('_CONTACT_FAX','Fax: ');
DEFINE('_CONTACT_EMAIL','E-mail: ');
DEFINE('_CONTACT_NAME','Nome: ');
DEFINE('_CONTACT_POSITION','Cargo: ');
DEFINE('_CONTACT_ADDRESS','Endereço: ');
DEFINE('_CONTACT_MISC','Informações: ');
DEFINE('_CONTACT_SEL','Selecione o contato:');
DEFINE('_CONTACT_NONE','Não existem detalhes sobre o contato listado.');
DEFINE('_CONTACT_ONE_EMAIL','Não pode indicar mais que um endereço de e-mail.');
DEFINE('_EMAIL_A_COPY','Enviar uma cópia desta mensagem para o meu e-mail');
DEFINE('_CONTACT_DOWNLOAD_AS','Fazer download dos dados deste contato como um arquivo');
DEFINE('_VCARD','VCard para livro de endereços');

/** pageNavigation */
DEFINE('_PN_LT','&lt;');
DEFINE('_PN_RT','&gt;');
DEFINE('_PN_PAGE','Página');
DEFINE('_PN_OF','de');
DEFINE('_PN_START','Início');
DEFINE('_PN_PREVIOUS','Anterior |');
DEFINE('_PN_NEXT','| Seguinte');
DEFINE('_PN_END','Final');
DEFINE('_PN_DISPLAY_NR','Exibir');
DEFINE('_PN_RESULTS','Resultados');

/** e-mailfriend */
DEFINE('_EMAIL_TITLE','Enviar a um amigo');
DEFINE('_EMAIL_FRIEND','Envie este artigo por e-mail a um amigo.');
DEFINE('_EMAIL_FRIEND_ADDR',"E-mail de destino:");
DEFINE('_EMAIL_YOUR_NAME','Seu nome:');
DEFINE('_EMAIL_YOUR_MAIL','Seu e-mail:');
DEFINE('_SUBJECT_PROMPT',' Assunto:');
DEFINE('_BUTTON_SUBMIT_MAIL','Enviar e-mail');
DEFINE('_BUTTON_CANCEL','Cancelar');
DEFINE('_EMAIL_ERR_NOINFO','É necessário introduzir um e-mail válido para o remetente e para o destinatário.');
DEFINE('_EMAIL_MSG',' A seguinte página do site "%s" foi enviado a você por %s ( %s ).

Você pode acessá-la no seguinte endereço:
 %s');
DEFINE('_EMAIL_INFO','Enviado por');
DEFINE('_EMAIL_SENT','O e-mail está sendo enviado para');
DEFINE('_PROMPT_CLOSE','Fechar janela');

/** classes/html/content.php */
DEFINE('_AUTHOR_BY', ' Enviado por');
DEFINE('_WRITTEN_BY', ' Escrito por');
DEFINE('_LAST_UPDATED', 'Atualizado em ');
DEFINE('_BACK','[ Voltar ]');
DEFINE('_LEGEND','Legenda');
DEFINE('_DATE','Data');
DEFINE('_ORDER_DROPDOWN','Ordenar');
DEFINE('_HEADER_TITLE','Título');
DEFINE('_HEADER_AUTHOR','Autor');
DEFINE('_HEADER_SUBMITTED','Enviado');
DEFINE('_HEADER_HITS','Acessos');
DEFINE('_E_EDIT','Editar');
DEFINE('_E_ADD','Adicionar');
DEFINE('_E_WARNUSER','Por favor, escolha entre Cancelar ou salvar as alterações');
DEFINE('_E_WARNTITLE','Conteúdo deve ter um Título');
DEFINE('_E_WARNTEXT','Conteúdo deve ter uma Introdução');
DEFINE('_E_WARNCAT','Por favor, selecione uma Categoria');
DEFINE('_E_CONTENT','Conteúdo');
DEFINE('_E_TITLE','Título:');
DEFINE('_E_CATEGORY','Categoria:');
DEFINE('_E_INTRO','Texto de Introdução');
DEFINE('_E_MAIN','Texto Principal');
DEFINE('_E_MOSIMAGE','INSERIR {mosimage}');
DEFINE('_E_IMAGES','Imagens');
DEFINE('_E_GALLERY_IMAGES','Imagens - Galeria');
DEFINE('_E_CONTENT_IMAGES','Imagens - Conteúdo');
DEFINE('_E_EDIT_IMAGE','Editar imagem');
DEFINE('_E_NO_IMAGE','Sem imagem');
DEFINE('_E_INSERT','Inserir');
DEFINE('_E_UP','Para cima');
DEFINE('_E_DOWN','Para baixo');
DEFINE('_E_REMOVE','Remover');
DEFINE('_E_SOURCE','Fonte:');
DEFINE('_E_ALIGN','Alinhamento:');
DEFINE('_E_ALT','Texto Alt:');
DEFINE('_E_BORDER','Borda:');
DEFINE('_E_CAPTION','Título');
DEFINE('_E_CAPTION_POSITION','Posição da Legenda');
DEFINE('_E_CAPTION_ALIGN','Alinhamento da Legenda');
DEFINE('_E_CAPTION_WIDTH','Largura da Legenda');
DEFINE('_E_APPLY','Aplicar');
DEFINE('_E_PUBLISHING','Publicação');
DEFINE('_E_STATE','Estado:');
DEFINE('_E_AUTHOR_ALIAS','Apelido do Autor:');
DEFINE('_E_ACCESS_LEVEL','Nível de acesso:');
DEFINE('_E_ORDERING','Ordenar:');
DEFINE('_E_START_PUB','Iniciar publicação:');
DEFINE('_E_FINISH_PUB','Terminar publicação:');
DEFINE('_E_SHOW_FP','Exibir na Página Inicial:');
DEFINE('_E_HIDE_TITLE','Ocultar Título:');
DEFINE('_E_METADATA','Metadados');
DEFINE('_E_M_DESC','Descrição:');
DEFINE('_E_M_KEY','Palavras-chave:');
DEFINE('_E_SUBJECT','Assunto:');
DEFINE('_E_EXPIRES','Expira em:');
DEFINE('_E_VERSION','Versão:');
DEFINE('_E_ABOUT','Sobre');
DEFINE('_E_CREATED','Criado em:');
DEFINE('_E_LAST_MOD','Modificado em:');
DEFINE('_E_HITS','Acessos:');
DEFINE('_E_SAVE','Salvar');
DEFINE('_E_CANCEL','Cancelar');
DEFINE('_E_REGISTERED','Somente Usuários Cadastrados');
DEFINE('_E_ITEM_INFO','Informação do item');
DEFINE('_E_ITEM_SAVED','Item salvo com sucesso.');
DEFINE('_ITEM_PREVIOUS','&lt; Anterior');
DEFINE('_ITEM_NEXT','Próximo &gt;');
DEFINE('_KEY_NOT_FOUND','Termo não encontrado');


/** content.php */
DEFINE('_SECTION_ARCHIVE_EMPTY','Atualmente não há registros arquivados nessa seção. Por favor, volte mais tarde');
DEFINE('_CATEGORY_ARCHIVE_EMPTY','Atualmente não há registros arquivados nessa categoria. Por favor, volte mais tarde');
DEFINE('_HEADER_SECTION_ARCHIVE','Arquivos de Seção');
DEFINE('_HEADER_CATEGORY_ARCHIVE','Arquivos de Categoria');
DEFINE('_ARCHIVE_SEARCH_FAILURE','Não há registros arquivados para %s %s');	// values are month then year
DEFINE('_ARCHIVE_SEARCH_SUCCESS','Há registros arquivos para %s %s');	// values are month then year
DEFINE('_FILTER','Filtro');
DEFINE('_ORDER_DROPDOWN_DA','Data recente');
DEFINE('_ORDER_DROPDOWN_DD','Data antiga');
DEFINE('_ORDER_DROPDOWN_TA','Título. A-Z');
DEFINE('_ORDER_DROPDOWN_TD','Título. Z-A');
DEFINE('_ORDER_DROPDOWN_HA','Mais acessos');
DEFINE('_ORDER_DROPDOWN_HD','Menos acessos');
DEFINE('_ORDER_DROPDOWN_AUA','Autor. A-Z');
DEFINE('_ORDER_DROPDOWN_AUD','Autor. Z-A');
DEFINE('_ORDER_DROPDOWN_O','Por Ordem');

/** poll.php */
DEFINE('_ALERT_ENABLED','Cookies devem esta habilitados!');
DEFINE('_ALREADY_VOTE','Você já votou nesta enquete!');
DEFINE('_NO_SELECTION','Por favor, escolha uma das opções disponíveis e tente novamente');
DEFINE('_THANKS','Agradecemos seu voto!');
DEFINE('_SELECT_POLL','Selecione uma enquete na lista');

/** classes/html/poll.php */
DEFINE('_JAN','Janeiro');
DEFINE('_FEB','Fevereiro');
DEFINE('_MAR','Março');
DEFINE('_APR','Abril');
DEFINE('_MAY','Maio');
DEFINE('_JUN','Junho');
DEFINE('_JUL','Julho');
DEFINE('_AUG','Agosto');
DEFINE('_SEP','Setembro');
DEFINE('_OCT','Outubro');
DEFINE('_NOV','Novembro');
DEFINE('_DEC','Dezembro');
DEFINE('_POLL_TITLE','Enquete - Resultados');
DEFINE('_SURVEY_TITLE','Título da enquete:');
DEFINE('_NUM_VOTERS','Número de votos');
DEFINE('_FIRST_VOTE','Primeiro voto');
DEFINE('_LAST_VOTE','Último voto');
DEFINE('_SEL_POLL','Escolha a enquete:');
DEFINE('_NO_RESULTS','Não existem resultados para esta enquete.');

/** registration.php */
DEFINE('_ERROR_PASS','Sinto muito, não foi encontrado o usuário correspondente');
DEFINE('_NEWPASS_MSG','A conta de usuário $checkusername tem este e-mail associado a ela.\n'
.'Um usuário de $mosConfig_live_site acabou de requerer o envio de uma nova senha.\n\n'
.' A sua nova senha é: $newpass\n\nSe não a solicitou, não se preocupe.'
.' Apenas você recebeu esta mensagem, e caso a mesma tenha sido enviada devido a manutenção do site ou por engano,'
.' basta autenticar-se com a nova senha e alterá-la para uma de sua preferência.');
DEFINE('_NEWPASS_SUB','$_sitename :: nova senha para - $checkusername');
DEFINE('_NEWPASS_SENT','A nova senha foi criada e enviada!');
DEFINE('_REGWARN_NAME','Por favor, introduza o seu nome real.');
DEFINE('_REGWARN_UNAME','Por favor, introduza o seu Nome de Usuário.');
DEFINE('_REGWARN_MAIL','Por favor, introduza um endereço de e-mail válido.');
DEFINE('_REGWARN_PASS','Por favor, introduza uma senha válida. Sem espaços, mais de 6 caracteres contendo apenas valores nos intervalos 0-9,a-z,A-Z');
DEFINE('_REGWARN_VPASS1','Por favor, confirme a senha.');
DEFINE('_REGWARN_VPASS2','A senha e a sua confirmação não coincidem. Por favor, tente novamente.');
DEFINE('_REGWARN_INUSE','Este Nome de Usuário/senha já está em uso. Por Favor, tente outro.');
DEFINE('_REGWARN_EMAIL_INUSE', 'Este e-mail já está registado. Caso se tenha esquecido da sua senha então clique em "Perdeu a senha" e uma nova senha ser-lhe-á enviada.');
DEFINE('_SEND_SUB','Detalhes da conta para %s no %s');
DEFINE('_USEND_MSG_ACTIVATE', 'Olá %s,

Agradecemos o seu registo no site %s. A sua conta pessoal foi criada mas terá de ser ativada antes de a poder utilizar.
Para ativar a sua conta pessoal clique no link seguinte ou copie e cole no seu navegador:
%s

Depois da ativação você pode efetuar a autenticação no %s utilizando o Nome de Usuário e a senha indicados abaixo:

Nome de Utilizador - %s
Senha - %s');
DEFINE('_USEND_MSG', "Olá %s,

Agradecemos por ter se registado em nosso site %s.

Pode efetuar agora a autenticação em %s usando o Nome de Usuário e senha que se registrou.");
DEFINE('_USEND_MSG_NOPASS','Olá $name,\n\nfoi adicionado como usuário do site $mosConfig_live_site.\n'
.'Pode efetuar a autenticação no $mosConfig_live_site usando o Nome de Usuário e Senha com que se registrou.\n\n'
.'Por favor, não responda a esta mensagem pois foi gerada automaticamente pelo sistema tendo apenas carácter informativo\n');
DEFINE('_ASEND_MSG','Olá %s,

Foi registrado um novo usuário no site %s.
Este e-mail contém os detalhes deste usuário:

Nome - %s
E-mail - %s
Nome de Usuário - %s

Por favor, não responda a esta mensagem pois a mesma foi gerada automaticamente pelo sistema, com caráter a apenas informativo');
DEFINE('_REG_COMPLETE_NOPASS','<div class="componentheading">Registro efetuado com Sucesso!</div><br />&nbsp;&nbsp;'
.'Você pode agora efetuar a entrada no site.<br />&nbsp;&nbsp;');
DEFINE('_REG_COMPLETE', '<div class="componentheading">Registro efetuado com Sucesso!</div><br />Você pode agora efetuar a entrada no site.');
DEFINE('_REG_COMPLETE_ACTIVATE', '<div class="componentheading">Registro efetuado com Sucesso!</div><br />Sua conta foi criada e um endereço para ativação foi enviado para o e-mail informado em seu cadastro. Você deve ativar sua conta clicando no link de ativação enviado no e-mail antes de poder efetuar a entrada no site.');
DEFINE('_REG_ACTIVATE_COMPLETE', '<div class="componentheading">Ativação efetuada com Sucesso!</div><br />Sua conta foi ativada com sucesso. Você pode agora efetuar o login no site utilizando o Nome de Usuário e a Senha com os quais você se cadastrou.');
DEFINE('_REG_ACTIVATE_NOT_FOUND', '<div class="componentheading">Endereço de Ativação Inválido!</div><br />Não existe esta conta em nosso banco de dados ou a mesma já foi ativada.');
DEFINE('_REG_ACTIVATE_FAILURE', '<div class="componentheading">Falha na Ativação!</div><br />O sistema não foi capaz de ativar sua conta, por favor contate o administrador do site.');

/** classes/html/registration.php */
DEFINE('_PROMPT_PASSWORD','Esqueceu sua senha?');
DEFINE('_NEW_PASS_DESC','Por favor, informe seu Nome de Usuário e E-mail e clique no botão Enviar Senha.<br />'
.'Você receberá uma nova senha brevemente.  Utilize a nova senha para acessar o site.');
DEFINE('_PROMPT_UNAME','Nome de Usuário:');
DEFINE('_PROMPT_EMAIL','Endereço de E-mail:');
DEFINE('_BUTTON_SEND_PASS','Enviar Senha');
DEFINE('_REGISTER_TITLE','Cadastro');
DEFINE('_REGISTER_NAME','Nome:');
DEFINE('_REGISTER_UNAME','Nome de Usuário:');
DEFINE('_REGISTER_EMAIL','E-mail:');
DEFINE('_REGISTER_PASS','Senha:');
DEFINE('_REGISTER_VPASS','Confirme sua senha:');
DEFINE('_REGISTER_REQUIRED','Os campos assinalados com asterisco (*) são obrigatórios.');
DEFINE('_BUTTON_SEND_REG','Enviar Cadastro');
DEFINE('_SENDING_PASSWORD','Sua senha será enviada para o endereço de e-mail acima.<br />Uma vez você tenha recebido sua'
.' nova senha você poderá efetuar o login e alterá-la.');

/** classes/html/search.php */
DEFINE('_SEARCH_TITLE','Pesquisar');
DEFINE('_PROMPT_KEYWORD','Pesquisa de palavra chave');
DEFINE('_SEARCH_MATCHES','retornou %d resultados');
DEFINE('_CONCLUSION','Foram encontrados $totalRows resultados. Pesquisa por [ <b>$searchword</b> ] com');
DEFINE('_NOKEYWORD','Nenhum resultado encontrado');
DEFINE('_IGNOREKEYWORD','Uma ou mais palavras comuns foram ignoradas durante a pesquisa');
DEFINE('_SEARCH_ANYWORDS','Quaisquer as palavras');
DEFINE('_SEARCH_ALLWORDS','Todas as palavras');
DEFINE('_SEARCH_PHRASE','Frase exata');
DEFINE('_SEARCH_NEWEST','Mais recentes primeiro');
DEFINE('_SEARCH_OLDEST','Mais antigos primeiro');
DEFINE('_SEARCH_POPULAR','Mais acessados');
DEFINE('_SEARCH_ALPHABETICAL','Alfabética');
DEFINE('_SEARCH_CATEGORY','Seção/Categoria');
DEFINE('_SEARCH_MESSAGE', 'As palavras pesquisadas devem ter no mínimo 3 caracteres e no máximo 20 caracteres');
DEFINE('_SEARCH_ARCHIVED','Arquivado');
DEFINE('_SEARCH_CATBLOG','Categoria como Blog');
DEFINE('_SEARCH_CATLIST','Categoria como Lista');
DEFINE('_SEARCH_NEWSFEEDS','Notícias Externas');
DEFINE('_SEARCH_SECLIST','Seção como Lista');
DEFINE('_SEARCH_SECBLOG','Seção como Blog');


/** templates/*.php */
DEFINE('_ISO','charset=iso-8859-1');
DEFINE('_DATE_FORMAT','l, F d Y');  //Uses PHP's DATE Command Format - Depreciated
/**
* Modify this line to reflect how you want the date to appear in your site
*
*e.g. DEFINE("_DATE_FORMAT_LC","%A, %d %e %Y %H:%M"); //Uses PHP's strftime Command Format
*/
DEFINE('_DATE_FORMAT_LC',"%d-%b-%Y"); //Uses PHP's strftime Command Format
DEFINE('_DATE_FORMAT_LC2',"%d de %B de %Y %H:%M");
DEFINE('_SEARCH_BOX','');
DEFINE('_NEWSFLASH_BOX','Destaques!');  //NEWSFLASH
DEFINE('_MAINMENU_BOX','Principal');   //MAIN MENU

/** classes/html/usermenu.php */
DEFINE('_UMENU_TITLE','Usuário');   //USER MENU
DEFINE('_HI','Olá, ');

/** user.php */
DEFINE('_SAVE_ERR','Por favor, preencha todos os campos.');
DEFINE('_THANK_SUB','Agradecemos a sua colaboração. O seu item será verificado pelos administradores antes de ser exibido no site.');
DEFINE('_THANK_SUB_PUB','Agradecemos a sua colaboração.');
DEFINE('_UP_SIZE','Não é possível enviar arquivos com mais de 15kb.');
DEFINE('_UP_EXISTS','Já existe uma imagem com o nome $userfile_name. Por favor, altere o nome do seu arquivo e tente novamente.');
DEFINE('_UP_COPY_FAIL','Erro ao copiar');
DEFINE('_UP_TYPE_WARN','Apenas pode enviar imagens em formato gif ou jpg.');
DEFINE('_MAIL_SUB','Sugestão de usuário');
DEFINE('_MAIL_MSG','Olá $adminName,\n\nfoi submetido $type:\n [ $title ]\n sugerido pelo usuário:\n [ $author ]\n'
.' para o $mosConfig_live_site website.\n'
.'Por favor, acesse o $mosConfig_live_site/administrator para visualizar e aprovar este $type.\n\n'
.'Por favor, não responda a esta mensagem pois foi gerada automaticamente pelo sistema tendo apenas carácter informativo\n');
DEFINE('_PASS_VERR1','Se está a alterar a sua Senha, por favor, digite-a novamente para confirmação.');
DEFINE('_PASS_VERR2','Se está a alterar a sua Senha, certifique-se de que a Senha e sua confirmação sejam idênticas.');
DEFINE('_UNAME_INUSE','Este Nome de Usuário já está em uso.');
DEFINE('_UPDATE','Enviar');
DEFINE('_USER_DETAILS_SAVE','As suas configurações foram gravadas.');
DEFINE('_USER_LOGIN','Autenticação do usuário');

/** components/com_user */
DEFINE('_EDIT_TITLE','Editar sua Conta');
DEFINE('_YOUR_NAME','Seu nome:');
DEFINE('_EMAIL','E-mail:');
DEFINE('_UNAME','Nome de Usuário:');
DEFINE('_PASS','Senha:');
DEFINE('_VPASS','Confirme sua Senha:');
DEFINE('_SUBMIT_SUCCESS','Enviado com Sucesso!');
DEFINE('_SUBMIT_SUCCESS_DESC','Sua colaboração foi enviada para a administração do site e será revisada antes de ser publicada.');
DEFINE('_WELCOME','Bem Vindo!');
DEFINE('_WELCOME_DESC','Bem-vindo à seção do usuário de nosso site');
DEFINE('_CONF_CHECKED_IN','Itens bloqueados foram agora desbloqueados');
DEFINE('_CHECK_TABLE','Verificando tabela');
DEFINE('_CHECKED_IN','Bloqueados ');
DEFINE('_CHECKED_IN_ITEMS',' itens');
DEFINE('_PASS_MATCH','As senhas não coincidem');

/** components/com_banners */
DEFINE('_BNR_CLIENT_NAME','Deve indicar um nome para o cliente.');
DEFINE('_BNR_CONTACT','Deve indicar um contato para o cliente.');
DEFINE('_BNR_VALID_EMAIL','Deve indicar um e-mail válido para o cliente.');
DEFINE('_BNR_CLIENT','Deve indicar um cliente,');
DEFINE('_BNR_NAME','Deve indicar um nome para o banner.');
DEFINE('_BNR_IMAGE','Deve escolher uma imagem para o banner.');
DEFINE('_BNR_URL','Deve indicar um endereço/código personalizado para o banner.');

/** components/com_login */
DEFINE('_ALREADY_LOGIN','Você já está autenticado!');
DEFINE('_LOGOUT','Carregue aqui para terminar a Sessão');
DEFINE('_LOGIN_TEXT','Preencha os campos Nome de Usuário e Senha para iniciar sessão como membro');
DEFINE('_LOGIN_SUCCESS','Iniciou a sessão com sucesso');
DEFINE('_LOGOUT_SUCCESS','Terminou a sessão com sucesso');
DEFINE('_LOGIN_DESCRIPTION','Para acessar à área privada do site, por favor, inicie a sessão autenticando-se');
DEFINE('_LOGOUT_DESCRIPTION','Atualmente está autenticado na área privada deste site');


/** components/com_weblinks */
DEFINE('_WEBLINKS_TITLE','Weblinks');
DEFINE('_WEBLINKS_DESC','Nós estamos regularmente na Internet. Quando achamos um site legal nós o listamos'
.' aqui para o seu divertimento.  <br />Da lista abaixo selecione uma categoria e então um site para visitar.');
DEFINE('_HEADER_TITLE_WEBLINKS','Weblink');
DEFINE('_SECTION','Seção:');
DEFINE('_SUBMIT_LINK','Sugerir um Weblink');
DEFINE('_URL','URL:');
DEFINE('_URL_DESC','Descrição:');
DEFINE('_NAME','Nome:');
DEFINE('_WEBLINK_EXIST','Já há um weblink com este título. Por favor, tente novamente.');
DEFINE('_WEBLINK_TITLE','O weblink deve ter um título.');

/** components/com_newfeeds */
DEFINE('_FEED_NAME','Nome da Notícia externa');
DEFINE('_FEED_ARTICLES','nº de artigos');
DEFINE('_FEED_LINK','Endereço da Notícia externa');

/** whos_online.php */
DEFINE('_WE_HAVE', 'Temos ');
DEFINE('_AND', ' e ');
DEFINE('_GUEST_COUNT','%s visitante');
DEFINE('_GUESTS_COUNT','%s visitantes');
DEFINE('_MEMBER_COUNT','%s membro');
DEFINE('_MEMBERS_COUNT','%s membros');
DEFINE('_ONLINE',' on-line');
DEFINE('_NONE','Nenhum usuário on-line');

/** modules/mod_banners */
DEFINE('_BANNER_ALT','Anúncio');

/** modules/mod_random_image */
DEFINE('_NO_IMAGES','Sem Imagens');

/** modules/mod_stats.php */
DEFINE('_TIME_STAT','Horas');
DEFINE('_MEMBERS_STAT','Membros');
DEFINE('_HITS_STAT','Acessos');
DEFINE('_NEWS_STAT','Notícias');
DEFINE('_LINKS_STAT','Web Links');
DEFINE('_VISITORS','Visitas');

/** /adminstrator/components/com_menus/admin.menus.html.php */
DEFINE('_MAINMENU_HOME','* O primeiro item publicado neste menu [mainmenu] é por padrão a `Página Inicial` do site *');
DEFINE('_MAINMENU_DEL','* Não pode `eliminar` este menu porque ele é necessário ao correcto funcionamento do Joomla! *');
DEFINE('_MENU_GROUP','* Alguns `Tipos de Menu` aparecem em mais do que um grupo *');


/** administrators/components/com_users */
DEFINE('_NEW_USER_MESSAGE_SUBJECT', 'Detalhes do Novo Usuário' );
DEFINE('_NEW_USER_MESSAGE', 'Olá %s,


Você foi adicionado como um usuário do site %s pelo Administrador.

Este e-mail contem o seu Nome de Usuário e Senha para que possa fazer a autenticação no %s:

Nome de Usuário - %s
Senha - %s


Por favor, não responda a esta mensagem pois foi gerada automaticamente pelo sistema tendo apenas carácter informativo');

/** administrators/components/com_massmail */
DEFINE('_MASSMAIL_MESSAGE', "Este é um e-mail de '%s'

Mensagem:
" );


/** includes/pdf.php */
DEFINE('_PDF_GENERATED','Produzido em:');
DEFINE('_PDF_POWERED','Fornecido por Joomla!');
?>