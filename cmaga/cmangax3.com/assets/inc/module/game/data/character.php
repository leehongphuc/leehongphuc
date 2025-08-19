<div id="character_profile_data" class="user_game_module character">
    <div class="back_dashboard"><i onclick="load_module('content','game/dashboard')" class="fas fa-chevron-square-left"></i></div>
    <div style="display:none" class="button_child_menu">
        <button onclick="character_fight();"><i class="center_div fas fa-swords"></i></button>
        <button class="chat"><i class="center_div fas fa-comments"></i></button>
    </div>
    <div onclick="popup_load('game/set_bonus')" class="set_bonus"></div>
    <div class="character_upgrade button_style button_menu"><button style="opacity: 0.2;" onclick="popup_load('game/character/upgrade')" class="button_yes"><i class="far fa-chevron-double-up"></i> <span class="text_character_upgrade"></span></button></div>
    <div class="avatar">
        <div class="user_profile_div">
            <div class="user_avatar">
                <div class="user_frame"></div>
                <img class="user_image" src="assets/img/transparent.png">
            </div>
        </div>
        <p class="name"></p>
        <div class="level bar_div"></div>
    </div>
    <div class="cp">
        <p class="title">Chiến lực</p>
        <p class="value">0</p>
    </div>
    <div class="admin_menu">
        <div class="list">
            <div style="display: none;" class="item" action="chat_banned" onclick="popup_data = {'id':character_profile_id};popup_load('manage/character_control')"><i class="center_div fas fa-cog"></i></div>
            <div style="display: none;" class="item trader" action="chat_banned" onclick="admin_character_action(this)"><i class="center_div fas fa-comment-slash"></i></div>
            <div style="display: none;" class="item trader" onclick="popup_close();popup_load('user/game_transaction')"><i class="center_div far fa-file-invoice-dollar"></i></div>
        </div>
    </div>
    <div class="menu">
        <div target="character_equipment" class="item active text_equipment"></div>
        <div target="character_stats" class="item text_stats"></div>
        <div target="character_talent" class="item text_talent"></div>
        <div target="character_master" class="item text_master"></div>
    </div>
    <div class="character_equipment child_module">
        <div class="list">
        </div>
        <div class="equipment_repair button_style">
            <button style="display:none;" onclick="popup_load('game/character/medicinal')" class="button_yes pet_button"><i class="fa-solid fa-fire-flame"></i> <span class="text_medicinal"></span></button>
            <button style="display:none;" onclick="popup_load('game/fight_setup')" class="button_yes pet_button"><i class="fa-solid fa-chess-board"></i> <span class="text_formation"></span></button>
            <button style="display:none;" onclick="popup_load('game/character/repair')" class="button_yes repair_button"><i class="fas fa-hammer"></i> <span class="text_repair"></span></button>
        </div>
    </div>
    <div style="display: none;" class="character_stats child_module">
        <div class="current_point">
            <p>Tiềm năng <span id="point_remain">0</span></p>
        </div>
        <table>
        </table>
        <div style="display:none;" class="add_stats_button button_style">
            <button onclick="character_stats_update(this)" class="button_yes text_up"></button>
            <button onclick="character_stats_reset(this)" class="button_no text_cancel"></button>
        </div>
    </div>
    <div style="display: none;" class="character_talent child_module">
        <div class="list">

        </div>
    </div>
    <div style="display: none;" class="character_master child_module">
        <div class="list master">

        </div>
    </div>
</div>
<script>
    page = 1;
    function admin_character_action(e){
        var action = $(e).attr('action');
        if(confirm('Bạn có chắc chắn muốn thực hiện hành động này?')){
            $.post("/assets/ajax/imanage.php", { action : action , character_id : character_profile_id })
            .done(function(data) {
                $('#result').html(data);
            });
        }
    }
    async function character_content_render(){
        server_data.game_trader = await get_server_data('game_trader');
        if(token_permission <= 3){
            $('#character_profile_data .admin_menu').show();
            $('#character_profile_data .admin_menu .item').show();
        }else if(server_data.game_trader[my_character]){
            $('#character_profile_data .admin_menu').show();
            $('#character_profile_data .admin_menu .item.trader').show();
        }
        
        equipment_list = ["weapon","amulet","ring","gloves","boots","armor","helmet","belt","pedant","treasure","book","skill","pet","friend","tactical","potion"];
        equipment_deteriorate = ["weapon","amulet","ring","gloves","boots","armor","helmet","belt","pedant","treasure"];
        element_list = ["metal","natural","water","fire","earth","wind","thunder","ice","light","dark"];
        weapon_list = ["battle_axe","bow","claw","crescents","dual_sword","fan","flute","harp","lute","medium_scimitar","mini_scimitar","moon_blade","pen","robot","rope_whip","scimitar","spear","stick","sword","umbrella","zither"];
        percent_stats = ["critical","critical_damage","m_def", "p_def", "avoid","skill_atk","skill_def"];
        disable_stats = ["critical","mp_restore","critical_damage"];

        server_data.game_exp = await get_server_data('game_exp');
        character_profile_data = await get_data_by_url('/api/get_data_by_id?table=game_character&data=info,data&id='+character_profile_id+'&v='+server_time);
        character_profile_data.data = JSON.parse(character_profile_data.data);
        character_profile_data.info = JSON.parse(character_profile_data.info);
        character_profile_id_talent = await get_character_list('/api/character_list?list=talent&character='+character_profile_id);
        character_profile_id_expert = await get_character_list('/api/character_list?list=expert&character='+character_profile_id);
        user_profile_data = await get_data_by_url('/api/user_info?user=' + character_profile_data.info.author);
        user_profile_data.info = JSON.parse(user_profile_data.info);
        character_deteriorate = getSafe(() => character_profile_data.data.deteriorate, 0);
        if(character_profile_id == my_character){
            $('#character_profile_data .equipment_repair .pet_button').show();
            if(character_deteriorate != 0){
                $('#character_profile_data .equipment_repair .repair_button').show();
            }
        }
        if(token_user != character_profile_data.info.author){
            $('#character_profile_data .button_child_menu .chat').attr("onclick",`chat_start(${character_profile_data.info.author});`);
            $('#character_profile_data .button_child_menu').show();
        }
        var set_bonus = getSafe(() => character_profile_data.data.set_bonus, 0);
        if(set_bonus != 0){
            $('#character_profile_data .set_bonus').html(`<img src="/assets/img/level/icon/set_bonus/${set_bonus}.png">`);
        }
        current_level = character_profile_data.info['level']['num'];
        next_level = current_level + 1;
        level_target = Math.ceil(current_level/10);
        if(character_profile_data.info['level']['exp'] >= server_data.game_exp.character[next_level] && next_level%10 == 0){
            $('#character_profile_data .character_upgrade button').css('opacity','1');
        }else{
            $('#character_profile_data .character_upgrade button').css('opacity','0.2');
        }
        var character_name = character_profile_data.data.element == "none" ? character_profile_data.info.name : character_profile_data.info.name + "<img style='margin-left: 7px;margin-top: -10px;' class='item_icon' src='assets/img/level/element/"+character_profile_data.data.element+".png'>";
        if(character_profile_data.info.guild){
            character_name = `[<a onclick="popup_data = {'guild_id':'${character_profile_data.info.guild.id}'};popup_load('profile/guild');">${character_profile_data.info.guild.tag}</a>] `+character_name
        }
        character_name = `#${character_profile_id} ` + character_name;
        $('#character_profile_data .avatar .user_profile_div .user_avatar .user_image').attr('src','assets/tmp/avatar/'+user_profile_data.info.avatar);
        $('#character_profile_data .avatar .user_profile_div .user_avatar .user_frame').html(user_frame_render(user_profile_data.info))
        $('#character_profile_data .avatar .name').html(character_name);
        $('#character_profile_data .avatar .level').html(level_render(character_profile_data.info.level));
        var character_cp = getSafe(() => character_profile_data.info.cp.total, 0);
        $('#character_profile_data .cp .value').text(character_cp);
        server_data.game_stats = await get_server_data('game_stats');
        stats_add = {};
        point_remain = character_profile_data.data.point.remain;
        def_stats = ["m_def", "p_def"];
        var html = "";
        $.each(equipment_list, function(key, value) {
            var equipment_id = getSafe(() => character_profile_data.data.equipment[value].id, 0);
            var equipment_button = `<button onclick="popup_data = {'type':'${value}','target':'character'};popup_load('game/character/equipment_select')">Mang</button>`;
            var equipment_avatar = `<div class="equipment_icon"><img class="center_div" src="/assets/img/transparent.png"></div>`;
            var equipment_name = json_data.language['equipment_'+value];
            var equipment_note = language_text('text_equipment_no');
            if(equipment_id != 0){
                var equipment_data = character_profile_data.data.equipment[value];
                equipment_button = `<button class="disable" target="${value}" onclick="equipment_out(this)">${language_text('text_unwear')}</button>`;
                if(value == "skill"){
                    var onclick = `popup_data = {'type':'skill','sign':'${equipment_data.sign}','strong':'${equipment_data.strong}','rare':'${equipment_data.rare}','level':'${equipment_data.level}'};popup_load('profile/item')`;
                    equipment_avatar = `<a onclick="${onclick}"><div class="equipment_icon skill_rare_${equipment_data.rare}"><img src="/assets/img/level/${value}/${equipment_data.sign}.png"></div></a>`;
                    equipment_name = `<a class="skill_rare_${equipment_data.rare}" onclick="${onclick}"> ${json_data.language[value+'_'+equipment_data.sign]} </a>`;
                    equipment_note = `<span>Tầng ${equipment_data.strong}</span>`;
                }else if(value == "pet"){
                    var equipment_quality = quality_convert(equipment_data.quality, 'pet');
                    var onclick = `popup_data = {'target':'pet','id':'${equipment_id}'};popup_load('profile/character')`;
                    equipment_avatar = `<a onclick="${onclick}"><div class="equipment_icon"><img src="/assets/img/level/pet/${equipment_data.sign}_${equipment_data.evolve}.png"></div></a>`;
                    equipment_name = `<a onclick="${onclick}"> ${json_data.language['pet_'+equipment_data.sign]} <img class='item_icon' src='assets/img/level/element/${equipment_data.element}.png'></a>`;
                    equipment_note = `<span class="level_${equipment_quality.num}">${equipment_quality.name}</span>`;
                }else if(value == "friend"){
                    var main_level = Math.floor(equipment_data.level/10);
                    var sub_level = equipment_data.level%10;
                    var text = json_data.language['character_level_'+main_level]+' '+json_data.language['character_mini_level_'+sub_level];
                    var onclick = `popup_data = {'target':'friend','id':'${equipment_id}'};popup_load('profile/character')`;
                    equipment_avatar = `<a onclick="${onclick}"><div class="equipment_icon skill_rare_${equipment_data.tier}"><img src="/assets/img/level/friend/100/${equipment_data.sign}.gif"></div></a>`;
                    equipment_name = `<a onclick="${onclick}" class="skill_rare_${equipment_data.tier}"> ${json_data.language['friend_name_'+equipment_data.sign]} <img class='item_icon' src='assets/img/level/element/${equipment_data.element}.png'></a>`;
                    equipment_note = `<span>${text}</span>`;
                }else if(value == "potion"){
                    var onclick = `popup_data = {'type':'item','sign':'${equipment_data.id}'};popup_load('profile/item')`;
                    equipment_avatar = `<a onclick="${onclick}"><div class="equipment_icon"><img src="/assets/img/level/item/${equipment_data.id}.png"></div></a>`;
                    equipment_name = `<a class="onclick="${onclick}"> ${json_data.language['item_'+equipment_data.id]} </a>`;
                    equipment_note = `<span>Số lượng: ${equipment_data.amount}</span>`;
                }else if(value == "book"){
                    var onclick = `popup_data = {'type':'equipment','id':'${equipment_data.id}'};popup_load('profile/item')`;
                    equipment_avatar = `<a onclick="${onclick}"><div class="equipment_icon border_level_${equipment_data.level}"><img src="/assets/img/level/equipment/${value}/${equipment_data.sign}_${equipment_data.level}.png"></div></a>`;
                    equipment_name = `<a class="level_${equipment_data.level}" onclick="${onclick}"> ${json_data.language[value+'_'+equipment_data.sign+'_'+equipment_data.level]}</a>`;
                    equipment_note = `<span>Cấp ${equipment_data.training.num}</span>`;
                }else if(value == "tactical"){
                    var onclick = `popup_data = {'type':'equipment','id':'${equipment_data.id}'};popup_load('profile/item')`;
                    equipment_avatar = `<a onclick="${onclick}"><div class="equipment_icon border_level_${equipment_data.level}"><img src="/assets/img/level/equipment/${value}/${equipment_data.sign}_${equipment_data.level}.png"></div></a>`;
                    equipment_name = `<a class="level_${equipment_data.level}" onclick="${onclick}"> ${json_data.language[value+'_'+equipment_data.sign+'_'+equipment_data.level]}</a>`;
                    equipment_note = `<span>${equipment_data.data.main.value}% chúc phúc</span>`;
                }else{
                    var onclick = `popup_data = {'type':'equipment','id':'${equipment_data.id}'};popup_load('profile/item')`;
                    equipment_avatar = `<a onclick="${onclick}"><div class="equipment_icon border_level_${equipment_data.level}"><img src="/assets/img/level/equipment/${value}/${equipment_data.sign}_${equipment_data.level}.png"></div></a>`;
                    equipment_name = `<a class="level_${equipment_data.level}" onclick="${onclick}"> ${json_data.language[value+'_'+equipment_data.sign+'_'+equipment_data.level]} +${equipment_data.upgrade}</a>`;
                    var equipment_quality = quality_convert(equipment_data.quality);
                    equipment_note = `<span class="level_${equipment_quality.num}">${equipment_quality.name}</span> · ${json_data.language['equipment_deteriorate']}: ${character_deteriorate}`;
                }
            }
            html += `
                <div class="item">
                    ${equipment_avatar}
                    <div class="info">
                        <p class="name">${equipment_name}</p>
                        <p class="equipment_note">${equipment_note}</p>
                    </div>
                    ${equipment_button}
                </div>`
            ;
        });
        $("#character_profile_data .character_equipment .list").html(html);
        $('#character_profile_data .character_stats .current_point #point_remain').html(character_profile_data.data.point.remain);
        $.each(server_data.game_stats.default_stats, function(key, value) {
            stats_add[key] = 0;
        });
        
        var html = "";
        $.each(element_list, function(key, value) {
            var talent_value = getSafe(() => character_profile_id_talent[value].level.num, 0);
            html += `
                <div class="item">
                    <div class="icon"><img src="/assets/img/level/element/${value}.png"></div>
                    <p class="name">${json_data.language['element_'+value]}</p>
                    <ul>
                        ${point_render(talent_value)}
                    </ul>
                </div>
            `;
        });
        $("#character_profile_data .character_talent .list").html(html);

        var html = "";
        $.each(weapon_list, function(user, value) {
            var expert_level = getSafe(() => character_profile_id_expert[value].level.num, 0);
            var expert_exp = getSafe(() => character_profile_id_expert[value].level.exp, 0);
            var expert_next_exp = getSafe(() => server_data.game_exp.expert[expert_level+1], 999999);

            html += `
                <div class="item">
                    <div class="equipment_icon"><img src="/assets/img/level/equipment/weapon/${value}_1.png"></div>
                    <div class="info">
                        <p class="name">${json_data.language['weapon_'+value]}</p>
                        <p class="type">${expert_exp}/${expert_next_exp}</p>
                    </div>
                    <ul>
                        ${point_render(expert_level,10)}
                    </ul>
                </div>
            `;
        });
        $("#character_profile_data .character_master .list").html(html);
        character_stats_render();
        $('#character_profile').attr('id','');
    }

    function character_stats_render(){
        var html = "";
        $.each(server_data.game_stats.default_stats, function(key, value) {
            current_stats = character_profile_data.data.stats[key].total;
            if(def_stats.includes(key)){
                var next_stats = character_profile_data.data.stats[key].main;
                $.each(character_profile_data.data.stats[key].bonus, function(def_stats_bonus, def_value_bonus) {
                    next_stats = next_stats + def_value_bonus;
                });
                current_stats = next_stats + (server_data.game_stats.bonus_stats[key]*stats_add[key]);
                current_stats = Math.round((100 - (100/(100+(current_stats*2)) * 100))*10)/10 + "%";
                next_stats = next_stats + (server_data.game_stats.bonus_stats[key]*(stats_add[key]+1));
                next_stats = Math.round((100 - (100/(100+(next_stats*2)) * 100))*10)/10 + "%";
            }else if(percent_stats.includes(key)){
                current_stats = number_fix(character_profile_data.data.stats[key].total + server_data.game_stats.bonus_stats[key]*stats_add[key])+"%";
                next_stats = number_fix(character_profile_data.data.stats[key].total + (server_data.game_stats.bonus_stats[key]*(stats_add[key]+1)))+"%";
            }else{
                current_stats = number_fix(character_profile_data.data.stats[key].total + server_data.game_stats.bonus_stats[key]*stats_add[key]);
                next_stats = number_fix(character_profile_data.data.stats[key].total + server_data.game_stats.bonus_stats[key]*(stats_add[key]+1));
            }
            if(point_remain == 0){
                var button_html = `<button id="add_stats_${key}" target="${key}" class="disable" onclick="alertify.error('${language_text('text_no_stats_point')}')"><i class="center_div far fa-plus"></i></button>`;
            }else{
                var button_html = `<button id="add_stats_${key}" target="${key}" class="add_point_button" onclick="target_stats_add = '${key}';character_stats_add_point('one')"><i class="center_div far fa-plus"></i></button> <button id="add_stats_${key}" target="${key}" class="add_point_button" onclick="target_stats_add = '${key}';popup_load('game/character/stats_add');"><i class="far fa-chevron-double-up"></i></button>`;
            }
            var third_td_html = `${character_profile_data.data.stats[key].level+stats_add[key]} ${button_html}`;
            var next_stats_html = `<i style="margin: 0 3px;" class="fas fa-caret-right"></i> <span>${next_stats}</span>`;
            if(disable_stats.includes(key)){
                var third_td_html = "";
                var next_stats_html = "";
            }
            html += `
                <tr>
                    <td>${json_data.language['stats_short_'+key]}</td>
                    <td>${current_stats} ${next_stats_html}</td>
                    <td>${third_td_html}</td>
                </tr>`
            ;
        });
        $("#character_profile_data .character_stats table").html(html);
    }

    function character_stats_add_point(type){
        if(type == "group"){
            var total_point = parseInt($("#point_stats_add").val());
        }else{
            var total_point = 1;
        }
        if(point_remain >= total_point){
            popup_close();
            point_remain = point_remain - total_point;
            stats_add[target_stats_add] = stats_add[target_stats_add] + total_point;
            $('#character_profile_data .character_stats .current_point #point_remain').html(point_remain);
            character_stats_render();
            $('#character_profile_data .add_stats_button').show();
        }else{
            alertify.error(language_text('text_stats_point_not_enough'));
        }
        if(point_remain == 0){
            $('#character_profile_data .character_stats table tr td button').addClass('disable');
        }
    }

    function character_stats_update(e){
        var stats_send = {};
        $.each(stats_add, function(key, value) {
            if(value != 0){
                stats_send[key] = value;
            }
        });
        if(Object.keys(stats_send).length > 0){
            $('#character_profile_data .add_stats_button').hide();
            character_profile_data.data.point.remain = point_remain;
            $.post("/assets/ajax/character.php", { action : 'stats_level_up' , stats_list : JSON.stringify(stats_send) })
            .done(function(data) {
                $('#result').html(data);
            });
        }
    }

    function character_stats_reset(e){
        $('#character_profile_data .add_stats_button').hide();
        point_remain = character_profile_data.data.point.remain;
        $.each(server_data.game_stats.default_stats, function(key, value) {
            stats_add[key] = 0;
        });
        $('#character_profile_data .character_stats .current_point #point_remain').html(point_remain);
        character_stats_render();
    }

    function equipment_out(e){
        $('.character_equipment .item button.disable').hide();
        var target = $(e).attr('target');
        if(character_deteriorate != 0){
            alertify.error(language_text('text_you_need_repair'));
        }else{
            $(e).hide();
            $.post('assets/ajax/character.php',{action:"equipment_out",equipment_type: target},function(data){
                $('#result').html(data);
                setTimeout(() => {
                    $('.character_equipment .item button.disable').show();
                }, 2000);
            });
        }
    }

    function character_fight(){
        $.post('assets/ajax/character_activity.php',{action:"character_fight",character_id: character_profile_id},function(data){
            $('#result').html(data);
        });
    }

    $("#character_profile_data .menu .item").click(function() {
        $("#character_profile_data .menu .item").removeClass('active');
        $(this).addClass('active');
        $("#character_profile_data .child_module").hide();
        $("#character_profile_data .child_module."+$(this).attr('target')).show();
    });

    character_content_render();

    language_render(['text_equipment','text_stats','text_talent','text_master','text_equipment_no','text_medicinal','text_formation','text_repair','text_up','text_cancel']);
</script>