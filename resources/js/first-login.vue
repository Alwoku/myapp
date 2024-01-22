<!-- Страница с формой -->
<template>
    <div class="hero is-middle">
        <div class="hero-body">
            <p class=" has-text-centered">
               <b class="title">
                    Создание заявки
                </b>  
            </p>
            <div class="conteiner m-5">
                <div class="field">
                    <label class="label">ID пользователя:</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Введите ID пользователя" v-model="userData.client_id"> 
                    </div>
                </div>
                <div class="field">
                    <label class="label">Секретный ключ:</label>
                    <div class="control">
                        <input class="input" type="password" placeholder="Введите секретный ключ" v-model="userData.client_secret">
                    </div>
                </div>
                <div class="field">
                    <label class="label">ID интеграции:</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Введите authorization_code" v-model="userData.grant_type">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Полученный код авторизации:</label>
                    <div class="control">
                        <input class="input" type="password" placeholder="Введите код авторизации" v-model="userData.code">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Redirect URI:</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Введите Redirect URI указанный в настройках интеграции" v-model="userData.redirect_uri">
                    </div>
                </div>
                <div class="field">
                    <button class="button" @click="save">
                        Войти
                    </button>
                </div>
            </div>
            <div class="block">
                <p class="is-size-6">
                    *все поля обязательны для заполнения 
                </p>
            </div>
        </div>
    </div>
</template>
<script>
let toastr = require("toastr");
import axios from "axios";

toastr.options.positionClass = "toast-bottom-right";


export default {
    name: 'amocrm',
    data() {
        return {
            userData: {},
        }
    },

    mounted() {

    },
    methods: {
        save(){
            axios({
                url: "/first-login",
                data: this.userData,
                method:"post"
            }).then(response => {
                // console.log(response.data);
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
        },
    },
}

</script>