<div class="user_game_dungeon">
    <div class="div_module">
        <h5></h5>
        <div class="current_balance currency_div">
            <div class="item">
                <img class="icon" src="/assets/img/level/material/friend_summon.png">
                <p class="num">0</p>
            </div>
        </div>
        <div class="list list_fr main">
        </div>
    </div>
</div>
<script> 
    async function content_render() {
        var summon_scroll = await get_data_by_url(`/api/character_count?type=material&sign=friend_summon&character=${my_character}&v=${server_time}`);
        $('.user_game_dungeon .current_balance .num').text(summon_scroll);
        character_data = await get_data_by_url(`/api/get_data_by_id?table=game_character&data=other&id=${my_character}&v=${server_time}`);
        character_data.other = JSON.parse(character_data.other);
        var summon_energy = getSafe(() => character_data.other.friend.energy, 0);
        $('.user_game_dungeon h5').text(json_data.language.menu_function_friend);
        var summon_list = [1,5,10];
        var html = `
            <div class="item" onclick="load_module('content','game/function/friend')">
                <img class="image" src="/assets/img/level/menu/back.png">
                <p class="title">Bảng điều khiển</p>
                <p class="note">Quay lại</p>
            </div>
        `;
        $.each(summon_list, function(index, value) {
            html += `
                <div class="item" onclick="popup_data = {'type':'normal','amount':${value}};popup_load('game/friend/summon_confirm');">
                    <img class="image" src="/assets/img/level/menu/friend/friend_summon_normal.png">
                    <p class="title">Thường x${value}</p>
                    <p class="note">${number_format(value)} <img class="item_icon" src="/assets/img/level/material/friend_summon.png"></p>
                </div>
            `;
        });
        html += `
            <div class="item" onclick="popup_data = {'type':'special','amount':3};popup_load('game/friend/summon_confirm');">
                <img class="image" src="/assets/img/level/menu/friend/friend_summon_special.png">
                <p class="title">[Thiên Cấp] Đặc Biệt</p>
                <p class="note">${summon_energy}/150 <img class="item_icon" src="/assets/img/level/icon/summon.png"></p>
            </div>
            <div class="item" onclick="popup_data = {'type':'special','amount':4};popup_load('game/friend/summon_confirm');">
                <img class="image" src="/assets/img/level/menu/friend/friend_summon_special.png">
                <p class="title">[Thần Cấp] Đặc Biệt</p>
                <p class="note">${summon_energy}/1500 <img class="item_icon" src="/assets/img/level/icon/summon.png"></p>
            </div>
        `;
        $('.user_game_dungeon .list.main').html(html);
    }

    function friend_summon(e){
        popup_close();
        var type = $(e).attr('type');
        var amount = $(e).attr('amount');
        $(e).prop('disabled', true);
        $.post("/assets/ajax/character_friend.php", { action : "summon" , type : type , amount : amount})
        .done(function(data) {
            $('#result').empty().append(data);
            $(e).prop('disabled', false);
        });
    }


    content_render();
    language_render(['text_game_dashboard','text_game_back']);
</script>