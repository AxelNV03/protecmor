<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ Agregar esto
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|Alumno[] $alumnos
 * @property Collection|Mensaje[] $mensajes
 * @property Collection|Notificacione[] $notificaciones
 * @property Collection|Profesore[] $profesores
 * @property Collection|Respaldo[] $respaldos
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	use Notifiable, HasRoles, HasFactory; // ← Agregar HasRoles aquí

	protected $table = 'users';
	
	protected $casts = [
		'email_verified_at' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token',
		'telefono'
	];

	public function alumnos()
	{
		return $this->hasMany(Alumno::class);
	}

	public function mensajes()
	{
		return $this->hasMany(Mensaje::class, 'usuario_id');
	}

	public function notificaciones()
	{
		return $this->hasMany(Notificacione::class, 'usuario_id');
	}

	public function profesores()
	{
		return $this->hasMany(Profesore::class);
	}

	public function respaldos()
	{
		return $this->hasMany(Respaldo::class, 'usuario_id');
	}
}
