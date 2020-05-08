'use strict';
{
    var menuItems_1 = document.querySelectorAll('.done-wanna-menu a');
    var contents_1 = document.querySelectorAll('.done-wanna-contents');
    menuItems_1.forEach(function (clickedItem) {
        clickedItem.addEventListener('click', function (e) {
            e.preventDefault();
            menuItems_1.forEach(function (item) {
                item.classList.remove('done-wanna-menu-active');
            });
            clickedItem.classList.add('done-wanna-menu-active');
            contents_1.forEach(function (content) {
                content.classList.remove('done-wanna-contents-active');
            });
            document.getElementById(clickedItem.dataset.id).classList.add('done-wanna-contents-active');
        });
    });
}
