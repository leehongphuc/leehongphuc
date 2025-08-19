<div class="popup_module center_important battle_mine_protect">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="buy_confirm item">
        <div class="preview">
            <div class="image"><img src="/assets/img/level/item/mine_protect.png" /></div>
            <p class="name"></p>
            <p class="note"></p>
        </div>
        <div class="confirm">
            <div class="item">
                <p class="label">Đang có</p>
                <div class="price balance"><span num="0" class="num">0</span> <span class="currency"><img src="/assets/img/level/item/mine_protect.png"/></span></div>
            </div>
            <div class="item item_amount">
                <p class="label">Số lượng dùng</p>
                <div class="amount">
                    <input style="width: 100px;" onkeyup="time_protect_render();" maxlength="5" value="1">
                </div>
            </div>
            <div class="item">
                <p class="label">Thời gian</p>
                <div class="price total_take"><span class="num"></span></div>
            </div>
            <div class="button">
                <button style="width:100%;margin:0;" onclick="battle_mine_protect(this);">Kích hoạt</button>
            </div>
        </div>
    </div>
</div>
<script>
    async function popup_render(){
        var current_protect = await get_data_by_url(`/api/character_count?type=item&sign=mine_protect&character=${my_character}&v=${server_time}`);
        $('.popup_module.battle_mine_protect .balance .num').html(number_format(current_protect));
        $('.popup_module.battle_mine_protect .name').html(language_text('item_mine_protect'));
        $('.popup_module.battle_mine_protect .note').html(language_text('item_detail_mine_protect'));
        time_protect_render();
    }
    function battle_mine_protect(e){
        var amount = $('.popup_module.battle_mine_protect .amount input').val();
        $(e).prop('disabled', true);
        $.post("../assets/ajax/character_activity.php", {
            action: "battle_mine_protect",
            time: amount
        }).done(function(data) {
            $("#result").html(data);
            $(e).prop('disabled', false);
        });
    }
    function time_protect_render(){
        var premium_level = getSafe(() => my_profile.info.game_premium.level, 0);
        var amount = $('.popup_module.battle_mine_protect .amount input').val();
        if(premium_level > 0){
            var time_protect = (60+server_data.user_premium.game_premium[premium_level].data.increase_battle_mine_protect) * amount;
        }else{
            var time_protect = 60 * amount;
        }
        $('.popup_module.battle_mine_protect .total_take .num').html(`${time_protect} phút`);
    }

    popup_render();
</script>