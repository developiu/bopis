<?php

use XPort\Auth;

$this->layout('layouts/empty');

Auth::logout();

header('location: /');