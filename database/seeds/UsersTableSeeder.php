<?php
use App\User;
use Illuminate\Database\Seeder;

/*
 * Author:  Rohit Pandita(rohit3nov@gmail.com)
 */
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->fill([
            'name' => 'rohit',
            'email' => 'rohit3nov@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        $user->email_verified_at = now();
        $user->save();
    }
}
