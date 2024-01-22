require('./bootstrap');

import $ from "jquery";
window.$ = window.jQuery = $;

import axios from "axios";
// jQuery плагины
require("chosen-js");

import { createApp } from 'vue'; 

export const files = require.context('./', true, /\.vue$/i);
const app =  createApp({});
files.keys().map(function (key) {
    let name = key.split('/').pop().split('.')[0];
    app.component(name, files(key).default);
});

app.mount('#app')

var popup;



// 2. Регистрируем обработчика сообщений из popup окна
window.addEventListener('message', updateAuthInfo);
console.log(window.addEventListener('message', updateAuthInfo))
// 3. Функция обработчик, зарегистрированная выше
function updateAuthInfo(e) {
  if (e.data.error !== undefined) {
    console.log(e)
    console.log('Ошибка - ' + e.data.error)
  } else {
    axios({
        url: "/first-login",
        data: e.data,
        method:"post"
    }).then(response => {
        if(response.data === "ok"){
            return location = "/"
        }

    }).catch(error => {
        if (error.response) {
            let data = error.response.data;

            var errors = data.errors;

            for (var key in errors) {
                toastr.error(errors[key][0]);
            }

            return;
        }

        toastr.error("Ошибка при сохранении записи");
    });

  }

  // 4. Закрываем модальное окно
  popup.close();
}