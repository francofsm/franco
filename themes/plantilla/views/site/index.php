<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
if(!Yii::app()->user->isGuest)
  $this->redirect(array('Admin/Index'));
?>



<section class="bg pd4">
<div class="container center">
  <div class="row-fluid">
    <div class="span8">
          <!--<i style="font-size: 200px" class="icon-credit-card icon-4x"></i>
          <h2>Gestor Documental</h2>
          <h4>
            Fiscalía Regional del Bío-Bío
          </h4>-->
    </div><!-- /.span4 -->

<div class="div_acceso">

      <?php $form=$this->beginWidget('CActiveForm', array(
          'id'=>'login-form',
          'action'=>$this->createUrl("site/login"),
          'enableClientValidation'=>true,
          'clientOptions'=>array(
            'validateOnSubmit'=>true,
          ),
        )); ?>

    <p style="font-size: 21.5px;color: #868686;margin-top: 20px;margin-bottom: 20px;"><img src="<?php echo Yii::app()->baseUrl; ?>/images/iconos/clave.png">
        Por favor ingrese su información</p>

    <?php echo $form->textField($model,'username', array('placeholder'=>' Nombre Usuario')); ?>
    <?php echo $form->error($model,'username'); ?>

    <?php echo $form->passwordField($model,'password', array('placeholder'=>' Clave Windows')); ?>
    <?php echo $form->error($model,'password'); ?>

    <div class="row rememberMe">
    <?php echo $form->checkBox($model,'rememberMe'); ?>
    <?php echo $form->label($model,'rememberMe'); ?>
    <?php echo $form->error($model,'rememberMe'); ?>
  </div>

    <?php echo CHtml::submitButton('Ingresar'); ?>

      <?php $this->endWidget(); ?> 

  </div>

  </div><!-- /.row -->
</div>
</section>



  <script>
    !function ($) {
      $(function(){
        // carousel demo
        $('#myCarousel').carousel()
      })
    }(window.jQuery)
  </script>
