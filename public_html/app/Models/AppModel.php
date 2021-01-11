<?php namespace App\Models;

use CodeIgniter\Model;

class AppModel extends Model{
  protected $table = 'apps';
  protected $allowedFields = ['appName','clientid', 'clientkey','userId'];
}