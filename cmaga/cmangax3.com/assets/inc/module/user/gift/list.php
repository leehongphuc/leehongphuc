<div class="title_menu">
    <h3>Quà tặng</h3>
    <div class="current_balance currency_div">
    </div>
</div>

<div class="gift_module">
    <div class="gift_list list_fr">
    </div>
    <div class="gift_button button">
        <button onclick="gift_exchange(this);" target = "0" class="button_yes">Đổi quà</button>
        <button onclick="load_module('content',module_back)" target = "0" class="button_no">Hủy</button>
    </div>
</div>
<script>
    async function balance_currency(){
        var currency_html = await balance_currency_html('crystal');
        $('.current_balance').html(currency_html);
    }
    async function module_render() {
        delete server_data.gift;
        await get_server_data('gift');
        html = "";
        $.each(server_data.gift, function(index, content_data) {
            if(content_data.remain > 0){
                html = html + `
                    <div target="${index}" class="item">
                        <img class='image' src="/assets/img/gift/${content_data.image}"/>
                        <p class="name">${content_data.name}</p>
                        <p class="price">${content_data.price} <img style="margin-top: -8px;" class="item_icon" src="/assets/img/level/currency/crystal.png"></p>
                        <div class="clear"></div> 
                    </div>
                `;
            }
        });
        html = html + `<div class="clear"></div>`;
        $('.gift_list').html(html)
        $(".gift_module .gift_list .item").click(function() {
            $(".gift_module .gift_list .item").removeClass('active');
            $(this).addClass('active');
            var gift_type = $(this).attr('target');
            $('.gift_button .button_yes').attr('target', gift_type);
        });
        balance_currency();
    }

    function gift_exchange(e) {
        alertify.confirm(language_text('text_confirm'),'Bạn chắc chắn muốn đổi loại quà này?', function() {
            var type = $(e).attr('target');
            if(type != '0'){
                $(e).hide();
                $.post("/assets/ajax/character.php", { action : 'gift_exchange' , type : type })
                .done(function(data) {
                    $('#result').html(data);
                    $(e).show();
                });
            }else{
                alertify.error('Vui lòng chọn loại quà cần đổi');
            }
        }, function() {
        });
    }
    setTimeout(() => {
        module_render();
    }, 300);
</script>