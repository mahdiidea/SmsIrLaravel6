<?php


namespace MahdiIDea\SmsIrLaravel6\Classes;


class GetSmsLines
{
    /**
     * gets API SMS Line Url.
     *
     * @return string Indicates the Url
     */
    protected function getAPISMSLineUrl()
    {
        return "http://RestfulSms.com/api/SMSLine";
    }

    /**
     * gets Api Token Url.
     *
     * @return string Indicates the Url
     */
    protected function getApiTokenUrl()
    {
        return "http://RestfulSms.com/api/Token";
    }

    /**
     * gets config parameters for sending request.
     *
     * @param string $APIKey API Key
     * @param string $SecretKey Secret Key
     * @return void
     */
    public function __construct($APIKey, $SecretKey)
    {
        $this->APIKey = $APIKey;
        $this->SecretKey = $SecretKey;
    }


    /**
     * Get Sms Lines.
     *
     * @return string Indicates the sent sms result
     */
    public function GetSmsLines()
    {
        $token = (new GetToken($this->APIKey, $this->SecretKey))->GetToken();
        if ($token != false) {

            $url = $this->getAPISMSLineUrl();
            $GetSmsLines = $this->execute($url, $token);

            $object = json_decode($GetSmsLines);

            if (is_object($object)) {
                $array = get_object_vars($object);
                if (is_array($array)) {
                    if ($array['IsSuccessful'] == true) {
                        $result = $array['SMSLines'];
                    } else {
                        $result = $array['Message'];
                    }
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }

        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * executes the main method.
     *
     * @param string $url url
     * @param string $token token string
     * @return string Indicates the curl execute result
     */
    private function execute($url, $token)
    {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'x-sms-ir-secure-token: ' . $token
        ));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
