'use strict'

{
  const menuItems: any = document.querySelectorAll('.done-wanna-menu a');
  const contents: any = document.querySelectorAll('.done-wanna-contents');

  menuItems.forEach(clickedItem => {
    clickedItem.addEventListener('click', e =>{
      e.preventDefault();

      menuItems.forEach(item => {
        item.classList.remove('done-wanna-active');
      });
      clickedItem.classList.add('done-wanna-active');

      contents.forEach(content => {
        content.classList.remove('done-wanna-active');
      });
      document.getElementById(clickedItem.dataset.id).classList.add('done-wanna-active');
    });
  });
}