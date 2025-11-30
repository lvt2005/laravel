<?php
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\User; use Illuminate\Support\Facades\Hash;

function seedUser($email,$name,$pass,$type){
  if(!User::where('email',$email)->exists()){
    User::create([
      'full_name'=>$name,
      'email'=>$email,
      'password'=>Hash::make($pass),
      'type'=>$type,
      'status'=>'ACTIVE'
    ]);
    echo "Seeded $email as $type\n";
  } else echo "Exists: $email\n";
}
seedUser('admin@test.local','Admin','admin123','ADMIN');
seedUser('doctor@test.local','Doctor','doctor123','DOCTOR');
seedUser('user@test.local','Normal User','user123','USER');

