<?php
/**
 * @file
 * The primary PHP file for this theme.
 */


function malao_node_preview($variables){
  
  $node = $variables['node'];

  $output = '<div class="preview">';

  $preview_trimmed_version = FALSE;

  //$elements = node_view($node, 'full');
  //$full = drupal_render($elements);

  // Do we need to preview trimmed version of post as well as full version?
  $output .= '<p class="pre-visualizar">Pré-visualizar: Você pode fazer alterações abaixo e clicar no botão "Salvar" quando terminar.</p>';

  $output .= $full;

  $output .= "</div>\n";

  return $output;

}

// replace the meta content-type tag for Drupal 7
function malao_html_head_alter(&$head_elements) {
  $head_elements['system_meta_content_type']['#attributes'] = array(
	'charset' => 'utf-8'
  );
  unset($head_elements['system_meta_generator']);
}

/**
 * Implements hook_theme().
 *
 * Register theme hook implementations.
 *
 * @see _bootstrap_theme()
 */
function malao(&$existing, $type, $theme, $path) {
  return _bootstrap_theme($existing, $type, $theme, $path);
}

function malao_breadcrumb($variables){ 
  $output = '';
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {

     if (theme_get_setting('bootstrap_breadcrumb_home') == 0) {
		$breadcrumb[0] = strip_tags($variables['breadcrumb'][0]);
	 }
	 else {
	 	$migalha = $variables['breadcrumb'][1];
	 	if (is_string($migalha)) {
			$breadcrumb[1] = strip_tags($migalha);
	 	}	
     }

     
	 // Se exibe última página no final setado no tema
	 if (theme_get_setting('bootstrap_breadcrumb_title') == 1) {
	     $breadcrumb[count($breadcrumb)-1] = drupal_get_title();
     }
	 
     $output = '<ol class="breadcrumb"><li>' . implode( '</li><li>', $breadcrumb) . '</li></ol>';
  }
	 
     return $output;
}

//function malao_preprocess_node(&$variables) {
  //dpm($variables);
//}

function malao_form_alter( &$form, &$form_state,$form_id ){


    // TRATA CAMPOS QUE NÃO PODEM SER EDITADOS PELO FILIADO
    if (isset($form['#node_edit_form'])){
		if ($form['#node_edit_form'] == TRUE) {
			
			/* Seta campos não editáveis para hidden */
			/*
			  Campos de edição do filiado:
			  CPF, Nome, area, siape, siape2
			  
			  Campos de edição exclusiva do operador - Não precisa tratar pois o filiado
			  não edita:
			  data de filiação, data de desligamento
			  contratado, auxilio funeral, cpf inst, nome inst, siape inst, observação
			*/
			global $user;

			if ($form_id == "assembleia_node_form"){
				
				//$form['title']['#disabled'] = TRUE;
				// não mostrar o campo título (Ccódigo da assembleia) na edição		 
				$form['title']['#access'] = FALSE;
			}
			
			/*
			if ($form_id == "participacao_assembleia_node_form"){
				//$form['title']['#disabled'] = TRUE;
				// não mostrar o campo título (Ccódigo da assembleia) na edição	
				//$form['title']['#access'] = FALSE;
				$form['title']['#disabled'] = TRUE;
				$form['field_cpf']['#disabled'] = TRUE;	
				$form['field_assembleia']['#disabled'] = TRUE;	
				$form['field_idcpf']['#disabled'] = TRUE;
				$form['field_idassembleia']['#disabled'] = TRUE;
				//$form['title']['#access'] = FALSE;
			}*/
			
			

			if ($form_id == "filiado_node_form"){

                if (empty($form['field_autorizacao']['und'][0]['value']['#default_value'])) {
					//Definido em thema - aparencia
					$msg_autorizacao = theme_get_setting('malao_msg_autorization');
					$form['field_autorizacao']['und'][0]['value']['#default_value'] = $msg_autorizacao;
				}
				$form['field_autorizacao']['#disabled'] = TRUE;
				
				if 	(in_array('filiado', $user->roles) or in_array('pendente', $user->roles) or in_array('sem filiado', $user->roles) or in_array('coordenador', $user->roles)) {		
						// Verificar se usuário é filiado ou pendente ou sem filiado	
						// com conteúdo preenchido, se sim não permitir edição.	

						if (!empty($form['field_cpf']['und'][0]['value']['#default_value'])) {					
							 $form['field_cpf']['#disabled'] = TRUE;
						} else {
							 $form['field_cpf']['und'][0]['value']['#default_value'] = $user->name; 
							 $form['field_cpf']['#disabled'] = TRUE;						 
						}
						  
						if (!empty($form['field_nome']['und'][0]['value']['#default_value'])) {					  
							 $form['field_nome']['#disabled'] = TRUE;
						}	
						  
						if (empty($form['field_mail']['und'][0]['value']['#default_value'])) {	
							$form['field_e_mail']['und'][0]['value']['#default_value'] = $user->mail; 
						}	
						  
						if (!empty($form['field_siape']['und'][0]['value']['#default_value'])) {	
							 $form['field_siape']['#disabled'] = TRUE;
						}
						
						unset($form['revision_information']);  

				}
				/*
				if 	(in_array('operador', $user->roles)) {		
					// Verificar se usuário é operador e só habilita campos
					// de edição exclusiva do operador 
					// conforme o cruzamento com a situação sindical.	
                    
					$situacao_sindical = strtolower($form['field_situacaofiliado']['und'][0]['value']['#default_value']);
					
					if ( $situacao_sindical == "aposentado") {		
                          // habilita data de aposentadoria					
							 $form['field_aposentadoria']['#disabled'] = FALSE;
							 $form['field_aposentadoria_1']['#disabled'] = FALSE;
					} else {
						  // desabilita data de aposentaddoria
							 $form['field_aposentadoria']['#disabled'] = TRUE;
							 $form['field_aposentadoria_1']['#disabled'] = TRUE;
                    }							 
				}*/
				
				/*
				* Popula um campo Field List dinamicamente em um formulário e
				* grava o valor selecionado, mas quando edita novamente perde.
				* informação do valor gravado.
				* A funcionalidade do Field List preve que a lista de itens
				* selecionáveis dever estar carregada no configuração do campo.
				* O Field List tem um atributo allow fields que até permite que
				* a lista de itens seja atualizada dinamicamente, mas o problema
				* é que isto irá replicar a tabela carregada dinamicamente no form
				* para todos os registros.
				*/
				
				$field_id = 'field_bancocbc';
                
				$query = new EntityFieldQuery();
				$results = $query->entityCondition('entity_type', 'node')
								 ->entityCondition('bundle', 'bancos')
								 ->fieldCondition('field_banco', 'value', 'NULL', '!=')
								 ->fieldOrderBy('field_banco', 'value', 'ASC')                    
								 ->execute();

				
				$nodes = $results['node'];
				field_attach_load('node', $nodes, FIELD_LOAD_CURRENT, array('field_bancocbc' => $field_id));			
				
				$options = array('_none' => '- Nenhum -');

				foreach($nodes as $nid => $node) {
				  $value = $node->field_banco['und'][0]['value'];
				  $nome  = $node->field_nome_do_banco['und'][0]['value'] . ' - '. $value;
				  $options[$value] = $nome;
				}		  
				asort($options);
					//busca o valor do field_bancocbc do filiado
					//para setar o valor selecionado no campo banco do form
					
					$cpf = $form['field_cpf']['und'][0]['value']['#default_value'] ;
					$queryfiliado = new EntityFieldQuery();
					$resultsfiliado = $queryfiliado->entityCondition('entity_type', 'node')
									 ->entityCondition('bundle', 'filiado')
									 ->propertyCondition('status', NODE_PUBLISHED)								 
									 ->propertyCondition('title', $cpf)                 
									 ->execute();				 
				if (!empty($resultsfiliado)) {	
					$filiados = node_load_multiple(array_keys($resultsfiliado['node']));
					foreach ($filiados as $filiado) { 
					    
					    if (isset($filiado->field_bancocbc['und'][0]['value'])) {
							$cbc = $filiado->field_bancocbc['und'][0]['value'];
						} else {
							$cbc = 0;
						}
						// Pesquisa se o CBC está na lista de bancos
						// Se não tiver insere o CBC como não identificado.
						if (!empty($cbc)) {
							if (!isset($options[$cbc])) {
								//dpm('nao achou');
								$value = $cbc;
								$nome = "Não identificado - ". $value;
								$options[$value] = $nome;
								asort($options);
							}
						}
					}
				} else {
				  $cbc = 0;
				}
					// Preenche o list box fieldbancocbc
					$form[$field_id]['und']['#type'] = 'select';
					$form[$field_id]['und']['#default_value'] = $cbc;
					$form[$field_id]['und']['#options'] = $options;
					//$form[$field_id]['und']['#size'] = 1;
					if (isset($form[$field_id]['und']['#size'])) {
						$form[$field_id]['und']['#size']= 1 ;
					}    
					
        							 
			}	
			
		}
    }
 }