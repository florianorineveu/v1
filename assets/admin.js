import $ from 'jquery';
import 'bootstrap';
import '@fortawesome/fontawesome-free/js/all'
import 'jquery-fullscreen-plugin/jquery.fullscreen-min';

import './admin/styles/admin.scss';

$(function() {
    $('#fullscreenToggler').on('click', function (e) {
        $(document).toggleFullScreen();
        $(this).children('.fa-lg').toggleClass('fa-expand fa-compress');
    });
});