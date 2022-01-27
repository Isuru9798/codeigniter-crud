<?php
defined('BASEPATH') or exit('No direct script access allowed');
 
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['/'] = 'home/index';
$route['insert'] = 'students/student/store';
$route['get-students'] = 'students/student/getStudents';
$route['delete'] = 'students/student/delete';
$route['edit'] = 'students/student/edit';
$route['update'] = 'students/student/update';
