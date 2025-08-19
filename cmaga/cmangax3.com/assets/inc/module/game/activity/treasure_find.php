<div class="user_game_module activity_position">
    <div class="back_dashboard"><i onclick="load_module('content','game/dashboard')" class="fas fa-chevron-square-left"></i></div>
    <div class="button_right button_style">
        <button class="button_yes" onclick="popup_data= {'type':'treasure_find_top','limit':100};popup_load('game/score')"><i class="fas fa-trophy"></i> <span>Xếp hạng</span></button>
    </div>
    <div class="avatar">
        <img class="image" src="/assets/img/level/menu/treasure_find.png" />
        <p class="name"></p>
        <p class="note"></p>
    </div>
    <div class="menu">
        <div target="guide" class="item active text_intro"></div>
        <div target="fight" class="item">Chiến đấu</div>
    </div>
    <div class="guide child_module">
        <ul>
            <li>Mỗi ngày, bạn có thể tham gia tối đa 3 lần.</li>
            <li>Trong lúc tầm bảo, bạn sẽ có 25% tỷ lệ gặp phải người chơi khác. 60 phút đầu tỷ lệ là 0%.</li>
            <li>Máu của người chơi sẽ được bảo lưu</li>
            <li>Cứ qua mỗi 60 phút tầm bảo, bạn sẽ bị giảm 20% tất cả chỉ số.</li>
            <li>Mỗi 1 phút khi đang tầm bảo bạn sẽ nhận được phần thưởng ngẫu nhiên: 150 <img class="item_icon" src="/assets/img/level/currency/gold.png">
                , 0.5 <img class="item_icon" src="/assets/img/level/currency/pet_exp.png">
                , 0.5 <img class="item_icon" src="/assets/img/level/currency/equipment_upgrade.png">
                , 0.5 <img class="item_icon" src="/assets/img/level/currency/friend_exp.png">
                , 0.2 <img class="item_icon" src="/assets/img/level/currency/pet_soul.png">
                , 1 <img class="item_icon" src="/assets/img/level/currency/treasure_coin.png">
            </li>
            <li>Cứ qua mỗi 60 phút tầm bảo, bạn sẽ được tăng số lượng <span>Xu Tầm Bảo</span> nhận mỗi phút thêm 0.5.</li>
        </ul>
    </div>
    <div class="fight child_module hide">
        <div class="battle">
            <p class="floor">Đội hình tầm bảo</p>
            <div class="formation player">
            </div>
            <div class="button_style">
                <button target="start" onclick="treasure_find_action(this)" class="button_yes start">Bắt đầu</button>
                <button target="reward" onclick="treasure_find_action(this)" class="button_yes hide reward">Nhận thưởng</button>
                <span class="time_life" revert="yes"></span>
            </div>
        </div>
        <div class="title history">
            <p>Phần thưởng tích lũy</p>
            <div class="list_item"></div>
        </div>
        <div class="title history">
            <p>Lịch sử va chạm</p>
            <div class="war_list">

            </div>
        </div>
    </div>
</div>
<script>
    activity_position_data = {"activity":"treasure_find"};
    async function content_render(){
        $('.user_game_module .avatar .name').html(json_data.language[`menu_activity_${activity_position_data.activity}`]);
        $('.user_game_module .avatar .note').html(json_data.language[`menu_activity_${activity_position_data.activity}_note`]);
        player_score = await activity_position_render(activity_position_data.activity);
        if(player_score.data.status){
            $('.user_game_module .fight .button_style .start').addClass('hide');
            if(player_score.data.status == 'death'){
                $('.user_game_module .fight .button_style .reward').removeClass('hide');
            }else{
                $('.user_game_module .fight .button_style .reward').addClass('hide');
            }
            var item_html = "";
            $.each(player_score.data.reward, function(key, value) {
                item_html += item_render(key,'currency',value);
            });
            $('.user_game_module .fight .history .list_item').html(item_html);

            var html = "";
            $.each(player_score.data.war_history, function(index, war_data) {
                html = `
                    <div class="item">
                        <p><a onclick="battle_id = '${war_data.war}';frame_load('battle');">${war_data.name}</a></p>
                        <p class="time">${time_format(war_data.time,'no','yes')}</p>
                    </div>
                `+html;
            });
            $('.user_game_module .fight .history .war_list').html(html);

            $('.user_game_module .fight .button_style .time_life').html(player_score.data.times+" phút");
        }else{
            $('.user_game_module .fight .button_style .time_life').html("");
            $('.user_game_module .fight .button_style .start').removeClass('hide');
            $('.user_game_module .fight .button_style .reward').addClass('hide');
        }
    }

    function treasure_find_action(e){
        var target = $(e).attr('target');
        $(e).prop('disabled',true);
        $.post('assets/ajax/character_activity.php',{action:"treasure_find_"+target},function(data){
            $('#result').html(data);
            $(e).prop('disabled',false);
        });
    }


    $(".user_game_module .menu .item").click(function() {
        $(".user_game_module .menu .item").removeClass('active');
        $(this).addClass('active');
        $(".user_game_module .child_module").hide();
        $(".user_game_module .child_module."+$(this).attr('target')).show();
    });

    content_render();
    language_render(["text_intro"]);
</script>