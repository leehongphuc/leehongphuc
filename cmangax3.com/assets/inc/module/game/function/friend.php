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
            <div class="item" onclick="window.open('https://doc.cmanga.com/whitepaper-tieng-viet/gameplay/dong-hanh')">
                <img class="image" src="/assets/img/level/menu/guide.png">
                <p class="title">Cẩm nang</p>
                <p class="note">Đồng Hành</p>
            </div>
        </div>
    </div>
</div>
<script> 
    async function content_render() {
        server_data.game_friend = await get_server_data('game_friend');
        currency_html = await balance_currency_html('friend_exp');
        $('.user_game_dungeon .current_balance').html(currency_html);
        $('.user_game_dungeon h5').text(json_data.language.menu_function_friend);
        var menu_list = ["list","summon"];
        var html = "";
        $.each(menu_list, function(index, value) {
            html += `
                <div class="item" onclick="load_module('content','game/friend/${value}')">
                    <img class="image" src="/assets/img/level/menu/friend/friend_${value}.png">
                    <p class="title">${json_data.language['function_friend_'+value]}</p>
                    <p class="note"></p>
                </div>
            `;
        });
        $('.user_game_dungeon .list.main').append(html);
    }

    content_render();
    language_render(['text_game_dashboard','text_game_back']);
</script>