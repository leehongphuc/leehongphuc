<div class="user_game_dungeon">
    <div class="div_module">
        <h5 class="text_dungeon"></h5>
        <div onclick="popup_load('game/buy/energy')" class="current_energy currency_div">
            <div class="item">
                <img class="icon" src="/assets/img/level/item/energy.png">
                <p class="num"></p>
                <i class="fas fa-plus"></i>
                <div style="display: none;" end="" class="next_time">00:00</div>
            </div>
        </div>
        <div class="list list_fr main">
        </div>
        <div style="display: none;" class="list list_fr sub_main">
        </div>
    </div>
</div>
<script> 
    page = 1;
    async function character_energy_reload(){
        var my_character_energy = await get_data_by_url('/api/character_energy?character='+my_character);
        $(".user_game_dungeon .current_energy .num").html(my_character_energy.current);
        $(".user_game_dungeon .current_energy .time_count_down").attr("end","");

        if(my_character_energy.current < 10 && my_character_energy.time > server_time && my_character_energy.remain != 0){
            $(".user_game_dungeon .current_energy .num").html(my_character_energy.current);
            $(".user_game_dungeon .current_energy .next_time").addClass('time_count_down');
            $(".user_game_dungeon .current_energy .next_time").attr("time",my_character_energy.time+5);
            $(".user_game_dungeon .current_energy .next_time").attr("end","character_energy_reload");
        }else if(my_character_energy.remain == 0){
            $(".user_game_dungeon .current_energy .next_time").html("Đã hết");
            $(".user_game_dungeon .current_energy .next_time").show();
        }
    }
    async function content_render() {
        server_data.game_dungeon = await get_server_data('game_dungeon');
        html = `
        <div class="item" onclick="load_module('content','game/dashboard')">
            <img class="image" src="/assets/img/level/menu/back.png">
            <p class="title">${language_text('text_game_dashboard')}</p>
            <p class="note">${language_text('text_game_back')}</p>
        </div>`;
        $.each(server_data.game_dungeon, function(key, value) {
            html += `<div class="item" onclick="dungeon_render('${key}')">
                        <img class="image" src="/assets/img/level/menu/dungeon/${key}.png">
                        <p class="title">${json_data.language['activity_dungeon_'+key]}</p>
                        <p class="note">${json_data.language['activity_dungeon_'+key+'_note']}</p>
                    </div>`;
        })
        $(".user_game_dungeon .list.main").html(html);
        character_energy_reload();
    }

    function dungeon_render(key){
        html = `
            <div class="item" onclick="$('.user_game_dungeon .list.sub_main').hide();$('.user_game_dungeon .list.main').show();">
                <img class="image" src="/assets/img/level/menu/back.png">
                <p class="title">${language_text('text_dungeon_select')}</p>
                <p class="note">${language_text('text_game_back')}</p>
            </div>`;
        $.each(server_data.game_dungeon[key].data, function(key2, value2) {
            if(server_data.game_dungeon[key].type == 'material'){
                var note = "";
                $.each(value2.reward, function(key3, value3) {
                    note += `<img class="item_icon" style="margin-top:0px;" src="/assets/img/level/${server_data.game_dungeon[key].type}/${value3}.png"/>`;
                })
            }else{
                var note = key == 'exp' ? ` ${value2.amount} <img class="item_icon" src="/assets/img/level/menu/dungeon/${key}.png"/>` : ` ${value2.amount} <img class="item_icon" src="/assets/img/level/${server_data.game_dungeon[key].type}/${key}.png"/>`;
            }
            html += `<div onclick="popup_data = {'main':'${key}','sub':'${key2}'};popup_load('game/dungeon_confirm')" class="item">
                        <img class="image" src="/assets/img/level/menu/dungeon/${key}.png">
                        <p class="title">${language_text('text_floor')} ${key2}</p>
                        <p class="note">${note}</p>
                    </div>`;
        })
        $(".user_game_dungeon .list.sub_main").html(html);
        $(".user_game_dungeon .list.main").hide();
        $(".user_game_dungeon .list.sub_main").show();
    }

    content_render();
    language_render(["text_dungeon"]);
</script>