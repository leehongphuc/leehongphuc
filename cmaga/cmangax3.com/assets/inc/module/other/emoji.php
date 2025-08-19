<script>
    async function emoji_render(e){
        var emoji_html = '';
        if(token_user != 0){
            profile_other = await get_data_by_url('/api/user_data?data=other&user=' + token_user);
            if(profile_other.emoji){
                emoji_html = emoji_html + `<div class="item" target="history"><img src="/assets/tmp/avatar/${my_profile.info .avatar}" /></div>`;
            }
        }
        $.each(server_data.emoji.list, function(index, value) {
            emoji_html = emoji_html + `<div class="item" target="${index}"><img src="/assets/img/emoji/${value.avatar}" /></div>`;
        });
        $(e).parent().find('.emoji_content .menu').html(emoji_html);
        $(e).parent().find('.emoji_content .menu .item').on("click", function() {
            $(e).parent().find('.emoji_content .menu .item').removeClass("active");
            var target = $(this).attr('target');
            emoji_select(target);
            $(this).addClass('active');
        });
        $(e).parent().find('.emoji_content .menu .item:first').click();
        $(e).parent().find('.emoji_content').show();
    }
    function emoji_select(target){
        var emoji_html = '';
        if(target == 'history'){
            $.each(profile_other.emoji, function(index, value) {
                var emo_sign = value.split('_')[0];
                var emo_num = value.split('_')[1];
                var emo_image = getSafe(() => server_data.emoji.data[emo_sign][emo_num], 0) ;
                if(emo_image != 0){
                    emoji_html = `<div class="item" onclick="emoji_send(this)" target="<emo>${value}</emo>"><img src="/assets/img/emoji/data/${emo_sign}/${emo_image}" /></div>` + emoji_html;
                }
            });
        }else{
            var sign = server_data.emoji.list[target].sign;
            $.each(server_data.emoji.data[sign], function(index, value) {
                emoji_html = emoji_html + `<div class="item" onclick="emoji_send(this)" target="<emo>${sign}_${index}</emo>"><img src="/assets/img/emoji/data/${sign}/${value}" /></div>`;
            });
        }
        $('.emoji_div .emoji_content .list').html(emoji_html);
    }
    function emoji_send(e){
        var target = $(e).attr('target');
        var text = $(e).parent().parent().parent().parent().find('.emo_input').val();
        text = text+" "+target;
        $(e).parent().parent().parent().parent().find('.emo_input').val(text);
        $('.emoji_div .emoji_content').hide();
    }
</script>
<div onclick="emoji_render(this)" class="icon_touch"><i class="far fa-smile"></i></div>
<div class="emoji_content">
    <div class="close" onclick="$('.emoji_content').hide();"><i class="center_div fas fa-times"></i></div>
    <div class="menu">
    </div>
    <div class="list">
    </div>
</div>