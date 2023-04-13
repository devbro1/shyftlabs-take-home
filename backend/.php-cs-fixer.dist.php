<?php

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@DoctrineAnnotation' => true,
        '@PhpCsFixer' => true,
        '@PSR12' => true,
    ])
;