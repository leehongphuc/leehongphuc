<div class="popup_module center_important">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="buy_confirm item">
        <div class="preview">
            <img src="/assets/img/level/item/energy.png" />
            <p class="name"></p>
            <p class="note"></p>
        </div>
        <div class="confirm">
            <div class="item">
                <p class="label text_today_buy" style="width: 160px;"></p>
                <div class="price today_buy"><span class="num">0</span> <span class="currency"><img src="/assets/img/level/item/energy.png"/></span></div>
            </div>
            <div class="item item_amount">
                <p class="label text_amount"></p>
                <div class="amount">
                    <input style="width: 100px;" onkeyup="confirm_cal_price();" maxlength="5" value="1">
                </div>
            </div>
            <div class="item">
                <p class="label text_need_pay" style="width: 160px;"></p>
                <div class="price need"><span num="0" class="num">0</span> <span class="currency"><img src="/assets/img/level/currency/gold.png"/></span></div>
            </div>
            <div class="item">
                <p class="label text_current_have" style="width: 160px;"></p>
                <div class="price balance"><span num="0" class="num">0</span> <span class="currency"><img src="/assets/img/level/currency/gold.png"/></span></div>
            </div>
        </div>
        <div class="button">
            <button style="width:100%;margin:0;" onclick="energy_buy();" class="text_button_buy"></button>
        </div>
    </div>
</div>
<script>
    async function popup_render() {
        $('.popup_module .preview .name').text(json_data.language['item_energy']);
        var character_balance = await get_data_by_url(`/api/character_count?character=${my_character}&type=currency&sign=gold`);
        $('.popup_module .confirm .price.balance .num').html(number_format(character_balance));
        my_character_energy = await get_data_by_url('/api/character_energy?character='+my_character);
        $('.popup_module .confirm .price.today_buy .num').html(number_format(my_character_energy.buy));
        confirm_cal_price();
    }
    function confirm_cal_price(){
        var base_gold = my_character_energy.buy*200;
        var total_need = 0;
        var amount = $('.popup_module .confirm .item_amount .amount input').val();
        for(i=0;i<amount;i++){
            base_gold += 200;
            total_need += base_gold;
        }
        $('.popup_module .confirm .price.need .num').html(number_format(total_need));
    }
    function energy_buy(){
        var amount = $('.popup_module .confirm .item_amount .amount input').val();
        $.post("/assets/ajax/character.php", { action : "energy_buy" , amount : amount })
        .done(function(data) {
            $('#result').empty().append(data);
        });
    }
    popup_render();

    language_render(['text_today_buy','text_amount','text_need_pay','text_current_have','text_button_buy']);
</script>