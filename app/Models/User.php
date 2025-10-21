<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * ✅ Gunakan primary key default Laravel ('id')
     */
    protected $primaryKey = 'id';

    /**
     * ✅ Jika tabel 'users' tidak memiliki kolom timestamps
     */
    public $timestamps = false;

    /**
     * ✅ Kolom yang bisa diisi melalui mass assignment
     */
    protected $fillable = [
        'username',
        'full_name',
        'email',
        'password',
        'role',
    ];

    /**
     * ✅ Kolom yang disembunyikan dari output JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * ✅ Relasi ke ProjectMember
     * 1 user → banyak project_members
     */
    public function projectMembers()
    {
        return $this->hasMany(ProjectMember::class, 'user_id', 'id');
    }

    /**
     * ✅ Relasi ke CardAssignment
     * 1 user → banyak assignments
     */
    public function assignments()
    {
        return $this->hasMany(CardAssignment::class, 'user_id', 'id');
    }

    /**
     * ✅ Getter tambahan (opsional)
     * Mengembalikan nama lengkap, atau username jika kosong
     */
    public function getDisplayNameAttribute()
    {
        return $this->full_name ?: $this->username;
    }
}
