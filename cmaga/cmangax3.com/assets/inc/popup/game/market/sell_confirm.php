<div class="popup_module center_important game_market_sell_confirm">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="buy_confirm item">
        <div class="preview">
            <img src="/assets/img/transparent.png" />
            <p class="name"></p>
        </div>
        <div class="confirm">
            <div class="item item_amount">
                <p class="label text_amount"></p>
                <div class="amount">
                    <button target = "minus" onclick="shop_confirm_value_change(this)" class="minus">-</button>
                    <input style="width: 100px;" onkeyup="shop_confirm_cal_price();" maxlength="5" value="1">
                    <button target = "plus" onclick="shop_confirm_value_change(this)" class="plus">+</button>
                </div>
            </div>
            <div class="item item_price">
                <p class="label text_price"></p>
                <div class="amount">
                    <button target = "minus" onclick="shop_confirm_value_change(this)" class="minus">-</button>
                    <input style="width: 100px;" onkeyup="shop_confirm_cal_price();" maxlength="5" value="0">
                    <button target = "plus" onclick="shop_confirm_value_change(this)" class="plus">+</button>
                </div>
            </div>
            <div class="item">
                <p class="label text_total_fee"></p>
                <div class="price total_price"><span class="num">0</span> <span class="currency"><img src="/assets/img/level/currency/crystal.png"/></span></div>
            </div>
            <div class="item">
                <p class="label text_total_get"></p>
                <div class="price total_take"><span num="0" class="num">0</span> <span class="currency"><img src="/assets/img/level/currency/crystal.png"/></span></div>
            </div>
            <div class="item">
                <p class="label text_market_fee"></p>
                <div class="price gold"><span class="num">500</span> <span class="currency"><img src="/assets/img/level/currency/gold.png"/></span></div>
            </div>
        </div>
        <div class="button">
            <button style="width:100%;margin:0;" onclick="game_market_sell(this);" class="text_market_sell"></button>
        </div>
    </div>
</div>
<script>
    async function popup_game_market_sell_confirm() {
        $('.popup_module.game_market_sell_confirm .preview img').attr('src',popup_data.avatar);
        if(popup_data.type == 'equipment'){
            $('.popup_module.game_market_sell_confirm .confirm .item_amount').hide();
            $('.popup_module.game_market_sell_confirm .preview .name').html(json_data.language[popup_data.sign]);
        }else{
            $('.popup_module.game_market_sell_confirm .confirm .item_amount').show();
            $('.popup_module.game_market_sell_confirm .preview .name').html(json_data.language[popup_data.type+'_'+popup_data.sign]);
        }
        shop_confirm_cal_price();
    }
    function shop_confirm_cal_price(){
        var item_amount = parseInt($('.popup_module.game_market_sell_confirm .confirm .item_amount input').val());
        var item_price = $('.popup_module.game_market_sell_confirm .confirm .item_price input').val();
        if(item_amount >= popup_data.amount){
            $('.popup_module.game_market_sell_confirm .confirm .item_amount input').val(popup_data.amount);
            item_amount = popup_data.amount;
        }
        var total_price = item_amount * item_price;
        $('.popup_module.game_market_sell_confirm .confirm .total_price .num').html(number_format(number_fix(total_price)));
        $('.popup_module.game_market_sell_confirm .confirm .total_take .num').html(number_format(number_fix(total_price*0.9)));
    }
    function shop_confirm_value_change(e){
        var target = $(e).attr('target');
        if(target == 'plus'){
            if($(e).parent().children('input').val() < 999){
                $(e).parent().children('input').val(parseInt($(e).parent().children('input').val())+1);
            }
        }else{
            if($(e).parent().children('input').val() > 1){
                $(e).parent().children('input').val(parseInt($(e).parent().children('input').val())-1);
            }
        }
        shop_confirm_cal_price();
    }
    function game_market_sell(e){
        var item_amount = parseInt($('.popup_module.game_market_sell_confirm .confirm .item_amount input').val());
        var item_price = $('.popup_module.game_market_sell_confirm .confirm .item_price input').val();
        if(item_price >= 0.1 && item_amount >= 1){
            $(e).prop('disabled', true);
            $.post("/assets/ajax/market.php", { action : "sell" , item_data : JSON.stringify(popup_data), item_amount : item_amount, item_price : item_price})
            .done(function(data) {
                $('#result').empty().append(data);
                delete bag_data[popup_data.type];
                popup_game_market_sell(popup_data.type);
                $('.popup_module.game_market_sell_confirm').parent('.popup_content').remove();
            });
        }else{
            alertify.error(language_text('text_market_sell_empty'));
        }
    }
    language_render(['text_amount','text_price','text_total_fee','text_total_get','text_market_fee','text_market_sell']);
    popup_game_market_sell_confirm();
</script>