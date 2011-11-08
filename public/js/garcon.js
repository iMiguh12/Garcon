/* 
 * Control de la orden de productos
 */
$(document).ready(function(){
	var array = new Array();
	var cantProd;	
          var totalOrden=0;
	$('.compra').click(function(){
		var idProd = $(this).parent().parent().attr("id");
                    var nomProd = $(this).parent().find(".nombre").html();
		var precProd = $(this).parent().find(".precio").html();
                   //acumulamos el precio para sacar el total
                   totalOrden= totalOrden + (parseInt(precProd));
                    
		if ( buscarProd(array,idProd) ){
			//Si ya existe sumamos el valor
			
			var cantProd = (parseInt($('#prod-'+idProd).children(".nombre").find('em').html())) + 1;
			var sumProd =  precProd*cantProd;
			$('#prod-'+idProd).children(".nombre").find('.precio').html(sumProd);
			$('#prod-'+idProd).children(".nombre").find('em').html(cantProd);
			$('#total').html(totalOrden);
			
		}else{
			array.push(idProd);
			$('#prod-select ul').append("<li id=\"prod-" + idProd + "\"><p class=\"nombre\"> <em>1</em> "
                                                                + nomProd + "---> $<span class=\"precio\">"+ precProd + "</span></p></li>");
                               $('#total').html(totalOrden);
                                             
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
                              break;
	   	}
        	  }
	}

