<?php
/**
 * User: 袁超<yccphp@163.com>
 * Time: 2015.07.19 上午3:28
 */
use Illuminate\Database\Seeder;
use App\Services\Registrar;
class UserSeeder extends Seeder{
    public function run(){
        $data = [
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' =>'123456',
            'desc'=>'管理员'
        ];
        $register = new Registrar();
        $register->create($data);
    }
}