<?php

namespace App\Http\Controllers;

use App\Models\AmoCRMModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// Контроллер обработки интеграции amocrm

class AmoCRMController extends Controller
{
    

    
    /**
     * Первичный вход в систему
     *
     */
    public function firstLogin(){
        $apiClient = new \AmoCRM\Client\AmoCRMApiClient(env("CLIENT_ID"), env("CLIENT_SECRET"),  env("CLIENT_REDIRECT_URI"));
            
        return view("first-login", [
            "cliet_id" => env("CLIENT_ID"), 
            "client_secret"=> env("CLIENT_SECRET"), 
            "client_redirect_url" => env("CLIENT_REDIRECT_URI")
        ]);
    }
  
    /**
     * первичное соединение с amocrm
     *
     */
    public function firstConnect(Request $request){

        $data = request()->all();
        $apiClient = new \AmoCRM\Client\AmoCRMApiClient(env("CLIENT_ID"), env("CLIENT_SECRET"),  env("CLIENT_REDIRECT_URI"));
        // но он не дает мне никаких данных, поэтому я даже не знаю что именно он мне отдает 
        $data = $request->all();
        
        $model = new AmoCRMModel();
        $model->receivingToken($apiClient);
    }

    /**
     * создание заявок
     *
     * @return void
     */
    public function sendingApplication(){

        
        return view("amocrm-form");
    }
    
    /**
     * отправка заявок в amocrm
     *
     * @return string
     */
    public function saveApplication(Request $request){

        // проверяем на правильность заполнения формы
        $validator = Validator::make($request->all(),[
            "name" => 'required|max:255',
            "email" => 'required|email',
            "number" => 'required|integer',
            "price" => 'required|integer',
        ], [
            "name.required" => 'Имя обязателенo для заполнения',
            "email.required" => 'Email обязателен для заполнения',
            "number.required" => 'Номер телефона обязателен для заполнения',
            "price.required" => 'Поле с ценой обязателено для заполнения',
            "price.integer" => 'Неверный формат поля с ценой',
            "number.integer" => 'Неверный формат номера телефона',
            "email.email" => 'Неверный формат номера телефона' 

        ])->validate();

        // забираем данные, которые были переданны асинхронно 

        $data = request()->all();
        
        $model = new AmoCRMModel();

        return $model->applicationProcessing($data);
      
    }
}
