<div class="user_game_module shop pet">
    <div class="back_dashboard"><i onclick="load_module('content','game/function/pet')" class="fas fa-chevron-square-left"></i></div>
    <div class="avatar">
        <img class="image" src="/assets/img/level/menu/pet/pet_list.png" />
        <p class="name"></p>
    </div>
    <div id="bag_render_content">
        <div class="child_module pet_list">
            <div class="item_list_use"></div>
        </div>
    </div>
</div>
<script>
    page = 1;
    bag_data = {};
    async function content_render(type) {
        bag_data = {};
        $('.user_game_module .avatar .name').text(json_data.language.function_pet_list);
        await get_server_data('game_item');
        bag_data[type] = await get_character_list(`/api/character_list?character=${my_character}&list=${type}`);

        html = '';
        $.each(bag_data[type], function(index, data) {
            var server_pet_data = server_data.game_pet.data[data.sign];
            var quality = quality_convert(data.quality, 'pet');
            var main_level = Math.floor(data.level.num/10);
            var sub_level = data.level.num%10;
            var text_level = json_data.language['character_level_'+main_level]+' '+json_data.language['character_mini_level_'+sub_level];
            var onclick = `popup_data = {'target':'${type}','id':'${index}'};popup_load('profile/character')`;
            var button = ``;
            var status = `<span>${text_level}</span>`;
            if (data.status != "free") {
                if(server_time >= data.end && ['fight', 'rest'].includes(data.status)){
                    pet_set_free(index);
                }
            }else{
                button += `
                    <button class="circle" onclick="popup_data = {'sign':'${data.sign}','evolve':'${data.evolve}','id':'${index}'};popup_load('game/pet/release')"><i class="fas fa-rabbit-fast"></i></button>
                    <button class="circle" onclick="popup_data = {'sign':'${data.sign}','evolve':'${data.evolve}','id':'${index}'};popup_load('game/pet/skill')"><i class="fas fa-bolt"></i></button>
                `;
            }
            var next_evolve = parseInt(data.evolve)+1;
            console.log(server_pet_data);
            if(server_pet_data.evolve.level[next_evolve]){
                button += `<button class="circle" onclick="popup_data = {'sign':'${data.sign}','evolve':'${data.evolve}','id':'${index}'};popup_load('game/pet/evolve')"><i class="fas fa-chevron-double-up"></i></button>`;
            }

            button += `
                <button class="circle" onclick="popup_data = {'id':'${index}'};popup_load('game/pet/heart')"><i class="fa-solid fa-heart"></i></button>
                <button class="circle" onclick="popup_data = {'sign':'pet_exp','type':'currency','id':'${index}'};popup_load('game/pet/eat')"><i class="fas fa-carrot"></i></button>
            `;
            html += `
                <div class="item break" id="${type}_${index}">
                    <div onclick="${onclick}" class="info">
                        <div class="image"><img class="avatar" src="/assets/img/level/pet/${data.sign}_${data.evolve}.png" /></div>
                        <div class="detail">
                            <p class="name">${json_data.language['pet_'+data.sign]}</p>
                            <p class="text">${status} · ${json_data.language['friend_status_'+data.status]}</span> <span class="time_count_down" time="${data.end}"></span></p>
                        </div>
                    </div>
                    <div class="button_control">
                        ${button}
                    </div>
                </div>
            `;
        });
        $("#bag_render_content .child_module .item_list_use").html(html);
    }

    function pet_set_free(id){
        $.post("/assets/ajax/character_pet.php", { action : "set_free" , id : id})
        .done(function(data) {
            $('#result').empty().append(data);
            $('#pet_'+id+' .status').text(json_data.language.pet_status_free);
        });
    }

    function equipment_out(e){
        var target = $(e).attr('target');
        var level = $(e).attr('level');
        var material_need = number_format(server_data.game_pet.equipment_out[level]);
        alertify.confirm(language_text('text_pet_equipment_out_confirm',[material_need]), function(){ 
            $(e).hide();
                $.post('assets/ajax/character_pet.php',{action:"equipment_out",equipment_type: target , pet_id : character_profile_id},function(data){
                    $('#result').html(data);
                    $(e).show();
                });
         }).set('labels', {ok:language_text('text_yes'), cancel:language_text('text_no')}); ;
    }
    content_render('pet');
</script>