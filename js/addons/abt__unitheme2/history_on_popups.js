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
(function(_, $) {
$(document).ready(function(){
if (_.abt__ut2.settings.general.push_history_on_popups[_.abt__ut2.device] === 'Y') {

var app = {
currentState: false,
originalPushState: false,
getCurrentState: function() {
return this.currentState;
},
setAbState: function(state) {
var _state = {};
_state[state] = true;
history.pushState(_state, '');
},
pushState : function(state, title, href) {
app.currentState = state;
app.originalPushState.call(history, state, title, href);
},
onPopstate : function(event) {
app.currentState = event.state;
},
init : function() {
window.onpopstate = app.onPopstate;
app.originalPushState = history.pushState;
history.pushState = app.pushState;
}
};
app.init();
$.ceEvent('on', 'ce.dialogshow', function(){
app.setAbState('abt__popup_opened');
});
$.ceEvent('on', 'ce.notificationshow', function(notification){
if (notification.hasClass('cm-notification-content-extended')) {
app.setAbState('abt__notice_opened');
var vanilla = notification[0];
var ab_observer = new MutationObserver(function(mutationsList) {
var last_state = app.getCurrentState();
for (var mutationRecord of mutationsList) {
if (mutationRecord.removedNodes.length) {
console.log(mutationRecord);
} else if (mutationRecord.attributeName !== undefined && mutationRecord.attributeName === 'style' && ~mutationRecord.oldValue.indexOf('display: none')) {
if (last_state.abt__notice_opened !== undefined && last_state.abt__notice_opened === true) {
history.go(-1);
}
}
}
});
ab_observer.observe(vanilla, {
attributes: true,
attributeOldValue: true,
});
}
});
$.ceEvent('on', 'ce.dialogclose', function(){
var last_state = app.getCurrentState();
if (last_state.abt__popup_opened !== undefined && last_state.abt__popup_opened === true) {
history.go(-1);
}
});
window.onpopstate = function() {
var last_state = app.getCurrentState();
if (last_state.abt__popup_opened !== undefined && last_state.abt__popup_opened === true) {
$.ceDialog('get_last').ceDialog('close');
last_state.abt__popup_opened = false;
}
if (last_state.abt__notice_opened !== undefined && last_state.abt__notice_opened === true) {
var _notification_container = $('.cm-notification-content-extended:visible');
if (_notification_container.length) {
$.ceNotification('close', _notification_container, false);
}
last_state.abt__notice_opened = false;
}
}
}
});
}(Tygh, Tygh.$));