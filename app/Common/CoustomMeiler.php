<?php
/**
 * Notes:
 * User: liumiao
 * Date: 2020/3/27
 * Time: 09:25
 */

namespace App\Common;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//
require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';

class CoustomMeiler
{
    /**
     * @param $recipient 收件人
     * @param $title     标题
     * @param $content   内容
     * @return string
     * @throws Exception
     */
    public static function sendMail($recipient, $title, $content)
    {
        $mail = new PHPMailer(true);
        //服务器配置
        $mail->CharSet ="UTF-8";                     //设定邮件编码
        $mail->SMTPDebug = 0;                        // 调试模式输出
        $mail->isSMTP();                             // 使用SMTP
        $mail->Host = 'smtp.qq.com';                // SMTP服务器
        $mail->SMTPAuth = true;                      // 允许 SMTP 认证
        $mail->Username = '495261512@qq.com';                // SMTP 用户名  即邮箱的用户名
        $mail->Password = 'dcaoupzakygebjjg';             // SMTP 密码  部分邮箱是授权码(例如163邮箱)
        $mail->SMTPSecure = 'ssl';                    // 允许 TLS 或者ssl协议
        $mail->Port = 465;                            // 服务器端口 25 或者465 具体要看邮箱服务器支持

        $mail->setFrom('495261512@qq.com');  //发件人
        $mail->addAddress($recipient);  // 收件人
        //$mail->addAddress('ellen@example.com');  // 可添加多个收件人
        $mail->addReplyTo('495261512@qq.com', 'info'); //回复的时候回复给哪个邮箱 建议和发件人一致
        //$mail->addCC('cc@example.com');                    //抄送
        //$mail->addBCC('bcc@example.com');                    //密送

        //发送附件
        // $mail->addAttachment('../xy.zip');         // 添加附件
        // $mail->addAttachment('../thumb-1.jpg', 'new.jpg');    // 发送附件并且重命名

        //Content
        $mail->isHTML(true);  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
        $mail->Subject = $title;//邮件标题
        $mail->Body    = $content; //邮件内容
        $mail->AltBody = '如果邮件客户端不支持HTML则显示此内容';
        $res = $mail->send();
        // 使用 send() 方法发送邮件
        if (!$res) {
            return array(
                'code' => ResponseCode::SYS_ERROR,
                'msg' => $mail->ErrorInfo,
                'time' => date('Y-m-d H:i:s'),
                'data' => '',
            );
        } else {
            return array(
                'code' => ResponseCode::SYS_ERROR,
                'msg' => '发送成功',
                'time' => date('Y-m-d H:i:s'),
                'data' => '',
            );;
        }
    }
}

