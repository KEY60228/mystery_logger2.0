'use strict'

// users/show.phpのdone-wannaのタブの切り替え用
{
  const menuItems: any = document.querySelectorAll('.done-wanna-menu a');
  const contents: any = document.querySelectorAll('.done-wanna-contents');

  menuItems.forEach(clickedItem => {
    clickedItem.addEventListener('click', e =>{
      e.preventDefault();

      menuItems.forEach(item => {
        item.classList.remove('done-wanna-menu-active');
      });
      clickedItem.classList.add('done-wanna-menu-active');

      contents.forEach(content => {
        content.classList.remove('done-wanna-contents-active');
      });
      document.getElementById(clickedItem.dataset.id).classList.add('done-wanna-contents-active');
    });
  });
}


/* posts/new.php, posts/edit.phpの文字数カウント用 */
function countLength(text: string): any {
  document.getElementById("js-count-characters").innerHTML = text.length + "文字";
}

