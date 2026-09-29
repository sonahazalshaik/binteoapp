var len = 0;
var clickLink = 0;
var search = null;
var process = false;

$('#searchInput').on('keydown', function (e) {
    var length = $('.search-list li').length;
    if (search != $(this).val() && process) {
        len = 0;
        clickLink = 0;
        $(`.search-list li:eq(${len}) a`).focus();
        $(`#searchInput`).focus();
    }
    //Down
    if (e.keyCode == 40 && length) {
        process = true;
        var contra = false;
        if (len < clickLink && clickLink < length) {
            len += 2;
        }
        $(`.search-list li[class="active"]`).removeClass('active');
        $(`.search-list li a[class="text-active"]`).removeClass('text-active');
        $(`.search-list li:eq(${len}) a`).focus().addClass('text-active');
        $(`.search-list li:eq(${len})`).addClass('active');
        $(`#searchInput`).focus();
        clickLink = len;
        if (!$(`.search-list li:eq(${clickLink}) a`).length) {
            $(`.search-list li:eq(${len})`).addClass('text-active');
        }
        len += 1;
        if (length == Math.abs(clickLink)) {
            len = 0;
        }
    }
    //Up
    else if (e.keyCode == 38 && length) {
        process = true;
        if (len > clickLink && len != 0) {
            len -= 2;
        }
        $(`.search-list li[class="active"]`).removeClass('active');
        $(`.search-list li a[class="text-active"]`).removeClass('text-active');
        $(`.search-list li:eq(${len}) a`).focus().addClass('text-active');
        $(`.search-list li:eq(${len})`).addClass('active');
        e.preventDefault();
        $(`#searchInput`).focus();
        clickLink = len;
        if (!$(`.search-list li:eq(${clickLink}) a`).length) {
            $(`.search-list li:eq(${len})`).addClass('text-active');
        }
        len -= 1;
        if (length == Math.abs(clickLink)) {
            len = 0;
        }
    }
    //Enter
    else if (e.keyCode == 13) {
        e.preventDefault();
        if ($(`.search-list li:eq(${clickLink}) a`).length && process) {
            $(`.search-list li:eq(${clickLink}) a`)[0].click();
        }
    }
    //Retry
    else if (e.keyCode == 8) {
        len = 0;
        clickLink = 0;
        $(`.search-list li:eq(${len}) a`).focus();
        $(`#searchInput`).focus();
    }
    search = $(this).val();
});

function filterSettings(query) {
    let filteredSettings = [];
    for (var key in settingsData) {
        if (settingsData.hasOwnProperty(key)) {
            var setting = settingsData[key];

            if (setting.submenu) {
                setting.submenu.forEach(subItem => {
                    var keywordMatch = false;
                    if (subItem['keyword'] && Array.isArray(subItem['keyword'])) {
                        keywordMatch = subItem['keyword'].some(function (keyword) {
                            return keyword.toLowerCase().includes(query.toLowerCase());
                        });
                    }

                    var titleMatch = subItem['title'] ? subItem['title'].toLowerCase().includes(query.toLowerCase()) : false;
                    if (keywordMatch || titleMatch) {
                        filteredSettings.push(subItem);
                    }
                });
            } else {
                var keywordMatch = false;
                if (setting.keyword && Array.isArray(setting.keyword)) {
                    keywordMatch = setting.keyword.some(function (keyword) {
                        return keyword.toLowerCase().includes(query.toLowerCase());
                    });
                }

                var titleMatch = setting.title ? setting.title.toLowerCase().includes(query.toLowerCase()) : false;
                var subtitleMatch = null;

                if (setting.subtitle) {
                    subtitleMatch = setting.subtitle.toLowerCase().includes(query.toLowerCase());
                }

                if (keywordMatch || titleMatch || subtitleMatch) {
                    filteredSettings.push(setting);
                }
            }
        }
    }
    return filteredSettings;
}

function isEmpty(obj) {
    return Object.keys(obj).length === 0;
}

function renderSettings(filteredSettings, query) {
    var search_result_pane = $('.search-list');
    if (isEmpty(filteredSettings)) {
        $(search_result_pane).append(getEmptyMessage());
    } else {
        var index = 0;
        for (const key in filteredSettings) {
            if (Object.hasOwnProperty.call(filteredSettings, key)) {
                const element = filteredSettings[key];
                var setting = element;
                var url = null;
                var title = null;
                var tempRoute = null;
                var parent = null;

                tempRoute = setting.route_name;
                routeParams = null;
                if (setting.params) {
                    routeParams = setting.params;
                }
                title = setting.title;

                for (let route of routes) {
                    if (tempRoute in route) {
                        url = route[tempRoute];
                        if (routeParams) {
                            $.each(routeParams, function (paramIndex, paramVal) {
                                $.each(paramVal, function (paramKey, paramValue) {
                                    url = url.replace(`{${paramKey}}`, paramValue);
                                    url = url.replace(`{${paramKey}?}`, paramValue);
                                });
                            });
                        }
                        var link = $(`.sidebar__menu a[href='${url}']`);
                        var text = $(link).parents('.sidebar-menu-item.sidebar-dropdown').find('.menu-title')
                            .first().text();

                        if (!text) {
                            text = 'Main Menu';
                            if (setting.subtitle) {
                                text = 'System Setting';
                            }
                        }
                        parent = text;
                    }
                }

                $(search_result_pane).append(`
                        <li>
                            <a class="search-list-link block px-5 py-3 hover:bg-slate-50 transition-colors border-b border-slate-100 last:border-0" href="${url}">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">${parent}</span>
                                <p class="text-[13px] font-black text-slate-800 mt-0.5">${title}</p>
                            </a>
                        </li>
                    `);
            }
        }
    }
}

$('#searchInput').on('input', function () {
    $('.search-list').html('');

    var query = $(this).val().trim();
    if (!query) {
        return false;
    }

    var filteredData = filterSettings(query);
    renderSettings(filteredData, query);
});