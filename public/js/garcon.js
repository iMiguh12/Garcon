/* 
 * Control de la orden de productos
 */
$(document).ready(function(){
	var array = new Array();
	var cantProd;	
	$('.compra').click(function(){
		var idProd = $(this).parent().attr("id");
		var nomProd = $(this).parent().find(".nombre").html();
		var precProd = $(this).parent().find(".precio").html();
				
		if ( buscarProd(array,idProd) ){
			//Si ya existe sumamos el valor
			
			var cantProd = (parseInt($('#prod-'+idProd).children(".nombre").find('em').html())) + 1;
			var sumProd =  precProd*cantProd;
			$('#prod-'+idProd).children(".nombre").find('.precio').html(sumProd);
			$('#prod-'+idProd).children(".nombre").find('em').html(cantProd);
			
			
		}else{
			array.push(idProd);
			$('#prod-selec ul').append("<li id=\"prod-" + idProd + "\"><p class=\"nombre\"> <em>1</em> "+ nomProd + "---> $<span class=\"precio\">"+ precProd + "</span></p></li>");
		}
	});
});
/* Buscamos si ya se ha agregado el producto a la lista
   retornamos verdadero sí si.
*/
function buscarProd(arr, id) {
	  for(var i=0; i<arr.length; i++) {
	   	if(arr[i]==id){
	   		return true;
	   	}else{
            return false;
        }
        
	  }
	}

