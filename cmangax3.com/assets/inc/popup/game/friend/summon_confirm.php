<div class="popup_module center_important friend_summon_confirm">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="buy_confirm item">
        <div class="preview">
            <img src="/assets/img/transparent.png" />
            <p class="name"></p>
            <p class="note"></p>
        </div>
        <div class="confirm">
            <div class="item">
                <p class="label">Đang có</p>
                <div class="price balance"><span num="0" class="num">0</span> <span class="currency"><img src="/assets/img/transparent.png"/></span></div>
            </div>
            <div class="item item_fee">
                <p class="label">Phí</p>
                <div class="price"><span num="0" class="num">0</span> <span class="currency"><img src="/assets/img/transparent.png"/></span></div>
            </div>
        </div>
        <div class="button">
            <button style="width:100%;margin:0;" onclick="friend_summon(this);">Triệu hồi</button>
        </div>
    </div>
</div>
<script>
    async function popup_friend_summon_confirm() {
        if(popup_data.type == 'normal'){
            var character_balance = await get_data_by_url(`/api/character_count?type=material&sign=friend_summon&character=${my_character}&v=${server_time}`);
            $('.popup_module.friend_summon_confirm .preview .name').html(json_data.language['friend_summon_'+popup_data.type]+' x'+popup_data.amount);  
            $('.popup_module.friend_summon_confirm .confirm .currency img').attr('src','/assets/img/level/material/friend_summon.png');
            var fee = popup_data.amount;
        }else{
            var character_balance = getSafe(() => character_data.other.friend.energy, 0);
            $('.popup_module.friend_summon_confirm .preview .name').html(json_data.language['friend_summon_'+popup_data.type]);
            $('.popup_module.friend_summon_confirm .confirm .currency img').attr('src','/assets/img/level/icon/summon.png');
            var fee = popup_data.amount == 3 ? 150 : 1500;
        }
        $('.popup_module.friend_summon_confirm .confirm .item_fee .num').html(number_format(fee));
        $('.popup_module.friend_summon_confirm .confirm .balance .num').html(number_format(character_balance));
        $('.popup_module.friend_summon_confirm .confirm .balance .num').attr('num',character_balance);
        $('.popup_module.friend_summon_confirm .preview img').attr('src',`/assets/img/level/menu/friend/friend_summon_${popup_data.type}.png`);
        $('.popup_module.friend_summon_confirm .button button').attr('type',popup_data.type);
        $('.popup_module.friend_summon_confirm .button button').attr('amount',popup_data.amount);
    }
    popup_friend_summon_confirm();
</script>