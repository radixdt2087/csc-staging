/*******************************************************************************************
*   ___  _          ______                     _ _                _                        *
*  / _ \| |         | ___ \                   | (_)              | |              © 2021   *
* / /_\ | | _____  _| |_/ /_ __ __ _ _ __   __| |_ _ __   __ _   | |_ ___  __ _ _ __ ___   *
* |  _  | |/ _ \ \/ / ___ \ '__/ _` | '_ \ / _` | | '_ \ / _` |  | __/ _ \/ _` | '_ ` _ \  *
* | | | | |  __/>  <| |_/ / | | (_| | | | | (_| | | | | | (_| |  | ||  __/ (_| | | | | | | *
* \_| |_/_|\___/_/\_\____/|_|  \__,_|_| |_|\__,_|_|_| |_|\__, |  \___\___|\__,_|_| |_| |_| *
*                                                         __/ |                            *
*                                                        |___/                             *
* ---------------------------------------------------------------------------------------- *
* This is commercial software, only users who have purchased a valid license and accept    *
* to the terms of the License Agreement can install and use this program.                  *
* ---------------------------------------------------------------------------------------- *
* website: https://cs-cart.alexbranding.com                                                *
*   email: info@alexbranding.com                                                           *
*******************************************************************************************/
(function (_, $) {
$.ceEvent('on', 'ce.commoninit', function(context) {
var items = context.find('[data-ab-sticker-id]');
for (var timeout in _.ab__stickers.timeouts) {
clearTimeout(_.ab__stickers.timeouts[timeout]);
}
if (items.length) {
var sticker_ids = [];
var stickers_storage = JSON.parse(localStorage.getItem('ab__stickers'));
var ids_to_remove = [];
items.each(function () {
var item = $(this);
var item_sticker = item.attr('data-ab-sticker-id');
if (stickers_storage !== null) {
if (stickers_storage.html[item_sticker] != void (0)) {
create_sticker(item, stickers_storage.html[item_sticker]);
ids_to_remove.push(item_sticker);
}
}
sticker_ids.push(item_sticker);
});
sticker_ids = sticker_ids.filter(function (value, index, self) {
return self.indexOf(value) === index && !(~ids_to_remove.indexOf(value));
});
if (sticker_ids.length) {
var sticker_placeholders = [];
sticker_ids.forEach(function(id) {
sticker_placeholders.push({placeholders: $('[data-ab-sticker-id="' + id + '"]').data('placeholders'), id: id});
});
$.ceAjax('request', fn_url('ab__stickers.get_stickers?sl=' + _.cart_language), {
method: 'post',
hidden: true,
data: {
sticker_ids: sticker_ids,
sticker_placeholders: sticker_placeholders,
controller_mode: _.ab__stickers.runtime.controller_mode,
},
callback: function (data, params) {
if (!is_object_empty(data.stickers_html)) {
var html = data.stickers_html;
var local_storage_assign = {
html: {},
};
items.each(function () {
var item = $(this);
var item_sticker = item.attr('data-ab-sticker-id');
local_storage_assign.html[item_sticker] = html[item_sticker];
create_sticker(item, html[item_sticker]);
});
if (_.ab__stickers.runtime.caching === true) {
if (stickers_storage !== null) {
local_storage_assign.html = Object.assign(local_storage_assign.html, stickers_storage.html);
}
try {
localStorage.setItem('ab__stickers', JSON.stringify(local_storage_assign));
} catch (e) {
localStorage.removeItem('ab__stickers');
localStorage.setItem('ab__stickers', JSON.stringify(local_storage_assign));
}
}
}
}
});
}
_.ab__stickers.close_tooltip = function(btn){
btn = $(btn);
var tooltip = btn.parent();
var id = tooltip.data('data-sticker-id');
clearTimeout(_.ab__stickers.timeouts[id]);
tooltip.css({
'display': 'none',
'top': '-1000px',
});
setTimeout(function(){
tooltip.css('display', '');
}, 50);
}
}
var wrapper = context.find('.ab-stickers-wrapper');
if (wrapper.length) {
var prev_w_size = 0;
var resize = function () {
if (prev_w_size !== window.innerWidth) {
prev_w_size = window.innerWidth;
var add_h = function () {
var height = context.find('.ty-product-img a.cm-image-previewer').first().outerHeight();
if (height > 100) {
wrapper.css('max-height', height + 'px');
} else {
setTimeout(function () {
add_h();
}, 100);
}
};
add_h();
}
};
$(window).on('resize', resize);
resize();
}
});

function create_sticker(item, sticker_html) {
item.html(sticker_html);
var sticker = item.find('[data-id]');
if (sticker.length) {
var id = sticker.data('id');
var tooltip = $("[data-sticker-id='" + id + "']").first();
sticker.hover(function () {
var tooltip_pointer = tooltip.next();
if (!tooltip.hasClass('moved')) {
$("[data-sticker-id='" + id + "']:not(:first)").remove();
$("[data-sticker-p-id='" + id + "']:not(:first)").remove();
tooltip.appendTo('#' + _.container).addClass('moved');
tooltip_pointer.appendTo('#' + _.container);
}
clearTimeout(_.ab__stickers.timeouts[id]);
var s_height = sticker.outerHeight(true);
var s_width = sticker.outerWidth(true);
var s_pos = sticker.offset();
var tooltip_w = tooltip.outerWidth();
var tooltip_pos_y = (s_pos.top + s_height + 10);
var tooltip_pos_x = (s_pos.left + s_width / 2) - tooltip_w / 2;
var rectangle = {
top: tooltip_pos_y,
left: tooltip_pos_x,
right: tooltip_pos_x + tooltip_w,
};
if (rectangle.right > window.innerWidth) {
rectangle.left -= rectangle.right - window.innerWidth + 25;
} else if (rectangle.left < 0) {
rectangle.left = 25;
}
tooltip_pointer.css({
top: rectangle.top + 'px',
left: sticker.offset().left + sticker.outerWidth() / 2,
}).hover(function () {
clearTimeout(_.ab__stickers.timeouts[id]);
tooltip.addClass('hovered');
}, function () {
clearTimeout(_.ab__stickers.timeouts[id]);
_.ab__stickers.timeouts[id] = setTimeout(function () {
tooltip.removeClass('hovered');
}, 50);
});
tooltip.css({
top: rectangle.top + 'px',
left: rectangle.left + 'px',
}).addClass('hovered');
}, function () {
clearTimeout(_.ab__stickers.timeouts[id]);
_.ab__stickers.timeouts[id] = setTimeout(function () {
tooltip.removeClass('hovered');
}, 50);
});
}
}

function is_object_empty(obj) {
return obj == void(0) || Object.keys(obj).length === 0;
}
})(Tygh, Tygh.$);