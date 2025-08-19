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
    pet_farm_data = {};
    async function content_render() {
        character_data = await get_data_by_url(`/api/get_data_by_id?table=game_character&data=pet&id=${my_character}&v=${server_time}`);
        character_data.pet = json_convert(character_data.pet);
        currency_html = await balance_currency_html('gold');
        $('.user_game_dungeon .current_balance').html(currency_html);
        $('.user_game_dungeon h5').text(json_data.language.function_pet_farm);
        var html = "";
        for(index=1;index<=6;index++){
            var note = ``;
            var pet_icon = "/assets/img/transparent.png";
            var onclick = "popup_load('game/pet/farm_select')";
            if(getSafe(() => character_data.pet.farm[index], 0) != 0){
                pet_icon = `/assets/img/level/pet/${character_data.pet.farm[index].sign}_${character_data.pet.farm[index].evolve}.png`;
                onclick = `popup_load('game/pet/farm_control')`;
                note = `<span class="time_count_down" time="${character_data.pet.farm[index].end}"></span>`
            }
            html += `
                <div class="item pet_slot" onclick="pet_farm_data.slot = ${index};${onclick}">
                    <img class="pet_icon" src="${pet_icon}">
                    <img style="height: 110px;margin-bottom: 0;" class="image" src="/assets/img/level/menu/pet/pet_farm.png">
                    <p class="title"></p>
                    <p class="note">${note}</p>
                </div>
            `;
        }
        $('.user_game_dungeon .list.main .pet_slot').remove();
        $('.user_game_dungeon .list.main').append(html);
    }
    function pet_farm_select(e){
        $(e).prop('disabled', true);
        pet_farm_data.target = $(e).attr('target');
        popup_load('game/pet/farm_select_confirm');
        setTimeout(function() {
            $(e).prop('disabled', false);
        }, 500);
    }
    function pet_farm_select_confirm(e){
        var hour = parseInt($('.popup_module.game_pet_farm_select_confirm .confirm .time .amount input').val());
        $(e).prop('disabled', true);
        $.post("/assets/ajax/character_pet.php", { action : "farm" , data : pet_farm_data , hour : hour})
        .done(function(data) {
            $('#result').empty().append(data);
            $(e).prop('disabled', false);
        });
    }
    content_render();
    language_render(['text_game_dashboard','text_game_back']);
</script>