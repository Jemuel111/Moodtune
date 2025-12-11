<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use MoodTune\Auth;

Auth::logout();
header('Location: login.php?logout=1');
exit;