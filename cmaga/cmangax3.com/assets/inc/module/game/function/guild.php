<div class="user_game_dungeon">
    <div class="div_module">
        <h5 class="menu_function_guild"></h5>
        <div class="list list_fr main">
        </div>
    </div>
</div>
<script> 
    my_guild_role = 0;
    my_guild_id = 0;
    async function content_render() {
        server_data.game_guild = await get_server_data('game_guild');
        character_profile_data = await get_data_by_url('/api/get_data_by_id?table=game_character&data=info&id='+my_character);
        character_profile_data.info = JSON.parse(character_profile_data.info);
        var html = `
        <div class="item" onclick="load_module('content','game/dashboard')">
            <img class="image" src="/assets/img/level/menu/back.png">
            <p class="title">${language_text('text_game_dashboard')}</p>
            <p class="note">${language_text('text_game_back')}</p>
        </div>`;
        if(character_profile_data.info.guild){
            my_guild_id = character_profile_data.info.guild.id;
            my_guild_role = await get_data_by_url(`/api/game_member_role?target=guild&target_id=${my_guild_id}&character=${my_character}`);
            get_guild_data = await get_data_by_url('/api/get_data_by_id?table=game_guild&data=data,donate&id='+my_guild_id+'&v='+server_time);
            guild_data = json_convert(get_guild_data.data);
            guild_donate = json_convert(get_guild_data.donate);
            var guild_list = {"home":1,"member":1,"quest":1,"medicinal":1,"skill":1,"stats":1,"storage":1,"crystal":1,"book":1,'salary':1};
            $.each(guild_list, function(key, value) {
                var level = getSafe(() => guild_data.building[key], 1);
                var image = key == 'home' ? 'assets/tmp/game/guild/'+guild_data.avatar : `assets/img/level/guild/menu/${key}.png`;
                var name = key == 'home' ? guild_data.name : json_data.language['guild_'+key];
                var onclick = value == 0 ? "alertify.error(language_text('text_building'))" : `load_module('content','game/guild/building/${key}')`;
                var css = value == 0 ? "filter: grayscale(1);" : "";
                html += `<div style="${css}" class="item" onclick="${onclick}">
                            <img class="image" src="${image}">
                            <p class="title">${name}</p>
                            <p class="note">Cấp ${level}</p>
                        </div>`;
            })
        }else{
            html += `
                <div class="item" onclick="load_module('content','game/guild/create')">
                    <img class="image" src="/assets/img/level/guild/menu/home/6.png">
                    <p class="title">${language_text('text_button_register')}</p>
                    <p class="note">${language_text('text_guild_create')}</p>
                </div>
                <div class="item" onclick="load_module('content','game/guild/list')">
                    <img class="image" src="/assets/img/level/menu/guild.png">
                    <p class="title">${language_text('text_list')}</p>
                    <p class="note">${language_text('text_guild_list')}</p>
                </div>
            ` ;
        }
        $(".user_game_dungeon .list.main").html(html);
    }

    content_render();

    language_render(["menu_function_guild"]);
</script>