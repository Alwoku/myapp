<!-- Страница с формой -->
<template>
    <div class="hero is-large">
        <div class="hero-body">
            <p class=" has-text-centered">
               <b class="title">
                    Создание заявки
                </b>  
            </p>
            <div class="conteiner m-5">
                <div class="field">
                    <label class="label">Ваше Имя:</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Введите имя" v-model="userData.name"> 
                    </div>
                </div>
                <div class="field">
                    <label class="label">Email:</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Введите email" v-model="userData.email">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Номер телефона:</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Введите номер телефона" v-model="userData.number">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Цена:</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Введите цену" v-model="userData.price">
                    </div>
                </div>
                <div class="field">
                    <button class="button" @click="save">
                        Сохранить
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
    methods: {
        save(){
            axios({
                url: "/sendingApplication",
                data: this.userData,
                method:"post"
            }).then(response => {
                console.log(response.data)
                if(response.data === "ok"){
                    return location. reload();
                }
            })
        },
    },
}

</script>