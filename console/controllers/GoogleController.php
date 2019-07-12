<?php

namespace console\controllers;

use common\classes\Debug;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_BatchUpdateSpreadsheetRequest;
use Google_Service_Sheets_Request;
use Google_Service_Sheets_Spreadsheet;
use Google_Service_Sheets_ValueRange;
use yii\console\Controller;
use yii\helpers\Url;

class GoogleController extends Controller
{
    public function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets API PHP Quickstart');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
        $client->setAuthConfig('Project.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = 'token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    public static function FillingTable($table, $data)
    {
        try {
            $googleAccountKeyFilePath = \Yii::getAlias('@app/../Project.json'); // Ключ который мы получили при регистрации
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath);
            $client = new Google_Client();

            $client->useApplicationDefaultCredentials();
            $client->addScope('https://www.googleapis.com/auth/spreadsheets');
            $service = new Google_Service_Sheets($client);
            $spreadsheetId = $table; // ID таблицы
            $spreadsheetName = "AmoCrmInfo"; // Название нашего листа
            //Удаление данных из таблицы
//            $requests = [
//                new Google_Service_Sheets_Request( [
//                    'deleteDimension' => [
//                        'range'          => [
//                            'sheetId' => $table,
//                            'startIndex' => 1,
//                            'endIndex' => 50,
//                            'dimension' => 'COLUMNS'
//                        ]
//                    ]
//                ] )
//            ];
//            $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest( [
//                'requests' => $requests
//            ] );
//            $service->spreadsheets->batchUpdate( $spreadsheetId, $batchUpdateRequest );
//
//// Полное удаление строк
//            $requests = [
//                new Google_Service_Sheets_Request( [
//                    'deleteDimension' => [
//                        'range'          => [
//                            'sheetId' => $table,
//                            'startIndex' => 1,
//                            'endIndex' => 5000,
//                            'dimension' => 'ROWS'
//                        ]
//                    ]
//                ] )
//            ];
//            $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest( [
//                'requests' => $requests
//            ] );
//            $service->spreadsheets->batchUpdate( $spreadsheetId, $batchUpdateRequest );
            $values = [
                ['Дата', 'Менеджеры', 'Новых сделок в воронке', 'Согласование договора', 'Успешно реализовано', 'Сумма оплат'],
            ];
            foreach ($data as $item)
            {
                array_push($values, array_values((array) $item));
            }
            $body = new Google_Service_Sheets_ValueRange(['values' => $values]);

            $options = array('valueInputOption' => 'USER_ENTERED'); // Данную строку не трогать
            // Выше мы указываем, что данные будут добавляться, будто сам пользователь их вписал

            $service->spreadsheets_values->update($spreadsheetId, $spreadsheetName, $body, $options);

            return 'https://docs.google.com/spreadsheets/d/' . $spreadsheetId;
        } catch (\Throwable $th) {
            echo $th->getMessage();

            return false;
        }
    }

    public function actionCreateTable()
    {
        $client = new Google_Client();

        $client->useApplicationDefaultCredentials();
        $client->addScope('https://www.googleapis.com/auth/spreadsheets');
        $service = new Google_Service_Sheets($client);
        $spreadsheet = new Google_Service_Sheets_Spreadsheet([
            'properties' => [
                'title' => 'test_sheet'
            ]
        ]);
        $spreadsheet = $service->spreadsheets->create($spreadsheet, [
            'fields' => 'spreadsheetId'
        ]);
        printf("Spreadsheet ID: %s\n", $spreadsheet->spreadsheetId);
//        $client = new Google_Client();
//        $client->useApplicationDefaultCredentials();
//        $client->addScope('https://www.googleapis.com/auth/spreadsheets');
//        $service = new Google_Service_Sheets($client);
//        $private_key  = file_get_contents(\Yii::getAlias('@app/../Project.json'));
//        $client_email = 'google@project-246308.iam.gserviceaccount.com';
//        $scopes       = implode(' ', ["https://www.googleapis.com/auth/drive"]);
//
//        $credentials =  $client->setAuthConfig(array(
//            'type' => 'service_accaunt',
//            'client_email' => $client_email,
//            'client_id' => '104874705327663944963',
//            'client_secret' => $private_key,
//        ));
//        // tried once with additional constructor params:
//        // $privateKeyPassword = 'notasecret',
//        // $assertionType = 'http://oauth.net/grant_type/jwt/1.0/bearer',
//        // $sub = 'myMail@gmail.com
//
//        $client = new \Google_Client();
//        $googleAccountKeyFilePath = \Yii::getAlias('@app/../Project.json'); // Ключ который мы получили при регистрации
//        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath);
////        $client->setAssertionCredentials($credentials);
////        if ($client->getAuth()->isAccessTokenExpired()) {
////            $client->getAuth()->refreshTokenWithAssertion();
////        }
//        $service = new \Google_Service_Sheets($client);
//        $newSheet = new \Google_Service_Sheets_Spreadsheet();
//        $newSheet->setSpreadsheetId('34534534'); // <- hardcoded for test
//        $response = $service->spreadsheets->create($newSheet);
//        print_r($response);
    }

}