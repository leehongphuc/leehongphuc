<div class="user_game_module user_game_dungeon shop equipment_upgrade">
    <div class="div_module">
        <h5></h5>
        <div id="bag_render_content">
            <div class="back_dashboard"><i onclick="load_module('content','game/function/blacksmith')" class="fas fa-chevron-square-left"></i></div>
            <div class="child_module">
            <div class="item_list_use">
            </div>
        </div>
    </div>
</div>
<script> 
    async function game_equipment_upgrade_render(){
        await get_server_data('game_item_combine');
        $(".user_game_dungeon h5").html(json_data.language['function_equipment_upgrade_combine']);
        html = '';
        $.each(server_data.game_item_combine, function(index, data) {
            var style = "";
            html += `
                <div style="${style}" upgrade="${data.upgrade}" id="equipment_${index}" class="item equipment_type_${data.type} equipment_level_${data.level}">
                    <div onclick="popup_data = popup_data = {'type':'${data.type}','sign':'${index}'};popup_load('profile/item');" class="info">
                        <div class="image equipment_icon"><img class="avatar" src="/assets/img/level/${data.type}/${index}.png" /></div>
                        <div class="detail"><p class="name">${json_data.language[data.type+'_'+index]}</p></div>
                    </div>
                    <div class="button_control">
                        <button onclick="popup_data={'target':'${index}'};popup_load('game/blacksmith/combine')">Ghép</button>
                    </div>
                </div>
            `;
        });
        $("#bag_render_content .child_module .item_list_use").html(html);
        $("#bag_render_content").show();
    }
    

    game_equipment_upgrade_render();
</script>