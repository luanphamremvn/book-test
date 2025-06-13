<?php
foreach (glob(__DIR__ . '/../app/constants/*.php') as $filename) {
    include $filename;
}
