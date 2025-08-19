<div class="user_game_module market">
    <div class="back_dashboard"><i onclick="load_module('content','game/dashboard')" class="fas fa-chevron-square-left"></i></div>
    <div onclick="popup_data = {'target':'crystal'};popup_load('user/topup')" class="current_balance currency_div">
    </div>
    <div class="market_module">
        <div class="market_menu">
            <div class="avatar">
                <img class="image" src="/assets/img/level/menu/market.png" />
                <p class="name menu_function_market"></p>
            </div>
            <div style="justify-content: center;gap: 20px;" class="button_style">
                <button onclick="popup_load('game/market/sell')" class="button_yes"><i class="fas fa-plus"></i> <text class="text_market_sell"></text></button>
                <button onclick="popup_load('game/market/filter')"><i class="fas fa-filter"></i> <text class="text_filter"></text></button>
            </div>
        </div>
        <div class="market_item">
            <table class="main_table">
                <thead>
                    <tr>
                        <td class="text_bag_item"></td>
                        <td class="pc_display_2 text_amount"></td>
                        <td class="pc_display_2 text_price"></td>
                        <td class="pc_display_2"></td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div class="page_redirect"></div>
        </div>
    </div>
</div>
<script>
    page = 1;
    page_loading = 'no';
    market_filter = {"type":"all","special_type":"all","special_level":1,"sign":"all","status":"all","owner":0,"sort":"new",'special_data':''};
    check_character = my_character.toString();
    market_lucky_number = my_character < 10 ? '0'+my_character : check_character.substr(check_character.length - 2);
    async function balance_currency(){
        var currency_html = await balance_currency_html('crystal');
        $('.user_game_module .current_balance').prepend(currency_html);
    }
    async function content_render() { 
        if(page != 0){
            if(page == 1){
                $('.user_game_module .market_item .main_table tbody').empty();
            }
            market_data = await get_data_by_url(`/api/game_market?page=${page}&limit=20&sort=${market_filter.sort}&type=${market_filter.type}&special_level=${market_filter.special_level}&special_data=${market_filter.special_data}&special_type=${market_filter.special_type}&sign=${market_filter.sign}&status=${market_filter.status}&owner=${market_filter.owner}`);
            if(Object.keys(market_data.data).length != 0){
                var html = "";
                $.each(market_data.data, function(index, item) {
                    var data = JSON.parse(item.data);
                    var onclick = "";
                    var button = `<div class="button"><button onclick="popup_data= {'id':'${item.id_game_market}'};popup_load('game/market/buy_confirm','no')">${language_text('text_button_buy')}</button></div>`
                    if(data.type == "equipment"){
                        var image = `<a onclick="popup_data = {'type':'equipment','id':'${data.id}'};popup_load('profile/item','no')"><img class="image" src="/assets/img/level/equipment/${data.special_type}/${data.sign}_${data.level}.png" /></a>`;
                        var name = `<a onclick="popup_data = {'type':'equipment','id':'${data.id}'};popup_load('profile/item','no')"><p class="name level_${data.level}">${json_data.language[data.special_type+'_'+data.sign+'_'+data.level]}</p></a>`;
                    }else{
                        var image = `<a onclick="popup_data = {'type':'${data.type}','sign':'${data.sign}'};popup_load('profile/item','no')"><img class="image" src="/assets/img/level/${data.type}/${data.sign}.png" /></a>`;
                        var name = `<a onclick="popup_data = {'type':'${data.type}','sign':'${data.sign}'};popup_load('profile/item','no')"><p class="name">${number_format(data.amount)} x ${json_data.language[data.type+'_'+data.sign]} <a class="mobile_display"></a></p></a>`;
                    }
                    var time_sold = time_convert(data.date);

                    if(data.status == "selling"){
                        if(data.owner == my_character){
                            button = `<div class="button"><button class="button_no" time_sold="${time_sold}" item_id="${item.id_game_market}" onclick="game_market_cancel(this)">${language_text('text_cancel')}</button></div>`
                        }else if(time_sold >= server_time){
                            button = `<span class="time_count_down" time = "${time_sold}"></span>`;
                            if(time_sold - 240 >= server_time){
                                button += `<div style="margin-left:10px;" class="button"><button class="button_yes" onclick="popup_data= {'id':'${item.id_game_market}'};popup_load('game/market/deposit_confirm','no')">${language_text('text_bid')}</button></div>`;
                            }
                        }
                    }else{
                        button = data.status == "sold" ? `<span style="color:var(--yellow-color);">${language_text('text_sold')}</span>` : `<span style="color:#ff6555">${language_text('text_canced')}</span>`
                    }
                    html += `
                        <tr market_id="${item.id_game_market}"  class="item_page_${page}">
                            <td>
                                ${image}
                                ${name}
                                <div class="other_detail mobile_display"><span>${number_format(data.price)}</span> <img class="item_icon" src="/assets/img/level/currency/crystal.png"> ${button}</div>
                            </td>
                            <td class="pc_display_2">${number_format(data.amount)}</td>
                            <td class="pc_display_2"><span>${number_format(data.price)}</span> <img class="item_icon" src="/assets/img/level/currency/crystal.png"></td>
                            <td class="pc_display_2">${button}</td>
                        </tr>
                    `;
                });
                $('.user_game_module .market_item .main_table tbody').html(html);
                $(`.page_redirect`).html(page_redirect_render(market_data.total,page,20));
                page_loading = 'no';
            }else{
                page = 0;
            }
        }
    }
    function game_market_cancel(e){
        var time_sold = parseInt($(e).attr('time_sold'));
        if(time_sold > server_time){
            var text = language_text('text_sell_cancel_warning');
        }else{
            var text = language_text('text_sell_cancel_note');
        }
        alertify.confirm(language_text('text_confirm'), text, function() {
            var item_id = $(e).attr('item_id');
            $(e).hide();
            $.post("/assets/ajax/market.php", { action : "cancel" , item_id : item_id })
            .done(function(data) {
                $('#result').empty().append(data);
            });
        }, function() {
        });
    }
    function page_redirect_start(){
        content_render();
        $([document.documentElement, document.body]).animate({
            scrollTop: $('.user_game_module .market_item .main_table tbody').offset().top - 200
        }, 200);
    }
    balance_currency();
    content_render();
    language_render(['text_market_sell','text_filter','text_bag_item','text_amount','text_price']);
</script>