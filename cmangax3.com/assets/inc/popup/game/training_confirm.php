<div class="popup_module center_div game_training_confirm">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="buy_confirm item">
        <div class="preview">
            <div class="image"><img src="/assets/img/transparent.png" /></div>
            <p class="name"></p>
            <p class="note"></p>
        </div>
        <div class="material">
        </div>
        <div class="confirm">
            <div class="item">
                <p class="label text_get_per_min" style="width:auto;"></p>
                <div class="price total_take"><span class="num">0</span> <span class="currency"><img src="/assets/img/transparent.png"/></span></div>
            </div>
            <div class="item">
                <p class="label text_fee_per_min" style="width:auto;"></p>
                <div class="price total_fee"><span class="num">0</span> <span class="currency"><img src="/assets/img/level/currency/gold.png"/></span></div>
            </div>
            <div class="item">
                <p class="label text_min_level" style="width:auto;"></p>
                <div class="price min_level"></div>
            </div>
        </div>
        <div class="button">
            <button style="width:100%;margin:0;" onclick="game_training_start(this);" class="text_training_start_button"></button>
        </div>
    </div>
</div>
<script>
    async function popup_game_training_confirm(){
        $('.game_training_confirm .name').text(json_data.language['activity_training_'+popup_data.main]);
        $('.game_training_confirm .note').text(`Tầng ${popup_data.level}`);
        $('.game_training_confirm .image img').attr('src',`/assets/img/level/menu/training/${popup_data.main}.png`);
        $('.game_training_confirm .total_fee .num').text(server_data.game_training[popup_data.main][popup_data.level].fee);
        $('.game_training_confirm .total_take .num').text(server_data.game_training[popup_data.main][popup_data.level].amount);
        $('.game_training_confirm .total_take img').attr('src',`/assets/img/level/menu/training/${popup_data.main}.png`);
        $('.game_training_confirm .min_level').text(json_data.language['character_level_'+(popup_data.level-1)]);
    }
    function game_training_start(e){
        var item_amount = parseInt($('.popup_module.game_job_create .confirm .item_amount input').val());
        $(e).hide();
        $.post("/assets/ajax/character_activity.php", { action : "game_training" , main : popup_data.main, level : popup_data.level})
        .done(function(data) {
            $('#result').empty().append(data);
        });
    }
    language_render(['text_training_start_button','text_get_per_min','text_fee_per_min','text_min_level']);
    popup_game_training_confirm();
</script>