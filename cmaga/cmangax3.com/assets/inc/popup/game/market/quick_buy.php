<div class="popup_module center_important game_market_quick_buy">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="input_screen">
        <ul class="form">
            <li class="market_item market_sub">
                <p class="label text_market_filter_name"></p>
                <div class="tags_list overwrite">
                    <div class="tags_module">
                        <input onkeyup="album_tags_search(this,1)" onclick="$('#album_tags_name').html('');album_tags_search(this,2)"/>
                        <div id="quick_buy_list" class="tags_create">
                            
                        </div>
                        <div onclick="$(this).hide();$(this).parent('.tags_module').children('.tags_create').hide();" class="tags_icon_close"><i class="far fa-times"></i></div>
                    </div>
                    <div id="album_tags_name" class="tags_show list_fr">
                    </div>
                </div>
            </li>
            <li class="market_item market_sub">
                <p class="label text_amount"></p>
                <input id="market_quick_buy_amount" type="input" min="1" value="1" maxlength="3"/>
            </li>
            <li class="market_item market_sub">
                <p class="label"><text class="text_market_quick_buy_price"></text> <img class="item_icon" src="assets/img/level/currency/crystal.png" /></p>
                <input id="market_quick_buy_price" type="input" min="1" value="1" maxlength="3"/>
            </li>
        </ul>
    </div>
    <div class="button">
        <button style="width:100%;margin:0;" onclick="game_market_quick_buy(this);" class="text_market_quick_buy_start"></button>
    </div>
</div>
<script>
    function popup_game_market_quick_buy(e){
        var html = '';
        $.each(market_item_list, function(index, value) {
            html = html + `<div onclick="tags_list_select(this);market_filter.sign = '${value}';" class="tags_span item_${value}">${json_data.language[value]}</div>`;
        });
        $("#quick_buy_list").html(html);
        $('.game_market_quick_buy .market_item').show();
    }
    function game_market_quick_buy(e) {
        var buy_amount = $('#market_quick_buy_amount').val();
        var buy_price = $('#market_quick_buy_price').val();
        $(e).hide();
        $.post("/assets/ajax/market.php", { action : "quick_buy" , sign : market_filter.sign, buy_amount : buy_amount, buy_price : buy_price})
        .done(function(data) {
            $('#result').empty().append(data);
            $(e).show();
        });
    }
    language_render(['text_market_filter_name','text_amount','text_market_quick_buy_price','text_market_quick_buy_start']);
    popup_game_market_quick_buy();
</script>