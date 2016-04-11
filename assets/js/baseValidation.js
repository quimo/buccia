$('#privato_check').click(function(){
			$('#privato').show();
			$('#societa').hide();
		});
		$('#societa_check').click(function(){
			$('#privato').hide();
			$('#societa').show();
		});
		$('.field').focus(function(){
			$(this).addClass('field-focus');
			$(this).removeClass('field-blur');
		});
		$('.field').blur(function(){
			$(this).removeClass('field-focus');
			$(this).addClass('field-blur');
		});
		
		//validazione
		$('#consenso').submit(function(){
			$('.warning-text').hide();
			var error = 0;	
			if ($('#privato_check').is(':checked')) {
				//nome
				var nome = $('#nome').val();
				nome = nome.trim();
				$('#nome').removeClass('warning');
				if (nome.length < 2) {
					$('#nome').addClass('warning');
					$('#nome-warning').show();
					error = 1;
				}
				//cognome
				var cognome = $('#cognome').val();
				cognome = cognome.trim();
				$('#cognome').removeClass('warning');
				if (cognome.length < 2) {
					$('#cognome').addClass('warning');
					$('#cognome-warning').show();
					error = 1;
				}
			} else {
				//ragione sociale
				var ragione_sociale = $('#ragione_sociale').val();
				ragione_sociale = ragione_sociale.trim();
				$('#ragione_sociale').removeClass('warning');
				if (ragione_sociale.length < 2) {
					$('#ragione_sociale').addClass('warning');
					$('#ragione_sociale-warning').show();
					error = 1;
				}
				//rappresentante legale
				var rappresentante_legale = $('#rappresentante_legale').val();
				rappresentante_legale = rappresentante_legale.trim();
				$('#rappresentante_legale').removeClass('warning');
				if (rappresentante_legale.length < 2) {
					$('#rappresentante_legale').addClass('warning');
					$('#rappresentante_legale-warning').show();
					error = 1;
				}
			}			
			//email
			var email = $('#email').val();
			$('#email').removeClass('warning');
			var email_regexp = new RegExp("^[a-zA-Z0-9\._\-]+[@]{1}([a-zA-Z0-9-_]+[\.]{1})?([a-zA-Z0-9-_]+[\.]{1}[a-zA-Z0-9-_]{2,4})$");
			if (email.search(email_regexp) == -1) {
				$('#email').addClass('warning');
				$('#email-warning').show();
				error = 1;
			}
			//accept
			$('.accept').removeClass('warning');
			if (!$('#accept').is(':checked')) {
				$('.accept').addClass('warning');
				error = 1;
			}
			if (error) return false;
			return true;
		});
		
		//aggiunge il metodo trim
		String.prototype.trim = function() {
			return this.replace(/^\s+|\s+$/g,"");
		}