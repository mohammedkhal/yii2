<?php

namespace frontend\api;


class ApiResponse extends \yii\web\Response
{

    public function send()
    {
        // var_dump(isset($this->data[0]['message']));exit;

        if (isset($this->data['message']) || isset($this->data[0]['message'])) {
            $message = !isset($this->data['message']) ? "validation error" : $this->data['message'];
        } else {
            $message = 'response successfully send';
        }

        $this->data = ['api-status' => ['http_code' => $this->statusCode, 'success' => !$this->isClientError, 'message' => $message], 'data' => $this->data];

        parent::send();
    }
}
