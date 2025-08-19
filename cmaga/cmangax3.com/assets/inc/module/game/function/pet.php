<div class="user_game_dungeon">
    <div class="div_module">
        <h5></h5>
        <div class="current_balance currency_div">
        </div>
        <div class="list list_fr main">
            <div class="item" onclick="load_module('content','game/dashboard')">
                <img class="image" src="/assets/img/level/menu/back.png">
                <p class="title text_game_dashboard"></p>
                <p class="note text_game_back"></p>
            </div>
            <div class="item" onclick="window.open('https://doc.cmanga.com/whitepaper-tieng-viet/gameplay/linh-thu')">
                <img class="image" src="/assets/img/level/menu/guide.png">
                <p class="title">Cẩm nang</p>
                <p class="note">Linh Thú</p>
            </div>
        </div>
    </div>
</div>
<script> 
    async function content_render() {
        server_data.game_pet = await get_server_data('game_pet');
        currency_html = await balance_currency_html('pet_exp');
        $('.user_game_dungeon .current_balance').html(currency_html);
        $('.user_game_dungeon h5').text(json_data.language.menu_function_pet);
        var menu_list = ["list","formation","farm"];
        var html = "";
        $.each(menu_list, function(index, value) {
            html += `
                <div class="item" onclick="load_module('content','game/pet/${value}')">
                    <img class="image" src="/assets/img/level/menu/pet/pet_${value}.png">
                    <p class="title">${json_data.language['function_pet_'+value]}</p>
                    <p class="note"></p>
                </div>
            `;
        });
        $('.user_game_dungeon .list.main').append(html);
    }

    content_render();
    language_render(['text_game_dashboard','text_game_back']);
</script>