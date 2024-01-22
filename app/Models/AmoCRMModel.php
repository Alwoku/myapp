<?php

namespace App\Models;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\LeadModel;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\OAuth2\Client\Token\AccessTokenInterface;

// Модель обработки интеграции amocrm

class AmoCRMModel extends Model
{
    use HasFactory;

    public function applicationProcessing($application){
        
        // входим под клиента

        $apiClient = new \AmoCRM\Client\AmoCRMApiClient(env("CLIENT_ID"), env("CLIENT_SECRET"),  env("CLIENT_REDIRECT_URI"));

        // запрашиваем и сохраняем токен
        $accessToken = $this->receivingToken($apiClient);  
        $apiClient->setAccessToken($accessToken)
        ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
        ->onAccessTokenRefresh(
            function (AccessTokenInterface $accessToken, string $baseDomain) {
                saveToken(
                    [
                        'accessToken' => $accessToken->getToken(),
                        'refreshToken' => $accessToken->getRefreshToken(),
                        'expires' => $accessToken->getExpires(),
                        'baseDomain' => $baseDomain,
                    ]
                );
            }
        );

        $leadsCollection = new LeadsCollection();
        
    //Создадим модели и заполним ими коллекцию
    $lead = (new LeadModel())
        ->setPrice($application['price'])
        ->setContacts(
            (new ContactsCollection())
                ->add(
                    (new ContactModel())
                        ->setFirstName($application['name'])
                        ->setCustomFieldsValues(
                            (new CustomFieldsValuesCollection())
                                ->add(
                                    (new MultitextCustomFieldValuesModel())
                                        ->setFieldCode('PHONE')
                                        ->setValues(
                                            (new MultitextCustomFieldValueCollection())
                                                ->add(
                                                    (new MultitextCustomFieldValueModel())
                                                        ->setValue($application['phone'])
                                                )
                                        )
                                        ->setFieldCode('EMAIL')
                                        ->setValues(
                                            (new MultitextCustomFieldValueCollection())
                                                ->add(
                                                    (new MultitextCustomFieldValueModel())
                                                        ->setValue($application['email'])
                                                )
                                        )
                                )

                        )
                )
        );
        $leadsCollection->add($lead);


        //Создадим сделки
        try {
            $addedLeadsCollection = $apiClient->leads()->addComplex($leadsCollection);
        } catch (AmoCRMApiException $e) {
            printError($e);
            die;
        }

        /** @var LeadModel $addedLead */

        foreach ($addedLeadsCollection as $addedLead) {

            //Пройдемся по добавленным сделкам и выведем результат

            $leadId = $addedLead->getId();
            $contactId = $addedLead->getContacts()->first()->getId();
            $companyId = $addedLead->getCompany()->getId();

            $externalRequestIds = $addedLead->getComplexRequestIds();
        
        }
        return "ok";
    }
    
    /**
     * код из примера забора токена
     * не смогла реализовать как должно было идти 
     *
     * @param  $apiClient
     */
    public function receivingToken($apiClient)
    {
       
        if (isset($_GET['referer'])) {
            $apiClient->setAccountBaseDomain($_GET['referer']);
        }
        if (!isset($_GET['code'])) {
            $state = bin2hex(random_bytes(16));
            session(['oauth2state'=> $state] );
            if (isset($_GET['button'])) {
                echo $apiClient->getOAuthClient()->getOAuthButton(
                    [
                        'title' => 'Установить интеграцию',
                        'compact' => true,
                        'class_name' => 'className',
                        'color' => 'default',
                        'error_callback' => 'handleOauthError',
                        'state' => $state,
                    ]
                );
                die;
            } else {
                $authorizationUrl = $apiClient->getOAuthClient()->getAuthorizeUrl([
                    'state' => $state,
                    'mode' => 'post_message',
                ]);
                header('Location: ' . $authorizationUrl);
                die;
            }
        } elseif (!isset($_GET['from_widget']) && (empty($_GET['state']) || empty(session()['oauth2state']) || ($_GET['state'] !== session()['oauth2state']))) {
            unset(session()['oauth2state']);
            exit('Invalid state');
        }

        /**
         * Ловим обратный код
         */
        try {
            $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($_GET['code']);

            if (!$accessToken->hasExpired()) {
                saveToken([
                    'accessToken' => $accessToken->getToken(),
                    'refreshToken' => $accessToken->getRefreshToken(),
                    'expires' => $accessToken->getExpires(),
                    'baseDomain' => $apiClient->getAccountBaseDomain(),
                ]);
            }
        } catch (Exception $e) {
            die((string)$e);
        }

        $ownerDetails = $apiClient->getOAuthClient()->getResourceOwner($accessToken);

        return $accessToken;

    }
}
