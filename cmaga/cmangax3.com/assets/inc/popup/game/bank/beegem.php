<div class="popup_module center_div game_bank_beegem">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="buy_confirm item">
        <div class="preview">
            <div class="image"><img src="/assets/img/level/menu/bank/beegem.png" /></div>
            <p class="name">Đổi BeeGem</p>
            <p class="note">BeeGem nhận được khi bạn đọc truyện và hoạt động trên BeeBook</p>
        </div>
        <div style="padding-top: 25px;" class="input_screen">
            <ul class="form">
                <li>
                    <p class="label">Email đăng nhập Beebook</p>
                    <input id="beebook_email" placeholder="" />
                </li>
                <li>
                    <p class="label">Mật khẩu</p>
                    <input type="password" id="beebook_password" />
                </li>
                <li>
                    <p style="color: var(--yellow-color);" class="note">Mỗi nhân vật chỉ có thể đổi BeeGem 15 ngày 1 lần</p>
                </li>
            </ul>
            <div class="button">
                <button style="width:100%;margin:0;" onclick="beegem_check_account(this);">Tiếp tục</button>
            </div>
        </div>
        <div style="display: none;" class="confirm">
            <div class="item">
                <p class="label">Đang có</p>
                <div class="price balance"><span num="0" class="num">0</span> <span class="currency"><img src="/assets/img/level/menu/bank/beegem.png"/></span></div>
            </div>
            <div class="item">
                <p class="label">Nhận được</p>
                <div class="price total_take"><span class="num">0</span> <span class="currency"><img src="/assets/img/level/currency/gold.png"/></span></div>
            </div>
            <div class="button">
                <button style="width:100%;margin:0;" onclick="game_bank_beegem(this);">Đổi</button>
            </div>
        </div>
    </div>
</div>
<script>
    bank_beegame = {};
    async function popup_game_bank_beegem(){
        var character_setting = await get_data_by_url('/api/get_data_by_id?table=game_character&data=setting&id='+my_character+'&v='+server_time);
        character_setting = json_convert(character_setting.setting);
        if(character_setting.beegem){
            if(character_setting.beegem >= server_time){
                $('.game_bank_beegem .input_screen .note').html(`Bạn có thể đổi BeeGem sau ${time_format(character_setting.beegem,'yes','yes')}`);
                $('.game_bank_beegem .input_screen .button button').prop('disabled', true);
                $('.game_bank_beegem .input_screen .button button').css('filter', 'grayscale(1)');
            }
        }
    }
    async function beegem_check_account(e){
        $(e).prop('disabled', true);
        var email = $('#beebook_email').val();
        var password = $('#beebook_password').val();
        password = CryptoJS.SHA1(password).toString();
        var account_data = await get_data_by_url(`https://bbbokkk.com/api/api_user_token_new?email=${email}&password=${password}`);
        if(account_data.status == 1){
            bank_beegame.token = account_data.data.token;
            bank_beegame.user = account_data.data.user_id;
            beegem_check_amount();
        }else{
            alertify.error('Tài khoản hoặc mật khẩu không đúng');
            $(e).prop('disabled', false);
        }
    }
    async function beegem_check_amount(){
        var item_data = await get_data_by_url(`https://bbbokkk.com/api/api_user_item?user=${bank_beegame.user}`);
        if(item_data.status == 1){
            bank_beegame.amount = item_data.data.gem;
            $('.popup_module.game_bank_beegem .input_screen').hide();
            $('.popup_module.game_bank_beegem .confirm .balance .num').html(number_format(bank_beegame.amount));
            $('.popup_module.game_bank_beegem .confirm .total_take .num').html(number_format(Math.floor(bank_beegame.amount*30)));
            $('.popup_module.game_bank_beegem .confirm').show();
        }
    }

    function game_bank_beegem(e){
        alertify.confirm('Bạn có chắc chắn tiến hành đổi Gem?', function(){ 
            $(e).prop('disabled', true);
            $.post("/assets/ajax/character.php", { action : "game_bank_beegem" , user : bank_beegame.user , token : bank_beegame.token})
            .done(function(data) {
                $('#result').empty().append(data);
            });
         }).set('labels', {ok:language_text('text_yes'), cancel:language_text('text_no')}); ;
    }

    popup_game_bank_beegem();
</script>