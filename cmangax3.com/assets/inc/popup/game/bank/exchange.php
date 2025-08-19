<div class="popup_module center_div game_bank_exchange">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="buy_confirm item">
        <div class="preview">
            <div class="image"><img src="/assets/img/level/menu/dungeon/gold.png" /></div>
            <p class="name text_bank_exchange"></p>
        </div>
        <div class="confirm">
            <div class="item">
                <p class="label text_current_have"></p>
                <div class="price balance"><span num="0" class="num">0</span> <span class="currency"><img src="/assets/img/level/currency/crystal.png"/></span></div>
            </div>
            <div class="item item_amount">
                <p class="label text_amount"></p>
                <div class="amount">
                    <button target = "minus" onclick="shop_confirm_value_change(this)" class="minus">-</button>
                    <input style="width: 100px;" onkeyup="shop_confirm_cal_price();" maxlength="5" value="1">
                    <button target = "plus" onclick="shop_confirm_value_change(this)" class="plus">+</button>
                </div>
            </div>
            <div class="item">
                <p class="label text_total_get"></p>
                <div class="price total_take"><span class="num">0</span> <span class="currency"><img src="/assets/img/level/currency/gold.png"/></span></div>
            </div>
        </div>
        <div class="button">
            <button style="width:100%;margin:0;" onclick="game_bank_exchange(this);" class="text_button_exchange"></button>
        </div>
    </div>
</div>
<script>
    async function popup_game_bank_exchange(){
        var character_balance = await get_data_by_url(`/api/character_count?character=${my_character}&type=currency&sign=crystal`);
        $('.popup_module .confirm .balance .num').html(number_format(character_balance));
        $('.popup_module .confirm .balance .num').attr('num',character_balance);
        shop_confirm_cal_price();
    }
    function shop_confirm_cal_price(){
        var item_amount = parseInt($('.popup_module.game_bank_exchange .confirm .item_amount input').val());
        var total_take = item_amount  * (100+(parseInt(server_data.setting.level)*2));
        $('.popup_module.game_bank_exchange .confirm .total_take .num').html(number_format(total_take));
    }
    function shop_confirm_value_change(e){
        var target = $(e).attr('target');
        if(target == 'plus'){
            if($(e).parent().children('input').val() < 99999){
                $(e).parent().children('input').val(parseInt($(e).parent().children('input').val())+1);
            }
        }else{
            if($(e).parent().children('input').val() > 1){
                $(e).parent().children('input').val(parseInt($(e).parent().children('input').val())-1);
            }
        }
        shop_confirm_cal_price();
    }
    function game_bank_exchange(e){
        var item_amount = parseInt($('.popup_module.game_bank_exchange .confirm .item_amount input').val());
        var character_balance = parseInt($('.popup_module .confirm .balance .num').attr('num'));
        if(item_amount <= character_balance && item_amount >= 1){
            $(e).hide();
            $.post("/assets/ajax/character.php", { action : "game_bank_exchange" , amount : item_amount})
            .done(function(data) {
                $('#result').empty().append(data);
                $(e).show();
            });
        }else{
            alertify.error(language_text('text_not_enought_resource'));
        }
    }
    popup_game_bank_exchange();
    language_render(['text_bank_exchange','text_current_have','text_amount','text_total_get','text_button_exchange']);
</script>