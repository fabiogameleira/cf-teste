if (typeof jQuery === 'undefined') {
  throw new Error('Bootstrap\'s JavaScript requires jQuery')
}

(function($) {
       Drupal.behaviors.customToggler = {
         attach: function(context, settings) {
			
			$('a.pdf-icon').attr('target', '_blank');
			
			$('.imprimir').click(function(){
							
				 window.print();
				 return false;
			});	
			
			if ($('#edit-field-lista-de-presenca-und').is(":checked")) {
				   $('#edit-field-controle-presenca-und').attr( 'disabled', false );
			    } else {
					$('#edit-field-controle-presenca-und').attr( 'disabled', true);
				};	
				
			$('#edit-field-lista-de-presenca-und').change(function(){
				if ($('#edit-field-lista-de-presenca-und').is(":checked")) {
				   $('#edit-field-controle-presenca-und').attr( 'disabled', false );
			    } else {
					$('#edit-field-controle-presenca-und').prop('checked', false);
					$('#edit-field-controle-presenca-und').attr( 'disabled', true);
				};				
			});	
			
			
			$('#edit-bundle-participacao-assembleia-show-value-field-participa').prop('checked', true);
			$('.form-item-bundle-participacao-assembleia-show-value-field-participa').hide();
	        
			//Remover opções de papeis
			
			// User profile
			//usuário autenticado
			$('.form-item-roles-2').hide();
			
			// Administrador
			$('.form-item-roles-3').hide();
			
			// sem filiado
			$('.form-item-roles-11').hide();
			
			//suporte			
			$('.form-item-roles-12').hide();

            // ****** USER PROFILE ******

			//Operador
			$('#user-profile-form .form-item-roles-5').hide();
			
			//Coordenador
			$('#user-profile-form .form-item-roles-9').hide();
		
			//***** USER REGISTER *******
			
			// Pendente
			$('#user-register-form .form-item-roles-8').hide();
			
			// Conta Pendente
			$('#user-register-form .form-item-roles-10').hide();
			
			// Bulck operation de filiados pendentes
			
			// Administrador
			$('.form-item-properties-roles-3').hide();
			//operador
			//$('.form-item-properties-roles-5').hide();
			//pendente	
			//$('.form-item-properties-roles-8').hide();	
			//Coordenador	
			//$('.form-item-properties-roles-9').hide();			
			//conta pendente
			//$('.form-item-properties-roles-10').hide();
			// sem filiado não permit.
			$('.form-item-properties-roles-11').hide();	
			// Suporte.
			$('.form-item-properties-roles-12').hide();	

			// Adicionar papeis
			$('.form-item-properties--appendroles').hide();
			$('.form-item-status').hide();
			$('#edit-locale').hide();
			$('#edit-timezone').hide();
			$('#edit-contact').hide();
	        
			//Se está em branco não mostra mascara
	        
			if ($('.field-name-field-filiado-celular .field-item').html() != '&nbsp;' ) {
				$('.field-name-field-filiado-celular .field-item').mask('(99)99999-9999'); 
			}
			
			if ($('.field-name-field-filiado-celular .field-item').html() != '&nbsp;' ) {
				$('.field-name-field-filiado-celular .field-item').mask('(99)99999-9999'); 
			}
				
			if ($('.field-name-field-filiado-celular-2 .field-item').html() != '&nbsp;') {	
				$('.field-name-field-filiado-celular-2 .field-item').mask('(99)99999-9999'); 
			}
			if ($('.field-name-field-filiado-residencial .field-item').html() != '&nbsp;') {
				$('.field-name-field-filiado-residencial .field-item').mask('(99)9999-9999'); 
			}
			if ($('.field-name-field-filiado-comercial .field-item').html() != '&nbsp;') {
				$('.field-name-field-filiado-comercial .field-item').mask('(99)9999-9999');
			}
			
			//remove mask input
			//$('#filiado-node-form').submit(function() {
			//	$('#edit-field-tel-cel-und-0-value').unmask();
			//	$('#edit-field-tel-cel-2-und-0-value').unmask();
			//	$('#edit-field-tel-res-und-0-value').unmask();
			//	$('#edit-field-tel-com-und-0-value').unmask();
			//});
		    //$('#user-profile-form').submit(function() {
			//	$('#edit-field-telefone-celular-und-0-value').unmask();
			//});		
        }
	   }
		
})(jQuery);
	
