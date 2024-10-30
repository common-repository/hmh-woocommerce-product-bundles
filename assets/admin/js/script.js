(function($) {
    'use strict';
    
    $(document).ready(function() {
        hmhpb_get_total_price();

        if ( $('#hmh_disable_auto_price').is( ':checked' ) ) {
            $('#hmh_disable_auto_price').val(1);
            $('.hmh_show_auto_price').hide( "slow" );
        }else{
            $('#hmh_disable_auto_price').val('');
            $('.hmh_show_auto_price').show( "slow" );
            $('#hmh_disable_auto_price').removeClass('hmh_checked');
        }

        if ( $('#hmh_optional_products').is( ':checked' ) ) {
            $('#hmh_optional_products').val(1);
            $('.hmh_show_limit_item_products').show( "slow" );
        }else{
            $('#hmh_optional_products').val('');
            $('.hmh_show_limit_item_products').hide( "slow" );
            $('#hmh_optional_products').removeClass('hmh_checked');
        }

        $('.bb-button-delete2').live('click', function(){
            var $self = $(this),
                id = $self.data('id'),
                $table = $self.closest('table').DataTable(),
                $row = $self.closest('tr');
                
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this Header!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then(function(willDelete){
                
                if (willDelete) {
                    $('.bb-ajax-loading').css({display: 'flex'});
                    $.post(ajaxurl, { 'action': 'Hmh_Wpb_delete_header', id: id }, function(response) {
                        
                        response = JSON.parse(response);
                        if(typeof response.status != 'undefined') {
                            $.growl({ title: response.title, message: response.message, location: 'br', style: response.status });
                            
                            if(response.status == 'notice') {
                                $table.row($row).remove();
                                $table.draw();
                            }
                        }
                        $('.bb-ajax-loading').css({display: 'none'});
                        
                    });
                }
            });
            return;
        });

        $('.remove_bundle').live('click', function(){
            $(this).closest('tr').remove();
            hmhpb_get_total_price();
            return false;
        });
    });

    $('#add_gift_product_bundle').on('click change', function(){
        
        var p = $('#choose_gift_product_bundle').val(),
            txt = $('#hmh-opt-id-'+ p ).text(),
            thumb= $('#hmh-opt-id-'+ p ).attr('data-src'),
            sales= $('#price_sales_'+ p ).attr('data-sales'),
            prices= $('#price_'+ p ).attr('data-prices');

        sales = accounting.formatMoney(sales, { symbol: "VND", precision : 3, format: "%v %s" });
        prices = accounting.formatMoney(prices, { symbol: "VND", precision : 3, format: "%v %s" });

        if($('#hmh-pb-quantity-'+p).length > 0) {
            var q = $('#hmh-pb-quantity-'+p).val();
            $('#hmh-pb-quantity-'+p).val(q*1+1);
            hmhpb_get_total_price();
            return;
        }

        if($('#list_gif_product_bundle').attr('data-id') == p) {
            return;
        }
        var html = '<tr>'
                        +'<td><img src="'+thumb+'" alt="image/png" style="max-width:40px; max-height:40px;" />'+'</td>'
                        +'<td class="hmh-product">'+txt+'</td>'
                        +'<td class="hmh-product">'
                            // if ($('#price_'+ p ).attr('data-prices') > 0 ) {
                                +'<p id="prices_'+p+'" class="hmh-price" data-price="'+$('#price_'+ p ).attr('data-prices')+'">'+prices+'</p>'
                            // }else{
                                // +'<p id="prices_sales_'+p+'" class="hmh-price-sales"  data-price_sale="'+$('#price_sales_'+ p ).attr('data-sales')+'">'+sales+'</p>'
                            // }
                        +'</td>'
                        +'<td class="hmh-quantity"><div class="box-input"><input id="hmh-pb-quantity-'+p+'" class="hmhpd-quantity" min="1" type="number" name="_gift_product_bundle['+p+'][\'quantity\']" value="1" / ></div></td>'
                        +'<td><button type="button" class="remove_bundle">Remove</button></td>'
                    +'</tr>';
        $('#list_gif_product_bundle').append(html);
        hmhpb_get_total_price();

        // if($('#list_gif_product_bundle').attr('data-id') == p) {
        //     $('#hmh-pb-quantity-'+p).on('change', function(){
        //         hmhpb_get_total_price();
        //     });
        // }
    });

    $('#hmh_disable_auto_price').on('change', function(){
        $(this).addClass('hmh_checked');
        if ( $( this ).is( ':checked' ) ) {
            $(this).val(1);
            $('.hmh_show_auto_price').hide( "slow" );
        }else{
            $(this).val('');
            $('.hmh_show_auto_price').show( "slow" );
            $(this).removeClass('hmh_checked');
        }
    });

    $('#hmh_optional_products').on('change', function(){
        $(this).addClass('hmh_checked');
        if ( $( this ).is( ':checked' ) ) {
            $(this).val(1);
            $('.hmh_show_limit_item_products').show( "slow" );
        }else{
            $(this).val('');
            $('.hmh_show_limit_item_products').hide( "slow" );
            $(this).removeClass('hmh_checked');
        }
    });


    $('.hmhpd-quantity').live('change click', function(){
        hmhpb_get_total_price();
    });


    function hmhpb_get_total_price(){
        var total_price = 0,
            p = $('#choose_gift_product_bundle').val();

        $( "#list_gif_product_bundle .hmhpb-products tr" ).each(function( index ) {
            var price,
                quantity = $(this).find('.hmhpd-quantity').val();
            if($(this).find('.hmh-price').attr('data-price') != '') {
                price = $(this).find('.hmh-price').attr('data-price');
            } else {
                price = $(this).find('.hmh-price-sales').attr('data-price_sale');
            }
            total_price += parseFloat(price) * parseInt(quantity);
        });

        total_price = accounting.formatMoney(total_price, { symbol: "VND", precision : 3, format: "%v %s" }); // precision : 3, format 3 số 0 đằng sau
        $('#hmh_regular_price').text(total_price);
        return total_price;
    }

    
}(window.jQuery));