<div class="user_game_module guild_create">
    <div class="back_dashboard"><i onclick="load_module('content','game/function/guild')" class="fas fa-chevron-square-left"></i></div>
    <div class="input_screen">
        <form id="form_send" method="post" enctype="multipart/form-data">
            <ul class="form">
                <li style="text-align:center;">
                    <img style="width: 100px;height: 100px;border-radius: 150px;margin-bottom: 10px;" id="guild_avatar_show" src="/assets/tmp/game/guild/default.png" />
                    <p style="color:white;"><a onclick="$('#guild_avatar').click();"><text class="text_profile_avatar_change"></text> <i class="far fa-pencil"></i></a></p>
                    <input onchange="render_preview_image(this, '#guild_avatar_show');"  id="guild_avatar" hidden="" name="guild_avatar" type="file" />
                    <input hidden="" name="action" value="guild_create"/>
                </li>                
                <li>
                    <p class="label text_guild_name"></p>
                    <input maxlength="25" id="guild_name" name="guild_name" />
                </li>
                <li>
                    <p class="label text_guild_tag"></p>
                    <input style="text-transform: uppercase;" maxlength="3" id="guild_tag" name="guild_tag" />
                </li>
                <li>
                    <p class="label text_guild_intro"></p>
                    <textarea maxlength="250" id="guild_detail" name="guild_detail"></textarea>
                </li>
                <li>
                    <p class="label text_guild_fee"></p>
                    <p><span>1,000</span> <img class="item_icon" src="/assets/img/level/currency/crystal.png"/></p>
                </li>
            </ul>
        </form>
        <div class="button">
            <button class="button_yes text_button_create" onclick="guild_create();"></button>
        </div>
    </div>
</div>
<script>
    $("#form_send").on('submit', (function(e) {
        e.preventDefault();
        $("#result").empty();
        $.ajax({
            url: "../assets/ajax/character_guild.php", // Url to which the request is send
            type: "POST", // Type of request to be send, called as method
            data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false, // To send DOMDocument or non processed data file it is set to false
            success: function(data) // A function to be called if request succeeds
            {
                $("#result").html(data);
                $('.button').show();
            }
        });
    }));

    function guild_create() {
        var name = $("#guild_name").val();
        var tag = $("#guild_tag").val();
        if ($.trim(name) == '') {
            alertify.error(language_text('text_guild_not_enter_name'));
        } else if (name.length <= 3 || name.length >= 25) {
            alertify.error(language_text('text_guild_name_not_valid'));
        } else if (tag.length != 3) {
            alertify.error(language_text('text_guild_tag_not_valid'));
        } else {
            $('.button').hide();
            $("#form_send").submit();
        }
    }
    language_render(["text_button_create","text_guild_name","text_guild_tag","text_guild_intro","text_guild_fee"]);
</script>