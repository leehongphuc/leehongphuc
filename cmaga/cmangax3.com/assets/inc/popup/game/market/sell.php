<div style="width: 500px;" class="popup_module center_important market_sell">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div style="padding-top: 0;" class="user_game_module shop">
        <div class="menu">
            <div target="equipment" class="item active text_bag_equipment"></div>
            <div target="item" class="item text_bag_item"></div>
            <div target="material" class="item text_bag_material"></div>
        </div>
        <div id="bag_render_content">
            <div class="child_module">
                <div class="item_list_use"></div>
            </div>
        </div>
    </div>
</div>
<script>
    bag_data = {};
    async function popup_game_market_sell(type) {
        item_cant_sell = ['energy', 'medicinal_point_reset','medicinal_upgrade_1','box_weapon_1','box_accessory_1','box_armor_1','egg_normal','friend_summon'];
        var my_character_data = await get_data_by_url('/api/get_data_by_id?table=game_character&data=data&id='+my_character);
         my_character_data.data = JSON.parse(my_character_data.data);
        var character_potion = getSafe(() => my_character_data.data.equipment.potion.id, 0);
        item_cant_sell.push(character_potion);

        if(!bag_data[type]){
            bag_data[type] = await get_character_list(`/api/character_list?character=${my_character}&list=${type}`);
        }
        
        html = '';
        $.each(bag_data[type], function(index, data) {
            var image = "";
            var detail = "";
            var onclick = "";
            var amount = "";
            var button = "";
            if(type == "equipment" && !data.lock && (data.level >= 2 || data.type == "book") && data.status == "free" || type != "equipment" && data.amount - data.amount_lock > 0 && item_cant_sell.indexOf(index) == -1 && index.indexOf('lock') == -1){
                if(type == "equipment"){
                    var quality = quality_convert(data.quality);
                    image = `<div class="image equipment_icon border_level_${data.level}"><img class="avatar" src="/assets/img/level/equipment/${data.type}/${data.sign}_${data.level}.png" /></div>`;
                    detail = `<div class="detail"><p class="name level_${data.level}">${json_data.language[data.type+'_'+data.sign+'_'+data.level]}</p><p class="text"><span class="level_${quality.num}">${quality.name}</span></p></div>`;
                    onclick = `popup_data = {'type':'${type}','id':'${index}'};popup_load('profile/item')`;
                }else{
                    image = `<div class="image"><img class="avatar" src="/assets/img/level/${type}/${index}.png" /></div>`;
                    detail = `<div class="detail"><p class="name">${json_data.language[type+'_'+index]}</p><p class="text">Số lượng: ${data.amount - data.amount_lock}</p></div>`;
                    onclick = `popup_data = {'type':'${type}','sign':'${index}'};popup_load('profile/item')`;
                }
                var popup_data = type == "equipment" ? `popup_data={'type':'${type}','avatar':'assets/img/level/${type}/${data.type}/${data.sign+'_'+data.level}.png','sign':'${data.type+'_'+data.sign+'_'+data.level}','id':'${index}','amount':'1'}` : `popup_data={'type':'${type}','avatar':'assets/img/level/${type}/${index}.png','sign':'${index}','id':'0','amount':'${data.amount - data.amount_lock}'}`;
                button = `<button onclick="${popup_data};popup_load('game/market/sell_confirm')">${language_text('text_sell_button')}</button>`;
                html += `
                    <div class="item">
                        <div onclick="${onclick}" class="info">
                            ${image}
                            ${detail}
                        </div>
                        <div class="button_control">
                            ${button}
                        </div>
                    </div>
                `;
            }
        });
        $("#bag_render_content .child_module .item_list_use").html(html);
    }

    $(".market_sell .user_game_module .menu .item").click(function() {
        $(".market_sell .user_game_module .menu .item").removeClass('active');
        $(this).addClass('active');
        popup_game_market_sell($(this).attr('target'));
    });

    language_render(['text_bag_equipment','text_bag_item','text_bag_material']);
    popup_game_market_sell('equipment');
</script>