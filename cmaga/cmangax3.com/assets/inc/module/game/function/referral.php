<div class="user_game_module referral">
    <div class="back_dashboard"><i onclick="load_module('content','game/dashboard')" class="fas fa-chevron-square-left"></i></div>
    <div class="avatar">
        <img class="image" src="/assets/img/level/menu/referral.png" />
        <p class="name menu_function_referral"></p>
        <p class="note text_referral_note"></p>
    </div>
    <div class="menu">
        <div target="guide" class="item active text_intro"></div>
    </div>
    <div class="guide child_module">
        <ul>
            <li class="text_referral_guide_1"></li>
            <li class="text_referral_guide_2"></li>
            <li class="text_referral_guide_3"></li>
            <li class="text_referral_guide_4"></li>
            <li class="text_referral_guide_6"></li>
            <li class="text_referral_guide_7"></li>
            <li class="text_referral_guide_8"></li>
            <li class="text_referral_guide_9"></li>
            <li class="text_referral_guide_10"></li>
        </ul>
    </div>
    <div style="display:none;" class="top child_module">
        <ul>
        </ul>
    </div>
</div>
<script>
    page = 1;
    async function content_render(){
        $('.ref_link a').attr('href','https://cmangax3.com/ref/'+token_user);
        $('.ref_link a').html('https://cmangax3.com/ref/'+token_user);
        var character_data = await get_data_by_url('/api/get_data_by_id?table=game_character&data=other&id='+my_character);
        character_data.other = JSON.parse(character_data.other);
        var total_gold = getSafe(() => character_data.other.ref.gold, 0);
        var total_energy = getSafe(() => character_data.other.ref.energy, 0);
        var total_ref = getSafe(() => character_data.other.ref.total, 0);
        $('.total_gold a').html(number_format(total_gold));
        $('.total_energy a').html(number_format(total_energy));
        $('.total_ref a').html(number_format(total_ref));
    }
    setTimeout(() => {
        content_render();        
    }, 200);
    language_render(['text_intro','text_referral_note','text_referral_guide_1','text_referral_guide_2','text_referral_guide_3','text_referral_guide_4','text_referral_guide_5','text_referral_guide_6','text_referral_guide_7','text_referral_guide_8','text_referral_guide_9','text_referral_guide_10','menu_function_referral']);
</script>