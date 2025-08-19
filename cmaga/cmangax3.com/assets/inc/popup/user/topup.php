<div style="width:600px" class="popup_module center_div">
    <div onclick="popup_close();" class="close"><i class="fal fa-times center_div"></i></div>
    <h4>Mua <span class="topup_currency"></span></h4>
    <div class="topup_screen input_screen">
        <div class="topup">
            <div class="payment_menu payment_menu_vn">
                <div id="recharge_menu">
                    <ul>
                        <li target="recharge_vn_payos" class="active"><img src="/assets/img/payment/qr.png" /><p class="name">QR Code</p><div class="clear"></div></li>
                        <li target="recharge_vn_payos"><img src="/assets/img/payment/momo.png" /><p class="name">Momo</p><div class="clear"></div></li>
                        <div class="clear"></div>
                    </ul>
                </div>
                <div class="pay_method">
                    <ul class="recharge_vn_payos recharge_module form">
                        <li class="coin_exchange">
                            <p class="label">Số <span class="topup_currency"></span> muốn mua <img class="topup_currency_sign" src="/assets/img/transparent.png"></p>
                            <input onkeyup="coin_exchange(this)" name="amount" id="recharge_vn_payos_amount" maxlength="10">
                            <p class="price coin_exchange_text">= 0đ</p>
                        </li>
                    </ul>
                    <ul style="display: none;" class="recharge_vn_momo recharge_module form">
                        <li class="coin_exchange">
                        <p class="label">Số <span class="topup_currency"></span> muốn mua <img class="topup_currency_sign" src="/assets/img/transparent.png"></p>
                            <input onkeyup="coin_exchange(this)" name="amount" id="recharge_vn_momo_amount" maxlength="10">
                            <p class="price coin_exchange_text">= 0đ</p>
                        </li>
                    </ul>
                    <ul style="display: none;"  class="recharge_vn_phone_card recharge_module form">
                        <li>
                            <p class="label">Loại thẻ</p>
                            <select name="type" id="vn_card_type">
                                <option value="0">-- Chọn loại thẻ --</option>
                                <option value="VIETTEL">Viettel</option>
                                <option value="MOBI">Mobifone</option>
                                <option value="VINA">Vinaphone</option>
                            </select>
                        </li>
                        <li>
                            <p class="label">Mệnh giá</p>
                            <select type='select' id="vn_card_amount" name="amount">
                            </select>
                        </li>
                        <li>
                            <p class="label">Seri</p>
                            <input maxlength="20" id="vn_card_txtseri" value="" />
                        </li>
                        <li>
                            <p class="label">Mã thẻ</p>
                            <input maxlength="20" id="vn_card_txtpin" value=""/>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="button">
                <button onclick="payment_action();" style="margin:20px 0;width:100%;" id="topup_button" class="topup button_yes">Mua</button>
            </div>
        </div>
    </div>
    <div class="frame_screen hide">
        <iframe width="100%" height="600px"></iframe>
    </div>
    <script>
        topup_exchange = {"crystal":200,"manga_coin":100};
        card_list = ["10000","20000","30000","50000","100000","200000","300000","500000","1000000"];
        payment_method = 'recharge_vn_payos'; 
        
        $( "#recharge_menu ul li" ).click(function(e) {
            $('#recharge_menu ul li').removeClass('active');
            $(this).addClass('active');
            $('.recharge_module').hide();
            target = $(this).attr('target');
            payment_method = target;
            $('.'+target).slideDown();
        });
        function coin_exchange(e) {
            var coin = $(e).val();
            var coin_exchange = coin * topup_exchange[popup_data.target];
            $(e).parent('.coin_exchange').children('.coin_exchange_text').text("= " + number_format(coin_exchange) + " đ");
        }
        function payment_render(){
            $('.topup_currency').text(json_data.language['topup_'+popup_data.target]);
            $('.topup_currency_sign').attr('src','/assets/img/user/currency/'+popup_data.target+'.png');
            html = `<option value="0">-- Chọn mệnh giá --</option>`;
            $.each(card_list, function(key, value) {
                html += `<option value="${value}">${number_format(value)}đ = ${Math.floor(value*0.75/topup_exchange[popup_data.target])} ${json_data.language['topup_'+popup_data.target]}</option>`;
            });
            $("#vn_card_amount").empty().html(html);
        }
            
        function payment_menu(e){
            country = $(e).children("option:selected").val();
            $('.payment_menu').hide();
            country_valid = ["VN"];
            if(country_valid.includes(country) == true){
                $("#default_payment_method").click();
                $('.payment_menu_vn').show();    
            }else{
                payment_method = 'recharge_global_paymentwall';
                $('.payment_menu_global').show();
            }
        }
        
        function payment_action(){
            if(payment_method == 'recharge_vn_payos'){   
                payment_amount = parseInt($("#recharge_vn_payos_amount").val());
                pay_amount = payment_amount*topup_exchange[popup_data.target];
                if($.trim(payment_amount) == ""){
                    alertify.error(`Bạn chưa nhập số ${json_data.language['topup_'+popup_data.target]} muốn mua`);
                }else if($.trim(pay_amount) < 10000){
                    alertify.error('Mua tối thiểu 10,000đ');
                }else{
                    $("#topup_button").hide();
                    $.post("/assets/payment_gate/create_order.php", { action : "beepay" , target : popup_data.target ,total_amount : payment_amount , user_topup : token_user})
                    .done(function(data) {
                        $('#result').empty().append(data);  
                        $("#topup_button").show();   
                    });
                }
            }else if(payment_method == 'recharge_vn_phone_card'){
                type = $("#vn_card_type option:selected").val();
                amount = $("#vn_card_amount option:selected").val();
                seri = $("#vn_card_txtseri").val();
                pin = $("#vn_card_txtpin").val();
                if($.trim(type) == 0){
                    alertify.error('Bạn chưa chọn loại thẻ');
                }else if($.trim(amount) == 0){
                    alertify.error('Bạn chưa chọn mệnh giá thẻ');
                }else if($.trim(seri) == ""){
                    alertify.error('Bạn chưa nhập seri');
                }else if($.trim(pin) == ""){
                    alertify.error('Bạn chưa nhập mã thẻ');
                }else{
                    $("#topup_button").hide();
                    $.post("/assets/payment_gate/card_service/kingcard.php", { type : type , target : popup_data.target , seri : seri , pin : pin , amount : amount , user_topup : token_user})
                    .done(function(data) {
                        $('#result').empty().append(data);
                        $("#topup_button").show();
                    });
                }
            }
        }
        function payment_frame(url){
            $('.topup_screen').hide();
            $('.frame_screen').show();
            $('.frame_screen iframe').attr('src',url);
        }
        payment_render();
    </script>
</div>