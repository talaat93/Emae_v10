<?php
require_once __DIR__ . '/../includes/bootstrap.php';
logout_admin();
flash('success', 'Vous êtes déconnecté.');
redirect_to('admin/login.php');
