/*
Mise a jour de div select en html
ajout des options

data            => paramètres de l'url en plus de valeur valeur selectionnée
url             => le php appelé
divselect       => div du onchange
divmaj          => la div à mettre à jour
parameters      =>
            divtogglefalse  => div à cacher (format json)
            divtoggletrue   => div à révéler (format json)
            divselectpicker => div select à rafraichir (format json)
valid           => true or false for the visibility of the validate button
msgerr          => message in case of ajax call trouble
*/

	///// Fonction loading ////
	afficherLoading = 

	function() {
		$.blockUI({ 
				message: $('#divLoading'), 
				css: { 
					top:  ($(window).height() - 300) /2 + 'px', 
					left: ($(window).width() - 400) /2 + 'px', 
					width: '400px',
					border: 'none', 
					padding: '15px', 
					backgroundColor: 'rgba(51, 51, 51, 0)',
					'-webkit-border-radius': '10px', 
					'-moz-border-radius': '10px', 
					opacity: .9, 
					color: '#00E2D3' 
				} 
			});
	};
	
	closeLoading = 
	
		function() {
			$.unblockUI();
		};
		
$(function(){
	$('#valider, #retour, #delete').click(function(){
		afficherLoading();
	});
});
	

function majdivselect(  data,
                        url,
                        divselect,
                        divmaj,
                        parameters,
                        valid,
                        msgerr
                      ){
                  
    $(divselect).change(function() {
        
        $.each( parameters.divtogglefalse, function( key, obj) {
            $(obj.name).toggle(false);
        });
        
        $("#valider").prop('disabled', valid);

        var donnee = "data=" + $(this).val()+'&client='+$(data).val();
        
        $.ajax({
            type        : "GET",
            data        : donnee,
            url         : url,
            async	: false,
            beforeSend	: function () { afficherLoading(); },
            complete	: function () { closeLoading(); },
            success: function(msg){

                if (msg != '')
                {		
                        $(divmaj).html(msg);

                }
                else
                {
                        $(divmaj).html(msgerr);		
                }
                
                $.each( parameters.divselectpicker, function( key, obj) {
                    $(obj.name).selectpicker('refresh');
                });
                
                $.each( parameters.divtoggletrue, function( key, obj) {
                    $(obj.name).toggle(true);
                });
	
           }   
        });

    });
	
}