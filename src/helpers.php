<?php

use Illuminate\Support\Facades\Mail;

if ( !function_exists('send_email') ) {
    /**
     * 发送邮件函数
     *
     * @param $email            收件人邮箱  如果群发 则传入数组
     * @param $name             收件人名称
     * @param $subject          标题
     * @param array  $data      邮件模板中用的变量 示例：['name'=>'帅白','phone'=>'110']
     * @param string $template  邮件模板
     * @return array            发送状态
     */
    function send_email($email, $name, $subject, $data = [], $template = 'emails.test')
    {
        Mail::send($template, $data, function ($message) use ($email, $name, $subject) {
            //如果是数组；则群发邮件
            if (is_array($email)) {
                foreach ($email as $k => $v) {
                    $message->to($v, $name)->subject($subject);
                }
            } else {
                $message->to($email, $name)->subject($subject);
            }
        });
        if (count(Mail::failures()) > 0) {
            $data = array('status_code' => 500, 'message' => '邮件发送失败');
        } else {
            $data = array('status_code' => 200, 'message' => '邮件发送成功');
        }
        return $data;
    }
}