<?php
/**
 * Created by kilo with IntelliJ IDEA on 2017/8/22 0:02.
 *
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="tagbox">

                <h1>当前状态：<button class="btn-warning" data="<?= $model->config_value ?>"><?= $model->config_value ? '开' : '关' ?></button></h1>
                <button class="btn-success btn-lg"><?= $model->config_value ? '点击关闭' : '点击打开' ?></button>
        </div>
    </div>
</div>
<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>

<script type="text/javascript">
    $('.btn-lg').click(null, function () {
        confirm('请问是否确定修改?');
        $.get({
            url:'http://192.168.209.128:8003/payswitch/read',
            success: function(){
                var obj = $('.btn-warning');
                var btn = $('.btn-lg');
                if(obj.attr('data') == '1'){
                    obj.attr('data', '0');
                    obj.html('关');
                    btn.html('点击打开')
                }else{
                    obj.attr('data', '1');
                    obj.html('开');
                    btn.html('点击关闭')
                }
            },
            error : function(){
                alert('修改失败!')
            }
        });
    });
</script>
