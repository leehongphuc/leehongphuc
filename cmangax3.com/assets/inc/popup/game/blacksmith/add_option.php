<div class="popup_module center_important item_profile">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="main_module">
        <img class="icon" src="/assets/img/transparent.png">
        <p class="name"></p>
        <p class="note"></p>
        <div class="info">

        </div>
        <div class="list_item"></div>
        <div class="button_style">
            <button style="width: 300px;margin: 40px auto 20px" class="button_yes text_add_option" onclick="equipment_add_option(this)"></button>
        </div>
    </div>
    <div style="display: none;" class="loading_module">
        <img style="max-height: 300px;"  src="/assets/img/level/icon/equipment_upgrade.gif">
    </div>
</div>
<script>
    async function popup_item_profile(){
        server_data.game_equipment = await get_server_data('game_equipment');
        $('.item_profile .icon_list .share').show();
        percent_stats = ["critical","critical_damage", "avoid","skill_atk","skill_def"];
        var get_data = await get_data_by_url(`/api/get_data_by_id?table=game_equipment&data=data&id=${popup_data.id}&v=${server_time}`);
        equipment_data = JSON.parse(get_data.data);
        var quality = quality_convert(equipment_data.info.quality);
        $('.item_profile .icon').attr('src',`/assets/img/level/equipment/${equipment_data.info.type}/${equipment_data.info.sign}_${equipment_data.info.level}.png`);
        var equipment_name = json_data.language[equipment_data.info.type+'_'+equipment_data.info.sign+'_'+equipment_data.info.level];
        $('.item_profile .name').text(equipment_name);
        $('.item_profile .name').addClass('level_'+equipment_data.info.level);
        var current_add_option = getSafe(() => equipment_data.info.add_option, 1);
        var weapon_type = equipment_data.info.type == 'weapon' ? json_data.language['weapon_'+equipment_data.info.sign]+' - ' : '';
        var html = `
                <div class="item border_bottom">
                     <p class="label">${language_text('text_add_option')}</p>
                     <p class="value">${current_add_option-1} ${language_text('text_times')}</p>
                </div>
            `;
        if(equipment_data.option){
            $.each(equipment_data.option, function(key, option_data) {
                var option_text = json_data.language['option_'+option_data.sign].replace(':value:',option_data.data.value);
                html += `<div style="font-size:12px;" class="item"><p class="label option">${option_text}</p></div>`;
            })
            html += `<div class="item border_bottom"></div>`;
        }
        $('.item_profile .info').html(html);
        var upgrade_stone_current = await get_data_by_url('/api/character_count?type=currency&sign=equipment_upgrade&character=' + my_character);
        var upgrade_stone_need = current_add_option*server_data.game_equipment.equipment_upgrade.add_option.num;
        var upgrade_material_current = await get_data_by_url(`/api/character_count?type=material&sign=add_option&character=` + my_character);
        var upgrade_stone_css = upgrade_stone_current < upgrade_stone_need ? 'disable' : '';
        var upgrade_material_css = upgrade_material_current < 1 ? 'disable' : '';
        var item_html = `
            <div onclick="popup_data = {'type':'currency','sign':'equipment_upgrade'};popup_load('profile/item')" class="item ${upgrade_stone_css}">
                <img class="center_div" src="/assets/img/level/currency/equipment_upgrade.png">
                <p class="amount">${upgrade_stone_current}/${upgrade_stone_need}</p>
            </div>
            <div onclick="popup_data = {'type':'material','sign':'add_option'};popup_load('profile/item')" class="item ${upgrade_material_css}">
                <img class="center_div" src="/assets/img/level/material/add_option.png">
                <p class="amount">${upgrade_material_current}/1</p>
            </div>
        `;
        $('.item_profile .list_item').html(item_html);
    }

    function equipment_add_option(e){
        if($('.item_profile .list_item .item.disable').length == 0){
            $(e).prop('disabled', true);
            $('.item_profile .main_module').hide();
            $('.item_profile .loading_module').show();
            setTimeout(() => {
                $.post("/assets/ajax/character.php", { action : "equipment_add_option" , equipment_id : popup_data.id})
                .done(function(data) {
                    $('#result').empty().append(data);
                });
            }, 1500);
        }else{
            alertify.error(language_text('text_not_enought_resource'));
        }
    }
    language_render(['text_add_option']);
    popup_item_profile();
</script> 