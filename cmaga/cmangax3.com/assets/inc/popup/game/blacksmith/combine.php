
<div class="popup_module center_important game_item_combine">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="buy_confirm item">
        <div class="preview">
            <div class="image"><img src="/assets/img/transparent.png" /></div>
            <p class="name"></p>
        </div>
        <div style="border-bottom: 1px solid var(--purple-color-2-highlight);margin-bottom: 25px;" class="confirm">
            <div class="item item_amount">
                <p class="label text_amount"></p>
                <div class="amount">
                    <button target = "minus" onclick="shop_confirm_value_change(this)" class="minus">-</button>
                    <input style="width: 100px;" onkeyup="shop_confirm_cal_price();" maxlength="5" value="1">
                    <button target = "plus" onclick="shop_confirm_value_change(this)" class="plus">+</button>
                </div>
            </div>
        </div>
        <div class="list_item"></div>
        <div class="button">
            <button style="width:100%;margin:0;" onclick="item_combine(this);" class="text_combine"></button>
        </div>
    </div>
</div>
<script>
    async function popup_game_item_combine(){
        bag_data = {};
        bag_data.currency = await get_character_list(`/api/character_list?character=${my_character}&list=currency`);
        bag_data.material = await get_character_list(`/api/character_list?character=${my_character}&list=material`);
        var item_data = server_data.game_item_combine[popup_data.target];
        $('.popup_module.game_item_combine .preview .image img').attr('src',`/assets/img/level/${item_data.type}/${popup_data.target}.png`);
        $('.popup_module.game_item_combine .preview .name').text(json_data.language[item_data.type+'_'+popup_data.target]);
        game_item_combine_cal();
    }

    function game_item_combine_cal(){
        var combine_amount = parseInt($('.popup_module.game_item_combine .confirm .item_amount input').val());
        var material_html = '';
        $.each(server_data.game_item_combine[popup_data.target].material, function(index, data) {
            var current = getSafe(() => bag_data[data.type][data.sign].amount, 0);
            var need = data.amount*combine_amount;
            var css = current < need ? 'disable' : '';
            material_html += `
                <div onclick="popup_data = {'type':'${data.type}','sign':'${data.sign}'};popup_load('profile/item')" class="item ${css}">
                    <img class="center_div" src="/assets/img/level/${data.type}/${data.sign}.png">
                    <p class="amount">${number_format(current)}/${number_format(need)}</p>
                </div>
            `;
        });
        $('.popup_module.game_item_combine .list_item').html(material_html);
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
        game_item_combine_cal();
    }

    function item_combine(e){
        var combine_amount = parseInt($('.popup_module.game_item_combine .confirm .item_amount input').val());
        if($('.popup_module.game_item_combine .list_item .item.disable').length == 0){
            $(e).prop('disabled',true);
            $.post("/assets/ajax/character.php", { action : "item_combine" , target : popup_data.target , amount : combine_amount })
            .done(function(data) {
                $('#result').empty().append(data);
                $(e).prop('disabled',false);
            });
        }else{
            alertify.error(language_text('text_not_enought_resource'));
        }
    }
    language_render(['text_amount','text_combine']);
    popup_game_item_combine();
</script> 