<div class="user_game_dungeon">
    <div class="div_module">
        <h5 class="text_game_bank_deposit"></h5>
        <div class="current_balance currency_div">
        </div>
        <div class="secret_code">
            <p class="label text_game_bank_id"></p>
            <a text = "" onclick="copy_clipboard(this)"><p class="code"><span></span> <i class="fas fa-copy"></i></p></a>
            <a onclick="bank_code_change(this)"><p style="position:relative;height: 100%;width: 18px;"><i class="far fa-sync-alt center_div"></i></p></a>
        </div>
        <div class="list list_fr main">
            <div class="item" onclick="load_module('content','game/dashboard')">
                <img class="image" src="/assets/img/level/menu/back.png">
                <p class="title text_game_dashboard"></p>
                <p class="note text_game_back"></p>
            </div>
            <div class="item hide" onclick="popup_data = {'target':'crystal'};popup_load('user/topup')">
                <img class="image" src="/assets/img/level/menu/dungeon/crystal.png">
                <p class="title text_game_bank_buy_crystal"></p>
                <p class="note text_game_bank_buy_crystal_note"></p>
            </div>
            <div class="item" onclick="popup_load('game/bank/exchange')">
                <img class="image" src="/assets/img/level/menu/dungeon/gold.png">
                <p class="title text_game_bank_exchange"></p>
                <p class="note">1 <img class="item_icon" src="/assets/img/level/currency/crystal.png"/> = <span class="gold_exchange"></span> <img class="item_icon" src="/assets/img/level/currency/gold.png"/></p>
            </div>
            <div class="item" onclick="popup_load('game/bank/transfer')">
                <img class="image" src="/assets/img/level/menu/dungeon/crystal.png">
                <p class="title text_game_bank_transfer"></p>
                <p class="note"></p>
            </div>
            <div class="item hide" onclick="load_module('content','user/gift/list');">
                <img class="image" src="/assets/img/level/menu/bank/gift.png">
                <p class="title text_game_bank_gift"></p>
                <p class="note"></p>
            </div>
            <div class="item hide" onclick="popup_load('game/bank/manga_coin')">
                <img class="image" src="/assets/img/level/menu/bank/manga_coin.png">
                <p class="title">Đổi Xu Truyện</p>
                <p class="note">Dùng để đọc truyện</p>
            </div>
            <div class="item hide" onclick="popup_load('game/bank/pokecoin')">
                <img class="image" src="/assets/img/level/menu/bank/pokecoin.png">
                <p class="title">Nạp Pokecoin</p>
                <p class="note">Poke Trainer Game</p>
            </div>
            <div class="item hide" onclick="popup_load('game/bank/beebook')">
                <img class="image" src="/assets/img/level/menu/bank/beecoin.png">
                <p class="title">Nạp BeeCoin</p>
                <p class="note">App BeeBook</p>
            </div>
            <div class="item hide" onclick="popup_load('game/bank/beegem')">
                <img class="image" src="/assets/img/level/menu/bank/beegem.png">
                <p class="title">Đổi BeeGem</p>
                <p class="note">1 <img class="item_icon" src="/assets/img/level/menu/bank/beegem.png"/> = 30 <img class="item_icon" src="/assets/img/level/currency/gold.png"/></p>
            </div>
            <div class="item hide" onclick="load_module('content','game/bank/trader')">
                <img class="image" src="/assets/img/level/menu/bank/trader.png">
                <p class="title text_game_bank_trader"></p>
                <p class="note text_game_bank_trader_note"></p>
            </div>
        </div>
    </div>
</div>
<script> 
    module_back = 'game/function/bank';
    function bank_code_change(e){
        $(e).hide();
        $.post("/assets/ajax/character.php", { action : "bank_code_change"})
        .done(function(data) {
            $('#result').empty().append(data);
            $(e).show();
        });
    }
    async function balance_currency(){
        var currency_html = await balance_currency_html('crystal');
        $('.user_game_dungeon .current_balance').html(currency_html);
    }
    async function content_render() {
        $('.gold_exchange').text((parseInt(server_data.setting.level)*2)+100);
        character_setting  = await get_data_by_url(`/api/character_secret_data?data=setting`);
        var bank_code = getSafe(() => character_setting.bank.code, 0);
        if(bank_code != 0){
            $('.secret_code .code span').text(bank_code);
            $('.secret_code a').attr('text',bank_code);
            $('.secret_code').show();
        }else{
            bank_code_change();
        }
        balance_currency();
    }

    content_render();
    language_render(["text_game_bank_deposit","text_game_bank_id","text_game_dashboard","text_game_back","text_game_bank_buy_crystal","text_game_bank_buy_crystal_note","text_game_bank_exchange","text_game_bank_transfer","text_game_bank_gift","text_game_bank_trader","text_game_bank_trader_note"]);
    $('.user_game_dungeon .list_fr .item').removeClass('hide');
</script>