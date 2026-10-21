<?php
// Oculta en phpMyAdmin las bases internas de MySQL, para que solo se vea automotora.
$cfg['Servers'][1]['hide_db'] = '^(information_schema|performance_schema|mysql|sys)$';
