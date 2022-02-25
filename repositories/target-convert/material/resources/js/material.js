require('./bootstrap');
require('./drop-uploader');
require('./vtt-background');

window.addEventListener('load', function () {
    document.querySelectorAll('[role="alert"]').forEach(item => setTimeout(function () {
        item.remove();
    }, (item.dataset.disappear || 3) * 1000))
});
