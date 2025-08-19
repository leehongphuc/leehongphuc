<div class="popup_module center_important item_profile upgrade_equipment">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="main_module">
        <img class="icon" src="/assets/img/transparent.png">
        <p class="name"></p>
        <p class="note"></p>
        <div class="info">

        </div>
        <div class="list_item"></div>
        <div class="custom_select upgrade_rate_material">
            <div onclick="$('.upgrade_rate_material .list').slideDown();" class="current item">
                <img style="filter: grayscale(1);" src="assets/img/level/material/lock_equipment_rate_1.png">
                <p class="title_name">Không dùng Cường Hóa Thạch</p>
                <p class="value">+0%</p>
            </div>
            <div style="display: none;" class="list">

            </div>
        </div>
        <div class="button_style">
            <button style="width: 300px;margin: 40px auto 20px" class="button_yes text_equipment_upgrade" onclick="equipment_upgrade(this)"></button>
        </div>
        <p><text class="text_success_rate"></text>: <span class="upgrade_rate" style="color: var(--yellow-color);"></span></p>
    </div>
    <div style="display: none;" class="loading_module">
        <img style="max-height: 300px;"  src="/assets/img/level/icon/equipment_upgrade.gif">
    </div>
</div>
<script>
    equipment_upgrade_data = {"material_rate":0};
    function upgrade_rate_material(){
        var html = `
            <div target="0" class="item">
                <img style="filter: grayscale(1);" src="assets/img/level/material/lock_equipment_rate_1.png">
                <p class="title_name">Không dùng Cường Hóa Thạch</p>
                <p class="value">+0%</p>
            </div>
        `;
        for(i=1;i<=4;i++){
            html += `
                <div target="${i}" class="item">
                    <img src="assets/img/level/material/lock_equipment_rate_${i}.png">
                    <p class="title_name">${json_data.language['material_lock_equipment_rate_'+i]}</p>
                    <p class="value">+${(i*5)}%</p>
                </div>
            `;
        }
        $('.custom_select .list').html(html);

        $('.custom_select .list .item').click(function(){
            var target = $(this).attr('target');
            $('.custom_select .current').html($(this).html());
            $('.custom_select .list').slideUp();
            equipment_upgrade_data.material_rate = target;
            popup_item_profile();
        });
    }
    async function popup_item_profile(){
        server_data.game_equipment = await get_server_data('game_equipment');
        $('.item_profile .icon_list .share').show();
        percent_stats = ["critical","critical_damage", "avoid","skill_atk","skill_def"];
        var get_data = await get_data_by_url(`/api/get_data_by_id?table=game_equipment&data=data&id=${popup_data.id}&v=${server_time}`);
        equipment_data = JSON.parse(get_data.data);
        var quality = quality_convert(equipment_data.info.quality);
        $('.item_profile .icon').attr('src',`/assets/img/level/equipment/${equipment_data.info.type}/${equipment_data.info.sign}_${equipment_data.info.level}.png`);
        var current_upgrade = getSafe(() => equipment_data.info.upgrade, 0);
        var next_upgrade = current_upgrade + 1;
        var equipment_name = json_data.language[equipment_data.info.type+'_'+equipment_data.info.sign+'_'+equipment_data.info.level] + (next_upgrade > 0 ? ` +${next_upgrade}` : '');
        $('.item_profile .upgrade_rate').text((server_data.game_equipment.equipment_upgrade.upgrade[next_upgrade].rate+(equipment_upgrade_data.material_rate*5))+'%')
        $('.item_profile .name').text(equipment_name);
        $('.item_profile .name').addClass('level_'+equipment_data.info.level);
        var weapon_type = equipment_data.info.type == 'weapon' ? json_data.language['weapon_'+equipment_data.info.sign]+' - ' : '';
        var percent_stats = ["critical","critical_damage", "avoid","skill_atk","skill_def"];
        var html = ``;
        $.each(equipment_data.stats,function(k,v){
            var value = percent_stats.includes(k) ? v.total+"%" : v.total;
            if(["critical","critical_damage"].includes(k)){
                var value_bonus = value;
            }else{
                var value_bonus = number_fix(v.main * (1+server_data.game_equipment.equipment_upgrade.upgrade[next_upgrade].bonus));
            }
            html += `
                <div class="item">
                    <p class="label">${json_data.language['stats_'+k]}</p>
                    <p class="value">${value} > <span class="upgrade_stats">${value_bonus}</span></p>
                </div>
            `;
        });
        html += `<div class="item border_bottom"></div>`;
        $('.item_profile .info').html(html);
        var upgrade_stone_current = await get_data_by_url('/api/character_count?type=currency&sign=equipment_upgrade&character=' + my_character);
        var upgrade_stone_need = jQuery.inArray(equipment_data.info.type, ["armor","weapon"]) !== -1 ? server_data.game_equipment.equipment_upgrade.upgrade[next_upgrade].num*5 : server_data.game_equipment.equipment_upgrade.upgrade[next_upgrade].num;
        var upgrade_material_current = await get_data_by_url(`/api/character_count?type=material&sign=${server_data.game_equipment.equipment_upgrade.upgrade[next_upgrade].material}&character=` + my_character);
        var upgrade_stone_css = upgrade_stone_current < upgrade_stone_need ? 'disable' : '';
        var upgrade_material_css = upgrade_material_current < 1 ? 'disable' : '';
        var item_html = `
            <div onclick="popup_data = {'type':'currency','sign':'equipment_upgrade'};popup_load('profile/item')" class="item ${upgrade_stone_css}">
                <img class="center_div" src="/assets/img/level/currency/equipment_upgrade.png">
                <p class="amount">${upgrade_stone_current}/${upgrade_stone_need}</p>
            </div>
            <div onclick="popup_data = {'type':'material','sign':'${server_data.game_equipment.equipment_upgrade.upgrade[next_upgrade].material}'};popup_load('profile/item')" class="item ${upgrade_material_css}">
                <img class="center_div" src="/assets/img/level/material/${server_data.game_equipment.equipment_upgrade.upgrade[next_upgrade].material}.png">
                <p class="amount">${upgrade_material_current}/1</p>
            </div>
        `;
        if(equipment_upgrade_data.material_rate > 0){
            var upgrade_material_current = await get_data_by_url(`/api/character_count?type=material&sign=lock_equipment_rate_${equipment_upgrade_data.material_rate}&character=` + my_character);
            var upgrade_material_css = upgrade_material_current < 1 ? 'disable' : '';
            item_html += `
                <div onclick="popup_data = {'type':'material','sign':'lock_equipment_rate_${equipment_upgrade_data.material_rate}'};popup_load('profile/item')" class="item ${upgrade_material_css}">
                    <img class="center_div" src="/assets/img/level/material/lock_equipment_rate_${equipment_upgrade_data.material_rate}.png">
                    <p class="amount">${upgrade_material_current}/1</p>
                </div>
            `;
        }
        $('.item_profile .list_item').html(item_html);
    }

    function equipment_upgrade(e){
        var current_upgrade = getSafe(() => equipment_data.info.upgrade, 0);
        var next_upgrade = current_upgrade + 1;
        if($('.item_profile .list_item .item.disable').length == 0){
            if(next_upgrade >= 10){
                alertify.confirm(language_text('text_confirm'),language_text('text_equipment_upgrade_warning'), function() {
                    $(e).prop('disabled', true);
                    $('.item_profile .main_module').hide();
                    $('.item_profile .loading_module').show();
                    setTimeout(() => {
                        $.post("/assets/ajax/character.php", { action : "equipment_upgrade" , material_rate : equipment_upgrade_data.material_rate , equipment_id : popup_data.id})
                        .done(function(data) {
                            $('#result').empty().append(data);
                        });
                    }, 1500);
                }, function() {
                });
            }else{
                $(e).prop('disabled', true);
                $('.item_profile .main_module').hide();
                $('.item_profile .loading_module').show();
                setTimeout(() => {
                    $.post("/assets/ajax/character.php", { action : "equipment_upgrade" , material_rate : equipment_upgrade_data.material_rate , equipment_id : popup_data.id})
                    .done(function(data) {
                        $('#result').empty().append(data);
                    });
                }, 1500);
            }
        }else{
            alertify.error(language_text('text_not_enought_resource'));
        }
    }
    language_render(['text_equipment_upgrade','text_success_rate']);
    popup_item_profile();
    upgrade_rate_material();
</script> 