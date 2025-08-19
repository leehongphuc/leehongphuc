<div load="no" onclick="private_chat_open(this);" id="private_chat_open">
    <i class="fas fa-comments"></i>
    <div class="unread">0</div>
</div>
<div id="room_chat" class="room_chat">
    <div class="chat_list">
        <h5>
            <p target='chat' class="active">Kênh chat</p>
            <p target='link'>Link hay</p>
            <div class="clear"></div>
        </h5>
        <div class="chat_module" id="chat_module_chat">
            <ul class="chat main">
                <li class="active" onclick="chat_load_channel(language_text('text_chat_channel_global'),'global','0')" id="chat_global_0">
                    <div class="avatar">
                        <img class="image" src="/assets/img/level/icon/chat_general.png">
                    </div>
                    <div class="detail">
                        <p class="name text_chat_channel_global"></p>
                        <p class="last_text"></p>
                    </div>
                    <div class="clear"></div>
                </li>
                <li onclick="chat_load_channel(language_text('text_chat_channel_market'),'global','1')" id="chat_global_1">
                    <div class="avatar">
                        <img class="image" src="/assets/img/level/icon/chat_market.png">
                    </div>
                    <div class="detail">
                        <p class="name text_chat_channel_market"></p>
                        <p class="last_text"></p>
                    </div>
                    <div class="clear"></div>
                </li>
                <li onclick="chat_load_channel(language_text('text_chat_channel_recruit'),'global','2')" id="chat_global_2">
                    <div class="avatar">
                        <img class="image" src="/assets/img/level/icon/chat_party.png">
                    </div>
                    <div class="detail">
                        <p class="name text_chat_channel_recruit"></p>
                        <p class="last_text"></p>
                    </div>
                    <div class="clear"></div>
                </li>
            </ul>
            <ul class="chat private">
            </ul>
        </div>
        <div style="display: none;" class="chat_module" id="chat_module_link">
            <ul class="link main">
                <li onclick="link_load_channel('hot','yes')" id="chat_link_hot">
                    <div class="avatar">
                        <img class="image" src="/assets/img/level/icon/link_hot.png">
                    </div>
                    <div class="detail">
                        <p class="name">Link hot</p>
                        <p class="last_text">Hot trong tuần</p>
                    </div>
                    <div class="clear"></div>
                </li>
                <li onclick="link_load_channel('new','yes')" id="chat_link_new">
                    <div class="avatar">
                        <img class="image" src="/assets/img/level/icon/link_new.png">
                    </div>
                    <div class="detail">
                        <p class="name">Link mới</p>
                        <p class="last_text">Mới đăng tải</p>
                    </div>
                    <div class="clear"></div>
                </li>
                <li onclick="popup_load('user/link_add')">
                    <div class="avatar">
                        <img class="image" src="/assets/img/level/icon/link_add.png">
                    </div>
                    <div class="detail">
                        <p class="name">Thêm link</p>
                        <p class="last_text">Chia sẻ link hot</p>
                    </div>
                    <div class="clear"></div>
                </li>
            </ul>
        </div>
    </div>
    <div class="chat_content">
        <h5>
            <p class="name text_chat_channel_global"></p>
            <div class="control">
                <p id="game_menu_open" onclick="popup_load('game/mini/menu');"><i class="fa-solid fa-game-console-handheld"></i></p>
                <p style="display: none;" id="private_blacklist" onclick="chat_block(this);"><i class="fa-solid fa-user-slash"></i></p>
                <p style="display: none;" id="chat_remove" onclick="chat_remove(this);"><i class="fas fa-trash"></i></p>
                <p class="pc_display" onclick="$('#room_chat .chat_list').css('height','600');$('#room_chat .chat_list .chat_module').css('height','559');$('#room_chat .chat_content .chat_text').css('height','533px');$('#room_chat .chat_content .chat_module_div.link .list').css('max-height','533px');$(this).hide();"><i class="fas fa-sort-size-up-alt"></i></p>
                <p style="display:none" id="private_volume" onclick="active_private_volume();"><i class="fas fa-volume-up"></i></p>
                <p class="active" id="private_chat_close" onclick="$('#room_chat').hide();$('#private_chat_open').show();$('#private_chat_open .unread').text(0);"><i class="fas fa-times"></i></p>
                <div class="clear"></div>
            </div>
        </h5>
        <div class="chat_module_div chat">
            <div style="display: none;" load="0" class="top_login">
                <div class="user_profile_div">
                    <div class="user_avatar">
                        <img class="user_image" src="assets/img/transparent.png">
                        <div class="user_frame"></div>
                    </div>
                </div>
                <div class="info">
                    <div class="badge_div name"></div>
                    <p class="text"></p>
                    <p class="text_online">Đã online</p>
                </div>
            </div>
            <div class="chat_text">
                <ul class="chat_div">
                </ul>
            </div>
            <div class="chat_loading">
                <img src="/assets/img/loading.png" />
            </div>
            <div class="chat_add">
                <input autocomplete="off" id="chat_add" class="emo_input" placeholder="" />
                <div onclick="send_chat_text()" class="send_chat_icon"><i class="center_div fas fa-paper-plane"></i></div>
                <div  class="send_chat_image"><i onclick="$('#chat_image').click();" class="fa-light fa-image-polaroid"></i></div>
                <div id="voice_chat_div" class="voice_div">
                    <div onclick="recording_open(this)" class="icon_touch">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <div class="recording">
                        <div onclick="$('.voice_div .recording').hide();" class="close"><i class="center_div fas fa-times"></i></div>
                        <div class="progress">
                            <a class="recording_action" onclick="recording_action();">
                                <div class="icon_status">
                                    <img style="display: block;" class="recording_start" src="/assets/img/icon/mic_start.png" />
                                    <img class="recording_stop" src="/assets/img/icon/mic_stop.png" />
                                    <img class="recording_play" src="/assets/img/icon/mic_play.png" />
                                    <img class="recording_reset" src="/assets/img/icon/mic_stop.png" />
                                </div>
                                <div class="status">
                                    <p>Thu âm</p>
                                </div>
                            </a>
                            <div style="display:none;" class="icon_other">
                                <div onclick="recording_reset()" style="background-color: #e74c3c;" class="item"><i class="center_div far fa-redo"></i></div>
                                <div onclick="recording_send()" class="item"><i class="center_div fas fa-paper-plane"></i></div>
                            </div>
                            <audio style="display: none;" id=recordedAudio></audio>
                        </div>
                    </div>
                </div>
                <div id="emoji_chat_div" class="emoji_div">

                </div>
            </div>
            <input class="hide" type="file" id="chat_image">
        </div>
        <div class="chat_module_div link hide">
            <div class="list">
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<script>
    $(document).ready(function() {
        $('#chat_image').on('change', function() {
            var file = $('#chat_image')[0].files[0];
            if (file) {
                // Kiểm tra định dạng của tệp (JPG, GIF, PNG)
                var fileType = file.type;
                var fileSize = file.size; // kích thước tệp tính bằng byte

                if (fileType === 'image/jpeg' || fileType === 'image/png' || fileType === 'image/gif') {
                    if (fileSize <= 5 * 1024 * 1024) { // kiểm tra kích thước tối đa 5MB
                        var reader = new FileReader();
                        reader.onloadend = function() {
                            chat_image_send(reader.result);
                        }
                        reader.readAsDataURL(file);
                    } else {
                        alert('Kích thước tệp quá lớn! Tối đa là 5MB.');
                    }
                } else {
                    alert('Chỉ hỗ trợ các định dạng JPG, GIF, PNG!');
                }
            }
        });
    });

    function chat_image_send(imgData) {
        if(token_user != 0){
            var premium_level = getSafe(() => my_profile.info.premium.level, 0);
            if(premium_level >= 1){
                $.post("../assets/ajax/user.php", {
                        action: "chat_image",
                        imgData: imgData,
                    })
                    .done(function(data) {
                        if($.trim(data) != ""){
                            current_text = $('#chat_add').val();
                            current_text = current_text + `<image>${data}</image>`;
                            $('#chat_add').val(current_text);
                            $('#chat_image').val('');
                        }else{
                            alertify.error('Lỗi không xác định');
                        }
                    });
            }else{
                alertify.error('Chức năng này chỉ dành cho thành viên VIP');
            }
        }
    }

    recording_data = {}
    recording_data.connect = "no";
    recording_data.time = 0;
    recording_data.time_play = 0;
    recording_data.target = 1;

    function recording_open(e) {
        $(e).parent().find('.recording').show();
        recording_connect();
    }

    function voice_play(e) {
        var audio = $(e).find('audio')[0];
        if (audio.paused) {
            audio.play();
            $(e).find('.icon_status img').attr('src', 'assets/img/icon/mic_stop.png');
            $(e).find('.status p').text('Đang phát');
        } else {
            audio.pause();
            audio.currentTime = 0;
            $(e).find('.icon_status img').attr('src', 'assets/img/icon/mic_play.png');
            $(e).find('.status p').text('Phát');
        }
        audio.addEventListener('ended', function() {
            $(e).find('.icon_status img').attr('src', 'assets/img/icon/mic_play.png');
            $(e).find('.status p').text('Phát');
        });
    }

    function recording_action() {
        if (recording_data.target == 1) {
            recording_data.time = 0;
            recording_data.target = 2;
            $('.voice_div .recording .icon_status img').hide();
            $('.voice_div .recording .icon_status .recording_stop').show();
            $('.voice_div .recording .status p').text(recording_data.time);
            $('.voice_div .recording .recording_action').attr('onclick', 'recording_data.target = 3;recording_action();');
            audioChunks = [];
            rec.start();
            setTimeout(function() {
                recording_action();
            }, 1000);
        } else if (recording_data.target == 2) {
            recording_data.time += 1;
            $('.voice_div .recording .status p').text(recording_data.time);
            setTimeout(function() {
                recording_action();
            }, 1000);
        } else if (recording_data.target == 3) {
            rec.stop();
            recording_data.target = 4;
            recording_action();
            $('.voice_div .recording .icon_other').show();
        } else if (recording_data.target == 4) {
            $('.voice_div .recording .icon_status img').hide();
            $('.voice_div .recording .icon_status .recording_play').show();
            $('.voice_div .recording .recording_action').attr('onclick', 'recording_action();');
            $('.voice_div .recording .status p').text(recording_data.time);
            setTimeout(function() {
                recording_data.target = 5;
            }, 1000);
        } else if (recording_data.target == 5) {
            recording_data.time_play = recording_data.time;
            recordedAudio.currentTime = 0;
            recordedAudio.play();
            $('.voice_div .recording .icon_status img').hide();
            $('.voice_div .recording .icon_status .recording_reset').show();
            $('.voice_div .recording .recording_action').attr('onclick', 'recording_data.target = 4;recording_action();');
            $('.voice_div .recording .status p').text(recording_data.time_play);
            recording_data.target = 6;
            setTimeout(function() {
                recording_action();
            }, 1000);
        } else if (recording_data.target == 6) {
            if (recording_data.time_play > 0) {
                recording_data.time_play -= 1;
                $('.voice_div .recording .status p').text(recording_data.time_play);
                setTimeout(function() {
                    recording_action();
                }, 1000);
            } else {
                recording_data.target = 4;
                recording_action();
            }
        }
    }

    function recording_connect() {
        if (recording_data.connect == "no") {
            navigator.mediaDevices.getUserMedia({
                    audio: true
                }).then(stream => {
                    handlerFunction(stream);
                    recording_data.connect = "yes";
                })
                .catch(error => {
                    console.error('Error accessing microphone:', error.message);
                    alert('Error accessing microphone. Please check your microphone settings and try again.');
                });
        }
    }

    function recording_reset() {
        recording_data.target = 0;
        $('.voice_div .recording .icon_other').hide();
        $('.voice_div .recording .recording_action').attr('onclick', 'recording_data.target = 1;recording_action();');
        $('.voice_div .recording .icon_status img').hide();
        $('.voice_div .recording .icon_status .recording_start').show();
        $('.voice_div .recording .status p').text('Thu âm');
    }

    function handlerFunction(stream) {
        rec = new MediaRecorder(stream);
        rec.ondataavailable = e => {
            audioChunks.push(e.data);
            if (rec.state == "inactive") {
                let blob = new Blob(audioChunks, {
                    type: 'audio/mpeg-3'
                });
                recordedAudio.src = URL.createObjectURL(blob);
                recordedAudio.controls = true;
                recording_data.blob = blob;
            }
        }
    }

    function recording_send() {
        recording_reset();
        $('.voice_div .recording').hide();
        const formData = new FormData();
        var file_name = token_user + '_' + server_time;
        formData.append('audioFile', recording_data.blob, file_name + '.mp3');
        formData.append('action', 'chat_voice');
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/assets/ajax/user.php');
        xhr.send(formData);

        xhr.onload = function() {
            if (xhr.status === 200) {
                popup_close();
                $("#private_chat_open").click();
                setTimeout(() => {
                    current_text = $('#chat_add').val();
                    current_text = current_text + `<voice>${file_name}</voice>`;
                    $('#chat_add').val(current_text);
                }, 300)
            } else {
                console.error('Error uploading audio file:', xhr.statusText);
            }
        };
    }
</script>
<script>
    channel = "global";
    channel_id = 0;
    reload_chat = 'yes';
    last_chat = 0;
    load_room_chat = 1;
    load_global_chat = 1;
    chat_link_filter = {
        "page": 1,
        "type": "hot",
        "loading": "no"
    };
    load_module('emoji_chat_div', 'other/emoji', 'no');
    async function popup_top_login(data) {
        var target_id = data.id;
        var target_data = await get_data_by_url('/api/user_info?user=' + target_id);
        target_data.info = json_convert(target_data.info);
        $('#room_chat .top_login .user_profile_div .user_avatar .user_frame').html(user_frame_render(target_data.info))
        $('#room_chat .top_login .user_profile_div .user_avatar .user_image').attr('src', 'assets/tmp/avatar/' + target_data.info.avatar);
        $('#room_chat .top_login .info .name').html(target_data.info.name + ' ' + user_badge_render(target_data.info));
        $('#room_chat .top_login .text').text(data.text);
        $('#room_chat .top_login').attr('onclick', `member_profile(${target_id})`);
        $('#room_chat .top_login').slideDown(500);
        setTimeout(function() {
            var load = parseInt($("#room_chat .top_login").attr('load'));
            $("#room_chat .top_login").attr('load', load - 1);
            $('#room_chat .top_login').slideUp(500);
        }, 10000);
    }

    function send_chat_text() {
        var text = $("#chat_add").val();
        var fitler_text = ['/ref/'];
        var text_block = 'no';
        $.each(fitler_text, function(id, value) {
            if ($.trim(text).search(value) != '-1') {
                text_block = value;
            }
        });
        if (text_block == 'no') {
            if ($.trim(text) != "" && token_user != 0 && (channel != "global" || containsURL(text) == false || containsURL(text) == true && $.trim(text).search('cmanga') != '-1')) {
                $("#chat_add").val('');
                $.post("../assets/ajax/user.php", {
                        action: "chat_add",
                        text: text,
                        channel: channel,
                        channel_id: channel_id
                    })
                    .done(function(data) {
                        $("#result").empty().html(data);
                        $('.chat_text').animate({
                            scrollTop: 99999
                        }, 0);
                    });
            } else if (containsURL(text) == true) {
                alertify.error(language_text('text_chat_no_link'));
            } else if (text == "") {
                alertify.error(language_text('text_chat_input_empty'));
            } else {
                alertify.error(language_text('text_chat_no_login'));
            }
        } else {
            alertify.error(language_text('text_chat_text_block', text_block));
        }
    }

    function add_chat_text(data) {
        $(".chat_div").append(data);
        if (reload_chat == 'yes') {
            $('.chat_text').animate({
                scrollTop: 99999
            }, 500);
        }
    }
    $(document).ready(function() {
        $("#chat_add").keypress(function(e) {
            if (e.which == 13) {
                send_chat_text();
            }
        });
        var lastScrollTop = 0;
        $('.chat_text').on('scroll', function() {
            var st = $(this).scrollTop();
            if (st == 0) {
                if (load_room_chat == 2) {
                    load_room_chat = 3;
                    reload_chat = 'no';
                    $('.reload_chat').show();
                    load_chat(last_chat);
                }
            } else if (st > 0) {
                if (st > lastScrollTop) {} else {
                    $('.reload_chat').show();
                    reload_chat = 'no';
                }
            } else {
                $('.reload_chat').show();
                reload_chat = 'no';
            }
            lastScrollTop = st;
        });
        $('.chat_text').on('scroll', box_scroll);
    });

    function box_scroll(e) {
        var elem = $(e.currentTarget);
        if (elem[0].scrollHeight - elem.scrollTop() == elem.outerHeight()) {
            reload_chat = 'yes';
        }
    }

    function last_channel_chat() {
        $.get("api/chat_last", {}).done(function(data) {
            $.each(data, function(index, value) {
                var text = value.text.replace(/<[^>]*>/g, '');
                if (text.length > 12) {
                    text = substr_utf8_bytes(text, 0, 12) + '...';
                }
                var get_html = `${text} <span>· ${chat_date_convert(value.date)}</span>`;
                $('#chat_global_' + value.channel_id + ' .last_text').html(get_html);
            });
        });
    }

    function load_chat_list() {
        if (token_user != 0) {
            $.get("api/chat_list", {}).done(function(data) {
                var get_html = '';
                $.each(data, function(index, value) {
                    var user_info = JSON.parse(value.info);
                    var avatar = "/assets/tmp/avatar/" + user_info.avatar;

                    var text = value.text.replace(/<[^>]*>/g, '');;
                    var class_add = '';
                    if (text.length > 12) {
                        text = substr_utf8_bytes(text, 0, 12) + '...';
                    }
                    var name = user_info.name;
                    if (name.length > 12) {
                        name = substr_utf8_bytes(name, 0, 12) + '...';
                    }
                    if (server_time - time_convert(value.last_online) <= 180) {
                        var online = 'on';
                    } else {
                        var online = 'off';
                    }

                    if (value.unread == 0) {
                        class_add = class_add + ' unread ';
                    }
                    get_html = get_html + `
                        <li class="${class_add}" onclick="chat_load_channel('${user_info.name}','user',${value.id_user})" id="chat_user_${value.id_user}">
                            <div class="avatar">
                                <div style='height:40px' class="user_profile_div">
                                    <div class="user_avatar">
                                        <img class="user_image" src="${avatar}" />
                                        <div class="user_frame">${user_frame_render(user_info)}</div>
                                        <div class='online ${online}'></div>
                                    </div>
                                </div>
                            </div>
                            <div class="detail">
                                <p class="name">${name}</p>
                                <p class="last_text">${text} <span>· ${chat_date_convert(value.date)}</span></p>
                            </div>
                            <div class='clear'></div>
                        </li>
                    `;
                });
                $('#room_chat .chat_list ul.chat.private').html(get_html);
            });
        }
    }

    function load_chat_list_online() {
        if (token_user != 0) {
            load_chat_list_online_time = server_time;
            $.get("api/chat_list_online", {}).done(function(data) {
                cv_li = "";
                $.each(data, function(id, value) {
                    if (server_time - time_convert(value.last_online) <= 180) {
                        $(`#chat_user_${value.id_user} .online`).addClass('on');
                    } else {
                        $(`#chat_user_${value.id_user} .online`).removeClass('on');
                    }
                });
            });
        }
    }

    function chat_content_render(data) {
        var user_info = JSON.parse(data.info);
        var avatar = "/assets/tmp/avatar/" + user_info.avatar;
        var time_to_date = time_format(data.date);
        var text = detect_emoji(detect_url(data.text));
        var onclick = `member_profile('${data.id_user}')`;
        if (data.id_user == token_user) {
            class_add = "me";
        } else {
            class_add = "";
        }
        if (channel != 'user') {
            var name = user_info.name.replace(/"/g, '');
            var character_level = getSafe(() => user_info.level, 0);
            var premium_level = getSafe(() => user_info.premium.level, 0);
            var premium_effect = getSafe(() => user_info.premium.effect, 1);

            var other_name = "";
            if (user_info.verify) {
                other_name += ` <i class="fas fa-check-circle"></i>`;
            }
            if (character_level >= 10) {
                var main_level = Math.floor(character_level / 10);
                //other_name += ` <img class="rank_icon" src="/assets/img/level/icon/level/mini/${main_level}.png">`;
            }
            name_html = `<span class='name badge_div'><text class="premium_name_${premium_level} premium_name_${premium_level}_${premium_effect}" onclick="${onclick}">${name}</text> ${user_badge_render(user_info)} ${other_name}: </span>`;

        } else {
            name_html = "";
        }
        var cv_li = `
            <li style='margin-top:10px;' class="${class_add}">
                <a onclick="${onclick}">
                    <div class="avatar">
                        <div style="height:34px;" class="user_profile_div">
                            <div class="user_avatar">
                                <img class="user_image" src="${avatar}" />
                                <div class="user_frame">${user_frame_render(user_info)}</div>
                            </div>
                        </div>
                    </div>
                </a>
                <div class='detail'>
                    <div class="text">${name_html}${text} <p class="time">${chat_date_convert(data.date)}</p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </li>
        `;
        return cv_li;
    }

    function load_chat_id(chat_id) {
        $.get("api/chat_id?id=" + chat_id, {}).done(function(data) {
            var cv_li = chat_content_render(data);
            var text = data.text.replace(/<[^>]*>/g, '');;
            if (text.length > 12) {
                text = substr_utf8_bytes(text, 0, 12) + '...';
            }
            var get_date = new Date(server_time * 1000);
            if (data.channel == "user") {
                channel_id_check = data.id_user;
            } else {
                channel_id_check = data.channel_id;
            }
            $('#chat_' + data.channel + '_' + channel_id_check + ' .last_text').html(`${text} <span>· ${chat_date_convert(server_time*1000,'no')}</span>`);
            if (channel != data.channel || data.channel == 'user' && channel_id != channel_id_check) {
                if (data.channel == 'user') {
                    if ($('#chat_' + data.channel + '_' + channel_id_check).length != 0) {
                        $('#chat_' + data.channel + '_' + channel_id_check).prependTo($('#chat_' + data.channel + '_' + channel_id_check).parent());
                    } else {
                        chat_create(channel_id_check, 'no', data.text, 'unread');
                    }
                }
                $('#chat_' + data.channel + '_' + channel_id_check).addClass('unread');
            } else if (channel == data.channel && data.id_user != token_user && channel_id == channel_id_check) {
                add_chat_text(cv_li);
            }
        });
    }

    function load_chat(last = 999999999) {
        var get_token = getSafe(() => token_chat[channel], "");
        $.get("api/chat?last=" + last + '&channel=' + channel + '&channel_id=' + channel_id + '&token_chat=' + get_token, {}).done(function(data) {
            get_data = "";
            $.each(data, function(index, data) {
                var cv_li = chat_content_render(data);
                get_data = cv_li + get_data;
                last_chat = data.id_chat;
            });
            current_height = $('.chat_div').height();
            $('.chat_div').prepend(get_data);
            $('#room_chat .chat_content .chat_loading').hide();
            if (load_room_chat <= 2 || load_room_chat == 4) {
                $('.chat_text').animate({
                    scrollTop: 99999
                }, 0);
            } else if (load_room_chat == 3) {
                var top = $('.chat_div').height() - current_height;
                $('.chat_text').animate({
                    scrollTop: top
                }, 0);
            }
            if (load_room_chat != 1) {
                load_room_chat = 2;
            }
        });
    }

    function chat_date_convert(date, convert = 'yes') {
        var my_date = date;
        if (convert == 'yes') {
            my_date = my_date.replace(/-/g, "/");
        }
        var day_now = new Date(my_date);
        var hours = day_now.getHours();
        if (hours < 10) {
            hours = '0' + hours;
        }
        var minutes = day_now.getMinutes();
        if (minutes < 10) {
            minutes = '0' + minutes;
        }
        return hours + ':' + minutes;
    }

    async function chat_create(user_id, click = 'no', text = '', active = '') {
        if (token_user != 0 && user_id != token_user) {
            $('#chat_user_' + user_id).remove();
            var user_info = await get_data_by_url('/api/user_data?data=info&user=' + user_id);
            var user_online = await get_data_by_url('/api/user_data?type=text&data=last_online&user=' + user_id);
            if (Object.keys(user_info).length != 0) {
                var avatar = user_info.avatar;
                var name = user_info.name;
                if (name.length > 12) {
                    name = substr_utf8_bytes(name, 0, 12) + '...';
                }
                if (text.length > 12) {
                    text = substr_utf8_bytes(text.replace(/<[^>]*>/g, ''), 0, 12) + '...';
                }

                if (server_time - time_convert(user_online[0]) <= 180) {
                    var online = 'on';
                } else {
                    var online = 'off';
                }
                get_html = `
                    <li onclick="chat_load_channel('${name}','user','${user_id}')" id="chat_user_${user_id}" class='${active}'>
                        <div class="avatar">
                            <div style='height:40px' class="user_profile_div">
                                <div class="user_avatar">
                                    <img class="user_image" src="/assets/tmp/avatar/${avatar}" />
                                    <div class="user_frame">${user_frame_render(user_info)}</div>
                                    <div class='online ${online}'></div>
                                </div>
                            </div>
                        </div>
                        <div class="detail">
                            <p class="name">${name}</p>
                            <p class="last_text">${text} <span>· ${chat_date_convert(server_time*1000,'no')}</span></p>
                        </div>
                        <div class='clear'></div>
                    </li>
                `;
                $('#room_chat .chat_list ul.chat.private').prepend(get_html);
                if (click == 'yes') {
                    setTimeout(function() {
                        $('#chat_user_' + user_id).click();
                    }, 300);
                }
            }
        } else {
            alertify.error(language_text('text_need_login'));
        }
    }

    function chat_read(target) {
        $.post("../assets/ajax/user.php", {
                action: "chat_read",
                target: target
            })
            .done(function(data) {
                $("#result").empty().html(data);
            });
    }

    function chat_block(e){
        alertify.confirm("Bạn có chắc chắn muốn block người này?", function(){ 
            $(e).prop('disabled', true);
            $.post("/assets/ajax/user.php", { action : 'chat_block' , target: channel_id})
            .done(function(data) {
                $(e).prop('disabled', false);
                $('#chat_remove').click();
            });
         }).set('labels', {ok:language_text('text_yes'), cancel:language_text('text_no')});
    }

    function chat_remove(e) {
        target = $(e).attr('target');
        $.post("/assets/ajax/user.php", {
                action: "chat_remove",
                target: channel_id
            })
            .done(function(data) {
                alertify.success(language_text('text_delete_success'));
                load_chat_list();
                chat_load_channel(language_text('text_chat_channel_global'), 'global', '0');
                $('#result').empty().append(data);
            });
    }

    function chat_load_channel(name, target_channel, target_channel_id) {
        channel = target_channel;
        channel_id = target_channel_id;
        onclick_html = "";
        $('#room_chat .chat_content .chat_add input').show();
        $('#room_chat .chat_content .chat_loading').show();
        $('#room_chat .chat_list ul.chat li').removeClass('active');
        $('#chat_' + channel + '_' + channel_id).removeClass('unread');
        $('#chat_' + channel + '_' + channel_id).addClass('active');
        if (channel == 'user') {
            onclick_html = 'member_profile(channel_id)';
            if ($("#private_blacklist_" + channel_id).length == 0) {
                $("#private_blacklist").show();
            } else {
                $("#private_blacklist").hide();
                $("#room_chat .chat_content .chat_add input").hide();
            }
            $('#chat_remove').show();
        } else {
            $("#private_blacklist").hide();
            $("#chat_remove").hide();
        }
        $("#room_chat .chat_content h5 .name").html(`<a class="no_hover" onclick="${onclick_html}">${name}</a>`);
        $('#room_chat .chat_text ul').html('');
        chat_read(channel_id);
        load_room_chat = 4;
        load_chat();
    }

    function chat_start(user) {
        $('#private_chat_open').click();
        popup_close();
        setTimeout(function() {
            chat_create(user, 'yes');
        }, 500);
    }
    async function chat_render() {
        var user_data = await get_data_by_url('/api/user_data?data=data&user=' + token_user);
        if (user_data.chat) {
            $('#private_chat_open .unread').text(user_data.chat);
        }
    }

    function private_chat_open(e) {
        $(e).hide();
        $('#room_chat').show();
        if (token_user != 0) {
            load_chat_list_online();
            $.post("/assets/ajax/user.php", {
                action: 'update_data',
                table: 'data',
                data: 'chat',
                value: 0
            });
        }
        if ($('#private_chat_open').attr('load') == 'no') {
            FireBaseApp.child("Chat/global").on('child_changed', function(snap) {
                if (load_global_chat != 1) {
                    var data = snap.val();
                    if (snap.key == 100) {
                        var load = parseInt($("#room_chat .top_login").attr('load'));
                        $("#room_chat .top_login").attr('load', load + 1);
                        setTimeout(function() {
                            popup_top_login(data);
                        }, 500 + (load * 11000));
                    } else {
                        load_chat_id(data.id);
                    }
                }
                load_global_chat = 2;
            });
            load_chat_list();
            load_chat();
            $('#private_chat_open').attr('load', 'yes');
        }
        $('#room_chat .chat_content .chat_text').animate({
            scrollTop: 99999
        }, 500);
    }
    if (token_user != 0) {
        FireBaseApp.child("Chat/user/" + token_chat.user).on('value', function(snap) {
            if (load_room_chat != 1) {
                var data = snap.val();
                load_chat_id(data.id);
                if ($("#room_chat").css("display") == "none") {
                    var num_unread = parseInt($('#private_chat_open .unread').text());
                    $('#private_chat_open .unread').text(num_unread + 1);
                    sound_chat.play();
                }
            }
            load_room_chat = 2;
        });
        chat_render();
        character_channel_chat();
    }

    async function character_channel_chat() {
        if (my_character != 0) {
            my_character_data = await get_data_by_url('/api/get_data_by_id?table=game_character&data=info,other&id=' + my_character);
            my_character_data.info = JSON.parse(my_character_data.info);
            if (my_character_data.info.party) {
                var html = `
                    <li onclick="chat_load_channel('${my_character_data.info.party.name}','party','${my_character_data.info.party.id}')" id="chat_party_${my_character_data.info.party.id}">
                        <div class="avatar">
                            <img class="image" src="/assets/tmp/game/party/${my_character_data.info.party.avatar}">
                        </div>
                        <div class="detail">
                            <p class="name">${my_character_data.info.party.name}</p>
                            <p class="last_text"></p>
                        </div>
                        <div class="clear"></div>
                    </li>
                `;
                $('#room_chat .chat_list ul.chat.main').prepend(html);
                load_room_chat = 1;
                FireBaseApp.child("Chat/party/" + my_character_data.info.party.id).on('value', function(snap) {
                    if (load_room_chat != 1) {
                        var data = snap.val();
                        load_chat_id(data.id);
                    }
                    load_room_chat = 2;
                });
            }
            if (my_character_data.info.guild) {
                var html = `
                    <li onclick="chat_load_channel('${my_character_data.info.guild.name}','guild','${my_character_data.info.guild.id}')" id="chat_guild_${my_character_data.info.guild.id}">
                        <div class="avatar">
                            <img class="image" src="/assets/tmp/game/guild/${my_character_data.info.guild.avatar}">
                        </div>
                        <div class="detail">
                            <p class="name">${my_character_data.info.guild.name}</p>
                            <p class="last_text"></p>
                        </div>
                        <div class="clear"></div>
                    </li>
                `;
                $('#room_chat .chat_list ul.chat.main').prepend(html);
                load_room_chat = 1;
                FireBaseApp.child("Chat/guild/" + token_chat.guild).on('value', function(snap) {
                    if (load_room_chat != 1) {
                        var data = snap.val();
                        load_chat_id(data.id);
                    }
                    load_room_chat = 2;
                });
            }
        }
    }

    async function link_load_channel(type, reset = "no") {
        if (chat_link_filter.loading == "no") {
            chat_link_filter.loading = "yes";
            $('#room_chat .chat_list li').removeClass('active');
            $('#chat_link_' + type).addClass('active');
            $("#room_chat .chat_content h5 .name").html(language_text('text_chat_link_' + type));
            chat_link_filter.type = type;
            if (reset == "yes") {
                chat_link_filter.page = 1;
                $('#room_chat .chat_module_div.link .list').html('');
            }
            var get_html = '';
            if (chat_link_filter.page != 0) {
                var chat_link_data = await get_data_by_url(`/api/chat_link?type=${chat_link_filter.type}&limit=10&page=${chat_link_filter.page}`);
                if (Object.keys(chat_link_data).length != 0) {
                    $.each(chat_link_data, function(id, value) {
                        var user_info = json_convert(value.info);
                        var chat_data = json_convert(value.data);
                        if (local_data['link_vote'][value.chat_id]) {
                            var up_vote = "disable";
                        } else {
                            var up_vote = "";
                        }
                        get_html += `
                            <div id="chat_link_${value.chat_id}" class="item">
                                <div class="content">
                                    <div class="title">
                                        <div onclick="member_profile(${value.id_user})" class="user_profile_div">
                                            <div class="user_avatar">
                                                <img class="user_image" src="/assets/tmp/avatar/${user_info.avatar}">
                                                <div class="user_frame">${user_frame_render(user_info)}</div>
                                            </div>
                                        </div>
                                        <div class="div_title"><p>${chat_data.title}</p></div>
                                        <p class="time">${time_format(value.date)}</p>
                                    </div>
                                    <div class="div_link"><p class="link"><a href="${chat_data.url}" target="_blank">${chat_data.url}</a></p></div>
                                </div>
                                <div chat_id = "${value.chat_id}" onclick="link_vote(this)" class="up_vote ${up_vote}">
                                    <p class="title"><i class="fa-solid fa-caret-up"></i></p>
                                    <p class="num">${chat_data.vote}</p>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    get_html = `<div style="justify-content: center;line-height: 30px;" class="item">Đã tải hết dữ liệu</div>`;
                    chat_link_filter.page = 0;
                }
            }
            chat_link_filter.loading = "no";
            $('#room_chat .chat_module_div.link .list').append(get_html);
        }
    }

    function link_vote(e) {
        if (token_user != 0) {
            var chat_id = $(e).attr('chat_id');
            if (!local_data['link_vote'][chat_id]) {
                var current_num = parseInt($(e).find('.num').text());
                $(e).find('.num').text(current_num + 1);
                $(e).addClass('disable');
                link_vote_update(chat_id);
                $.post("/assets/ajax/user.php", {
                    action: 'chat_link_vote',
                    chat_id: chat_id
                }).done(function(data) {

                });
            }
        } else {
            alertify.error(language_text('text_need_login'));
        }
    }

    $('.chat_list h5 p').click(function() {
        var target = $(this).attr('target');
        $('.chat_list h5 p').removeClass('active');
        $(this).addClass('active');
        $('.chat_module').hide();
        $('.chat_module_div').hide();
        $('#chat_module_' + target).show();
        $('.chat_module_div.' + target).show();
        if (target == 'link') {
            $('#chat_link_new').click();
        } else if (target == 'chat') {
            $('#chat_global_0').click();
        }
    });

    $(document).ready(function() {
        $('#room_chat .chat_module_div.link .list').on('scroll', function() {
            var scrollHeight = $(this)[0].scrollHeight;
            var elementHeight = $(this).height();
            var scrollTop = $(this).scrollTop();
            if (scrollTop + elementHeight >= scrollHeight - 20 && chat_link_filter.page != 0) {
                chat_link_filter.page += 1;
                link_load_channel(chat_link_filter.type);
            }
        });
    });
    $(document).on('keyup', function(e) {
        if (e.which == 27) {
            $('#room_chat').hide();
            $('#private_chat_open').show();
            $('#private_chat_open .unread').text(0);
        }
    });
    language_render(['text_chat_channel', 'text_chat_channel_global', 'text_chat_channel_market', 'text_chat_channel_recruit', 'text_chat_block']);
    $('#chat_add').attr('placeholder', language_text('text_chat_input'));
    local_data_check('link_vote');
    setTimeout(() => {
        last_channel_chat();
    }, 1000);
</script>