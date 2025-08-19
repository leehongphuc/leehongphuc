function localStorageGetItem(key) {
    const itemStr = localStorage.getItem(key);
    if (!itemStr) {
        return null;
    }

    const item = JSON.parse(itemStr);
    const now = new Date();

    if (now.getTime() > item.expiry) {
        localStorage.removeItem(key);
        return null;
    }
    return item.value;
}

function localStorageSetItem(key, value, ttl = 10) {
    const now = new Date();
    const ttlMilliseconds = ttl * 60 * 1000;
    const item = {
        value: value,
        expiry: now.getTime() + ttlMilliseconds,
    };
    localStorage.setItem(key, JSON.stringify(item));
}


async function local_data_check(type) {
    var data_array = {};
    if (token_user != 0) {
        if (localStorageGetItem(type)) {
            var data_array = JSON.parse(localStorageGetItem(type));
        } else {
            for (var i = 0; i < 2; i++) {
                var data_list = await get_data_by_url(`/api/user_local_data?type=${type}&user=${token_user}&token=${token_security}`);
                if (typeof data_list === "object" && data_list !== null && Object.keys(data_list).length !== 0) {
                    data_array = {};
                    if (type == "album_history") {
                        $.each(data_list, function(index, value) {
                            data_array[value.album] = {};
                            data_array[value.album].num = value.num;
                            data_array[value.album].id = value.id;
                        });
                    } else if (type == "link_vote") {
                        $.each(data_list, function(index, value) {
                            data_array[value.chat_id] = value.date;
                        });
                    }
                    break;
                }
            }
        }
        localStorageSetItem(type, JSON.stringify(data_array));
    }
    local_data[type] = data_array;
}

async function album_history_update(album, chapter, num) {
    if (localStorageGetItem('album_history')) {
        var data_array = JSON.parse(localStorageGetItem('album_history'));
        data_array[album] = {};
        data_array[album].num = num;
        data_array[album].id = chapter;
        localStorageSetItem('album_history', JSON.stringify(data_array));
        local_data.album_history = data_array;
    }
}

async function link_vote_update(chat_id) {
    if (localStorageGetItem('link_vote')) {
        var data_array = JSON.parse(localStorageGetItem('link_vote'));
        data_array[chat_id] = server_time;
        localStorageSetItem('link_vote', JSON.stringify(data_array));
        local_data.link_vote = data_array;
    }
}

async function user_profile_data() {
    if (token_user != 0) {
        my_profile = await get_data_by_url('/api/user_info?user=' + token_user);
        my_profile.info = json_convert(my_profile.info);
        my_profile.fame = json_convert(my_profile.fame);
        my_profile.other = json_convert(my_profile.other);
    }
}

async function profile_render() {
    if (token_user != 0) {
        await user_profile_data();
        await local_data_check('album_history');
        my_character = my_profile.info.character ? ? 0;
        $('header .right .profile .user_profile_div .user_avatar .user_frame').html(user_frame_render(my_profile.info))
        $('header .right .profile .user_profile_div .user_avatar .user_image').attr('src', 'assets/tmp/avatar/' + my_profile.info.avatar);
        $('header .right .profile .name').text(my_profile.info.name);
        $('body header .right .profile ul.setting li.profile_login').hide();
        $('header .right .notification_div').show();
        $('header .right .user_currency').show();
        var premium_level = getSafe(() => my_profile.info.premium.level, 0);
        $('header .right .profile .premium_level .current img').attr('src', `/assets/img/user/premium/${premium_level}.png`);
        $('header .right .profile .premium_level .current span').text(premium_level);
        var premium_game = getSafe(() => my_profile.info.game_premium.level, 0);
        if (premium_game > 0) {
            $('header .right .profile .premium_game .current img').attr('src', `/assets/img/level/premium/${premium_game}.png`);
            $('header .right .profile .premium_game .current .title').text(language_text('user_game_premium_' + premium_game));
        }
        var fame_level = getSafe(() => my_profile.fame.level, 0);
        var fame_point = getSafe(() => my_profile.fame.point.total, 0);
        $('header .right .profile .fame_level .current img').attr('src', '/assets/img/fame/badge/point_' + fame_level + '.png');
        $('header .right .profile .fame_level .current span').text(json_data.language['fame_badge_point_' + fame_level]);
        $('header .right .profile .fame_level .fp_value').text(number_format(fame_point));
        if (my_profile.info.team) {
            $('header .right .profile .setting .team_menu_redirect').show();
        }
        load_firebase = 1;
        last_chat = [];
        FireBaseApp.child("User/Update/" + token_user).on('value', function(snap) {
            if (load_firebase != 1) {
                var data = snap.val();
                if (data.type == 'logout') {
                    logout();
                }
            }
        });
        FireBaseApp.child("User/Notification/" + token_user).on('value', function(snap) {
            if (load_firebase != 1) {
                var data = snap.val();
                notification_import(data.id);
                if ($('.notification_div .menu_open').attr('target') == 'off') {
                    var current_noti = to_int($('.notification_div .menu_open .amount').html());
                    current_noti = current_noti + 1;
                    $('.notification_div .menu_open .amount').html(current_noti);
                    $('.notification_div .menu_open .amount').show();
                    sound_chat.play();
                }
            }
            load_firebase = 2;
        });
        notification_render();
    } else {
        $('header .right .profile .user_profile_div .user_avatar .user_image').attr('src', 'assets/img/no_avatar.png');
        $('body header .right .profile ul.setting li').hide();
        $('body header .right .profile ul.setting li.profile_login').show();
    }
}

async function get_user_ads(div) {
    var user_ads_data = await get_data_by_url('/api/get_user_ads');
    if (Object.keys(user_ads_data).length != 0) {
        if (token_user != 0) {
            var button = `<button class='button_style_one' onclick="location.href='/user/user/ads/intro'">Đặt ngay</button>`;
        } else {
            var button = `<button class='button_style_one' onclick="alertify.error(language_text('text_need_login'))">Đặt ngay</button>`;
        }
        var html = `
            <div class='pr_module'>
                <img class='image' onclick='open_user_ads(${user_ads_data.id});window.open("${user_ads_data.url}")' src='/assets/tmp/ads/${user_ads_data.image}' />
                <div class='intro'>
                    <p>Quảng bá sản phẩm của bạn chỉ với <span>1.000.000đ</span> ${button}</p>
                </div>
            </div>
        `;
        $(div).html(html);
    }
}

function open_user_ads(adsId) {
    $.post("/assets/ajax/user.php", {
            action: "ads_open",
            ads_id: adsId
        })
        .done(function(data) {
            $('#result').empty().append(data);
        });
}

function open_menu(action) {
    if (action == "open") {
        $('#nav_menu').css("margin-left", "0px");
        $("#main_header .left .left_content").hide();
        $("#main_header header").removeClass("full");
        $("#main_header header").attr("style", "width: calc(100% - 240px);left: 240px;");
        $('.mgd_module .mgd_content').removeClass('full');
    } else {
        $('#nav_menu').css("margin-left", "-240px");
        $("#main_header .left .left_content").show();
        $("#main_header header").attr("style", "width: 100%;left: 0;");
        $('.mgd_module .mgd_content').addClass('full');
    }
}

async function profile_update() {
    if (my_character != 0) {
        var currency_check = ['gold', 'crystal'];
        $.each(currency_check, async function(index, value) {
            var currency_check = await get_data_by_url(`/api/character_count?type=currency&sign=${value}&character=${my_character}`);
            $(`.my_currency_${value}`).text(number_format(currency_check));
        });
    }
    var currency_check = ['manga_coin', 'fire_coin'];
    $.each(currency_check, async function(index, value) {
        var currency_check = await get_data_by_url(`/api/user_count?type=currency&sign=${value}&user=${token_user}`);
        $(`.my_currency_${value}`).text(number_format(currency_check));
    });
}

function header_render() {
    load_site_setting();
}

function setting_render() {
    if (getCookie('setting_volume')) {
        audio_background_music.volume = to_int(getCookie('setting_volume')) / 100;
        $("#volume_control").val(getCookie('setting_volume'));
    }
}

async function notification_render() {
    var get_content = await get_data_by_url(`/api/user_data_by_token?data=data&user=${token_user}&token=${token_security}`);
    if (Object.keys(get_content).length != 0) {
        get_content = json_convert(get_content.data);
        var total_notification = getSafe(() => get_content.notification, 0);
        if (total_notification > 0) {
            $('.notification_div .amount').html(total_notification);
            $('.notification_div .amount').show();
        }
    }
}
async function notification_load(target) {
    if (target == 0 && nofitication_loading == 'none' || target == 1) {
        var get_content = await get_data_by_url(`/api/user_notification?page=${nofitication_page}&limit=20&user=${token_user}`);
        var html = "";
        if (Object.keys(get_content).length != 0) {
            $.each(get_content, function(index, value) {
                var text = json_data.language['notification_' + value.type];
                var data = JSON.parse(value.data);
                $.each(data, function(index2, value2) {
                    text = text.replace(new RegExp(`:value_${index2}:`, 'g'), value2);
                });
                html = html + `
                    <div class="item">
                        ${text}
                        <div class="time">${time_format(value.date)}</div>
                    </div>
                `;
            });
            nofitication_loading = 'no';
        } else {
            nofitication_page = 0;
            html = `
                <div class="item bee_chibi">
                    <img src="/assets/img/bee_chibi.png"/>
                    <p>${language_text('text_no_notification')}</p>
                </div>
            `;
        }
        if (target == 0) {
            $('.notification_div .menu_open .amount').html('0');
            $('.notification_div .menu_open .amount').hide();
            $('.notification_div .notification .list').html(html);
        } else {
            $('.notification_div .notification .list').append(html);
        }
        $.post("/assets/ajax/user.php", {
            action: 'update_data',
            table: 'data',
            data: 'notification',
            value: 0
        }, function(data) {});
    }
}
async function notification_import(id) {
    var get_content = await get_data_by_url(`/api/notification_detail?id=${id}`);
    var html = "";
    if (Object.keys(get_content).length != 0) {
        $.each(get_content, function(index, value) {
            var text = json_data.language['notification_' + value.type];
            var data = JSON.parse(value.data);
            $.each(data, function(index2, value2) {
                text = text.replace(`:value_${index2}:`, value2);
            });
            html = html + `
                <div class="item">
                    ${text}
                    <div class="time">${time_format(value.date)}</div>
                </div>
            `;
        });
        $('.notification_div .notification .list').prepend(html);
    }
}
$(document).ready(function() {
    $(".notification_div .notification .list").scroll(function() {
        if ($(this).scrollTop() + $(".notification_div .notification .list").height() >= $(".notification_div .notification .list")[0].scrollHeight) {
            if (nofitication_loading == 'no' && nofitication_page != 0) {
                nofitication_loading = 'yes';
                nofitication_page = nofitication_page + 1;
                notification_load(1);
            }
        }
    });
});

function header_search_status(status) {
    if (status == 'on' && $('header .search').hasClass('off')) {
        $('header .search .search_result .child').hide();
        $('header .search .search_result .child.waiting').show();
        $('header .search').removeClass('off');
        $('header .search').addClass('on');
    } else if (status == 'off' && $('header .search').hasClass('on')) {
        $('header .search').removeClass('on');
        $('header .search').addClass('off');
    }
}
async function header_search() {
    var text = $('header .search input').val();
    $('header .search_result .child').hide();
    var total_result = 0;
    if (text != '') {
        var album_list = await get_data_by_url('/api/search?file=' + site_brand.file + '&type=album&limit=5&string=' + text);
        if (Object.keys(album_list).length != 0) {
            var html = "";
            $.each(album_list, function(index, value) {
                total_result = total_result + 1;
                value.info = JSON.parse(value.info);
                html = html + `
                    <li onclick="location.href='/album/${value.info.url}-${value.id_album}'">
                        <div class="search_avatar"><img src="/assets/tmp/album/${value.info.avatar}" /></div>
                        <div class="search_info">
                            <p class="name">${value.info.name}</p>
                            <p>Chapter ${value.info.chapter.last}</p>
                            <div class="statics">
                                <p><i class="fa-solid fa-eye"></i> ${number_convert(value.info.statics.view)}</p>
                                <p><i class="fa-solid fa-bookmark"></i> ${number_convert(value.info.statics.follow)}</p>
                            </div>
                            <p class="album_status ${value.info.status}">${json_data.language['album_status_'+value.info.status]}</p>
                        </div>
                    </li>
                    `;
            });
            $('header .search_result .child.manga').show();
            $('header .search_result .child.manga ul').html(html);
        }
        var team_list = await get_data_by_url('/api/team_list?limit=5&string=' + text);
        if (Object.keys(team_list).length != 0) {
            var html = "";
            $.each(team_list, function(index, value) {
                total_result = total_result + 1;
                value.info = json_convert(value.info);
                value.data = json_convert(value.data);
                html = html + `
                    <li class="team" onclick="location.href='/team/${value.id_team}'">
                        <div class="search_avatar"><img src="/assets/tmp/team/avatar/${value.info.avatar}" /></div>
                        <div class="search_info">
                            <p class="name">${value.info.name}</p>
                            <div class="statics">
                                <p><i class="fa-solid fa-eye"></i> ${number_convert(value.data.statics.view)}</p>
                                <p><i class="fa-solid fa-bookmark"></i> ${number_convert(value.data.statics.follow)}</p>
                            </div>
                            <p class="album_status">${time_format(value.last_action)}</p>
                        </div>
                    </li>
                    `;
            });
            $('header .search_result .child.team').show();
            $('header .search_result .child.team ul').html(html);
        }
        if (total_result == 0) {
            $('header .search_result .child.no_result').show();
        }
    } else {
        $('header .search_result .child.waiting').show();
    }
    $('header .search button').html('<i class="far fa-search"></i>');
}

function changeSetting(e) {
    var status = $(e).attr('status');
    var target = $(e).attr('target');
    if (status == 'on') {
        setSetting(target, 'off');
        $(e).removeClass('active');
        $(e).attr('status', 'off');
    } else {
        setSetting(target, 'on');
        $(e).addClass('active');
        $(e).attr('status', 'on');
    }
}
async function checkCountry() {
    user_country_data = getCookie('user_country');
    if (user_country_data) {
        user_country_data = JSON.parse(user_country_data);
    } else {
        user_country_data = await get_data_by_url('/api/user_country_data');
        if (user_country_data.countryCode) {
            setCookie('user_country', JSON.stringify(user_country_data));
        }
    }
}

function load_site_setting() {
    var child_protect = getSetting('child_protect') ? ? 'on';
    if (child_protect == 'on') {
        $('#setting_child_protect').addClass('active');
        $('#setting_child_protect').attr('status', 'on');
    } else {
        $('#setting_child_protect').removeClass('active');
        $('#setting_child_protect').attr('status', 'off');
    }
    var chapter_style = getSetting('chapter_style') ? ? 'grid';
}

async function load_brand_data() {
    $('.brand_logo_white').attr('src', `/assets/img/brand/${site_brand.brand}/logo-white.png`)
    $('.brand_logo_mobile').attr('src', `/assets/img/brand/${site_brand.brand}/logo-mobile.png`)
    await get_server_data('setting');
    $('#site_banner').attr('src', server_data.setting.brand[site_brand.brand].banner.img);
    $('#site_banner').attr('onclick', `window.open('${server_data.setting.brand[site_brand.brand].banner.url}')`);
}

function ads_click(e) {
    var num = parseInt($(e).attr('num'));
    if (num < 2) {
        num = num + 1;
        $(e).attr('num', num);
    } else {
        $(e).hide();
        setTimeout(function() {
            $('.google_adsen_div').hide();
        }, 3000);
        setCookie('ads_time', server_time + 86400);
        $.post("/assets/ajax/user.php", {
                action: "ads_click"
            })
            .done(function(data) {
                $('#result').empty().append(data);
            });
    }
}

function check_browser_score() {
    setTimeout(() => {
        if (!getCookie('google_score')) {
            var total_check = getCookie('google_score_time') ? ? 0;
            if (total_check <= 10) {
                if ($('#turnstile-container div').length == 0) {
                    turnstile.render('#turnstile-container', {
                        sitekey: '0x4AAAAAABeaUcdpJM9TPlsN',
                        callback: function(token) {
                            $.post("/assets/ajax/user.php", {
                                action: 'google_score',
                                token: token
                            }, function(data) {
                                $("#turnstile-container").remove();
                                setCookie('google_score_time', to_int(total_check) + 1);
                            });
                        }
                    });
                }
            }
        } else {
            $("#turnstile-container").remove();
        }
    }, 400);
}


function item_show_render(data) {
    $('.popup_item_show').removeClass('show');
    $('.popup_item_show .avatar').attr('src', data.avatar);
    $('.popup_item_show .text').html(data.text);
    $('.popup_item_show .price span').html(data.price);
    if (data.price == 0) {
        $('.popup_item_show .price').hide();
    } else {
        $('.popup_item_show .price').show();
    }
    $('.popup_item_show a').attr('onclick', data.onclick);
    setTimeout(function() {
        $('.popup_item_show').addClass('show');
    }, 200);
    setTimeout(function() {
        var load = parseInt($(".popup_item_show").attr('load'));
        $(".popup_item_show").attr('load', load - 1);
    }, 8500);
}

function global_message_render() {
    global_message = 1;
    FireBaseApp.child("Global/Message").on('value', function(snap) {
        if (global_message != 1) {
            var data = snap.val();
            data = JSON.parse(data.data);
            load = parseInt($(".popup_item_show").attr('load'));
            $(".popup_item_show").attr('load', load + 1);
            setTimeout(function() {
                item_show_render(data);
            }, 500 + (load * 8500));
        }
        global_message = 2;
    });
}

$(window).scroll(function() {
    if ($(this).scrollTop() > 70) {
        $('body header.main_header').addClass('header_background');
    } else {
        $('body header.main_header').removeClass('header_background');
    }
});

function adblock_action() {
    $('.zzzzgggg').show();
}