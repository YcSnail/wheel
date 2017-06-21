<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index(){
        $this->display();
    }

    /**
     * 创建conf
     *
     */
    public function createConf(){

        if (!empty($_POST)){

            if (empty($_POST['name'])){
                echo '请输入文件名,不能为空';
                die();
            }

            $name = $_POST['name'];

            $path = YC_COMMON.'newFile/conf/';

            $postfix = '.wshoto.com.conf';

            $filePath = $path.$name.$postfix;

            if (!file_exists($filePath)){
                $file =  file_get_contents(YC_COMMON.'conf/test.conf');
                $saveFile = str_replace('test',$name,$file);
                $Res = file_put_contents($filePath,$saveFile);

                if (empty($Res)){
                    echo '创建失败.ZZ';
                    die();
                }

            }

            $http = 'http://';
            $http .= $_SERVER['HTTP_HOST']. $filePath;
            $array = array(
                'filePath'=> $http
            );

            $this->assign($array);
            $this->display('createConfDown');
            die();

        }

        $this->display();
    }

    /**
     * 创建sql 配置文件
     *
     */
    public function createMysql(){


        if (!empty($_POST)){

            if (empty($_POST['host']) || empty($_POST['name'] ) || empty($_POST['pwd'] )|| empty($_POST['base'] )){
                echo '输入的参数不完整';
                die();
            }

            $file =  file_get_contents(YC_COMMON.'conf/config.php');

            $array = array(
                'changeHost',
                'changeName',
                'changePwd',
                'changeBase',
                'changeAuthkey'
            );

            $authkey = $this->get_password(8);

            $cheArr = array(
                $_POST['host'],
                $_POST['name'],
                $_POST['pwd'],
                $_POST['base'],
                $authkey,
            );
            $SavePath = YC_COMMON.'newFile/mysql/config.php.bak';

            $saveFile = str_replace($array,$cheArr,$file);
            $Res = file_put_contents($SavePath,$saveFile);

            if (empty($Res)){
                echo '创建失败.ZZ';
                die();
            }

            $http = 'http://';
            $http .= $_SERVER['HTTP_HOST']. $SavePath;

            $array = array(
                'filePath'=> $http
            );

            $this->assign($array);
            $this->display('createConfDown');
            die();
        }

        $this->display();
    }

    public function get_password( $length = 16 ) {
        $str = substr(md5(time()), 0, $length);
        return $str;
    }


    /**
     * 修改微擎代码
     *
     */
    public function changePass(){

        if (!empty($_POST)){

            if (empty($_POST['pass']) || empty($_POST['salt']) || empty($_POST['authkey']) ){
                echo ('输入的参数不完整');
                die();
            }

            $pass = $_POST['pass'];
            $salt = $_POST['salt'];
            $authkey = $_POST['authkey'];
            //pass-salt-authkey
            $passwordinput = "$pass-$salt-$authkey";
            $pssworld = sha1($passwordinput);

            $array = array(
                'pssworld'=> $pssworld
            );
            $this->assign($array);
        }

        $this->display();
    }



    /**
     * 开始微擎
     */
    public function begin(){

        $path = YC_COMMON.'conf/';
        $name = 'settings.jar';
        $filePath = $path.$name;


        $http = 'http://';
        $http .= $_SERVER['HTTP_HOST']. $filePath;
        $array = array(
            'filePath'=> $http
        );
        $this->assign($array);
        $this->display();
    }


    /**
     * @param $status
     * @param $msg
     * @return array|string
     */
    public function Message($status,$msg){

        $res = array(
            'status'=>$status,
            'msg'=>$msg
        );

        $res = json_encode($res);
        return $res;

        header("Content-type: text/html; charset=utf-8");
        echo $msg;
        echo '<br>';
        echo '<a href="index.html">点击返回</a>';
        die();
    }


}