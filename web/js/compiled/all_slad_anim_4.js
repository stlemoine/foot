/*!
 * Copyright 2014 SLAD 
 */
// les alertes disparaissent progressivement
$(".alert").fadeOut(10000);

// Fonctions liées au loading
$(function(){

	// Afficher le loading
	afficherLoading = function() 
    {
		$.blockUI({ 
				message: $('#divLoading'), 
				css: { 
                    border: 'none', 
                    padding: '15px', 
                    //backgroundColor: '#000', 
                    '-webkit-border-radius': '10px', 
                    '-moz-border-radius': '10px', 
                    opacity: .5, 
                    color: '#fff' 
				} 
			});
	};
	
	closeLoading = function() 
    {
        $.unblockUI();
    };
    
    
    // Lancement du loader à chaque fois que le menu est cliqué
	$(".navbar-nav a").each(function(){
		$(this).click(function(){ 
			if($(this).attr("class") !== "dropdown-toggle"){
				afficherLoading();
			}
		});
	})

});


