<div class="user_game_module shop">
    <div class="back_dashboard"><i onclick="load_module('content','game/dashboard')" class="fas fa-chevron-square-left"></i></div>
    <div class="current_balance currency_div">

    </div>
    <div class="avatar">
        <img class="image" src="/assets/img/level/menu/shop.png" />
        <p class="name menu_function_shop"></p>
    </div>
    <div class="menu">
    </div>
    <div id="shop_render_content">
    </div>
</div>
<script>
    page = 1;
    async function balance_currency(){
        currency_html = await balance_currency_html('gold');
        currency_html += await balance_currency_html('crystal');
        $('.user_game_module .current_balance').html(currency_html);
    }
    async function content_render() {
        server_data.game_shop = await get_server_data('game_shop');
        html = '';
        menu_html = '';
        $.each(server_data.game_shop, function(currency, value) {
            menu_html += `<div class="item" target="shop_${currency}"><img style="margin-bottom:8px;" src="/assets/img/level/currency/${currency}.png"></div>`;
            item_html = '';
            $.each(value.data, function(index, data) {
                if(data.type == 'friend'){
                    item_html += `
                        <div class="item">
                            <div class="info">
                                <div class="image"><img class="avatar" src="/assets/img/level/menu/friend/friend_summon_special.png" /></div>
                                <div class="detail"><p class="name">[Thiên Cấp] Đồng Hành</p></div>
                            </div>
                            <div class="price">
                                ${number_format(data.price)} <img class="item_icon" src="/assets/img/level/currency/${currency}.png">
                            </div>
                            <div class="button_control">
                                <button onclick="game_shop_confirm_currency = '${currency}',game_shop_confirm_target = '${index}',popup_load('game/shop_confirm')">Mua</button>
                            </div>
                        </div>
                    `;
                }else{
                    item_html += `
                        <div class="item" id="${data.type}_${data.sign}">
                            <div onclick="popup_data = {'type':'${data.type}','sign':'${data.sign}'};popup_load('profile/item')" class="info">
                                <div class="image"><img class="avatar" src="/assets/img/level/${data.type}/${data.sign}.png" /></div>
                                <div class="detail"><p class="name">${json_data.language[data.type+'_'+data.sign]}</p></div>
                            </div>
                            <div class="price">
                               ${number_format(data.price)} <img class="item_icon" src="/assets/img/level/currency/${currency}.png">
                            </div>
                            <div class="button_control">
                                <button onclick="game_shop_confirm_currency = '${currency}',game_shop_confirm_target = '${index}',popup_load('game/shop_confirm')">Mua</button>
                            </div>
                        </div>
                    `;
                }
            });
            html += `
                <div style="display: none;" class="shop_${currency} child_module">
                    <div class="item_list_use">
                        ${item_html}
                    </div>
                </div>
            `;
        });
        $('.user_game_module .menu').html(menu_html);
        $("#shop_render_content").html(html);
        $(".user_game_module .menu .item").click(function() {
            $(".user_game_module .menu .item").removeClass('active');
            $(this).addClass('active');
            $(".user_game_module .child_module").hide();
            $(".user_game_module .child_module."+$(this).attr('target')).show();
        });
        $('.user_game_module .menu .item[target=shop_gold]').click();
        balance_currency();
    }

    content_render();
    language_render(['menu_function_shop']);
</script>