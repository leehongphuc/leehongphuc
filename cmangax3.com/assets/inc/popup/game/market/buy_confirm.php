<div class="popup_module center_important game_market_buy_confirm">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="buy_confirm item">
        <div class="preview">
            <div class="image"><img src="/assets/img/transparent.png" /></div>
            <p class="name"></p>
        </div>
        <div class="confirm">
            <div style="display: none;" class="item item_amount">
                <p class="label text_amount"></p>
                <div class="amount">
                    <button target = "minus" onclick="shop_confirm_value_change(this)" class="minus">-</button>
                    <input style="width: 100px;" onkeyup="shop_confirm_cal_price();" maxlength="5" value="1">
                    <button target = "plus" onclick="shop_confirm_value_change(this)" class="plus">+</button>
                </div>
            </div>
            <div class="item">
                <p class="label text_price"></p>
                <div class="price unit_price"><span class="num">0</span> <span class="currency"><img src="/assets/img/level/currency/crystal.png"/></span></div>
            </div>
            <div class="item">
                <p class="label text_total_fee"></p>
                <div class="price total_price"><span class="num">0</span> <span class="currency"><img src="/assets/img/level/currency/crystal.png"/></span></div>
            </div>
            <div class="item">
                <p class="label text_current_have"></p>
                <div class="price balance"><span num="0" class="num">0</span> <span class="currency"><img src="/assets/img/level/currency/crystal.png"/></span></div>
            </div>
        </div>
        <div class="button">
            <button style="width:100%;margin:0;" onclick="game_market_buy_confirm(this);" class="text_button_buy"></button>
        </div>
    </div>
</div>
<script>
    async function popup_game_market_buy_confirm(){
        var character_balance = await get_data_by_url(`/api/character_count?character=${my_character}&type=currency&sign=crystal`);
        $('.popup_module .confirm .balance .num').html(number_format(character_balance));
        $('.popup_module .confirm .balance .num').attr('num',character_balance);
        market_item_data = await get_data_by_url(`/api/get_data_by_id?table=game_market&data=data&id=${popup_data.id}`);
        market_item_data = JSON.parse(market_item_data.data);
        if(market_item_data.type == "equipment"){
            var image = `<a onclick="popup_data = {'type':'equipment','id':'${market_item_data.id}'};popup_load('profile/item','no')"><img src="/assets/img/level/equipment/${market_item_data.special_type}/${market_item_data.sign}_${market_item_data.level}.png" /></a>`;
            var name = `<a onclick="popup_data = {'type':'equipment','id':'${market_item_data.id}'};popup_load('profile/item','no')" class="level_${market_item_data.level}">${json_data.language[market_item_data.special_type+'_'+market_item_data.sign+'_'+market_item_data.level]}</a>`;
        }else{
            var image = `<a onclick="popup_data = {'type':'${market_item_data.type}','sign':'${market_item_data.sign}'};popup_load('profile/item','no')"><img  src="/assets/img/level/${market_item_data.type}/${market_item_data.sign}.png" /></a>`;
            var name = `<a onclick="popup_data = {'type':'${market_item_data.type}','sign':'${market_item_data.sign}'};popup_load('profile/item','no')">${json_data.language[market_item_data.type+'_'+market_item_data.sign]}</a>`;
        }
        $('.popup_module.game_market_buy_confirm .preview .image').html(image);
        $('.popup_module.game_market_buy_confirm .preview .name').html(name);
        $('.popup_module.game_market_buy_confirm .confirm .unit_price .num').html(number_format(market_item_data.price));

        if(market_item_data.type != 'equipment'){
            $('.popup_module.game_market_buy_confirm .confirm .item_amount').show();
        }
        shop_confirm_cal_price();
    }
    function shop_confirm_cal_price(){
        var item_amount = parseInt($('.popup_module.game_market_buy_confirm .confirm .item_amount input').val());
        if(item_amount >= market_item_data.amount){
            $('.popup_module.game_market_buy_confirm .confirm .item_amount input').val(market_item_data.amount);
            item_amount = market_item_data.amount;
        }
        var total_price = item_amount * market_item_data.price;
        $('.popup_module.game_market_buy_confirm .confirm .total_price .num').html(number_format(total_price));
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
    function game_market_buy_confirm(e){
        var item_amount = parseInt($('.popup_module.game_market_buy_confirm .confirm .item_amount input').val());
        var character_balance = to_int($('.popup_module .confirm .balance .num').attr('num'));
        var total_price = number_fix(item_amount * market_item_data.price);
        if(total_price <= character_balance && item_amount >= 1 && item_amount <= market_item_data.amount){
            $(e).hide();
            $.post("/assets/ajax/market.php", { action : "buy" , item_id : popup_data.id, item_amount : item_amount})
            .done(function(data) {
                $('#result').empty().append(data);
                $('.popup_module.game_market_buy_confirm').parent('.popup_content').remove();
            });
        }else if(market_item_data.type == "equipment" && market_item_data.amount == 0){
            alertify.error(language_text('text_market_sold'));
        }else if(total_price > character_balance){
            alertify.error(language_text('text_not_enought_resource'));
        }else if(item_amount > market_item_data.amount){
            alertify.error(language_text('text_market_buy_amount'));
        }
    }
    language_render(['text_amount','text_price','text_total_fee','text_current_have','text_button_buy']);
    popup_game_market_buy_confirm();
</script>