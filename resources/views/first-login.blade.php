@extends("index")

@section("content")
<section class="hero is-medium">
  <div class="hero-body">
    <div class="has-text-centered">
        <p class="title">
        Здравствуйте !
        </p>
        <p class="subtitle">
        дайте разрешение сайту на взаимодействие с amocrm:
        </p>
        <!-- подружить ужа с ежом не получилось  -->
        <script
            class="amocrm_oauth"
            charset="utf-8"
            data-name="Установить интеграцию"
            data-description="Integration description"
            data-redirect_uri="https://localhost.com"
            data-secrets_uri="https://panda55505.amocrm.ru/"
            data-logo="https://example.com/amocrm_logo.png"
            data-scopes="crm,notifications"
            data-title="Button"
            data-compact="false"
            data-class-name="className"
            data-color="default"
            data-state="state"
            data-error-callback="updateAuthInfo"
            data-mode="popup"
            src="https://www.amocrm.ru/auth/button.min.js"
        ></script>
    </div>
    

  </div>
</section>


@endsection