$( function() {
    $( "#slider-range" ).slider({
      range: true,
      min: 0,
      max: maxslider,
      values: [ $('#slider0').val(), $('#slider1').val() ],
      slide: function( event, ui ) { //Evento de deslizamento
        $( "#amount" ).val( "R$" + ui.values[ 0 ] + " - R$" + ui.values[ 1 ] );
      },
      change: function (event, ui) { //Ao ocorrer alguma alteração nos valores
        $("#slider"+ui.handleIndex).val(ui.value); //ui.value estará o valor selecionado, ui.handleIndex é o posição do valor modificado, no caso Slider0 é 0 e Slider1 é 1
        $('.filterarea form').submit();
      }
    });


    $( "#amount" ).val( "R$" + $( "#slider-range" ).slider( "values", 0 ) +
      " - R$" + $( "#slider-range" ).slider( "values", 1 ) );
  
  
    $('.filterarea').find('input').on('change', function(){ //Se há uma alteração nos falores dentro dessa div, é atualizado automaticamente
      $('.filterarea form').submit();
    });

    } );
  