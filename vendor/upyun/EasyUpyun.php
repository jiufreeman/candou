<?php
/**
 *
 *
 * PHP version 5.3.x
 *
 * @category
 * @package
 * @author   gouki <gouki.xiao@gmail.com>
 */
/**
 * EasyUpyun.php
 * @example:
 *  Yii::app()->upyun->upload($domain,$savedname,$datas,$autoCreateDir=true);
 *  这个是一个demo，推荐仍然使用upyun提供的API，这样就可以几乎不用改代码
 *  UpyunBase的用法是$upyun->setApiDomain('abc');
 *  组件用法：Yii::app()->upyun->setApiDomain('bucket的别名','abc');
 *
 * @category upyun
 * @package  upyun
 * @author   gouki <gouki.xiao@gmail.com>
 * @version  $Id$
 * @created  12-7-6 PM11:07
 */
include "upyun.class.php";
class EasyUpyun extends Component {
    /**
     * 别名
     * 因为又拍云的bucket的名字比较长，在开发的时候，如果用很长的bucket会很痛苦，因此就做了一个别名功能用来代替bucket
     * @var array
     */
    public $alias = array(
        'movie' => 'candou-movie',
        'stage' => 'candou-stage',
        'actor' => 'candou-actor',
    );
    /**
     * 又拍云的bucket
     * key 为 bucket
     * value 为 bucket对应的用户名和密码,格式为“用户名:密码”
     * @var array
     */
    public $bucket = array(
        'candou-movie' => 'maxwelldu:maxwelldu',
        'candou-stage' => 'maxwelldu:maxwelldu',
        'candou-actor' => 'maxwelldu:maxwelldu',
    );
    /**
     * 又拍云API实例
     * @var UpYun
     */
    public $upyun;
    /**
     * 是否采用upyun的debug功能，该功能为全局打开，一旦开启，所有bucket涉及的debug都开启
     * @var bool
     */
    public $debug = FALSE;
    /**
     * 测试代码。
     * @param      $domain
     * @param      $savedname
     * @param      $datas
     * @param bool $autoCreateDir
     *
     * @return bool
     */
    public function upload($domain, $savedname, $datas, $autoCreateDir = TRUE)
    {
        $upyun = $this->getBucketInfo($domain);
        $isfile = FALSE;
        if (!is_string($datas)) {
            $datas = serialize($datas);
        }
        if (is_string($datas)) {
            if (file_exists($datas)) {
                $isfile = TRUE;
                $upyun->setContentMD5(md5_file($datas));
            }
            else {
                $upyun->setContentMD5(md5($datas));
            }
        }
        if ($isfile == TRUE) {
            $fp = fopen($datas, "r");
            $ret = $upyun->writeFile($savedname, $fp, $autoCreateDir);
            fclose($fp);
        }
        else {
            $ret = $upyun->writeFile($savedname, $datas, $autoCreateDir);
        }
        return $ret;
    }
    /**
     * demo
     * 可以用这个代码，也可以根本不实现这个方法，而会自动调用官方的方法
     * @param $domain
     * @param $key
     *
     * @return mixed
     */
    public function getWritedFileInfo($domain , $key)
    {
        $upyun = $this->getBucketInfo($domain);
        return $upyun->getWritedFileInfo($key);
    }
    /**
     * @param $domain
     *
     * @return UpYun
     * @throws CException
     */
    public function getBucketInfo($domain)
    {
        if (!isset($this->upyun[$domain]) || !($this->upyun[$domain] instanceof UpYun)) {
            if (isset($this->alias[$domain])) {
                $domain = $this->alias[$domain];
            }
            if (isset($this->bucket[$domain])) {
                list($user, $pass) = explode(":", $this->bucket[$domain]);
                $this->upyun[$domain] = new UpYun($domain, $user, $pass);
            }
            else {
                throw new CException(sprintf("%s不是有效的bucket",$domain));
            }
        }
        $this->upyun[$domain]->debug = $this->debug;
        return $this->upyun[$domain];
    }
    /**
     * @param string $name
     * @param array  $paramaters
     *
     * @return mixed
     */
    public function __call($name,$paramaters){
        $domain = array_shift($paramaters);
        $upyun = $this->getBucketInfo($domain);
        if(method_exists($upyun,$name)){
            return call_user_func_array(array($upyun,$name),$paramaters);
        }
        return parent::__call($name,$paramaters);
    }
}
