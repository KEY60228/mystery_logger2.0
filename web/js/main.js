'use strict';
{
    var menuItems_1 = document.querySelectorAll('.menu li a');
    var contents_1 = document.querySelectorAll('.content');
    menuItems_1.forEach(function (clickedItem) {
        clickedItem.addEventListener('click', function (e) {
            e.preventDefault();
            menuItems_1.forEach(function (item) {
                item.classList.remove('active');
            });
            clickedItem.classList.add('active');
            contents_1.forEach(function (content) {
                content.classList.remove('active');
            });
            document.getElementById(clickedItem.dataset.id).classList.add('active');
        });
    });
}
