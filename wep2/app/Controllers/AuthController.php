<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\MailHelper;

class AuthController
{
    public function register()
    {
        // Ambil data dari form
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $token = bin2hex(random_bytes(16));

        // Simpan data pengguna dan token verifikasi ke database
        User::create([
            'email' => $email,
            'password' => $password,
            'email_verified' => false,
            'verification_token' => $token
        ]);

        // Kirim email verifikasi
        MailHelper::sendVerificationEmail($email, $token);

        echo "Pendaftaran berhasil! Silakan cek email Anda untuk verifikasi.";
    }

    public function verifyEmail()
    {
        // Ambil token dari URL
        $token = $_GET['token'];

        // Verifikasi token
        $user = User::where('verification_token', $token)->first();
        if ($user) {
            $user->email_verified = true;
            $user->verification_token = null; // Hapus token setelah verifikasi
            $user->save();

            echo "Email Anda berhasil diverifikasi!";
        } else {
            echo "Token verifikasi tidak valid!";
        }
    }
}
