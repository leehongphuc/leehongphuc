<div class="user_game_dungeon">
    <div class="div_module">
        <h5></h5>
        <div class="current_balance currency_div">
        </div>
        <div class="list list_fr main">
            <div class="item" onclick="load_module('content','game/function/pet')">
                <img class="image" src="/assets/img/level/menu/back.png">
                <p class="title text_game_dashboard"></p>
                <p class="note text_game_back"></p>
            </div>
        </div>
    </div>
</div>
<script> 
    pet_formation_data = {};
    async function content_render() {
        character_data = await get_data_by_url(`/api/get_data_by_id?table=game_character&data=pet&id=${my_character}&v=${server_time}`);
        character_data.pet = json_convert(character_data.pet);
        currency_html = await balance_currency_html('gold');
        $('.user_game_dungeon .current_balance').html(currency_html);
        $('.user_game_dungeon h5').text(json_data.language.function_pet_formation);
        var menu_list = [0,800,1200,1600,2000]
        var html = "";
        $.each(menu_list, function(index, value) {
            var note = `${value} <img class="item_icon" src="/assets/img/level/currency/gold.png"/> / giờ`;
            var pet_icon = "/assets/img/transparent.png";
            var onclick = "popup_load('game/pet/formation_select')";
            if(getSafe(() => character_data.pet.formation[index], 0) != 0){
                if(character_data.pet.formation[index].type == "egg"){
                    pet_icon = `/assets/img/level/material/${character_data.pet.formation[index].target}.png`;
                }else{
                    pet_icon = `/assets/img/level/pet/${character_data.pet.formation[index].sign}_${character_data.pet.formation[index].evolve}.png`;
                }
                note = `<span class="time_count_down" time="${character_data.pet.formation[index].end}"></span>`
                onclick = `popup_load('game/pet/formation_control')`;
            }
            html += `
                <div class="item pet_slot" onclick="pet_formation_data.slot = ${index};${onclick}">
                    <img class="pet_icon" src="${pet_icon}">
                    <img class="image" src="/assets/img/level/menu/pet/pet_formation.png">
                    <p class="title">${language_text('text_pet_formation_position')} ${index+1}</p>
                    <p class="note">${note}</p>
                </div>
            `;
        });
        $('.user_game_dungeon .list.main .pet_slot').remove();
        $('.user_game_dungeon .list.main').append(html);
    }
    function pet_formation_select(e){
        $(e).prop('disabled', true);
        pet_formation_data.type = $(e).attr('type');
        pet_formation_data.target = $(e).attr('target');
        popup_load('game/pet/formation_select_confirm');
        setTimeout(function() {
            $(e).prop('disabled', false);
        }, 500);
    }
    function pet_formation_select_confirm(e){
        pet_formation_data.time = $('.popup_module.game_pet_formation_select_confirm .confirm .amount input').val();
        $(e).prop('disabled', true);
        $.post("/assets/ajax/character_pet.php", { action : "formation" , data : pet_formation_data})
        .done(function(data) {
            $('#result').empty().append(data);
            $(e).prop('disabled', false);
        });
    }
    content_render();
    language_render(['text_game_dashboard','text_game_back']);
</script>